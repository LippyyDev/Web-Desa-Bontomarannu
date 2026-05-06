<?php

namespace App\Controllers\Guest;

use App\Controllers\BaseController;
use App\Libraries\EmailService;
use App\Models\UserModel;
use App\Models\UserProfileModel;

class AuthController extends BaseController
{
    private function generateOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function generateVerificationToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function login()
    {
        if ($this->currentUser) {
            return redirect()->to('/dashboard');
        }

        return view('Guest/auth/login');
    }

    public function doLogin()
    {
        $identity = trim($this->request->getPost('identity'));
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user      = $userModel->where('email', $identity)
            ->orWhere('username', $identity)
            ->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Kredensial tidak valid.');
        }

        if ($user['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Akun nonaktif. Hubungi admin.');
        }

        if (!(bool) $user['is_verified']) {
            $otp = $this->generateOtp();
            $verificationToken = $this->generateVerificationToken();
            
            // Simpan data verifikasi di session (tidak perlu tabel)
            $verificationData = [
                'user_id'    => $user['id'],
                'email'      => $user['email'],
                'username'   => $user['username'],
                'otp'        => $otp,
                'token'      => $verificationToken,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            ];
            session()->set('pending_user_verification', $verificationData);
            
            // Buat link verifikasi
            $verificationLink = base_url('/verify/' . $verificationToken);
            
            // Kirim email OTP dan link
            $emailService = new EmailService();
            $emailService->sendOtpRegister($user['email'], $user['username'], $otp, $verificationLink);
            
            session()->setFlashdata('otp_preview', $otp);
            session()->set('pending_verification', $user['email']);

            return redirect()->to('/verify')->with('info', 'Silakan verifikasi akun terlebih dahulu. Kode OTP dan link verifikasi telah dikirim ke email Anda.');
        }

        session()->set('user', [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role'],
        ]);

