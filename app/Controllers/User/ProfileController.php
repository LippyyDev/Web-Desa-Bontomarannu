<?php

namespace App\Controllers\User;

use App\Controllers\ProtectedController;
use App\Models\UserModel;
use App\Models\UserProfileModel;

class ProfileController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $profileModel = new UserProfileModel();
        $userModel = new UserModel();
        $profile = $profileModel->find($this->currentUser['id']);
        $user    = $userModel->find($this->currentUser['id']);

        return view('User/profile/index', [
            'title'   => 'Profil Saya | Website Desa Bonto Marannu',
            'profile' => $profile,
            'user'    => $user,
        ]);
    }

    public function update()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $profileModel = new UserProfileModel();
        $userModel    = new UserModel();
        $uid          = $this->currentUser['id'];
        $db           = \Config\Database::connect();

        $newUsername = trim($this->request->getPost('username') ?? '');
        $newEmail    = trim($this->request->getPost('email') ?? '');

        // Cek duplikat username (kecuali akun sendiri)
        if ($db->table('users')->where('username', $newUsername)->where('id !=', $uid)->get()->getRow()) {
            return redirect()->to('/user/profil')->with('error', 'Username "' . esc($newUsername) . '" sudah digunakan oleh akun lain.');
        }

        // Cek duplikat email (kecuali akun sendiri)
        if ($db->table('users')->where('email', $newEmail)->where('id !=', $uid)->get()->getRow()) {
            return redirect()->to('/user/profil')->with('error', 'Email "' . esc($newEmail) . '" sudah digunakan oleh akun lain.');
        }

        $nik = trim($this->request->getPost('nik') ?? '');
        
        $jenisKelamin = $this->request->getPost('jenis_kelamin');
        $data = [
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => in_array($jenisKelamin, ['Laki-laki', 'Perempuan']) ? $jenisKelamin : null,
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'agama'         => $this->request->getPost('agama'),
            'pekerjaan'     => $this->request->getPost('pekerjaan'),
            'nik'           => $nik !== '' ? $nik : null,
            'alamat'        => $this->request->getPost('alamat'),
        ];

        $file = $this->request->getFile('foto_profil');
        if ($file && $file->isValid()) {
            // Validasi Ekstensi (Whitelist JPG/JPEG/PNG)
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = strtolower($file->getClientExtension());
            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->to('/user/profil')->with('error', 'Format foto profil hanya boleh JPG, JPEG, atau PNG.');
            }

            // Validasi Ukuran (Max 1 MB)
            if ($file->getSize() > 1048576) {
                return redirect()->to('/user/profil')->with('error', 'Ukuran foto profil maksimal 1 MB.');
            }

            $uploadPath = FCPATH . 'uploads/profile';
            $this->ensureUploadPath($uploadPath);
            
            // Upload file sementara
            $tempName = $file->getRandomName();
            $file->move($uploadPath, $tempName);
            $tempPath = $uploadPath . '/' . $tempName;
            
            // Convert ke WebP
            $image = \Config\Services::image();
            $webpName = pathinfo($tempName, PATHINFO_FILENAME) . '.webp';
            $webpPath = $uploadPath . '/' . $webpName;
            
            try {
                $image->withFile($tempPath)
                    ->convert(IMAGETYPE_WEBP)
                    ->save($webpPath, 85); // Quality 85
                
                // Hapus file sementara
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                
                $data['foto_profil'] = 'uploads/profile/' . $webpName;
            } catch (\Exception $e) {
                // Jika konversi gagal, hapus file sementara
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                return redirect()->to('/user/profil')->with('error', 'Gagal memproses gambar. Pastikan file adalah gambar yang valid.');
            }
        }

        $profile = $profileModel->find($uid);
        if ($profile) {
            $profileModel->update($uid, $data);
        } else {
            $data['user_id'] = $uid;
            $profileModel->insert($data);
        }

        $userModel->update($uid, [
            'username' => $newUsername,
            'email'    => $newEmail,
        ]);

        // Refresh session agar username/email di header dan form ikut berubah
        $sessionUser             = session('user');
        $sessionUser['username'] = $newUsername;
        $sessionUser['email']    = $newEmail;
        session()->set('user', $sessionUser);

        return redirect()->to('/user/profil')->with('success', 'Profil diperbarui.');
    }

    public function changePassword()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $userModel = new UserModel();
        $uid = $this->currentUser['id'];
        $user = $userModel->find($uid);

        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validasi password lama
        if (!password_verify($oldPassword, $user['password_hash'])) {
            return redirect()->to('/user/profil')->with('error', 'Password lama tidak sesuai.');
        }

        // Validasi password baru dan konfirmasi
        if ($newPassword !== $confirmPassword) {
            return redirect()->to('/user/profil')->with('error', 'Password baru dan konfirmasi password tidak sama.');
        }

        // Validasi panjang password baru
        if (strlen($newPassword) < 6) {
            return redirect()->to('/user/profil')->with('error', 'Password baru minimal 6 karakter.');
        }

        // Update password
        $userModel->update($uid, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/user/profil')->with('success', 'Password berhasil diubah.');
    }

    public function updateSecurityQuestion()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $userModel = new UserModel();
        $uid       = $this->currentUser['id'];

        $question = trim($this->request->getPost('security_question') ?? '');
        $answer   = trim($this->request->getPost('security_answer') ?? '');

        // Jika keduanya kosong → hapus pertanyaan keamanan
        if ($question === '' && $answer === '') {
            $userModel->update($uid, [
                'security_question'   => null,
                'security_answer_hash' => null,
            ]);
            return redirect()->to('/user/profil')->with('success', 'Pertanyaan keamanan dihapus.');
        }

        // Validasi: keduanya harus diisi jika salah satu diisi
        if ($question === '') {
            return redirect()->to('/user/profil')->with('error', 'Pertanyaan keamanan tidak boleh kosong.');
        }
        if ($answer === '') {
            return redirect()->to('/user/profil')->with('error', 'Jawaban tidak boleh kosong.');
        }

        // Simpan pertanyaan dan hash jawaban (lowercase untuk case-insensitive)
        $userModel->update($uid, [
            'security_question'   => $question,
            'security_answer_hash' => password_hash(strtolower($answer), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/user/profil')->with('success', 'Pertanyaan keamanan berhasil disimpan.');
    }
}


