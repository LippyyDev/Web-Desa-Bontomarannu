<?php

namespace App\Controllers\Admin;

use App\Controllers\ProtectedController;
use App\Models\UserModel;
use App\Models\UserProfileModel;

class AccountController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        return view('Admin/users/index', [
            'currentUserId' => $this->currentUser['id'] ?? 0,
        ]);
    }

    public function api()
    {
        if ($redirect = $this->guard(['admin'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        $page        = max(1, (int) ($this->request->getPost('page') ?? 1));
        $length      = (int) ($this->request->getPost('length') ?? 10);
        if ($length <= 0) $length = 10;
        $search      = trim($this->request->getPost('search') ?? '');
        $roleFilter  = trim($this->request->getPost('role_filter') ?? '');
        $statusFilter= trim($this->request->getPost('status_filter') ?? '');
        $offset      = ($page - 1) * $length;

        // Build base query with join
        $builder = $db->table('users u')
            ->select('u.id, u.username, u.email, u.role, u.status, u.created_at, u.last_seen_at, up.foto_profil')
            ->join('user_profiles up', 'up.user_id = u.id', 'left');

        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('u.username', $search)
                ->orLike('u.email', $search)
                ->orLike('u.role', $search)
                ->orLike('u.status', $search)
                ->groupEnd();
        }

        // Apply role filter
        if (!empty($roleFilter)) {
            $builder->where('u.role', $roleFilter);
        }

        // Apply status filter
        if (!empty($statusFilter)) {
            $builder->where('u.status', $statusFilter);
        }

        $total = $builder->countAllResults(false);

        $builder->orderBy('u.created_at', 'DESC')->limit($length, $offset);
        $users = $builder->get()->getResultArray();

        // Threshold online: 5 menit (300 detik)
        $onlineThreshold = 300;
        $now = time();

        $data = [];
        foreach ($users as $user) {
            $lastSeenAt  = $user['last_seen_at'];
            $isOnline    = false;
            $lastSeenStr = 'Belum pernah';

            if (!empty($lastSeenAt)) {
                $lastSeenTs  = strtotime($lastSeenAt);
                $isOnline    = ($now - $lastSeenTs) <= $onlineThreshold;
                $lastSeenStr = date('d M Y, H:i', $lastSeenTs);
            }

            $data[] = [
                'id'           => $user['id'],
                'username'     => esc($user['username']),
                'email'        => esc($user['email']),
                'role'         => esc($user['role']),
                'status'       => esc($user['status']),
                'created_at'   => date('d M Y', strtotime($user['created_at'])),
                'foto_profil'  => (!empty($user['foto_profil'])) ? $user['foto_profil'] : null,
                'is_online'    => $isOnline,
                'last_seen_at' => $lastSeenStr,
            ];
        }

        return $this->response->setJSON([
            'success'     => true,
            'data'        => $data,
            'total'       => $total,
            'total_pages' => $length > 0 ? (int) ceil($total / $length) : 1,
            'current_page'=> $page,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        return view('Admin/users/create');
    }

    public function edit($id)
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        $userModel    = new UserModel();
        $profileModel = new UserProfileModel();
        $user         = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/akun')->with('error', 'User tidak ditemukan.');
        }

        return view('Admin/users/edit', [
            'user'    => $user,
            'profile' => $profileModel->find($id),
        ]);
    }

    public function store()
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        // Validasi password
        $password        = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!$password || strlen($password) < 6) {
            return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
        }

        if ($password !== $confirmPassword) {
            return redirect()->back()->withInput()->with('error', 'Password dan konfirmasi password tidak sama.');
        }

        $userModel    = new UserModel();
        $profileModel = new UserProfileModel();

        $userId = $userModel->insert([
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role') ?: 'user',
            'status'        => $this->request->getPost('status') ?: 'aktif',
            'is_verified'   => 1,
        ], true);

        $profileData = [
            'user_id'      => $userId,
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        ];

        // Handle foto profil upload dengan 8-layer validasi
        $file = $this->request->getFile('foto_profil');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            helper('upload');
            $error = validate_image_upload($file);
            if ($error !== null) {
                // Hapus user yang baru dibuat karena foto tidak valid
                $userModel->delete($userId, true);
                return redirect()->back()->withInput()->with('error', 'Foto profil: ' . $error);
            }

            $this->ensureUploadPath(FCPATH . 'uploads/profile');
            $path = $this->uploadToWebp($file, 'uploads/profile');
            if ($path === null) {
                $userModel->delete($userId, true);
                return redirect()->back()->withInput()->with('error', 'Gagal memproses foto profil. Pastikan file adalah gambar yang valid.');
            }
            $profileData['foto_profil'] = $path;
        }

        $profileModel->insert($profileData);

        return redirect()->to('/admin/akun')->with('success', 'Akun baru berhasil dibuat.');
    }

    public function update($id)
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        $userModel = new UserModel();
        $profileModel = new UserProfileModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Update user data
        $userData = [
            'username' => $this->request->getPost('username') ?: $user['username'],
            'email'    => $this->request->getPost('email') ?: $user['email'],
            'role'     => $this->request->getPost('role') ?: $user['role'],
            'status'   => $this->request->getPost('status') ?: $user['status'],
        ];
        $userModel->update($id, $userData);

        // Update profile data
        $nik = trim($this->request->getPost('nik') ?? '');
        $jenisKelamin = $this->request->getPost('jenis_kelamin');
        $profileData = [
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => in_array($jenisKelamin, ['Laki-laki', 'Perempuan']) ? $jenisKelamin : null,
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'agama'         => $this->request->getPost('agama'),
            'pekerjaan'     => $this->request->getPost('pekerjaan'),
            'nik'           => $nik !== '' ? $nik : null,
            'alamat'        => $this->request->getPost('alamat'),
        ];

        // Handle foto profil upload
        $file = $this->request->getFile('foto_profil');
        if ($file && $file->isValid()) {
            // Tolak format .GIF
            $extension = $file->getClientExtension();
            if (strtolower($extension) === 'gif') {
                return redirect()->back()->with('error', 'Format file .GIF tidak diperbolehkan.');
            }

            $path = FCPATH . 'uploads/profile';
            $this->ensureUploadPath($path);
            
            // Upload file sementara
            $tempName = $file->getRandomName();
            $file->move($path, $tempName);
            $tempPath = $path . '/' . $tempName;
            
            // Convert ke WebP
            $image = \Config\Services::image();
            $webpName = pathinfo($tempName, PATHINFO_FILENAME) . '.webp';
            $webpPath = $path . '/' . $webpName;
            
            try {
                $image->withFile($tempPath)
                    ->convert(IMAGETYPE_WEBP)
                    ->save($webpPath, 85); // Quality 85
                
                // Hapus file sementara
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
        }

                $profileData['foto_profil'] = 'uploads/profile/' . $webpName;
            } catch (\Exception $e) {
                // Jika konversi gagal, hapus file sementara
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                return redirect()->back()->with('error', 'Gagal memproses gambar. Pastikan file adalah gambar yang valid.');
            }
        }

        $profile = $profileModel->find($id);
        if ($profile) {
            $profileModel->update($id, $profileData);
        } else {
            $profileData['user_id'] = $id;
            $profileModel->insert($profileData);
        }

        return redirect()->back()->with('success', 'Akun diperbarui.');
    }

    public function changePassword($id)
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validasi password baru dan konfirmasi
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Password baru dan konfirmasi password tidak sama.');
        }

        // Validasi panjang password baru
        if (strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal 6 karakter.');
        }

        // Update password
        $userModel->update($id, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }

    public function delete($id)
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        $userModel = new UserModel();
        $userModel->delete($id);

        return redirect()->back()->with('success', 'Akun dihapus.');
    }

    public function toggleStatus($id)
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        // Cegah admin menonaktifkan akunnya sendiri
        if ((int) $id === (int) $this->currentUser['id']) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Akun tidak ditemukan.');
        }

        $newStatus = ($user['status'] === 'aktif') ? 'nonaktif' : 'aktif';
        $userModel->update($id, ['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun \"{$user['username']}\" berhasil {$label}.");
    }
}


