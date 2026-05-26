<?php

namespace App\Controllers\Guest;

use App\Controllers\BaseController;
use App\Libraries\EmailService;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Models\UserProfileModel;

class AuthController extends BaseController
{
    // ─── Konstanta Rate Limit ─────────────────────────────────────────────────

    private const LOGIN_MAX_ATTEMPTS   = 3;
    private const LOGIN_BLOCK_SECONDS  = 900;  // 15 menit
    private const OTP_COOLDOWN_SECONDS = 60;   // 60 detik per request
    private const OTP_MAX_REQUESTS     = 5;    // maks request dalam 1 jam
    private const OTP_FREEZE_SECONDS   = 3600; // pembekuan 1 jam
    private const OTP_WINDOW_SECONDS   = 3600; // jendela waktu 1 jam

    // ─── Notifikasi Admin ─────────────────────────────────────────────────────

    /**
     * Kirim notifikasi ke semua akun admin ketika ada registrasi baru.
     */
    private function notifyAdmins(string $username, string $email): void
    {
        $userModel  = new UserModel();
        $notifModel = new NotificationModel();

        $admins = $userModel->where('role', 'admin')->findAll();
        $now    = date('Y-m-d H:i:s');

        foreach ($admins as $admin) {
            $notifModel->insert([
                'user_id'    => $admin['id'],
                'type'       => 'new_registration',
                'title'      => 'Akun Baru Terdaftar',
                'message'    => "Pengguna baru \"$username\" ($email) telah berhasil mendaftar dan memverifikasi akunnya.",
                'is_read'    => 0,
                'created_at' => $now,
            ]);
        }
    }

    // ─── Crypto Helpers ───────────────────────────────────────────────────────

    private function generateOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function generateVerificationToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Buat signed token untuk magic link reset password.
     * Format: base64(payload).hmac_sha256
     * Tidak memerlukan tabel database — diverifikasi via HMAC.
     */
    private function generateSignedResetToken(string $email, string $otp): string
    {
        $payload = base64_encode(json_encode([
            'email' => $email,
            'otp'   => $otp,
            'exp'   => time() + 3600,
        ]));
        $secret  = env('app.key', 'fallback-secret-key');
        $sig     = hash_hmac('sha256', $payload, $secret);
        return $payload . '.' . $sig;
    }

    /**
     * Verifikasi signed token dari magic link.
     * Return array payload jika valid, null jika tidak valid / kedaluwarsa.
     */
    private function verifySignedResetToken(string $token): ?array
    {
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) {
            return null;
        }

