<?php

namespace App\Controllers\Guest;

use App\Controllers\BaseController;
use App\Models\DesaProfileModel;
use App\Models\GalleryAlbumModel;
use App\Models\GalleryMediaModel;
use App\Models\GalleryModel;
use App\Models\GeografiDesaModel;
use App\Models\NewsModel;
use App\Models\NewsMediaModel;
use App\Models\PerangkatDesaModel;
use App\Models\InventarisDesaModel;
use App\Models\PengumumanModel;
use App\Models\PengaduanModel;
use App\Models\UmkmModel;
use App\Models\UmkmEcommerceModel;
use App\Models\UmkmProdukModel;
use App\Models\UmkmProdukGambarModel;
use App\Models\PariwisataModel;
use App\Models\PariwisataGambarModel;

class LandingController extends BaseController
{
    public function index()
    {
        $profileModel = new DesaProfileModel();
        $albumModel   = new GalleryAlbumModel();
        $newsModel    = new NewsModel();
        $pengumumanModel = new PengumumanModel();


        $profile = $profileModel->first();
        if ($profile && !empty($profile['maps_url'])) {
            $profile['maps_embed_url'] = $this->convertToMapsEmbed($profile['maps_url']);
        }

        $umkmProdukModel = new \App\Models\UmkmProdukModel();
        $umkmGambarModel = new \App\Models\UmkmProdukGambarModel();
        
        $randomProducts = $umkmProdukModel->orderBy('RAND()')->findAll(6);
        foreach ($randomProducts as &$produk) {
            $gambar = $umkmGambarModel->where('produk_id', $produk['id'])->orderBy('id', 'ASC')->first();
            $produk['gambar_path'] = $gambar ? $gambar['gambar_path'] : null;
        }

        $pariwisataModel = new \App\Models\PariwisataModel();
        $pariwisataGambarModel = new \App\Models\PariwisataGambarModel();
        
        // Ambil 6 pariwisata terbaru
        $pariwisataList = $pariwisataModel->orderBy('created_at', 'DESC')->findAll(6);
        foreach ($pariwisataList as &$pariwisata) {
            if (empty($pariwisata['thumbnail'])) {
                $gambar = $pariwisataGambarModel->where('pariwisata_id', $pariwisata['id'])->first();
                $pariwisata['thumbnail_display'] = $gambar ? $gambar['gambar_path'] : null;
            } else {
                $pariwisata['thumbnail_display'] = $pariwisata['thumbnail'];
            }
        }

        $data = [
            'title'          => 'Beranda | Website Desa Bonto Marannu',
            'desaProfile'    => $profile,
            'albums'         => $albumModel->orderBy('tanggal_waktu', 'DESC')->findAll(6),
            'news'           => $newsModel->orderBy('tanggal_waktu', 'DESC')->findAll(6),
            'pengumuman'     => $pengumumanModel->orderBy('created_at', 'DESC')->findAll(4),
            'umkmProducts'   => $randomProducts,
            'pariwisataList' => $pariwisataList,
        ];

        return view('Guest/home', $data);
    }

    public function profil()
    {
        $profileModel = new DesaProfileModel();
        $geografiModel = new \App\Models\GeografiDesaModel();
        
        $profile = $profileModel->first();
        $geografi = $geografiModel->first();
        
        if ($profile && !empty($profile['maps_url'])) {
            $profile['maps_embed_url'] = $this->convertToMapsEmbed($profile['maps_url']);
        }

        return view('Guest/profil', [
            'title'       => 'Profil Desa | Website Desa Bonto Marannu',
            'desaProfile' => $profile,
            'geografi'    => $geografi,
        ]);
    }

    public function perangkatDesa()
    {
        return view('Guest/perangkat_desa', [
            'title' => 'Perangkat Desa | Website Desa Bonto Marannu',
        ]);
    }

    public function perangkatDesaApi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $page   = (int)($this->request->getPost('page') ?: 1);
        $limit  = 12;
        $search = trim($this->request->getPost('search') ?? '');
        $offset = ($page - 1) * $limit;

        $model = new PerangkatDesaModel();

        if ($search !== '') {
            $model->groupStart()
                ->like('nama', $search)
                ->orLike('jabatan', $search)
                ->orLike('kontak', $search)
                ->groupEnd();
        }

        $total = $model->countAllResults(false);

