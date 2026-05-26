<?php

namespace App\Controllers\Staff;

use App\Controllers\ProtectedController;
use App\Models\LetterAttachmentModel;
use App\Models\LetterModel;
use App\Models\LetterReplyModel;
use App\Models\NotificationModel;
use App\Models\ReplyAttachmentModel;
use App\Models\UserModel;
use App\Models\UserProfileModel;

class LetterController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/letters/index', ['title' => 'Kelola Surat Masuk | Website Desa Bonto Marannu']);
    }

    public function api()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Bad Request'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        // Baca params via POST
        $page   = max(1, (int) ($this->request->getPost('page') ?? 1));
        $length = (int) ($this->request->getPost('length') ?? 10);
        if ($length < 1) $length = 10;
        $start  = ($page - 1) * $length;

        $search          = trim($this->request->getPost('search') ?? '');
        $dateStart       = $this->request->getPost('date_start') ?? '';
        $dateEnd         = $this->request->getPost('date_end') ?? '';
        $tipeSuratFilter = $this->request->getPost('tipe_surat_filter') ?? '';
        $statusFilter    = $this->request->getPost('status_filter') ?? '';

        $hasFilters = !empty($dateStart) || !empty($dateEnd) || !empty($tipeSuratFilter) || !empty($statusFilter) || !empty($search);

        // Build base query with join
        $builder = $db->table('letters l')
            ->select('l.*, COALESCE(up.nama_lengkap, u.username, "Unknown") as sender_name')
            ->join('users u', 'u.id = l.user_id', 'left')
            ->join('user_profiles up', 'up.user_id = l.user_id', 'left');

        // Total sebelum filter (tanpa query JOIN yang berat)
        $recordsTotal = $db->table('letters')->countAllResults();

        // Apply filter
        if (!empty($dateStart)) {
            $builder->where('DATE(l.sent_at) >=', $dateStart);
        }
        if (!empty($dateEnd)) {
            $builder->where('DATE(l.sent_at) <=', $dateEnd);
        }
        if (!empty($tipeSuratFilter)) {
            $builder->where('l.tipe_surat', $tipeSuratFilter);
        }
        if (!empty($statusFilter)) {
            $builder->where('l.status', $statusFilter);
        }
        if (!empty($search)) {
            $builder->groupStart()
                ->like('l.kode_unik', $search)
                ->orLike('l.judul_perihal', $search)
                ->orLike('l.status', $search)
                ->orLike('up.nama_lengkap', $search)
                ->orLike('u.username', $search)
                ->groupEnd();
        }

        // Total setelah filter (hanya eksekusi COUNT jika ada filter pencarian)
        $recordsFiltered = $hasFilters ? (clone $builder)->countAllResults(false) : $recordsTotal;

        // Ordering & pagination
        $builder->orderBy('l.sent_at', 'DESC')
                ->limit($length, $start);

        $letters = $builder->get()->getResultArray();

        $data = [];
        foreach ($letters as $letter) {
            $data[] = [
                'id'            => $letter['id'],
                'kode_unik'     => $letter['kode_unik'] ?? '-',
                'judul_perihal' => $letter['judul_perihal'],
                'tipe_surat'    => $letter['tipe_surat'] ?? '-',
                'sender_name'   => $letter['sender_name'],
                'status'        => $letter['status'],
                'sent_at'       => date('d M Y H:i', strtotime($letter['sent_at'])),
            ];
        }

        return $this->response->setJSON([
            'success'          => true,
            'data'             => $data,
            'recordsTotal'     => $recordsTotal,
            'recordsFiltered'  => $recordsFiltered,
            'page'             => $page,
            'total_pages'      => $length > 0 ? (int) ceil($recordsFiltered / $length) : 1,
        ]);
    }


    public function show($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $letterModel      = new LetterModel();
        $attachmentModel  = new LetterAttachmentModel();
        $replyModel       = new LetterReplyModel();
        $replyAttachModel = new ReplyAttachmentModel();
        $userModel        = new UserModel();
        $profileModel     = new UserProfileModel();

        $letter = $letterModel->find($id);
        if (!$letter) {
            return redirect()->to('/staff/surat')->with('error', 'Surat tidak ditemukan.');
        }

        // Otomatis ubah status Menunggu → Dibaca saat staff membuka surat
        if ($letter['status'] === LetterModel::STATUS_MENUNGGU) {
            $letterModel->update($id, [
                'status'           => LetterModel::STATUS_DIBACA,
                'read_at'          => date('Y-m-d H:i:s'),
                'assigned_staff_id'=> $this->currentUser['id'],
            ]);
            $letter['status']  = LetterModel::STATUS_DIBACA;
            $letter['read_at'] = date('Y-m-d H:i:s');
            
            // Ambil nama staff
            $staffProfile = $profileModel->find($this->currentUser['id']);
            $staffName = ($staffProfile && !empty($staffProfile['nama_lengkap'])) 
                ? $staffProfile['nama_lengkap'] 
                : $this->currentUser['username'];
            
            // Notifikasi untuk user
            $notifModel = new NotificationModel();
            $notifModel->insert([
                'user_id'           => $letter['user_id'],
                'type'              => 'letter_read',
                'title'             => 'Surat Anda telah dibaca',
                'message'           => 'Surat Anda: ' . $letter['judul_perihal'] . ' telah dibaca oleh ' . $staffName,
                'related_letter_id' => $id,
                'is_read'           => 0,
                'created_at'        => date('Y-m-d H:i:s'),
            ]);
            
            // Email notifikasi ke user
            $user = $userModel->find($letter['user_id']);
            if ($user) {
                try {
                    $emailService = new \App\Libraries\EmailService();
                    $letterUrl    = base_url('/user/surat/' . $id);
                    $emailService->sendNotification(
                        $user['email'],
                        $user['username'],
                        'Surat Anda telah dibaca',
                        'Surat Anda: ' . $letter['judul_perihal'] . ' telah dibaca oleh ' . $staffName,
                        'letter_read',
                        $letterUrl,
                        $letter['judul_perihal'],
                        $letter['tipe_surat'] ?? null
                    );
                } catch (\Exception $e) {
                    log_message('error', 'Gagal queue email surat dibaca: ' . $e->getMessage());
                }
            }
        }

        $replies = $replyModel->where('letter_id', $id)->findAll();
        $replyAttachments = [];
        $replyProfiles = [];
        
        foreach ($replies as $reply) {
            $replyAttachments[$reply['id']] = $replyAttachModel->where('reply_id', $reply['id'])->findAll();
            
            if (!empty($reply['staff_id'])) {
                $staffProfile = $profileModel->find($reply['staff_id']);
                $staffUser = $userModel->find($reply['staff_id']);
                
                $replyProfiles[$reply['id']] = [
                    'foto_profil'  => $staffProfile['foto_profil'] ?? null,
                    'nama_lengkap' => $staffProfile['nama_lengkap'] ?? null,
                    'username'     => $staffUser['username'] ?? 'Staff',
                ];
            }
        }

        return view('Staff/letters/detail', [
            'title'       => 'Detail Surat | Website Desa Bonto Marannu',
            'letter'           => $letter,
            'attachments'      => $attachmentModel->where('letter_id', $id)->findAll(),
            'replies'          => $replies,
            'replyAttachments' => $replyAttachments,
            'replyProfiles'    => $replyProfiles,
            'currentStaffId'   => $this->currentUser['id'],
        ]);
    }

    public function reply($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $letterModel = new LetterModel();
        $letter      = $letterModel->find($id);

        if (!$letter) {
            return redirect()->to('/staff/surat')->with('error', 'Surat tidak ditemukan.');
        }

        $replyModel = new LetterReplyModel();
        $replyId    = $replyModel->insert([
            'letter_id'  => $id,
            'staff_id'   => $this->currentUser['id'],
            'reply_text' => $this->request->getPost('reply_text'),
        ], true);

        $this->handleReplyAttachments($replyId);

        // Tidak mengubah status surat — status diatur lewat tombol Terima/Tolak
        // Hanya update assigned_staff_id jika belum diisi
        if (empty($letter['assigned_staff_id'])) {
            $letterModel->update($id, ['assigned_staff_id' => $this->currentUser['id']]);
        }

        // Ambil nama staff
        $profileModel = new UserProfileModel();
        $staffProfile = $profileModel->find($this->currentUser['id']);
        $staffName = ($staffProfile && !empty($staffProfile['nama_lengkap'])) 
            ? $staffProfile['nama_lengkap'] 
            : $this->currentUser['username'];

        $notifModel = new NotificationModel();
        $notifModel->insert([
            'user_id'           => $letter['user_id'],
            'type'              => 'reply',
            'title'             => 'Surat Anda dibalas',
            'message'           => 'Balasan baru dari ' . $staffName . ' untuk surat: ' . $letter['judul_perihal'],
            'related_letter_id' => $id,
            'related_reply_id'  => $replyId,
            'is_read'           => 0,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);
        
        // Email notifikasi ke user
        $userModel = new UserModel();
        $user      = $userModel->find($letter['user_id']);
        if ($user) {
            try {
                $emailService = new \App\Libraries\EmailService();
                $letterUrl    = base_url('/user/surat/' . $id);
                $emailService->sendNotification(
                    $user['email'],
                    $user['username'],
                    'Surat Anda dibalas',
                    'Balasan baru dari ' . $staffName . ' untuk surat: ' . $letter['judul_perihal'],
                    'letter_replied',
                    $letterUrl,
                    $letter['judul_perihal'],
                    $letter['tipe_surat'] ?? null
                );
            } catch (\Exception $e) {
                log_message('error', 'Gagal queue email balasan surat: ' . $e->getMessage());
            }
        }

        return redirect()->to('/staff/surat/' . $id)->with('success', 'Balasan dikirim.');
    }

    /**
     * Terima surat — ubah status menjadi Diterima
     */
    public function accept($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $letterModel = new LetterModel();
        $letter      = $letterModel->find($id);

        if (!$letter) {
            return redirect()->to('/staff/surat')->with('error', 'Surat tidak ditemukan.');
        }

        // Hanya bisa diterima jika status Dibaca
        if (!in_array($letter['status'], [LetterModel::STATUS_DIBACA, LetterModel::STATUS_MENUNGGU], true)) {
            return redirect()->to('/staff/surat/' . $id)->with('error', 'Surat tidak dapat diterima pada status saat ini.');
        }

        $catatanPenerimaan = $this->request->getPost('reply_text') ?? null;

        $letterModel->update($id, [
            'status'             => LetterModel::STATUS_DITERIMA,
            'catatan_penolakan'  => $catatanPenerimaan, // dipakai juga untuk catatan penerimaan opsional
            'decided_at'         => date('Y-m-d H:i:s'),
            'assigned_staff_id'  => $this->currentUser['id'],
        ]);

        $files = $this->request->getFileMultiple('reply_attachments');
        $hasFiles = false;
        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid()) { $hasFiles = true; break; }
            }
        }
        
        if (!empty(trim($catatanPenerimaan)) || $hasFiles) {
            $replyModel = new LetterReplyModel();
            $replyId    = $replyModel->insert([
                'letter_id'  => $id,
                'staff_id'   => $this->currentUser['id'],
                'reply_text' => trim($catatanPenerimaan) ?: 'Surat telah diterima.',
            ], true);
            $this->handleReplyAttachments($replyId);
        }

        // Ambil nama staff
        $profileModel = new UserProfileModel();
        $staffProfile = $profileModel->find($this->currentUser['id']);
        $staffName = ($staffProfile && !empty($staffProfile['nama_lengkap'])) 
            ? $staffProfile['nama_lengkap'] 
            : $this->currentUser['username'];

        // Notifikasi untuk user
        $notifModel = new NotificationModel();
        $notifModel->insert([
            'user_id'           => $letter['user_id'],
            'type'              => 'letter_accepted',
            'title'             => 'Surat Anda diterima',
            'message'           => 'Surat Anda: ' . $letter['judul_perihal'] . ' telah diterima oleh ' . $staffName,
            'related_letter_id' => $id,
            'is_read'           => 0,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        // Email notifikasi ke user
        $userModel = new UserModel();
        $user      = $userModel->find($letter['user_id']);
        if ($user) {
            try {
                $emailService = new \App\Libraries\EmailService();
                $letterUrl    = base_url('/user/surat/' . $id);
                $emailService->sendNotification(
                    $user['email'],
                    $user['username'],
                    'Surat Anda diterima',
                    'Surat Anda: ' . $letter['judul_perihal'] . ' telah diterima oleh ' . $staffName,
                    'letter_accepted',
                    $letterUrl,
                    $letter['judul_perihal'],
                    $letter['tipe_surat'] ?? null
                );
            } catch (\Exception $e) {
                log_message('error', 'Gagal queue email surat diterima: ' . $e->getMessage());
            }
        }

        return redirect()->to('/staff/surat/' . $id)->with('success', 'Surat berhasil diterima.');
    }

    /**
     * Tolak surat — ubah status menjadi Ditolak
     */
    public function reject($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $letterModel = new LetterModel();
        $letter      = $letterModel->find($id);

        if (!$letter) {
            return redirect()->to('/staff/surat')->with('error', 'Surat tidak ditemukan.');
        }

        // Hanya bisa ditolak jika status Dibaca atau Menunggu
        if (!in_array($letter['status'], [LetterModel::STATUS_DIBACA, LetterModel::STATUS_MENUNGGU], true)) {
            return redirect()->to('/staff/surat/' . $id)->with('error', 'Surat tidak dapat ditolak pada status saat ini.');
        }

        $catatanPenolakan = trim($this->request->getPost('reply_text') ?? '');

        // Catatan penolakan wajib diisi
        if (empty($catatanPenolakan)) {
            return redirect()->to('/staff/surat/' . $id)->with('error', 'Catatan penolakan / pesan wajib diisi.');
        }

        $letterModel->update($id, [
            'status'            => LetterModel::STATUS_DITOLAK,
            'catatan_penolakan' => $catatanPenolakan,
            'decided_at'        => date('Y-m-d H:i:s'),
            'assigned_staff_id' => $this->currentUser['id'],
        ]);

        $replyModel = new LetterReplyModel();
        $replyId    = $replyModel->insert([
            'letter_id'  => $id,
            'staff_id'   => $this->currentUser['id'],
            'reply_text' => $catatanPenolakan,
        ], true);
        $this->handleReplyAttachments($replyId);

        // Ambil nama staff
        $profileModel = new UserProfileModel();
        $staffProfile = $profileModel->find($this->currentUser['id']);
        $staffName = ($staffProfile && !empty($staffProfile['nama_lengkap'])) 
            ? $staffProfile['nama_lengkap'] 
            : $this->currentUser['username'];

        // Notifikasi untuk user
        $notifModel = new NotificationModel();
        $notifModel->insert([
            'user_id'           => $letter['user_id'],
            'type'              => 'letter_rejected',
            'title'             => 'Surat Anda ditolak',
            'message'           => 'Surat Anda: ' . $letter['judul_perihal'] . ' ditolak oleh ' . $staffName . '. Alasan: ' . $catatanPenolakan,
            'related_letter_id' => $id,
            'is_read'           => 0,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        // Email notifikasi ke user
        $userModel = new UserModel();
        $user      = $userModel->find($letter['user_id']);
        if ($user) {
            try {
                $emailService = new \App\Libraries\EmailService();
                $letterUrl    = base_url('/user/surat/' . $id);
                $emailService->sendNotification(
                    $user['email'],
                    $user['username'],
                    'Surat Anda ditolak',
                    'Surat Anda: ' . $letter['judul_perihal'] . ' ditolak. Alasan: ' . $catatanPenolakan,
                    'letter_rejected',
                    $letterUrl,
                    $letter['judul_perihal'],
                    $letter['tipe_surat'] ?? null
                );
            } catch (\Exception $e) {
                log_message('error', 'Gagal queue email surat ditolak: ' . $e->getMessage());
            }
        }

        return redirect()->to('/staff/surat/' . $id)->with('success', 'Surat berhasil ditolak.');
    }

    public function delete($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $letterModel = new LetterModel();
        $attachmentModel = new LetterAttachmentModel();
        $replyModel = new LetterReplyModel();
        $replyAttachModel = new ReplyAttachmentModel();
        $notificationModel = new NotificationModel();

        $letter = $letterModel->find($id);
        if (!$letter) {
            return redirect()->to('/staff/surat')->with('error', 'Surat tidak ditemukan.');
        }

        // Hapus lampiran surat
        $attachments = $attachmentModel->where('letter_id', $id)->findAll();
        foreach ($attachments as $att) {
            $filePath = FCPATH . ltrim($att['file_path'], '/');
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
        $attachmentModel->where('letter_id', $id)->delete();

        // Hapus balasan dan lampiran balasan
        $replies = $replyModel->where('letter_id', $id)->findAll();
        foreach ($replies as $reply) {
            $replyAttachments = $replyAttachModel->where('reply_id', $reply['id'])->findAll();
            foreach ($replyAttachments as $replyAtt) {
                $filePath = FCPATH . ltrim($replyAtt['file_path'], '/');
                if (is_file($filePath)) {
                    @unlink($filePath);
                }
            }
            $replyAttachModel->where('reply_id', $reply['id'])->delete();
        }
        $replyModel->where('letter_id', $id)->delete();

        // Hapus notifikasi terkait
        $notificationModel->where('related_letter_id', $id)->delete();

        // Hapus surat
        $letterModel->delete($id);

        return redirect()->to('/staff/surat')->with('success', 'Surat berhasil dihapus.');
    }

    public function deleteReply($letterId, $replyId)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $letterModel = new LetterModel();
        $replyModel = new LetterReplyModel();
        $replyAttachModel = new ReplyAttachmentModel();

        $letter = $letterModel->find($letterId);
        $reply  = $replyModel->find($replyId);

        if (!$letter || !$reply || $reply['letter_id'] != $letterId) {
            return redirect()->to('/staff/surat/' . $letterId)->with('error', 'Balasan tidak ditemukan.');
        }

        // Cek apakah balasan milik staff yang login
        if ($reply['staff_id'] != $this->currentUser['id']) {
            return redirect()->to('/staff/surat/' . $letterId)->with('error', 'Anda hanya bisa menghapus balasan Anda sendiri.');
        }

        // Hapus lampiran balasan
        $attachments = $replyAttachModel->where('reply_id', $replyId)->findAll();
        foreach ($attachments as $att) {
            $filePath = FCPATH . ltrim($att['file_path'], '/');
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
        $replyAttachModel->where('reply_id', $replyId)->delete();

        // Hapus balasan
        $replyModel->delete($replyId);

        return redirect()->to('/staff/surat/' . $letterId)->with('success', 'Balasan dihapus.');
    }

    private function handleReplyAttachments(int $replyId): void
    {
        helper('upload');
        $files = $this->request->getFileMultiple('reply_attachments');
        if (!$files) {
            return;
        }

        $uploadPath       = FCPATH . 'uploads/replies';
        $replyAttachModel = new ReplyAttachmentModel();
        $this->ensureUploadPath($uploadPath);
        $errors = [];

        foreach ($files as $file) {
            if (!$file->isValid() || $file->hasMoved()) {
                continue;
            }

            $error = validate_letter_attachment($file);
            if ($error !== null) {
                $errors[] = $file->getClientName() . ': ' . $error;
                continue;
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            $replyAttachModel->insert([
                'reply_id'      => $replyId,
                'file_path'     => 'uploads/replies/' . $newName,
                'original_name' => $file->getClientName(),
                'mime_type'     => $file->getClientMimeType(),
                'file_size'     => $file->getSize(),
            ]);
        }

        if (!empty($errors)) {
            session()->setFlashdata('attachment_errors', $errors);
        }
    }
}
