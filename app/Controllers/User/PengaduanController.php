<?php

namespace App\Controllers\User;

use App\Controllers\ProtectedController;
use App\Models\PengaduanModel;
use App\Models\UserProfileModel;

class PengaduanController extends ProtectedController
{
    // ----------------------------------------------------------------
    // Batas rate limit: 3 pengaduan per jam per user
    // ----------------------------------------------------------------
    private const RATE_LIMIT       = 3;
    private const RATE_WINDOW_SECS = 3600; // 1 jam

    // ----------------------------------------------------------------
    // Konfigurasi CAPTCHA
    // ----------------------------------------------------------------
    private const CAPTCHA_LENGTH = 5;

    // ----------------------------------------------------------------
    // index() — tampilkan form pengaduan
    // ----------------------------------------------------------------
    public function index()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $profileModel = new UserProfileModel();
        $profile      = $profileModel->find($this->currentUser['id']);

        return view('User/pengaduan/index', [
            'title'           => 'Buat Pengaduan',
            'profile'         => $profile,
            'cooldownSeconds' => $this->getCooldownSeconds(),
        ]);
    }

    // ----------------------------------------------------------------
    // captcha() — generate GD image, simpan jawaban di session
    // ----------------------------------------------------------------
    public function captcha()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $answer = $this->generateCaptchaString(self::CAPTCHA_LENGTH);
        session()->set('captcha_answer', strtolower($answer));
        session()->set('captcha_verified', false);

        // Buat gambar GD 150 × 50
        $width  = 160;
        $height = 50;
        $img    = imagecreatetruecolor($width, $height);

        // Warna latar & teks
        $bg        = imagecolorallocate($img, 245, 247, 250);
        $textColor = imagecolorallocate($img, 30, 80, 30);
        $noiseColor = imagecolorallocate($img, 180, 200, 180);

        imagefilledrectangle($img, 0, 0, $width - 1, $height - 1, $bg);

        // Tambah noise (titik-titik & garis)
        for ($i = 0; $i < 400; $i++) {
            imagesetpixel($img, rand(0, $width), rand(0, $height), $noiseColor);
        }
        for ($i = 0; $i < 4; $i++) {
            imageline(
                $img,
                rand(0, $width / 2), rand(0, $height),
                rand($width / 2, $width), rand(0, $height),
                $noiseColor
            );
        }

        // Tulis karakter satu per satu dengan posisi & rotasi acak
        $fontPath  = $this->getCaptchaFontPath();
        $charWidth = (int) ($width / (self::CAPTCHA_LENGTH + 1));
        for ($i = 0; $i < strlen($answer); $i++) {
            $x     = $charWidth * $i + rand(8, 14);
            $y     = rand(28, $height - 6);
            $angle = rand(-12, 12);
            if ($fontPath !== null && function_exists('imagettftext')) {
                imagettftext($img, rand(20, 24), $angle, $x, $y, $textColor, $fontPath, $answer[$i]);
            } else {
                // Fallback: imagestring (tidak rotasi, tapi selalu bekerja)
                imagestring($img, 5, $x, (int) ($height / 2) - 8, $answer[$i], $textColor);
            }
        }

        // Border tipis
        $border = imagecolorallocate($img, 200, 220, 200);
        imagerectangle($img, 0, 0, $width - 1, $height - 1, $border);

        // Output sebagai PNG
        $this->response->setHeader('Content-Type', 'image/png');
        $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');

        ob_start();
        imagepng($img);
        $imageData = ob_get_clean();
        imagedestroy($img);

        return $this->response->setBody($imageData);
    }

    // ----------------------------------------------------------------
    // verifyCaptcha() — AJAX POST, cek jawaban user vs session
    // ----------------------------------------------------------------
    public function verifyCaptcha()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false]);
        }

        $userAnswer = strtolower(trim($this->request->getPost('answer') ?? ''));
        $correct    = session()->get('captcha_answer') ?? '';

        if ($userAnswer === '' || $correct === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Jawaban tidak boleh kosong.']);
        }

        if ($userAnswer === $correct) {
            session()->set('captcha_verified', true);
            return $this->response->setJSON(['success' => true]);
        }

        // Jawaban salah — reset session, paksa generate ulang
        session()->set('captcha_answer', '');
        session()->set('captcha_verified', false);
        return $this->response->setJSON(['success' => false, 'message' => 'Kode CAPTCHA salah. Silakan coba lagi.']);
    }

    // ----------------------------------------------------------------
    // store() — proses simpan pengaduan
    // ----------------------------------------------------------------
    public function store()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        // --- Cek CAPTCHA server-side ---
        if (!session()->get('captcha_verified')) {
            return redirect()->back()->withInput()->with('error', 'Verifikasi CAPTCHA diperlukan sebelum mengirim pengaduan.');
        }

        // --- Cek rate limit ---
        $cooldown = $this->getCooldownSeconds();
        if ($cooldown > 0) {
            $menit  = ceil($cooldown / 60);
            return redirect()->back()->withInput()->with('error', "Batas pengiriman tercapai. Coba lagi dalam {$menit} menit.");
        }

        $pengaduanModel = new PengaduanModel();
        $userId         = session()->get('user_id');

        // ----------------------------------------------------------------
        // Sanitasi & validasi input
        // ----------------------------------------------------------------
        $nama    = trim(strip_tags($this->request->getPost('nama') ?? ''));
        $kontak  = trim(strip_tags($this->request->getPost('kontak') ?? ''));
        $perihal = trim(strip_tags($this->request->getPost('perihal') ?? ''));
        $isi     = trim(strip_tags($this->request->getPost('isi') ?? ''));

        // Hapus karakter < > dari semua field (mencegah tag injection sisa strip_tags)
        $perihal = preg_replace('/[<>]/', '', $perihal);
        $isi     = preg_replace('/[<>]/', '', $isi);

        // Nama: hanya huruf & spasi
        if (!preg_match('/^[\p{L}\s]{1,100}$/u', $nama)) {
            return redirect()->back()->withInput()->with('error', 'Nama tidak valid. Hanya huruf dan spasi, maksimal 100 karakter.');
        }

        // Kontak: tidak boleh kosong, maksimal 50 karakter
        if (mb_strlen($kontak) < 1 || mb_strlen($kontak) > 50) {
            return redirect()->back()->withInput()->with('error', 'Kontak tidak boleh kosong dan maksimal 50 karakter.');
        }

        // Perihal: max 200 karakter
        if (mb_strlen($perihal) < 3 || mb_strlen($perihal) > 200) {
            return redirect()->back()->withInput()->with('error', 'Perihal harus antara 3–200 karakter.');
        }

        // Isi: max 2000 karakter
        if (mb_strlen($isi) < 10 || mb_strlen($isi) > 2000) {
            return redirect()->back()->withInput()->with('error', 'Isi pengaduan harus antara 10–2000 karakter.');
        }

        // Blok pola script berbahaya di perihal & isi
        $dangerPattern = '/(javascript\s*:|vbscript\s*:|data\s*:|expression\s*\(|on\w+\s*=|<\s*script|\\$\{|`[^`]*`)/i';
        if (preg_match($dangerPattern, $perihal) || preg_match($dangerPattern, $isi)) {
            return redirect()->back()->withInput()->with('error', 'Input mengandung karakter atau pola yang tidak diizinkan.');
        }

        $userName = $nama ?: (session()->get('nama_lengkap') ?: session()->get('username'));

        $data = [
            'user_id' => $userId,
            'nama'    => $userName,
            'kontak'  => $kontak,
            'perihal' => $perihal,
            'isi'     => $isi,
        ];

        // Handle foto upload
        helper('upload');
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $error = validate_image_upload($foto);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Foto tidak valid: ' . $error);
            }

            $path = FCPATH . 'uploads/pengaduan';
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            $newName = $foto->getRandomName();
            $foto->move($path, $newName);
            $data['foto'] = 'uploads/pengaduan/' . $newName;
        }

        $pengaduanId = $pengaduanModel->insert($data, true);

        // Reset CAPTCHA session setelah berhasil kirim
        session()->set('captcha_verified', false);
        session()->set('captcha_answer', '');

        // Buat notifikasi untuk semua staff
        $userModel    = new \App\Models\UserModel();
        $staffList    = $userModel->where('role', 'staf')->findAll();
        $notifModel   = new \App\Models\NotificationModel();
        $emailService = new \App\Libraries\EmailService();

        $pengaduanUrl = base_url('/staff/pengaduan/' . $pengaduanId);

        foreach ($staffList as $staff) {
            $notifModel->insert([
                'user_id'              => $staff['id'],
                'type'                 => 'new_pengaduan',
                'title'                => 'Pengaduan Baru Masuk',
                'message'              => 'Pengaduan baru dari ' . $data['nama'] . ' - ' . $data['perihal'],
                'related_pengaduan_id' => $pengaduanId,
                'is_read'              => 0,
                'created_at'           => date('Y-m-d H:i:s'),
            ]);

            $emailService->sendNotification(
                $staff['email'],
                $staff['username'],
                'Pengaduan Baru Masuk',
                'Pengaduan baru dari ' . $data['nama'],
                'info',
                $pengaduanUrl,
                $data['perihal'],
                'Pengaduan'
            );
        }

        return redirect()->to('/user/pengaduan')->with('message', 'Pengaduan berhasil dikirim');
    }

    // ================================================================
    // PRIVATE HELPERS
    // ================================================================

    /**
     * Hitung sisa detik cooldown rate-limit untuk user saat ini.
     * Return 0 jika user masih boleh mengirim.
     */
    private function getCooldownSeconds(): int
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return 0;
        }

        $pengaduanModel = new PengaduanModel();
        $windowStart    = date('Y-m-d H:i:s', time() - self::RATE_WINDOW_SECS);

        // Ambil pengaduan dalam jendela 1 jam terakhir, urut dari yang paling lama
        $recentList = $pengaduanModel
            ->where('user_id', $userId)
            ->where('created_at >=', $windowStart)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $count = count($recentList);

        if ($count < self::RATE_LIMIT) {
            return 0;
        }

        // Hitung kapan pengaduan ke-(count - RATE_LIMIT + 1) dari urutan lama akan expire
        $oldestInWindow = $recentList[$count - self::RATE_LIMIT];
        $expireAt       = strtotime($oldestInWindow['created_at']) + self::RATE_WINDOW_SECS;
        $remaining      = $expireAt - time();

        return $remaining > 0 ? (int) $remaining : 0;
    }

    /**
     * Generate string acak huruf & angka untuk CAPTCHA
     */
    private function generateCaptchaString(int $length): string
    {
        // Hindari karakter yang mudah membingungkan: 0/O, 1/I/l
        $chars  = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $result;
    }

    /**
     * Kembalikan path font TTF untuk imagettftext().
     * Return null jika tidak ada font TTF yang tersedia (akan fallback ke imagestring).
     */
    private function getCaptchaFontPath(): ?string
    {
        // Font custom project (letakkan di public/assets/fonts/captcha.ttf untuk override)
        $candidates = [
            FCPATH . 'assets/fonts/captcha.ttf',
            // Windows
            'C:/Windows/Fonts/arialbd.ttf',
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/courbd.ttf',
            'C:/Windows/Fonts/cour.ttf',
            // Linux / macOS
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            '/usr/share/fonts/truetype/freefont/FreeSansBold.ttf',
            '/usr/share/fonts/truetype/ubuntu/Ubuntu-B.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