        return redirect()->to('/dashboard');
    }

    public function register()
    {
        return view('Guest/auth/register');
    }

    public function doRegister()
    {
        $username = trim($this->request->getPost('username'));
        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('password_confirm');

        if ($password !== $confirm) {
            return redirect()->back()->withInput()->with('error', 'Password tidak sama.');
        }

        // Cek apakah email atau username sudah digunakan
        $userModel = new UserModel();
        $exists    = $userModel->where('email', $email)->orWhere('username', $username)->first();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Username atau email sudah digunakan.');
        }

        // JANGAN SIMPAN KE DATABASE DULU - Simpan data sementara di session
        $otp = $this->generateOtp();
        $verificationToken = $this->generateVerificationToken();
        
        // Simpan data registrasi di session
        $registrationData = [
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'otp'           => $otp,
            'token'         => $verificationToken,
            'expires_at'    => date('Y-m-d H:i:s', strtotime('+1 hour')),
        ];
        
        session()->set('pending_registration', $registrationData);
        
        // Buat link verifikasi (token disimpan di session, tidak di database)
        $verificationLink = base_url('/verify/' . $verificationToken);
        
        // Kirim email dengan OTP dan link
        $emailService = new EmailService();
        $emailSent = $emailService->sendOtpRegister($email, $username, $otp, $verificationLink);

        session()->set('pending_verification', $email);
        session()->setFlashdata('otp_preview', $otp);

        if ($emailSent) {
            return redirect()->to('/verify')->with('info', 'Registrasi berhasil! Silakan cek email Anda untuk kode OTP dan link verifikasi.');
        } else {
            return redirect()->to('/verify')->with('warning', 'Registrasi berhasil, namun gagal mengirim email. Silakan cek kode OTP di bawah ini.');
        }
    }

    public function verify()
    {
        $pendingEmail = session()->get('pending_verification');

        return view('Guest/auth/verify', [
            'pendingEmail' => $pendingEmail,
            'previewOtp'   => session()->getFlashdata('otp_preview'),
        ]);
    }

    public function doVerify()
    {
        $email = trim($this->request->getPost('email'));
        $otp   = trim($this->request->getPost('otp'));

        // Cek apakah ini registrasi baru atau verifikasi user yang sudah ada
        $registrationData = session()->get('pending_registration');
        $userVerificationData = session()->get('pending_user_verification');
        
        // Kasus 1: Registrasi baru
        if ($registrationData) {
            // Cek apakah email cocok
            if ($registrationData['email'] !== $email) {
                return redirect()->back()->with('error', 'Email tidak sesuai dengan data registrasi.');
            }

            // Cek apakah OTP cocok
            if ($registrationData['otp'] !== $otp) {
                return redirect()->back()->with('error', 'OTP tidak valid.');
            }

            // Cek apakah sudah kadaluarsa
            if (strtotime($registrationData['expires_at']) < time()) {
                session()->remove('pending_registration');
                return redirect()->to('/register')->with('error', 'Kode verifikasi sudah kadaluarsa. Silakan daftar ulang.');
            }

            // Verifikasi berhasil - SIMPAN KE DATABASE SEKARANG
            $userModel = new UserModel();
            $userId = $userModel->insert([
                'username'      => $registrationData['username'],
                'email'         => $registrationData['email'],
                'password_hash' => $registrationData['password_hash'],
                'role'          => 'user',
                'status'        => 'aktif',
                'is_verified'   => 1,
            ], true);

            // Buat profile
            $profileModel = new UserProfileModel();
            $profileModel->insert([
                'user_id'      => $userId,
                'nama_lengkap' => $registrationData['username'],
            ]);

            // Bersihkan session
            session()->remove('pending_registration');
            session()->remove('pending_verification');
            
            // Set session user
            $user = $userModel->find($userId);
            session()->set('user', [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'     => $user['role'],
            ]);

            return redirect()->to('/dashboard')->with('success', 'Akun berhasil diverifikasi dan dibuat.');
        }
        
        // Kasus 2: Verifikasi user yang sudah ada (belum verified)
        if ($userVerificationData) {
            // Cek email
            if ($userVerificationData['email'] !== $email) {
                return redirect()->back()->with('error', 'Email tidak sesuai.');
        }

            // Cek OTP
            if ($userVerificationData['otp'] !== $otp) {
                return redirect()->back()->with('error', 'OTP tidak valid.');
            }

            // Cek kadaluarsa
            if (strtotime($userVerificationData['expires_at']) < time()) {
                session()->remove('pending_user_verification');
                return redirect()->to('/login')->with('error', 'Kode verifikasi sudah kadaluarsa. Silakan login lagi untuk mendapatkan kode baru.');
            }

            // Update user menjadi verified
            $userModel = new UserModel();
            $userModel->update($userVerificationData['user_id'], ['is_verified' => 1]);

            // Bersihkan session
            session()->remove('pending_user_verification');
            session()->remove('pending_verification');
            
            // Set session user
            $user = $userModel->find($userVerificationData['user_id']);
            session()->set('user', [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'     => $user['role'],
            ]);

            return redirect()->to('/dashboard')->with('success', 'Akun berhasil diverifikasi.');
        }

        // Tidak ada data verifikasi
        return redirect()->back()->with('error', 'Sesi verifikasi tidak ditemukan. Silakan daftar atau login ulang.');
    }
    
    /**
     * Verifikasi via link
     */
    public function verifyByLink(string $token)
    {
        // Ambil data registrasi dari session
        $registrationData = session()->get('pending_registration');
        
        if (!$registrationData) {
            return redirect()->to('/register')->with('error', 'Sesi registrasi tidak ditemukan. Silakan daftar ulang.');
        }

        // Cek apakah token cocok dengan yang di session
        // Tidak perlu cek di database karena token disimpan di session
        if ($registrationData['token'] !== $token) {
            return redirect()->to('/register')->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }

        // Cek apakah sudah kadaluarsa
        if (strtotime($registrationData['expires_at']) < time()) {
            session()->remove('pending_registration');
            return redirect()->to('/register')->with('error', 'Link verifikasi sudah kadaluarsa. Silakan daftar ulang.');
        }

        // Verifikasi berhasil - SIMPAN KE DATABASE SEKARANG
        $userModel = new UserModel();
        $userId = $userModel->insert([
            'username'      => $registrationData['username'],
            'email'         => $registrationData['email'],
            'password_hash' => $registrationData['password_hash'],
            'role'          => 'user',
            'status'        => 'aktif',
            'is_verified'   => 1, // Langsung verified karena sudah verifikasi
        ], true);

        // Buat profile
        $profileModel = new UserProfileModel();
        $profileModel->insert([
            'user_id'      => $userId,
            'nama_lengkap' => $registrationData['username'],
        ]);

        // Tidak perlu update token di database karena token tidak disimpan di database untuk registrasi pending

        // Bersihkan session
        session()->remove('pending_registration');
        session()->remove('pending_verification');
        
        // Set session user
        $user = $userModel->find($userId);
        session()->set('user', [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role'],
        ]);

        return redirect()->to('/dashboard')->with('success', 'Akun berhasil diverifikasi dan dibuat.');
    }

    public function forgotPassword()
    {
        return view('Guest/auth/forgot');
    }

    public function sendReset()
    {
        $email     = trim($this->request->getPost('email'));
        $userModel = new UserModel();
        $user      = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak terdaftar.');
        }

        // Generate OTP untuk reset password - simpan di session (tidak perlu tabel)
        $otp = $this->generateOtp();
        
        // Simpan data reset di session
        $resetData = [
            'user_id'    => $user['id'],
            'email'      => $user['email'],
            'username'   => $user['username'],
            'otp'        => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
        ];
        session()->set('pending_password_reset', $resetData);
        
        // Kirim email OTP
        $emailService = new EmailService();
        $emailSent = $emailService->sendOtpReset($email, $user['username'], $otp);
        
        session()->setFlashdata('otp_preview', $otp);
        session()->set('pending_reset', $email);

        if ($emailSent) {
            return redirect()->to('/reset-password')->with('info', 'Kode OTP telah dikirim ke email Anda. Silakan cek inbox email Anda.');
        } else {
            return redirect()->to('/reset-password')->with('warning', 'Gagal mengirim email. Silakan cek kode OTP di bawah ini atau coba lagi nanti.');
        }
    }

    public function resetPassword()
    {
        return view('Guest/auth/reset', [
            'pendingEmail' => session()->get('pending_reset'),
            'previewOtp'   => session()->getFlashdata('otp_preview'),
        ]);
    }

    public function doResetPassword()
    {
        $email    = trim($this->request->getPost('email'));
        $otp      = trim($this->request->getPost('otp'));
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('password_confirm');

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Password tidak sama.');
        }

        // Ambil data reset dari session (tidak perlu tabel)
        $resetData = session()->get('pending_password_reset');

        if (!$resetData) {
            return redirect()->back()->with('error', 'Sesi reset password tidak ditemukan. Silakan request reset password lagi.');
        }

        // Cek email
        if ($resetData['email'] !== $email) {
            return redirect()->back()->with('error', 'Email tidak sesuai.');
        }

        // Cek OTP
        if ($resetData['otp'] !== $otp) {
            return redirect()->back()->with('error', 'OTP tidak valid.');
        }

        // Cek kadaluarsa
        if (strtotime($resetData['expires_at']) < time()) {
            session()->remove('pending_password_reset');
            return redirect()->to('/forgot-password')->with('error', 'Kode OTP sudah kadaluarsa. Silakan request reset password lagi.');
        }

        // Update password
        $userModel = new UserModel();
        $userModel->update($resetData['user_id'], [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);
        
        // Bersihkan session
        session()->remove('pending_password_reset');
        session()->remove('pending_reset');

        return redirect()->to('/login')->with('success', 'Password berhasil direset.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }
}


