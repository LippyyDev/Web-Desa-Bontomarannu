<?php

namespace App\Controllers\User;

use App\Controllers\ProtectedController;
use App\Models\LetterAttachmentModel;
use App\Models\LetterModel;
use App\Models\LetterReplyModel;
use App\Models\ReplyAttachmentModel;
use App\Models\NotificationModel;
use App\Models\UserModel;

class LetterController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        return view('User/letters/index', ['title' => 'Riwayat Surat | Website Desa Bonto Marannu']);
    }

    public function api()
    {
        if ($redirect = $this->guard(['user'])) {
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

        // Build base query — hanya surat milik user yang login
        $builder = $db->table('letters')
            ->where('user_id', $this->currentUser['id']);

        // Total sebelum filter
        $recordsTotal = (clone $builder)->countAllResults(false);

        // Apply filter
        if (!empty($dateStart)) {
            $builder->where('DATE(sent_at) >=', $dateStart);
        }
        if (!empty($dateEnd)) {
            $builder->where('DATE(sent_at) <=', $dateEnd);
        }
        if (!empty($tipeSuratFilter)) {
            $builder->where('tipe_surat', $tipeSuratFilter);
        }
        if (!empty($statusFilter)) {
            $builder->where('status', $statusFilter);
        }
        if (!empty($search)) {
            $builder->groupStart()
                ->like('kode_unik', $search)
                ->orLike('judul_perihal', $search)
                ->orLike('tipe_surat', $search)
                ->orLike('status', $search)
                ->groupEnd();
        }

        // Total setelah filter
        $recordsFiltered = (clone $builder)->countAllResults(false);

        // Ordering & pagination
        $builder->orderBy('sent_at', 'DESC')
                ->limit($length, $start);

        $letters = $builder->get()->getResultArray();

        $data = [];
        foreach ($letters as $letter) {
            $data[] = [
                'id'            => $letter['id'],
                'kode_unik'     => $letter['kode_unik'] ?? '-',
                'judul_perihal' => $letter['judul_perihal'],
                'tipe_surat'    => $letter['tipe_surat'],
                'status'        => $letter['status'],
                'sent_at'       => date('d M Y H:i', strtotime($letter['sent_at'])),
            ];
        }

        return $this->response->setJSON([
            'success'         => true,
            'data'            => $data,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'page'            => $page,
            'total_pages'     => $length > 0 ? (int) ceil($recordsFiltered / $length) : 1,
        ]);
    }


    public function create()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $profileModel    = new \App\Models\UserProfileModel();
        $userProfile     = $profileModel->find($this->currentUser['id']);
        $missingFields   = $this->getIncompleteProfileFields($userProfile);
        $profileIncomplete = !empty($missingFields);

        return view('User/letters/form', [
            'title'             => 'Ajukan Surat Baru | Website Desa Bonto Marannu',
            'profileIncomplete' => $profileIncomplete,
            'missingFields'     => $missingFields,
        ]);
    }

    public function store()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        // Cek kelengkapan profil sebelum izinkan pengiriman surat
        $profileModel  = new \App\Models\UserProfileModel();
        $userProfile   = $profileModel->find($this->currentUser['id']);
        $missingFields = $this->getIncompleteProfileFields($userProfile);
        if (!empty($missingFields)) {
            return redirect()->to('/user/surat/buat')
                ->with('profile_incomplete', true)
                ->with('missing_fields', $missingFields);
        }

        helper('upload');

        // Validasi jenis surat
        $allowedTypes = [
            'Keterangan Usaha',
            'Keterangan Tidak Mampu',
            'Keterangan Belum Menikah',
            'Keterangan Domisili',
            'Undangan',
            'Lain Lain'
        ];
        
        $tipeSurat = $this->request->getPost('tipe_surat');
        if (!in_array($tipeSurat, $allowedTypes)) {
            return redirect()->back()->withInput()->with('error', 'Jenis surat tidak valid.');
        }

        $letterModel = new LetterModel();

        // ----------------------------------------------------------------
        // Sanitasi & validasi judul dan isi surat
        // ----------------------------------------------------------------
        $judulPerihal = trim(strip_tags($this->request->getPost('judul_perihal') ?? ''));
        $isiSurat     = trim(strip_tags($this->request->getPost('isi_surat') ?? ''));
        $judulPerihal = preg_replace('/[<>]/', '', $judulPerihal);
        $isiSurat     = preg_replace('/[<>]/', '', $isiSurat);

        if (mb_strlen($judulPerihal) < 3 || mb_strlen($judulPerihal) > 200) {
            return redirect()->back()->withInput()->with('error', 'Judul/Perihal harus antara 3–200 karakter.');
        }
        if (mb_strlen($isiSurat) < 10 || mb_strlen($isiSurat) > 3000) {
            return redirect()->back()->withInput()->with('error', 'Isi surat harus antara 10–3000 karakter.');
        }
        $dangerPattern = '/(javascript\s*:|vbscript\s*:|data\s*:|expression\s*\(|on\w+\s*=|<\s*script|\$\{|`[^`]*`)/i';
        if (preg_match($dangerPattern, $judulPerihal) || preg_match($dangerPattern, $isiSurat)) {
            return redirect()->back()->withInput()->with('error', 'Input mengandung karakter atau pola yang tidak diizinkan.');
        }

        // Generate kode unik
        $kodeUnik = $this->generateKodeUnik($letterModel);

        $data = [
            'kode_unik'     => $kodeUnik,
            'user_id'       => $this->currentUser['id'],
            'judul_perihal' => $judulPerihal,
            'tipe_surat'    => $tipeSurat,
            'isi_surat'     => $isiSurat,
            'status'        => 'Menunggu',
            'sent_at'       => date('Y-m-d H:i:s'),
        ];

        $letterId = $letterModel->insert($data, true);
        $this->handleAttachments($letterId);

        // Buat notifikasi untuk semua staff
        $userModel    = new UserModel();
        $profileModel = new \App\Models\UserProfileModel();
        $staffList    = $userModel->where('role', 'staf')->findAll();
        $notifModel   = new NotificationModel();
        $letterUrl    = base_url('/staff/surat/' . $letterId);

        // Ambil nama user (nama_lengkap dari profile, fallback ke username)
        $userProfile = $profileModel->find($this->currentUser['id']);
        $userName    = ($userProfile && !empty($userProfile['nama_lengkap']))
            ? $userProfile['nama_lengkap']
            : $this->currentUser['username'];

        // Instantiate EmailService sekali di luar loop
        try {
            $emailService = new \App\Libraries\EmailService();
        } catch (\Exception $e) {
            log_message('error', 'Gagal init EmailService di User/LetterController: ' . $e->getMessage());
            $emailService = null;
        }

        foreach ($staffList as $staff) {
            $notifModel->insert([
                'user_id'           => $staff['id'],
                'type'              => 'new_letter',
                'title'             => 'Surat Baru Masuk',
                'message'           => 'Surat baru dari ' . $userName,
                'related_letter_id' => $letterId,
                'is_read'           => 0,
                'created_at'        => date('Y-m-d H:i:s'),
            ]);

            // Kirim email notifikasi ke staff
            if ($emailService !== null) {
                try {
                    $emailService->sendNotification(
                        $staff['email'],
                        $staff['username'],
                        'Surat Baru Masuk',
                        'Surat baru dari ' . $userName,
                        'new_letter',
                        $letterUrl,
                        $data['judul_perihal'],
                        $data['tipe_surat']
                    );
                } catch (\Exception $e) {
                    log_message('error', 'Gagal queue email surat baru ke staff ' . $staff['email'] . ': ' . $e->getMessage());
                }
            }
        }

        return redirect()->to('/user/surat')->with('success', 'Surat berhasil dikirim.');
    }

    public function show($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $letterModel       = new LetterModel();
        $attachmentModel   = new LetterAttachmentModel();
        $replyModel        = new LetterReplyModel();
        $replyAttachModel  = new ReplyAttachmentModel();

        $letter = $letterModel->where('user_id', $this->currentUser['id'])->find($id);
        if (!$letter) {
            return redirect()->to('/user/surat')->with('error', 'Surat tidak ditemukan.');
        }

        $userModel = new \App\Models\UserModel();
        $profileModel = new \App\Models\UserProfileModel();
        
        $replies = $replyModel->where('letter_id', $id)->findAll();
        $replyAttachments = [];
        $replyProfiles = [];
        
        foreach ($replies as $reply) {
            $replyAttachments[$reply['id']] = $replyAttachModel->where('reply_id', $reply['id'])->findAll();
            
            // Get staff profile for reply
            if (!empty($reply['staff_id'])) {
                $staffProfile = $profileModel->find($reply['staff_id']);
                $staffUser = $userModel->find($reply['staff_id']);
                
                $replyProfiles[$reply['id']] = [
                    'foto_profil' => $staffProfile['foto_profil'] ?? null,
                    'nama_lengkap' => $staffProfile['nama_lengkap'] ?? null,
                    'username' => $staffUser['username'] ?? 'Staff',
                ];
            }
        }

        return view('User/letters/detail', [
            'title'       => 'Detail Surat | Website Desa Bonto Marannu',
            'letter'           => $letter,
            'attachments'      => $attachmentModel->where('letter_id', $id)->findAll(),
            'replies'          => $replies,
            'replyAttachments' => $replyAttachments,
            'replyProfiles'    => $replyProfiles,
        ]);
    }

    public function edit($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $letterModel = new LetterModel();
        $letter      = $letterModel->where('user_id', $this->currentUser['id'])->find($id);

        if (!$letter) {
            return redirect()->to('/user/surat')->with('error', 'Surat tidak ditemukan.');
        }

        $attachmentModel = new LetterAttachmentModel();
        $attachments = $attachmentModel->where('letter_id', $id)->findAll();

        return view('User/letters/form', ['letter' => $letter, 'attachments' => $attachments, 'title' => 'Edit Surat | Website Desa Bonto Marannu']);
    }

    public function update($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        // Validasi jenis surat
        $allowedTypes = [
            'Keterangan Usaha',
            'Keterangan Tidak Mampu',
            'Keterangan Belum Menikah',
            'Keterangan Domisili',
            'Undangan',
            'Lain Lain'
        ];
        
        $tipeSurat = $this->request->getPost('tipe_surat');
        if (!in_array($tipeSurat, $allowedTypes)) {
            return redirect()->back()->withInput()->with('error', 'Jenis surat tidak valid.');
        }

        $letterModel = new LetterModel();
        $letter      = $letterModel->where('user_id', $this->currentUser['id'])->find($id);

        if (!$letter) {
            return redirect()->to('/user/surat')->with('error', 'Surat tidak ditemukan.');
        }

        // ----------------------------------------------------------------
        // Sanitasi & validasi judul dan isi surat
        // ----------------------------------------------------------------
        $judulPerihal = trim(strip_tags($this->request->getPost('judul_perihal') ?? ''));
        $isiSurat     = trim(strip_tags($this->request->getPost('isi_surat') ?? ''));
        $judulPerihal = preg_replace('/[<>]/', '', $judulPerihal);
        $isiSurat     = preg_replace('/[<>]/', '', $isiSurat);

        if (mb_strlen($judulPerihal) < 3 || mb_strlen($judulPerihal) > 200) {
            return redirect()->back()->withInput()->with('error', 'Judul/Perihal harus antara 3–200 karakter.');
        }
        if (mb_strlen($isiSurat) < 10 || mb_strlen($isiSurat) > 3000) {
            return redirect()->back()->withInput()->with('error', 'Isi surat harus antara 10–3000 karakter.');
        }
        $dangerPattern = '/(javascript\s*:|vbscript\s*:|data\s*:|expression\s*\(|on\w+\s*=|<\s*script|\$\{|`[^`]*`)/i';
        if (preg_match($dangerPattern, $judulPerihal) || preg_match($dangerPattern, $isiSurat)) {
            return redirect()->back()->withInput()->with('error', 'Input mengandung karakter atau pola yang tidak diizinkan.');
        }

        $letterModel->update($id, [
            'judul_perihal' => $judulPerihal,
            'tipe_surat'    => $tipeSurat,
            'isi_surat'     => $isiSurat,
        ]);

        $this->handleAttachments($id);

        return redirect()->to('/user/surat/' . $id)->with('success', 'Surat diperbarui.');
    }

    public function delete($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $letterModel = new LetterModel();
        $attachmentModel = new LetterAttachmentModel();
        $replyModel = new LetterReplyModel();
        $replyAttachModel = new ReplyAttachmentModel();
        $notificationModel = new NotificationModel();

        $letter = $letterModel->where('user_id', $this->currentUser['id'])->find($id);
        if (!$letter) {
            return redirect()->to('/user/surat')->with('error', 'Surat tidak ditemukan.');
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
            // Hapus lampiran balasan
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

        return redirect()->to('/user/surat')->with('success', 'Surat berhasil dihapus.');
    }

    public function deleteAttachment($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $attachmentModel = new LetterAttachmentModel();
        $attachment = $attachmentModel->find($id);
        
        if (!$attachment) {
            return redirect()->back()->with('error', 'Lampiran tidak ditemukan.');
        }

        // Verify letter ownership
        $letterModel = new LetterModel();
        $letter = $letterModel->where('user_id', $this->currentUser['id'])->find($attachment['letter_id']);
        
        if (!$letter) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $filePath = FCPATH . ltrim($attachment['file_path'], '/');
        if (is_file($filePath)) {
            @unlink($filePath);
        }

        $attachmentModel->delete($id);

        return redirect()->back()->with('success', 'Lampiran berhasil dihapus.');
    }

    private function handleAttachments(int $letterId): void
    {
        helper('upload');
        $files = $this->request->getFileMultiple('attachments');
        if (!$files) {
            return;
        }

        $uploadPath      = FCPATH . 'uploads/letters';
        $attachmentModel = new LetterAttachmentModel();
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

            $attachmentModel->insert([
                'letter_id'     => $letterId,
                'file_path'     => 'uploads/letters/' . $newName,
                'original_name' => $file->getClientName(),
                'mime_type'     => $file->getClientMimeType(),
                'file_size'     => $file->getSize(),
            ]);
        }

        if (!empty($errors)) {
            session()->setFlashdata('attachment_errors', $errors);
        }
    }

    /**
     * Generate kode unik untuk surat
     * Format: SURAT-YYYYMMDD-XXXXXX (6 digit random)
     */
    private function generateKodeUnik(LetterModel $letterModel): string
    {
        $prefix = 'SURAT-' . date('Ymd') . '-';
        $maxAttempts = 100;
        $attempt = 0;

        do {
            $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
            $kodeUnik = $prefix . $random;
            $exists = $letterModel->where('kode_unik', $kodeUnik)->first();
            $attempt++;
        } while ($exists && $attempt < $maxAttempts);

        return $kodeUnik;
    }

    /**
     * Periksa field profil yang belum dilengkapi.
     * Mengembalikan array label field yang kosong.
     * Array kosong berarti profil sudah lengkap.
     */
    private function getIncompleteProfileFields(?array $profile): array
    {
        $requiredFields = [
            'nama_lengkap'   => 'Nama Lengkap',
            'jenis_kelamin'  => 'Jenis Kelamin',
            'tempat_lahir'   => 'Tempat Lahir',
            'tanggal_lahir'  => 'Tanggal Lahir',
            'agama'          => 'Agama',
            'pekerjaan'      => 'Pekerjaan',
            'nik'            => 'NIK',
            'alamat'         => 'Alamat',
        ];

        $missing = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($profile[$field])) {
                $missing[] = $label;
            }
        }
        return $missing;
    }
}


