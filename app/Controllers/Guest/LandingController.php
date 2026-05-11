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


        $profile = $profileModel->first();
        if ($profile && !empty($profile['maps_url'])) {
            $profile['maps_embed_url'] = $this->convertToMapsEmbed($profile['maps_url']);
        }

        $data = [
            'desaProfile' => $profile,
            'albums'      => $albumModel->orderBy('tanggal_waktu', 'DESC')->findAll(6),
            'news'        => $newsModel->orderBy('tanggal_waktu', 'DESC')->findAll(6),

        ];

        return view('Guest/home', $data);
    }

    public function profil()
    {
        $profileModel = new DesaProfileModel();
        
        $profile = $profileModel->first();
        
        if ($profile && !empty($profile['maps_url'])) {
            $profile['maps_embed_url'] = $this->convertToMapsEmbed($profile['maps_url']);
        }

        return view('Guest/profil', [
            'desaProfile' => $profile,
        ]);
    }

    public function perangkatDesa()
    {
        $perangkatDesaModel = new PerangkatDesaModel();
        $perangkatDesa = $perangkatDesaModel->orderBy('id', 'ASC')->findAll();
        
        // Format foto URL
        foreach ($perangkatDesa as &$perangkat) {
            $perangkat['foto_url'] = $perangkat['foto'] 
                ? base_url($perangkat['foto']) 
                : base_url('assets/img/guest.webp');
        }

        return view('Guest/perangkat_desa', [
            'perangkatDesa' => $perangkatDesa,
        ]);
    }

    public function inventaris()
    {
        $inventarisModel = new InventarisDesaModel();
        $inventaris = $inventarisModel->orderBy('created_at', 'DESC')->findAll();

        return view('Guest/inventaris', [
            'title' => 'Inventaris Aset Desa - Desa Bontomarannu',
            'inventaris' => $inventaris
        ]);
    }

    public function pengumuman()
    {
        $pengumumanModel = new PengumumanModel();
        $pengumuman = $pengumumanModel->orderBy('created_at', 'DESC')->findAll();

        return view('Guest/pengumuman', ['pengumuman' => $pengumuman]);
    }

    public function pengaduan()
    {
        return view('Guest/pengaduan');
    }

    public function submitPengaduan()
    {
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
            'title' => 'Geografi Desa - Desa Padangloang',
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
            'album'  => $album,
            'media'  => $media,
        ]);
    }

    public function berita()
    {
        $newsModel = new NewsModel();

        return view('Guest/berita', [
            'news' => $newsModel->orderBy('tanggal_waktu', 'DESC')->findAll(),
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

        return view('Guest/berita_detail', [
            'item'  => $news,
            'media' => $media,
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
            'title'  => 'UMKM Desa Padang Loang',
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

        $umkm = $umkmModel->where('status', 'approved')->find($id);
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
            'title'     => $umkm['nama_toko'] . ' - UMKM Desa Padang Loang',
            'umkm'      => $umkm,
            'ecommerce' => $ecommerce,
            'produk'    => $produkList,
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
            'title'  => 'Pariwisata Desa Padang Loang',
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
            'title'  => $item['nama_tempat'] . ' - Pariwisata Desa Padang Loang',
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
}