        [$payload, $sig] = $parts;
        $secret   = env('app.key', 'fallback-secret-key');
        $expected = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($expected, $sig)) {
            return null;
        }

        $data = json_decode(base64_decode($payload), true);
        if (!$data || !isset($data['exp']) || $data['exp'] < time()) {
            return null;
        }

        return $data;
    }

    // ─── Rate Limit: Login ────────────────────────────────────────────────────

    /**
     * Buat cache key berbasis IP yang di-hash untuk keamanan.
     */
    private function ipKey(string $prefix): string
    {
        return $prefix . '_' . md5($this->request->getIPAddress());
    }

    /**
     * Cek status rate limit login untuk IP saat ini.
     * Return: ['blocked' => true,  'seconds_left' => int]
     *      or ['blocked' => false, 'fail_count'   => int]
     */
    private function getLoginRateLimit(): array
    {
        $blockKey = $this->ipKey('login_block');
        $failsKey = $this->ipKey('login_fails');

        $blockedUntil = cache($blockKey);
        if ($blockedUntil && $blockedUntil > time()) {
            return ['blocked' => true, 'seconds_left' => (int) ($blockedUntil - time())];
        }

        return ['blocked' => false, 'fail_count' => (int) (cache($failsKey) ?? 0)];
    }

    /**
     * Catat satu kegagalan login. Blokir IP jika sudah >= LOGIN_MAX_ATTEMPTS.
     * Return array berisi info hasil (blocked, fail_count, remaining).
     */
    private function recordLoginFailure(): array
    {
        $blockKey = $this->ipKey('login_block');
        $failsKey = $this->ipKey('login_fails');

        $count = (int) (cache($failsKey) ?? 0) + 1;
        cache()->save($failsKey, $count, self::LOGIN_BLOCK_SECONDS);

        if ($count >= self::LOGIN_MAX_ATTEMPTS) {
            $blockedUntil = time() + self::LOGIN_BLOCK_SECONDS;
            cache()->save($blockKey, $blockedUntil, self::LOGIN_BLOCK_SECONDS);
            return ['blocked' => true, 'seconds_left' => self::LOGIN_BLOCK_SECONDS, 'fail_count' => $count];
        }

        return ['blocked' => false, 'fail_count' => $count, 'remaining' => self::LOGIN_MAX_ATTEMPTS - $count];
    }

    /**
     * Reset counter kegagalan login (dipanggil setelah login berhasil).
     */
    private function clearLoginFailures(): void
    {
        cache()->delete($this->ipKey('login_block'));
        cache()->delete($this->ipKey('login_fails'));
    }

    // ─── Rate Limit: OTP ─────────────────────────────────────────────────────

    /**
     * Cek status rate limit OTP untuk IP saat ini.
     * Return salah satu dari:
     *   ['ok'       => true, 'request_count' => int, 'requests' => array]
     *   ['cooldown' => true, 'seconds_left'  => int]
     *   ['frozen'   => true, 'seconds_left'  => int]
     */
    private function getOtpRateLimit(): array
    {
        $freezeKey   = $this->ipKey('otp_freeze');
        $requestsKey = $this->ipKey('otp_requests');

        // Cek pembekuan aktif
        $frozenUntil = cache($freezeKey);
        if ($frozenUntil && $frozenUntil > time()) {
            return ['frozen' => true, 'seconds_left' => (int) ($frozenUntil - time())];
        }

        // Ambil log timestamp request OTP dalam 1 jam terakhir
        $requests = cache($requestsKey) ?? [];
        $now      = time();
        $cutoff   = $now - self::OTP_WINDOW_SECONDS;
        $requests = array_values(array_filter($requests, fn ($t) => $t > $cutoff));

        // Cek cooldown 60 detik sejak request terakhir
        if (!empty($requests)) {
            $sinceLastRequest = $now - max($requests);
            if ($sinceLastRequest < self::OTP_COOLDOWN_SECONDS) {
                return ['cooldown' => true, 'seconds_left' => (int) (self::OTP_COOLDOWN_SECONDS - $sinceLastRequest)];
            }
        }

        return ['ok' => true, 'request_count' => count($requests), 'requests' => $requests];
    }

    /**
     * Catat satu request OTP. Bekukan IP jika >= OTP_MAX_REQUESTS dalam 1 jam.
     */
    private function recordOtpRequest(): void
    {
        $freezeKey   = $this->ipKey('otp_freeze');
        $requestsKey = $this->ipKey('otp_requests');

        $requests = cache($requestsKey) ?? [];
        $now      = time();
        $cutoff   = $now - self::OTP_WINDOW_SECONDS;
        $requests = array_values(array_filter($requests, fn ($t) => $t > $cutoff));
        $requests[] = $now;

        cache()->save($requestsKey, $requests, self::OTP_WINDOW_SECONDS);

        // Bekukan jika sudah >= 5 request dalam 1 jam
        if (count($requests) >= self::OTP_MAX_REQUESTS) {
            cache()->save($freezeKey, $now + self::OTP_FREEZE_SECONDS, self::OTP_FREEZE_SECONDS);
        }
    }

    // ─── Login ────────────────────────────────────────────────────────────────

    public function login()
    {
        if ($this->currentUser) {
            return redirect()->to('/dashboard');
        }

        $rateLimit = $this->getLoginRateLimit();

        return view('Guest/auth/login', [
            'title'     => 'Login | Website Desa Bonto Marannu',
            'rateLimit' => $rateLimit,
        ]);
    }

    public function doLogin()
    {
        // Cek rate limit login sebelum memproses
        $rateLimit = $this->getLoginRateLimit();
        if ($rateLimit['blocked']) {
            return redirect()->back()->with('error', 'Terlalu banyak percobaan login. Akses diblokir selama 15 menit.');
        }

        $identity = trim($this->request->getPost('identity'));
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user      = $userModel->where('email', $identity)
            ->orWhere('username', $identity)
            ->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $result = $this->recordLoginFailure();
            if ($result['blocked']) {
                return redirect()->back()->withInput()
                    ->with('error', 'Terlalu banyak percobaan login. Akses login diblokir selama 15 menit.');
            }
            return redirect()->back()->withInput()
                ->with('error', 'Kredensial tidak valid. Sisa percobaan: ' . $result['remaining'] . ' kali.');
        }

        if ($user['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Akun nonaktif. Hubungi admin.');
        }

        if (!(bool) $user['is_verified']) {
            $otp               = $this->generateOtp();
            $verificationToken = $this->generateVerificationToken();

            $verificationData = [
                'user_id'    => $user['id'],
                'email'      => $user['email'],
                'username'   => $user['username'],
                'otp'        => $otp,
                'token'      => $verificationToken,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            ];
            session()->set('pending_user_verification', $verificationData);

            $verificationLink = base_url('/verify/' . $verificationToken);

            try {
                $emailService = new EmailService();
                $emailService->sendOtpRegister($user['email'], $user['username'], $otp, $verificationLink);
            } catch (\Exception $e) {
                log_message('error', 'Gagal queue OTP login: ' . $e->getMessage());
            }

            session()->set('pending_verification', $user['email']);

            return redirect()->to('/verify')
                ->with('info', 'Silakan verifikasi akun terlebih dahulu. Kode OTP dan link verifikasi telah dikirim ke email Anda.');
        }

        // Login berhasil — reset counter kegagalan
        $this->clearLoginFailures();

        session()->set('user', [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role'],
        ]);

        return redirect()->to('/dashboard');
    }

    // ─── Register ─────────────────────────────────────────────────────────────

    public function register()
    {
        return view('Guest/auth/register', ['title' => 'Daftar Akun | Website Desa Bonto Marannu']);
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

        $userModel = new UserModel();
        $exists    = $userModel->where('email', $email)->orWhere('username', $username)->first();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Username atau email sudah digunakan.');
        }

        $otp               = $this->generateOtp();
        $verificationToken = $this->generateVerificationToken();

        $registrationData = [
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'otp'           => $otp,
            'token'         => $verificationToken,
            'expires_at'    => date('Y-m-d H:i:s', strtotime('+1 hour')),
        ];

        session()->set('pending_registration', $registrationData);

        $verificationLink = base_url('/verify/' . $verificationToken);

        $emailSent = false;
        try {
            $emailService = new EmailService();
            $emailSent    = $emailService->sendOtpRegister($email, $username, $otp, $verificationLink);
        } catch (\Exception $e) {
            log_message('error', 'Gagal queue OTP registrasi: ' . $e->getMessage());
        }

        session()->set('pending_verification', $email);

        if ($emailSent) {
            return redirect()->to('/verify')
                ->with('info', 'Registrasi berhasil! Silakan cek email Anda untuk kode OTP dan link verifikasi.');
        } else {
            return redirect()->to('/verify')
                ->with('warning', 'Registrasi berhasil, namun gagal mengirim email. Silakan coba lagi atau hubungi admin.');
        }
    }

    // ─── Verify (Registrasi) ──────────────────────────────────────────────────

    public function verify()
    {
        $pendingEmail = session()->get('pending_verification');

        return view('Guest/auth/verify', [
            'title'        => 'Verifikasi Akun | Website Desa Bonto Marannu',
            'pendingEmail' => $pendingEmail,
        ]);
    }

    public function doVerify()
    {
        $email = trim($this->request->getPost('email'));
        $otp   = trim($this->request->getPost('otp'));

        $registrationData     = session()->get('pending_registration');
        $userVerificationData = session()->get('pending_user_verification');

        // Kasus 1: Registrasi baru
        if ($registrationData) {
            if ($registrationData['email'] !== $email) {
                return redirect()->back()->with('error', 'Email tidak sesuai dengan data registrasi.');
            }

            if ($registrationData['otp'] !== $otp) {
                return redirect()->back()->with('error', 'OTP tidak valid.');
            }

            if (strtotime($registrationData['expires_at']) < time()) {
                session()->remove('pending_registration');
                return redirect()->to('/register')->with('error', 'Kode verifikasi sudah kadaluarsa. Silakan daftar ulang.');
            }

            $userModel = new UserModel();
            $userId    = $userModel->insert([
                'username'      => $registrationData['username'],
                'email'         => $registrationData['email'],
                'password_hash' => $registrationData['password_hash'],
                'role'          => 'user',
                'status'        => 'aktif',
                'is_verified'   => 1,
            ], true);

            $profileModel = new UserProfileModel();
            $profileModel->insert([
                'user_id'      => $userId,
                'nama_lengkap' => $registrationData['username'],
            ]);

            session()->remove('pending_registration');
            session()->remove('pending_verification');

            $this->notifyAdmins($registrationData['username'], $registrationData['email']);

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
            if ($userVerificationData['email'] !== $email) {
                return redirect()->back()->with('error', 'Email tidak sesuai.');
            }

            if ($userVerificationData['otp'] !== $otp) {
                return redirect()->back()->with('error', 'OTP tidak valid.');
            }

            if (strtotime($userVerificationData['expires_at']) < time()) {
                session()->remove('pending_user_verification');
                return redirect()->to('/login')->with('error', 'Kode verifikasi sudah kadaluarsa. Silakan login lagi untuk mendapatkan kode baru.');
            }

            $userModel = new UserModel();
            $userModel->update($userVerificationData['user_id'], ['is_verified' => 1]);

            session()->remove('pending_user_verification');
            session()->remove('pending_verification');

            $user = $userModel->find($userVerificationData['user_id']);
            session()->set('user', [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'     => $user['role'],
            ]);

            return redirect()->to('/dashboard')->with('success', 'Akun berhasil diverifikasi.');
        }

        return redirect()->back()->with('error', 'Sesi verifikasi tidak ditemukan. Silakan daftar atau login ulang.');
    }

    /**
     * Verifikasi via link magic token.
     */
    public function verifyByLink(string $token)
    {
        $registrationData = session()->get('pending_registration');

        if (!$registrationData) {
            return redirect()->to('/register')->with('error', 'Sesi registrasi tidak ditemukan. Silakan daftar ulang.');
        }

        if ($registrationData['token'] !== $token) {
            return redirect()->to('/register')->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }

        if (strtotime($registrationData['expires_at']) < time()) {
            session()->remove('pending_registration');
            return redirect()->to('/register')->with('error', 'Link verifikasi sudah kadaluarsa. Silakan daftar ulang.');
        }

        $userModel = new UserModel();
        $userId    = $userModel->insert([
            'username'      => $registrationData['username'],
            'email'         => $registrationData['email'],
            'password_hash' => $registrationData['password_hash'],
            'role'          => 'user',
            'status'        => 'aktif',
            'is_verified'   => 1,
        ], true);

        $profileModel = new UserProfileModel();
        $profileModel->insert([
            'user_id'      => $userId,
            'nama_lengkap' => $registrationData['username'],
        ]);

        session()->remove('pending_registration');
        session()->remove('pending_verification');

        $this->notifyAdmins($registrationData['username'], $registrationData['email']);

        $user = $userModel->find($userId);
        session()->set('user', [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role'],
        ]);

        return redirect()->to('/dashboard')->with('success', 'Akun berhasil diverifikasi dan dibuat.');
    }

    // ─── Forgot Password ──────────────────────────────────────────────────────

    public function forgotPassword()
    {
        $otpRateLimit = $this->getOtpRateLimit();

        return view('Guest/auth/forgot', [
            'title'        => 'Lupa Password | Website Desa Bonto Marannu',
            'otpRateLimit' => $otpRateLimit,
        ]);
    }

    public function sendReset()
    {
        // Cek OTP rate limit terlebih dahulu
        $otpRateLimit = $this->getOtpRateLimit();

        if (isset($otpRateLimit['frozen']) && $otpRateLimit['frozen']) {
            return redirect()->back()
                ->with('error', 'Terlalu banyak permintaan OTP. IP Anda dibekukan, coba lagi nanti.');
        }

        if (isset($otpRateLimit['cooldown']) && $otpRateLimit['cooldown']) {
            return redirect()->back()
                ->with('error', 'Mohon tunggu ' . $otpRateLimit['seconds_left'] . ' detik sebelum meminta OTP lagi.');
        }

        $email     = trim($this->request->getPost('email'));
        $userModel = new UserModel();
        $user      = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak terdaftar.');
        }

        $otp       = $this->generateOtp();
        $token     = $this->generateSignedResetToken($email, $otp);
        $directUrl = base_url('/reset-password/' . urlencode($token));

        $resetData = [
            'user_id'               => $user['id'],
            'email'                 => $user['email'],
            'username'              => $user['username'],
            'otp'                   => $otp,
            'expires_at'            => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'has_security_question' => !empty($user['security_question']),
        ];
        session()->set('pending_password_reset', $resetData);
        session()->set('pending_reset', $email);

        $emailSent = false;
        try {
            $emailService = new EmailService();
            $emailSent    = $emailService->sendOtpReset($email, $user['username'], $otp, $directUrl);
        } catch (\Exception $e) {
            log_message('error', 'Gagal queue OTP reset password: ' . $e->getMessage());
        }

        // Catat request OTP (setelah proses, terlepas dari sukses email)
        $this->recordOtpRequest();

        if ($emailSent) {
            return redirect()->to('/verify-reset')
                ->with('info', 'Kode OTP telah dikirim ke email Anda. Silakan cek inbox email Anda.');
        } else {
            return redirect()->to('/verify-reset')
                ->with('warning', 'Gagal mengirim email. Silakan coba lagi nanti atau hubungi admin.');
        }
    }

    // ─── Verify Reset (OTP lupa password) ────────────────────────────────────

    public function verifyReset()
    {
        $otpRateLimit = $this->getOtpRateLimit();
        $resetData    = session()->get('pending_password_reset');

        // Cek apakah sebelumnya dari halaman SQ yang error (auto-buka kembali form SQ)
        $sqOpen          = (bool) session()->getFlashdata('sq_open');
        $sqLockedSeconds = (int)  (session()->getFlashdata('sq_locked_seconds') ?? 0);

        return view('Guest/auth/verify_reset', [
            'title'               => 'Verifikasi OTP | Website Desa Bonto Marannu',
            'pendingEmail'        => session()->get('pending_reset'),
            'otpRateLimit'        => $otpRateLimit,
            'hasSecurityQuestion' => !empty($resetData['has_security_question']),
            'sqOpen'              => $sqOpen,
            'sqLockedSeconds'     => $sqLockedSeconds,
            'securityQuestion'    => '', // Pertanyaan tidak ditampilkan di sini — hanya di form JS toggle
        ]);
    }

    /**
     * Kirim ulang OTP reset password via AJAX.
     * Endpoint: POST /resend-otp
     */
    public function resendOtp()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Bad request.',
            ]);
        }

        // Cek OTP rate limit
        $otpRateLimit = $this->getOtpRateLimit();

        if (isset($otpRateLimit['frozen']) && $otpRateLimit['frozen']) {
            return $this->response->setJSON([
                'success'      => false,
                'frozen'       => true,
                'seconds_left' => $otpRateLimit['seconds_left'],
                'message'      => 'IP Anda dibekukan karena terlalu banyak permintaan OTP. Coba lagi nanti.',
            ]);
        }

        if (isset($otpRateLimit['cooldown']) && $otpRateLimit['cooldown']) {
            return $this->response->setJSON([
                'success'      => false,
                'cooldown'     => true,
                'seconds_left' => $otpRateLimit['seconds_left'],
                'message'      => 'Mohon tunggu ' . $otpRateLimit['seconds_left'] . ' detik sebelum meminta OTP lagi.',
            ]);
        }

        // Cek sesi reset yang aktif
        $resetData = session()->get('pending_password_reset');
        if (!$resetData) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sesi tidak ditemukan. Silakan mulai ulang proses lupa password.',
            ]);
        }

        // Generate OTP & token baru
        $otp   = $this->generateOtp();
        $token = $this->generateSignedResetToken($resetData['email'], $otp);

        // Update sesi dengan OTP baru
        $resetData['otp']        = $otp;
        $resetData['expires_at'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
        session()->set('pending_password_reset', $resetData);

        $directUrl = base_url('/reset-password/' . urlencode($token));

        try {
            $emailService = new EmailService();
            $emailService->sendOtpReset($resetData['email'], $resetData['username'], $otp, $directUrl);
        } catch (\Exception $e) {
            log_message('error', 'Gagal resend OTP reset: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengirim email. Silakan coba lagi.',
            ]);
        }

        // Catat request OTP
        $this->recordOtpRequest();

        // Cek apakah setelah record sekarang IP langsung dibekukan
        $newLimit  = $this->getOtpRateLimit();
        $nowFrozen = isset($newLimit['frozen']) && $newLimit['frozen'];

        return $this->response->setJSON([
            'success'      => true,
            'cooldown'     => true,
            'seconds_left' => self::OTP_COOLDOWN_SECONDS,
            'frozen_after' => $nowFrozen,
            'message'      => 'Kode OTP baru telah dikirim ke email Anda.',
        ]);
    }

    /**
     * Magic link reset password.
     * Dipanggil ketika user klik tombol/link di email.
     * Token sudah berisi OTP yang terenkripsi dengan HMAC — tanpa perlu tabel DB.
     */
    public function resetByLink(string $token)
    {
        $data = $this->verifySignedResetToken(urldecode($token));

        if (!$data) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Tautan reset password tidak valid atau sudah kedaluwarsa. Silakan minta ulang.');
        }

        $userModel = new UserModel();
        $user      = $userModel->where('email', $data['email'])->first();

        if (!$user) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Akun tidak ditemukan.');
        }

        session()->set('pending_password_reset', [
            'user_id'    => $user['id'],
            'email'      => $user['email'],
            'username'   => $user['username'],
            'otp'        => $data['otp'],
            'expires_at' => date('Y-m-d H:i:s', $data['exp']),
        ]);
        session()->set('reset_otp_verified', true);

        return redirect()->to('/new-password')
            ->with('success', 'Tautan berhasil diverifikasi. Silakan buat password baru Anda.');
    }

    public function doVerifyReset()
    {
        $email = trim($this->request->getPost('email'));
        $otp   = trim($this->request->getPost('otp'));

        $resetData = session()->get('pending_password_reset');

        if (!$resetData) {
            return redirect()->back()->with('error', 'Sesi reset password tidak ditemukan. Silakan request ulang.');
        }

        if ($resetData['email'] !== $email) {
            return redirect()->back()->with('error', 'Email tidak sesuai.');
        }

        // Cek rate limit percobaan OTP salah (maks 3x per 1 jam per IP)
        $otpFailKey = 'otp_fail_' . md5($this->request->getIPAddress());
        $otpFails   = (int) (cache($otpFailKey) ?? 0);
        if ($otpFails >= 3) {
            return redirect()->back()->with('error', 'Terlalu banyak percobaan OTP salah. Silakan request OTP baru dalam 1 jam.');
        }

        if ($resetData['otp'] !== $otp) {
            $otpFails++;
            cache()->save($otpFailKey, $otpFails, 3600); // lock 1 jam
            $remaining = 3 - $otpFails;
            if ($remaining <= 0) {
                return redirect()->back()->with('error', 'Terlalu banyak percobaan OTP salah. Silakan request OTP baru dalam 1 jam.');
            }
            return redirect()->back()->with('error', 'OTP tidak valid. Sisa percobaan: ' . $remaining . ' kali.');
        }

        if (strtotime($resetData['expires_at']) < time()) {
            session()->remove('pending_password_reset');
            return redirect()->to('/forgot-password')->with('error', 'Kode OTP kadaluarsa. Silakan request ulang.');
        }

        // OTP benar — reset counter
        cache()->delete($otpFailKey);
        session()->set('reset_otp_verified', true);

        return redirect()->to('/new-password')->with('success', 'Kode OTP valid. Silakan buat password baru Anda.');
    }

    // ─── Verify Security Question ─────────────────────────────────────────────

    /**
     * Verifikasi identitas via pertanyaan keamanan saat reset password.
     * Endpoint: POST /verify-security-question
     */
    public function verifySecurityQuestion()
    {
        $resetData = session()->get('pending_password_reset');

        if (!$resetData) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Sesi tidak ditemukan. Silakan mulai ulang proses lupa password.');
        }

        if (empty($resetData['has_security_question'])) {
            return redirect()->to('/verify-reset')
                ->with('error', 'Akun ini tidak memiliki pertanyaan keamanan.');
        }

        // Cek rate limit percobaan jawaban salah (maks 3x per 1 jam per IP)
        $sqFailKey = 'sq_fail_'     . md5($this->request->getIPAddress());
        $sqExpKey  = 'sq_fail_exp_' . md5($this->request->getIPAddress());
        $sqFails   = (int) (cache($sqFailKey) ?? 0);

        if ($sqFails >= 3) {
            // Sudah terkunci — hitung sisa waktu lalu buka form SQ dengan countdown
            $expiry  = (int) (cache($sqExpKey) ?? 0);
            $secsLeft = max(0, $expiry - time());
            session()->setFlashdata('sq_open', true);
            session()->setFlashdata('sq_locked_seconds', $secsLeft);
            return redirect()->to('/verify-reset')
                ->with('error', 'Terlalu banyak percobaan salah. Tunggu ' . ceil($secsLeft / 60) . ' menit atau gunakan OTP email.');
        }

        $answer    = trim($this->request->getPost('security_answer') ?? '');
        $userModel = new UserModel();
        $user      = $userModel->find($resetData['user_id']);

        if (!$user || empty($user['security_answer_hash'])) {
            return redirect()->to('/verify-reset')
                ->with('error', 'Pertanyaan keamanan tidak ditemukan.');
        }

        // Cocokkan jawaban (case-insensitive: di-lowercase sebelum hash)
        if (!password_verify(strtolower($answer), $user['security_answer_hash'])) {
            $sqFails++;
            $expiry = time() + 3600;
            cache()->save($sqFailKey, $sqFails, 3600);  // lock 1 jam
            cache()->save($sqExpKey,  $expiry,  3600);  // simpan waktu kunci

            $remaining = 3 - $sqFails;

            // Selalu buka kembali form SQ setelah error
            session()->setFlashdata('sq_open', true);

            if ($remaining <= 0) {
                $secsLeft = 3600;
                session()->setFlashdata('sq_locked_seconds', $secsLeft);
                return redirect()->to('/verify-reset')
                    ->with('error', 'Terlalu banyak percobaan salah. Tunggu 60 menit atau gunakan OTP email.');
            }

            return redirect()->to('/verify-reset')
                ->with('error', 'Jawaban salah. Sisa percobaan: ' . $remaining . ' kali.');
        }

        // Jawaban benar — reset counter & set session verified
        cache()->delete($sqFailKey);
        session()->set('reset_otp_verified', true);

        return redirect()->to('/new-password')
            ->with('success', 'Identitas berhasil diverifikasi. Silakan buat password baru Anda.');
    }

    /**
     * Ambil teks pertanyaan keamanan dari sesi reset yang aktif.
     * Endpoint: POST /get-security-question (AJAX)
     */
    public function getSecurityQuestion()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false]);
        }

        $resetData = session()->get('pending_password_reset');

        if (!$resetData || empty($resetData['has_security_question'])) {
            return $this->response->setJSON(['success' => false, 'question' => null]);
        }

        $userModel = new UserModel();
        $user      = $userModel->find($resetData['user_id']);

        if (!$user || empty($user['security_question'])) {
            return $this->response->setJSON(['success' => false, 'question' => null]);
        }

        return $this->response->setJSON([
            'success'  => true,
            'question' => $user['security_question'],
        ]);
    }

    // ─── New Password ─────────────────────────────────────────────────────────

    public function newPassword()
    {
        if (!session()->get('reset_otp_verified') || !session()->get('pending_password_reset')) {
            return redirect()->to('/forgot-password')->with('error', 'Akses ditolak. Silakan verifikasi OTP terlebih dahulu.');
        }

        return view('Guest/auth/new_password', [
            'title' => 'Buat Password Baru | Website Desa Bonto Marannu',
        ]);
    }

    public function doNewPassword()
    {
        if (!session()->get('reset_otp_verified') || !session()->get('pending_password_reset')) {
            return redirect()->to('/forgot-password')->with('error', 'Sesi tidak valid.');
        }

        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('password_confirm');

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Password tidak sama.');
        }

        $resetData = session()->get('pending_password_reset');

        $userModel = new UserModel();
        $userModel->update($resetData['user_id'], [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        session()->remove('pending_password_reset');
        session()->remove('pending_reset');
        session()->remove('reset_otp_verified');

        return redirect()->to('/login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }
}