        $list = $model->orderBy('id', 'ASC')
            ->findAll($limit, $offset);

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id'      => $item['id'],
                'nama'    => $item['nama'],
                'jabatan' => $item['jabatan'],
                'kontak'  => $item['kontak'] ?? '',
                'foto_url' => !empty($item['foto']) ? base_url($item['foto']) : null,
            ];
        }

        return $this->response->setJSON([
            'success'     => true,
            'data'        => $data,
            'total'       => $total,
            'total_pages' => $limit > 0 ? (int)ceil($total / $limit) : 1,
            'page'        => $page,
        ]);
    }

    public function inventaris()
    {
        $inventarisModel = new InventarisDesaModel();
        $inventaris = $inventarisModel->orderBy('created_at', 'DESC')->findAll();

        return view('Guest/inventaris', [
            'title' => 'Inventaris Aset Desa | Website Desa Bonto Marannu',
            'inventaris' => $inventaris
        ]);
    }

    public function pengumuman()
    {
        return view('Guest/pengumuman', [
            'title' => 'Pengumuman | Website Desa Bonto Marannu',
        ]);
    }

    public function pengumumanApi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $page   = (int)($this->request->getPost('page') ?: 1);
        $limit  = 9;
        $search = trim($this->request->getPost('search') ?? '');
        $offset = ($page - 1) * $limit;

        $model = new PengumumanModel();

        if ($search !== '') {
            $model->groupStart()
                ->like('judul', $search)
                ->orLike('isi', $search)
                ->groupEnd();
        }

        $total = $model->countAllResults(false);

        $list = $model->orderBy('created_at', 'DESC')
            ->findAll($limit, $offset);

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id'        => $item['id'],
                'judul'     => $item['judul'],
                'isi'       => strip_tags($item['isi']),
                'tanggal'   => date('d M Y', strtotime($item['created_at'])),
                'thumbnail' => !empty($item['thumbnail']) ? base_url($item['thumbnail']) : null,
            ];
        }

        return $this->response->setJSON([
            'success'     => true,
            'data'        => $data,
            'total'       => $total,
            'total_pages' => $limit > 0 ? (int)ceil($total / $limit) : 1,
            'page'        => $page,
        ]);
    }

    public function detailPengumuman($id)
    {
        $pengumumanModel = new PengumumanModel();
        $pengumuman = $pengumumanModel->find($id);

        if (!$pengumuman) {
            return redirect()->to('/pengumuman')->with('error', 'Pengumuman tidak ditemukan.');
        }

        $other_pengumuman = $pengumumanModel->where('id !=', $id)->orderBy('created_at', 'DESC')->findAll(3);

        return view('Guest/pengumuman_detail', [
            'title'            => 'Detail Pengumuman | Website Desa Bonto Marannu',
            'item'             => $pengumuman,
            'other_pengumuman' => $other_pengumuman,
        ]);
    }

    public function pengaduan()
    {
        return view('Guest/pengaduan', ['title' => 'Layanan Pengaduan | Website Desa Bonto Marannu']);
    }

    public function captcha()
    {
        $answer = $this->generateCaptchaString(5);
        session()->set('captcha_answer', strtolower($answer));
        session()->set('captcha_verified', false);

        $width  = 160;
        $height = 50;
        $img    = imagecreatetruecolor($width, $height);

        $bg        = imagecolorallocate($img, 245, 247, 250);
        $textColor = imagecolorallocate($img, 30, 80, 30);
        $noiseColor = imagecolorallocate($img, 180, 200, 180);

        imagefilledrectangle($img, 0, 0, $width - 1, $height - 1, $bg);

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

        $fontPath  = $this->getCaptchaFontPath();
        $charWidth = (int) ($width / (5 + 1));
        for ($i = 0; $i < strlen($answer); $i++) {
            $x     = $charWidth * $i + rand(8, 14);
            $y     = rand(28, $height - 6);
            $angle = rand(-12, 12);
            if ($fontPath !== null && function_exists('imagettftext')) {
                imagettftext($img, rand(20, 24), $angle, $x, $y, $textColor, $fontPath, $answer[$i]);
            } else {
                imagestring($img, 5, $x, (int) ($height / 2) - 8, $answer[$i], $textColor);
            }
        }

        $border = imagecolorallocate($img, 200, 220, 200);
        imagerectangle($img, 0, 0, $width - 1, $height - 1, $border);

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

    public function verifyCaptcha()
    {
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

        session()->set('captcha_answer', '');
        session()->set('captcha_verified', false);
        return $this->response->setJSON(['success' => false, 'message' => 'Kode CAPTCHA salah. Silakan coba lagi.']);
    }

    public function submitPengaduan()
    {
        if (!session()->get('captcha_verified')) {
            return redirect()->back()->withInput()->with('error', 'Verifikasi CAPTCHA diperlukan sebelum mengirim pengaduan.');
        }

        $pengaduanModel = new PengaduanModel();

        $data = [
            'user_id' => session()->get('user_id'), // Will be null if not logged in
            'nama'    => $this->request->getPost('nama'),
            'kontak'  => $this->request->getPost('kontak'),
            'perihal' => $this->request->getPost('perihal'),
            'isi'     => $this->request->getPost('isi')
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
        $userModel = new \App\Models\UserModel();
        $staffList = $userModel->where('role', 'staf')->findAll();
        $notifModel = new \App\Models\NotificationModel();
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
            
            // Kirim email notifikasi ke staff (masuk ke EmailQueue)
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

        return redirect()->to('/pengaduan')->with('message', 'Pengaduan Anda telah berhasil dikirim. Terima kasih.');
    }

    public function geografis()
    {
        $geografiModel = new GeografiDesaModel();
        $geografi = $geografiModel->first();
        
        return view('Guest/geografis', [
            'title' => 'Geografi Desa | Website Desa Bonto Marannu',
            'geografi' => $geografi
        ]);
    }

    public function galeri()
    {
        $albumModel   = new GalleryAlbumModel();
        $mediaModel   = new GalleryMediaModel();
        $albums       = $albumModel->orderBy('tanggal_waktu', 'DESC')->findAll();
        $albumMedia   = [];

        foreach ($albums as $album) {
            $firstMedia = $mediaModel->where('album_id', $album['id'])->first();
            $albumMedia[$album['id']] = $firstMedia ? $firstMedia['media_path'] : $album['thumbnail'];
        }

        return view('Guest/galeri', [
            'title'      => 'Galeri | Website Desa Bonto Marannu',
            'albums'     => $albums,
            'albumMedia' => $albumMedia,
        ]);
    }

    public function galeriDetail($id)
    {
        $albumModel = new GalleryAlbumModel();
        $mediaModel = new GalleryMediaModel();

        $album = $albumModel->find($id);
        if (!$album) {
            return redirect()->to('/galeri')->with('error', 'Album tidak ditemukan.');
        }

        $media = $mediaModel->where('album_id', $id)->findAll();
        foreach ($media as &$item) {
            if ($item['media_type'] === 'video_link') {
                $item['embed_url'] = $this->toEmbedUrl($item['media_path']);
            }
        }

        return view('Guest/galeri_detail', [
            'title'  => 'Detail Galeri | Website Desa Bonto Marannu',
            'album'  => $album,
            'media'  => $media,
        ]);
    }

    public function berita()
    {
        return view('Guest/berita', [
            'title' => 'Berita | Website Desa Bonto Marannu',
        ]);
    }

    public function beritaApi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $page   = (int)($this->request->getPost('page') ?: 1);
        $limit  = 9;
        $search = trim($this->request->getPost('search') ?? '');
        $offset = ($page - 1) * $limit;

        $model = new NewsModel();

        if ($search !== '') {
            $model->groupStart()
                ->like('judul', $search)
                ->orLike('isi', $search)
                ->groupEnd();
        }

        $total = $model->countAllResults(false);

        $list = $model->orderBy('tanggal_waktu', 'DESC')
            ->findAll($limit, $offset);

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id'            => $item['id'],
                'judul'         => $item['judul'],
                'isi'           => strip_tags($item['isi']),
                'tanggal_waktu' => date('d M Y', strtotime($item['tanggal_waktu'])),
                'thumbnail'     => !empty($item['thumbnail']) ? base_url($item['thumbnail']) : null,
            ];
        }

        return $this->response->setJSON([
            'success'     => true,
            'data'        => $data,
            'total'       => $total,
            'total_pages' => $limit > 0 ? (int)ceil($total / $limit) : 1,
            'page'        => $page,
        ]);
    }

    public function detailBerita($id)
    {
        $newsModel  = new NewsModel();
        $mediaModel = new NewsMediaModel();
        $news       = $newsModel->find($id);

        if (!$news) {
            return redirect()->to('/berita')->with('error', 'Berita tidak ditemukan.');
        }

        $media = $mediaModel->where('news_id', $id)->findAll();
        foreach ($media as &$item) {
            if (isset($item['media_type']) && $item['media_type'] === 'video_link') {
                $item['embed_url'] = $this->toEmbedUrl($item['media_path']);
            }
        }

        $other_news = $newsModel->where('id !=', $id)->orderBy('tanggal_waktu', 'DESC')->findAll(3);

        return view('Guest/berita_detail', [
            'title'      => 'Detail Berita | Website Desa Bonto Marannu',
            'item'       => $news,
            'media'      => $media,
            'other_news' => $other_news,
        ]);
    }


    public function umkm()
    {
        $umkmModel    = new UmkmModel();
        $produkModel  = new UmkmProdukModel();
        $gambarModel  = new UmkmProdukGambarModel();

        $search = $this->request->getGet('search') ?? '';

        $builder = $umkmModel->where('status', 'approved');
        if (!empty($search)) {
            $builder->like('nama_toko', $search);
        }

        $list = $builder->orderBy('approved_at', 'DESC')->findAll();

        // Ambil produk pertama + thumbnail untuk tiap toko
        foreach ($list as &$item) {
            $produkPertama = $produkModel->where('umkm_id', $item['id'])->first();
            if ($produkPertama) {
                $gambar = $gambarModel->where('produk_id', $produkPertama['id'])->first();
                $item['thumbnail'] = $gambar ? $gambar['gambar_path'] : null;
            } else {
                $item['thumbnail'] = null;
            }
        }
        unset($item);

        return view('Guest/umkm', [
            'title'  => 'UMKM | Website Desa Bonto Marannu',
            'list'   => $list,
            'search' => $search,
        ]);
    }

    public function umkmDetail($id)
    {
        $umkmModel   = new UmkmModel();
        $ecomModel   = new UmkmEcommerceModel();
        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();

        $umkm = $umkmModel->select('umkm.*, users.username as pemilik_username, users.role as pemilik_role')
                          ->join('users', 'users.id = umkm.user_id', 'left')
                          ->where('umkm.status', 'approved')
                          ->find($id);
        if (!$umkm) {
            return redirect()->to('/umkm')->with('error', 'UMKM tidak ditemukan.');
        }

        $ecommerce  = $ecomModel->where('umkm_id', $id)->findAll();
        $produkList = $produkModel->where('umkm_id', $id)->findAll();

        foreach ($produkList as &$produk) {
            $produk['gambar'] = $gambarModel->where('produk_id', $produk['id'])->findAll();
        }
        unset($produk);

        return view('Guest/umkm_detail', [
            'title'     => esc($umkm['nama_toko']) . ' - UMKM | Website Desa Bonto Marannu',
            'umkm'      => $umkm,
            'ecommerce' => $ecommerce,
            'produk'    => $produkList,
        ]);
    }

    public function umkmProdukDetail($produkId)
    {
        $umkmModel   = new UmkmModel();
        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();

        $produk = $produkModel->find($produkId);
        if (!$produk) {
            return redirect()->to('/umkm')->with('error', 'Produk tidak ditemukan.');
        }

        // Pastikan UMKM-nya sudah approved
        $umkm = $umkmModel->select('umkm.*, users.username as pemilik_username, users.role as pemilik_role')
                          ->join('users', 'users.id = umkm.user_id', 'left')
                          ->where('umkm.status', 'approved')
                          ->find($produk['umkm_id']);
        if (!$umkm) {
            return redirect()->to('/umkm')->with('error', 'UMKM tidak ditemukan atau belum disetujui.');
        }

        $produk['gambar'] = $gambarModel->where('produk_id', $produkId)->findAll();

        return view('Guest/produk_detail', [
            'title'  => esc($produk['nama_produk']) . ' - ' . esc($umkm['nama_toko']) . ' | Website Desa Bonto Marannu',
            'produk' => $produk,
            'umkm'   => $umkm,
        ]);
    }

    public function pariwisata()
    {
        $pariwisataModel = new PariwisataModel();
        $gambarModel     = new PariwisataGambarModel();

        $search = $this->request->getGet('search') ?? '';

        $builder = $pariwisataModel;
        if (!empty($search)) {
            $builder->like('nama_tempat', $search);
        }

        $list = $builder->orderBy('created_at', 'DESC')->findAll();

        // Ambil gambar pertama jika tidak ada thumbnail
        foreach ($list as &$item) {
            if (empty($item['thumbnail'])) {
                $gambar = $gambarModel->where('pariwisata_id', $item['id'])->first();
                $item['thumbnail_display'] = $gambar ? $gambar['gambar_path'] : null;
            } else {
                $item['thumbnail_display'] = $item['thumbnail'];
            }
        }
        unset($item);

        return view('Guest/pariwisata', [
            'title'  => 'Pariwisata | Website Desa Bonto Marannu',
            'list'   => $list,
            'search' => $search,
        ]);
    }

    public function pariwisataDetail($id)
    {
        $pariwisataModel = new PariwisataModel();
        $gambarModel     = new PariwisataGambarModel();

        $item = $pariwisataModel->find($id);
        if (!$item) {
            return redirect()->to('/pariwisata')->with('error', 'Data pariwisata tidak ditemukan.');
        }

        $gambar = $gambarModel->where('pariwisata_id', $id)->findAll();

        return view('Guest/pariwisata_detail', [
            'title'  => esc($item['nama_tempat']) . ' - Pariwisata | Website Desa Bonto Marannu',
            'item'   => $item,
            'gambar' => $gambar,
        ]);
    }



    private function toEmbedUrl(string $url): string
    {
        $trimmed = trim($url);
        if ($trimmed === '') {
            return $url;
        }

        $host = parse_url($trimmed, PHP_URL_HOST);
        if (!$host) {
            return $trimmed;
        }

        // youtu.be/<id>
        if (str_contains($host, 'youtu.be')) {
            $path = ltrim((string) parse_url($trimmed, PHP_URL_PATH), '/');
            return $path ? 'https://www.youtube.com/embed/' . $path : $trimmed;
        }

        // youtube.com/watch?v=ID or /shorts/ID
        if (str_contains($host, 'youtube.com')) {
            parse_str((string) parse_url($trimmed, PHP_URL_QUERY), $query);
            if (!empty($query['v'])) {
                return 'https://www.youtube.com/embed/' . $query['v'];
            }
            $path = (string) parse_url($trimmed, PHP_URL_PATH);
            if (str_starts_with($path, '/shorts/')) {
                return 'https://www.youtube.com/embed/' . ltrim(substr($path, 7), '/');
            }
            if (str_starts_with($path, '/embed/')) {
                return $trimmed;
            }
        }

        return $trimmed;
    }

    private function convertToMapsEmbed(string $url): ?string
    {
        $trimmed = trim($url);
        if ($trimmed === '') {
            return null;
        }

        // Extract URL from iframe tag if user pasted full HTML
        if (preg_match('/src=["\']([^"\']+)["\']/', $trimmed, $matches)) {
            $trimmed = $matches[1];
        }
        // Clean any remaining HTML tags
        $trimmed = strip_tags($trimmed);
        $trimmed = trim($trimmed);

        // Jika sudah embed URL, langsung return
        if (str_contains($trimmed, '/maps/embed') || str_contains($trimmed, 'maps/embed')) {
            return $trimmed;
        }

        // Handle Google Maps regular URL: https://www.google.com/maps?q=... atau /maps/place/...
        if (str_contains($trimmed, 'google.com/maps')) {
            // Coba extract place ID atau query
            $parsed = parse_url($trimmed);
            $host = $parsed['host'] ?? '';
            
            if (str_contains($host, 'maps.google.com') || str_contains($host, 'google.com')) {
                // Jika ada query parameter q, gunakan itu
                if (isset($parsed['query'])) {
                    parse_str($parsed['query'], $query);
                    if (!empty($query['q'])) {
                        return 'https://www.google.com/maps?q=' . urlencode($query['q']) . '&output=embed';
                    }
                }
                
                // Jika ada path seperti /place/..., coba extract
                if (isset($parsed['path'])) {
                    if (preg_match('#/place/([^/]+)#', $parsed['path'], $matches)) {
                        $place = urlencode($matches[1]);
                        return 'https://www.google.com/maps?q=' . $place . '&output=embed';
                    }
                }
            }
        }

        // Untuk Google Share link atau link lainnya yang tidak bisa dikonversi
        // Return null agar view bisa handle dengan menampilkan link biasa
        return null;
    }

    private function generateCaptchaString(int $length): string
    {
        $chars  = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $result;
    }

    private function getCaptchaFontPath(): ?string
    {
        $candidates = [
            FCPATH . 'assets/fonts/captcha.ttf',
            'C:/Windows/Fonts/arialbd.ttf',
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/courbd.ttf',
            'C:/Windows/Fonts/cour.ttf',
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

