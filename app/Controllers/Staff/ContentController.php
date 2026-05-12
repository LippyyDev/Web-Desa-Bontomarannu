<?php

namespace App\Controllers\Staff;

use App\Controllers\ProtectedController;
use App\Models\DesaProfileModel;
use App\Models\GalleryModel;
use App\Models\GalleryAlbumModel;
use App\Models\GeografiDesaModel;
use App\Models\GalleryMediaModel;
use App\Models\NewsMediaModel;
use App\Models\NewsModel;
use App\Models\PerangkatDesaModel;
use App\Models\InventarisDesaModel;
use App\Models\PengumumanModel;
use App\Models\PengaduanModel;

class ContentController extends ProtectedController
{
    public function __construct()
    {
        helper('upload');
    }

    public function desaProfile()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $profileModel = new DesaProfileModel();
        
        $profile = $profileModel->first();

        // Jika profile belum ada, buat record baru default
        if (!$profile) {
            $profileModel->save(['nama_desa' => 'Desa Bontomarannu']);
            $profile = $profileModel->first();
        }

        return view('Staff/desa_profile/index', [
            'title' => 'Profil Desa',
            'profile' => $profile
        ]);
    }

    public function geografis()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $geografiModel = new GeografiDesaModel();
        $geografi = $geografiModel->first();
        
        // Jika geografi belum ada, buat record baru
        if (!$geografi) {
            $geografiModel->save([]);
            $geografi = $geografiModel->first();
        }

        return view('Staff/geografi/index', [
            'title' => 'Geografi Desa',
            'geografi' => $geografi
        ]);
    }

    public function updateDesaProfile()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $profileModel = new DesaProfileModel();
        $data = [
            'nama_desa'           => $this->request->getPost('nama_desa'),
            'kecamatan'           => $this->request->getPost('kecamatan'),
            'kabupaten'           => $this->request->getPost('kabupaten'),
            'provinsi'            => $this->request->getPost('provinsi'),
            'kode_pos'            => $this->request->getPost('kode_pos'),
            'jumlah_penduduk'     => $this->request->getPost('jumlah_penduduk'),
            'jumlah_kk'           => $this->request->getPost('jumlah_kk'),
            'tahun_berdiri'       => $this->request->getPost('tahun_berdiri'),
            'sejarah_desa'        => $this->request->getPost('sejarah_desa'),
            'visi'                => $this->request->getPost('visi'),
            'misi'                => $this->request->getPost('misi'),
            'deskripsi_lokasi'    => $this->request->getPost('deskripsi_lokasi'),
            'updated_by'          => $this->currentUser['id'],
        ];

        $profile = $profileModel->first();
        if ($profile) {
            $profileModel->update($profile['id'], $data);
        } else {
            $profileModel->save($data);
        }

        return redirect()->to('/staff/desa')->with('message', 'Profil desa berhasil diperbarui');
    }

    public function updateGeografis()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $mapsUrl = $this->request->getPost('maps_embed_url');
        // Ekstrak URL dari iframe tag jika user paste HTML penuh
        if ($mapsUrl && preg_match('/src=["\']([^"\']+)["\']/', $mapsUrl, $matches)) {
            $mapsUrl = $matches[1];
        }
        // Bersihkan HTML tag dan whitespace
        $mapsUrl = trim(strip_tags($mapsUrl ?? ''));

        // Validasi backend: hanya izinkan URL Google Maps yang valid
        if ($mapsUrl !== '') {
            $mapsPatterns = [
                '/maps\.app\.goo\.gl/i',
                '/goo\.gl\/maps/i',
                '/google\.com\/maps/i',
                '/maps\.google\.com/i',
            ];
            $isValid = false;
            foreach ($mapsPatterns as $pattern) {
                if (preg_match($pattern, $mapsUrl)) {
                    $isValid = true;
                    break;
                }
            }
            if (!$isValid) {
                return redirect()->back()->withInput()
                    ->with('error', 'Link Google Maps tidak valid. Gunakan link dari Google Maps (maps.app.goo.gl, google.com/maps, dsb.).');
            }
        }

        $geografiModel = new GeografiDesaModel();
        $geografi = $geografiModel->first();

        $geografiData = [
            'luas_wilayah'      => $this->request->getPost('luas_wilayah'),
            'batas_wilayah'     => $this->request->getPost('batas_wilayah'),
            'kondisi_geografis' => $this->request->getPost('kondisi_geografis'),
            'maps_embed_url'    => $mapsUrl,
        ];

        if ($geografi) {
            $geografiModel->update($geografi['id'], $geografiData);
        } else {
            $geografiModel->save($geografiData);
        }

        return redirect()->to('/staff/geografi')->with('message', 'Data geografis berhasil diperbarui.');
    }

    public function gallery()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/gallery/index');
    }

    public function galleryApi()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $page      = (int)($this->request->getPost('page') ?: 1);
        $limit     = (int)($this->request->getPost('limit') ?: 12);
        $search    = trim($this->request->getPost('search') ?? '');
        $dateStart = trim($this->request->getPost('date_start') ?? '');
        $dateEnd   = trim($this->request->getPost('date_end') ?? '');
        $offset    = ($page - 1) * $limit;

        $total  = $this->applyGalleryFilters(new GalleryAlbumModel(), $search, $dateStart, $dateEnd)
                      ->countAllResults(false);

        $albums = $this->applyGalleryFilters(new GalleryAlbumModel(), $search, $dateStart, $dateEnd)
                      ->orderBy('tanggal_waktu', 'DESC')
                      ->findAll($limit, $offset);

        $data = [];
        foreach ($albums as $album) {
            $data[] = [
                'id'            => $album['id'],
                'nama_album'    => $album['nama_album'],
                'deskripsi'     => $album['deskripsi'] ?? '',
                'tanggal_waktu' => date('d M Y', strtotime($album['tanggal_waktu'])),
                'thumbnail'     => $album['thumbnail'] ? base_url($album['thumbnail']) : null,
            ];
        }

        return $this->response->setJSON([
            'success'     => true,
            'data'        => $data,
            'total'       => $total,
            'page'        => $page,
            'limit'       => $limit,
            'total_pages' => $limit > 0 ? (int)ceil($total / $limit) : 1,
        ]);
    }

    private function applyGalleryFilters(GalleryAlbumModel $model, string $search, string $dateStart, string $dateEnd): GalleryAlbumModel
    {
        if ($dateStart !== '') {
            $model->where('DATE(tanggal_waktu) >=', $dateStart);
        }
        if ($dateEnd !== '') {
            $model->where('DATE(tanggal_waktu) <=', $dateEnd);
        }
        if ($search !== '') {
            $model->groupStart()
                ->like('nama_album', $search)
                ->orLike('deskripsi', $search)
                ->groupEnd();
        }
        return $model;
    }

    public function createGallery()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/gallery/create');
    }

    public function editGallery($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $albumModel = new GalleryAlbumModel();
        $album      = $albumModel->find($id);
        if (!$album) {
            return redirect()->to('/staff/galeri')->with('error', 'Album tidak ditemukan.');
        }

        $mediaModel = new GalleryMediaModel();
        $media      = $mediaModel->where('album_id', $id)->findAll();
        foreach ($media as &$m) {
            if ($m['media_type'] === 'video_link') {
                $m['embed_url'] = $this->toEmbedUrl($m['media_path']);
            }
        }

        return view('Staff/gallery/edit', [
            'album' => $album,
            'media' => $media,
        ]);
    }



    public function storeGallery()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $thumb = $this->request->getFile('thumbnail');
        if ($thumb && $thumb->isValid()) {
            $error = validate_image_upload($thumb);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Thumbnail tidak valid: maksimal 1MB dan format harus JPG/PNG.');
            }
        }

        $files = $this->request->getFileMultiple('media');
        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $error = validate_image_upload($file);
                    if ($error !== null) {
                        return redirect()->back()->withInput()->with('error', 'Upload dibatalkan. Pastikan semua foto maksimal 1MB dan format JPG/PNG.');
                    }
                }
            }
        }

        $albumModel = new GalleryAlbumModel();
        $data       = [
            'nama_album'   => $this->request->getPost('nama_album'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'tanggal_waktu'=> $this->request->getPost('tanggal_waktu') ?: date('Y-m-d H:i:s'),
            'created_by'   => $this->currentUser['id'],
        ];

        if ($thumb && $thumb->isValid()) {

            $path = FCPATH . 'uploads/gallery';
            $this->ensureUploadPath($path);
            
            // Upload file sementara
            $tempName = $thumb->getRandomName();
            $thumb->move($path, $tempName);
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
                
                $data['thumbnail'] = 'uploads/gallery/' . $webpName;
            } catch (\Exception $e) {
                // Jika konversi gagal, hapus file sementara
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                return redirect()->back()->with('error', 'Gagal memproses gambar thumbnail. Pastikan file adalah gambar yang valid.');
            }
        }

        $albumId = $albumModel->insert($data, true);
        $this->saveGalleryMedia($albumId);

        return redirect()->back()->with('success', 'Album galeri disimpan.');
    }

    public function updateGallery($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $albumModel = new GalleryAlbumModel();
        $album      = $albumModel->find($id);
        if (!$album) {
            return redirect()->back()->with('error', 'Album tidak ditemukan.');
        }

        $thumb = $this->request->getFile('thumbnail');
        if ($thumb && $thumb->isValid()) {
            $error = validate_image_upload($thumb);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Thumbnail tidak valid: maksimal 1MB dan format harus JPG/PNG.');
            }
        }

        $files = $this->request->getFileMultiple('media');
        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $error = validate_image_upload($file);
                    if ($error !== null) {
                        return redirect()->back()->withInput()->with('error', 'Upload dibatalkan. Pastikan semua foto maksimal 1MB dan format JPG/PNG.');
                    }
                }
            }
        }

        $data = [
            'nama_album'    => $this->request->getPost('nama_album'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'tanggal_waktu' => $this->request->getPost('tanggal_waktu') ?: $album['tanggal_waktu'],
        ];

        if ($thumb && $thumb->isValid()) {

            $path = FCPATH . 'uploads/gallery';
            $this->ensureUploadPath($path);
            
            // Hapus thumbnail lama jika ada
            if ($album['thumbnail']) {
                $oldFile = FCPATH . ltrim($album['thumbnail'], '/');
                if (is_file($oldFile)) {
                    @unlink($oldFile);
                }
            }
            
            // Upload file sementara
            $tempName = $thumb->getRandomName();
            $thumb->move($path, $tempName);
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
                
                $data['thumbnail'] = 'uploads/gallery/' . $webpName;
            } catch (\Exception $e) {
                // Jika konversi gagal, hapus file sementara
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                return redirect()->back()->with('error', 'Gagal memproses gambar thumbnail. Pastikan file adalah gambar yang valid.');
            }
        }

        $albumModel->update($id, $data);
        $this->saveGalleryMedia($id);

        return redirect()->back()->with('success', 'Album diperbarui.');
    }

    public function deleteGallery($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $albumModel = new GalleryAlbumModel();
        $mediaModel = new GalleryMediaModel();
        $album      = $albumModel->find($id);

        if ($album) {
            $mediaList = $mediaModel->where('album_id', $id)->findAll();
            foreach ($mediaList as $media) {
                $file = FCPATH . ltrim($media['media_path'], '/');
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            $mediaModel->where('album_id', $id)->delete();
            $albumModel->delete($id);
        }

        return redirect()->back()->with('success', 'Album dihapus.');
    }

    private function saveGalleryMedia(int $albumId): void
    {
        $files = $this->request->getFileMultiple('media');
        if (!$files) {
            return;
        }

        $mediaModel = new GalleryMediaModel();
        $path       = FCPATH . 'uploads/gallery';
        $this->ensureUploadPath($path);
        $image      = \Config\Services::image();

        foreach ($files as $file) {
            if (!$file->isValid()) {
                continue;
            }
            
            // Upload file sementara
            $tempName = $file->getRandomName();
            $file->move($path, $tempName);
            $tempPath = $path . '/' . $tempName;
            
            // Convert ke WebP
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
                
                $mediaModel->insert([
                    'album_id'   => $albumId,
                    'media_type' => 'foto',
                    'media_path' => 'uploads/gallery/' . $webpName,
                ]);
            } catch (\Exception $e) {
                // Jika konversi gagal, hapus file sementara
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                // Skip file yang gagal dikonversi
                continue;
            }
        }

        $videoLinks = $this->request->getPost('video_links');
        if (is_array($videoLinks)) {
            foreach ($videoLinks as $block) {
                $lines = preg_split('/\r\n|\r|\n/', (string) $block);
                foreach ($lines as $link) {
                    if (trim($link) === '') {
                        continue;
                    }
                    $mediaModel->insert([
                        'album_id'   => $albumId,
                        'media_type' => 'video_link',
                        'media_path' => trim($link),
                    ]);
                }
            }
        }
    }

    public function deleteGalleryMedia($mediaId)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $mediaModel = new GalleryMediaModel();
        $media      = $mediaModel->find($mediaId);
        if ($media) {
            if ($media['media_type'] === 'foto') {
                $file = FCPATH . ltrim($media['media_path'], '/');
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            $albumId = $media['album_id'];
            $mediaModel->delete($mediaId);
            return redirect()->to('/staff/galeri/' . $albumId . '/edit')->with('success', 'Media dihapus.');
        }

        return redirect()->back()->with('error', 'Media tidak ditemukan.');
    }

    public function news()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/news/index');
    }

    public function newsApi()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $page   = (int)($this->request->getPost('page') ?: 1);
        $limit  = 12;
        $search = trim($this->request->getPost('search') ?? '');
        $offset = ($page - 1) * $limit;

        $newsModel = new NewsModel();

        if ($search !== '') {
            $newsModel->groupStart()
                ->like('judul', $search)
                ->orLike('isi', $search)
                ->groupEnd();
        }

        $total = $newsModel->countAllResults(false);

        $news = $newsModel->orderBy('tanggal_waktu', 'DESC')
            ->findAll($limit, $offset);

        $data = [];
        foreach ($news as $item) {
            $data[] = [
                'id'            => $item['id'],
                'judul'         => $item['judul'],
                'isi'           => strip_tags($item['isi']),
                'tanggal_waktu' => date('d M Y', strtotime($item['tanggal_waktu'])),
                'thumbnail'     => $item['thumbnail'] ? base_url($item['thumbnail']) : null,
            ];
        }

        return $this->response->setJSON([
            'success'     => true,
            'data'        => $data,
            'total'       => $total,
            'page'        => $page,
            'limit'       => $limit,
            'total_pages' => $limit > 0 ? (int)ceil($total / $limit) : 1,
        ]);
    }

    public function createNews()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/news/create');
    }

    public function editNews($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $newsModel = new NewsModel();
        $news      = $newsModel->find($id);
        if (!$news) {
            return redirect()->to('/staff/berita')->with('error', 'Berita tidak ditemukan.');
        }

        $mediaModel = new NewsMediaModel();
        $media      = $mediaModel->where('news_id', $id)->findAll();
        foreach ($media as &$m) {
            if (isset($m['media_type']) && $m['media_type'] === 'video_link') {
                $m['embed_url'] = $this->toEmbedUrl($m['media_path']);
            }
        }

        return view('Staff/news/edit', [
            'item'  => $news,
            'media' => $media,
        ]);
    }

    public function storeNews()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        // Validasi thumbnail
        $thumb = $this->request->getFile('thumbnail');
        if ($thumb && $thumb->isValid()) {
            $error = validate_image_upload($thumb);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Thumbnail tidak valid: maksimal 1MB dan format harus JPG/PNG.');
            }
        }

        // Validasi semua foto media
        $files = $this->request->getFileMultiple('media');
        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $error = validate_image_upload($file);
                    if ($error !== null) {
                        return redirect()->back()->withInput()->with('error', 'Upload dibatalkan. Pastikan semua foto maksimal 1MB dan format JPG/PNG.');
                    }
                }
            }
        }

        $newsModel = new NewsModel();
        $data      = [
            'judul'         => $this->request->getPost('judul'),
            'tanggal_waktu' => $this->request->getPost('tanggal_waktu') ?: date('Y-m-d H:i:s'),
            'isi'           => $this->request->getPost('isi'),
            'created_by'    => $this->currentUser['id'],
        ];

        if ($thumb && $thumb->isValid()) {
            $path = FCPATH . 'uploads/news';
            $this->ensureUploadPath($path);

            $tempName = $thumb->getRandomName();
            $thumb->move($path, $tempName);
            $tempPath = $path . '/' . $tempName;

            $image    = \Config\Services::image();
            $webpName = pathinfo($tempName, PATHINFO_FILENAME) . '.webp';
            $webpPath = $path . '/' . $webpName;

            try {
                $image->withFile($tempPath)->convert(IMAGETYPE_WEBP)->save($webpPath, 85);
                if (file_exists($tempPath)) @unlink($tempPath);
                $data['thumbnail'] = 'uploads/news/' . $webpName;
            } catch (\Exception $e) {
                if (file_exists($tempPath)) @unlink($tempPath);
                return redirect()->back()->with('error', 'Gagal memproses gambar thumbnail. Pastikan file adalah gambar yang valid.');
            }
        }

        $newsId = $newsModel->insert($data, true);
        $this->saveNewsMedia($newsId);

        return redirect()->back()->with('success', 'Berita disimpan.');
    }

    public function updateNews($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $newsModel = new NewsModel();
        $news      = $newsModel->find($id);

        if (!$news) {
            return redirect()->back()->with('error', 'Berita tidak ditemukan.');
        }

        // Validasi thumbnail
        $thumb = $this->request->getFile('thumbnail');
        if ($thumb && $thumb->isValid()) {
            $error = validate_image_upload($thumb);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Thumbnail tidak valid: maksimal 1MB dan format harus JPG/PNG.');
            }
        }

        // Validasi semua foto media
        $files = $this->request->getFileMultiple('media');
        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $error = validate_image_upload($file);
                    if ($error !== null) {
                        return redirect()->back()->withInput()->with('error', 'Upload dibatalkan. Pastikan semua foto maksimal 1MB dan format JPG/PNG.');
                    }
                }
            }
        }

        $data = [
            'judul'         => $this->request->getPost('judul'),
            'tanggal_waktu' => $this->request->getPost('tanggal_waktu') ?: $news['tanggal_waktu'],
            'isi'           => $this->request->getPost('isi'),
        ];

        if ($thumb && $thumb->isValid()) {
            $path = FCPATH . 'uploads/news';
            $this->ensureUploadPath($path);

            // Hapus thumbnail lama
            if ($news['thumbnail']) {
                $oldFile = FCPATH . ltrim($news['thumbnail'], '/');
                if (is_file($oldFile)) @unlink($oldFile);
            }

            $tempName = $thumb->getRandomName();
            $thumb->move($path, $tempName);
            $tempPath = $path . '/' . $tempName;

            $image    = \Config\Services::image();
            $webpName = pathinfo($tempName, PATHINFO_FILENAME) . '.webp';
            $webpPath = $path . '/' . $webpName;

            try {
                $image->withFile($tempPath)->convert(IMAGETYPE_WEBP)->save($webpPath, 85);
                if (file_exists($tempPath)) @unlink($tempPath);
                $data['thumbnail'] = 'uploads/news/' . $webpName;
            } catch (\Exception $e) {
                if (file_exists($tempPath)) @unlink($tempPath);
                return redirect()->back()->with('error', 'Gagal memproses gambar thumbnail. Pastikan file adalah gambar yang valid.');
            }
        }

        $newsModel->update($id, $data);
        $this->saveNewsMedia($id);

        return redirect()->back()->with('success', 'Berita diperbarui.');
    }

    public function deleteNews($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $newsModel = new NewsModel();
        $mediaModel= new NewsMediaModel();
        $news      = $newsModel->find($id);

        if ($news) {
            $mediaList = $mediaModel->where('news_id', $id)->findAll();
            foreach ($mediaList as $media) {
                if (isset($media['media_type']) && $media['media_type'] === 'foto') {
                    $file = FCPATH . ltrim($media['media_path'], '/');
                    if (is_file($file)) {
                        @unlink($file);
                    }
                }
            }
            $mediaModel->where('news_id', $id)->delete();
            $newsModel->delete($id);
        }

        return redirect()->back()->with('success', 'Berita dihapus.');
    }

    private function saveNewsMedia(int $newsId): void
    {
        $files = $this->request->getFileMultiple('media');
        if ($files) {
            $mediaModel = new NewsMediaModel();
            $path       = FCPATH . 'uploads/news';
            $this->ensureUploadPath($path);

            foreach ($files as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                $name = $file->getRandomName();
                $file->move($path, $name);
                $mediaModel->insert([
                    'news_id'    => $newsId,
                    'media_type' => 'foto',
                    'media_path' => 'uploads/news/' . $name,
                ]);
            }
        }

        $videoLinks = $this->request->getPost('video_links');
        if (is_array($videoLinks)) {
            $mediaModel = new NewsMediaModel();
            foreach ($videoLinks as $block) {
                $lines = preg_split('/\r\n|\r|\n/', (string) $block);
                foreach ($lines as $link) {
                    if (trim($link) === '') {
                        continue;
                    }
                    $mediaModel->insert([
                        'news_id'    => $newsId,
                        'media_type' => 'video_link',
                        'media_path' => trim($link),
                    ]);
                }
            }
        }
    }

    public function deleteNewsMedia($mediaId)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $mediaModel = new NewsMediaModel();
        $media      = $mediaModel->find($mediaId);
        if ($media) {
            if (isset($media['media_type']) && $media['media_type'] === 'foto') {
                $file = FCPATH . ltrim($media['media_path'], '/');
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            $newsId = $media['news_id'];
            $mediaModel->delete($mediaId);
            return redirect()->to('/staff/berita/' . $newsId . '/edit')->with('success', 'Media dihapus.');
        }

        return redirect()->back()->with('error', 'Media tidak ditemukan.');
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

        if (str_contains($host, 'youtu.be')) {
            $path = ltrim((string) parse_url($trimmed, PHP_URL_PATH), '/');
            return $path ? 'https://www.youtube.com/embed/' . $path : $trimmed;
        }

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

    // Perangkat Desa Methods
    public function perangkatDesa()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/perangkat_desa/index');
    }

    public function perangkatDesaApi()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }

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

        $list = $model->orderBy('id', 'DESC')
            ->findAll($limit, $offset);

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id'      => $item['id'],
                'nama'    => $item['nama'],
                'jabatan' => $item['jabatan'],
                'kontak'  => $item['kontak'] ?? '',
                'foto_url' => $item['foto'] ? base_url($item['foto']) : base_url('assets/img/guest.webp'),
            ];
        }

        return $this->response->setJSON([
            'success'      => true,
            'data'         => $data,
            'total'        => $total,
            'total_pages'  => $limit > 0 ? (int)ceil($total / $limit) : 1,
            'page'         => $page,
        ]);
    }

    public function createPerangkatDesa()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/perangkat_desa/create');
    }

    public function editPerangkatDesa($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $model = new PerangkatDesaModel();
        $item = $model->find($id);

        if (!$item) {
            return redirect()->to('/staff/perangkat-desa')->with('error', 'Data tidak ditemukan.');
        }

        return view('Staff/perangkat_desa/edit', [
            'item' => $item,
        ]);
    }

    public function storePerangkatDesa()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        helper('upload');

        $model = new PerangkatDesaModel();

        $data = [
            'nama'    => $this->request->getPost('nama'),
            'jabatan' => $this->request->getPost('jabatan'),
            'kontak'  => $this->request->getPost('kontak'),
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $imgError = validate_image_upload($foto);
            if ($imgError !== null) {
                return redirect()->back()->withInput()->with('error', 'Foto: ' . $imgError);
            }

            $fotoPath = $this->uploadToWebp($foto, 'uploads/perangkat_desa');
            if ($fotoPath === null) {
                return redirect()->back()->withInput()->with('error', 'Gagal memproses foto. Pastikan file adalah gambar yang valid.');
            }
            $data['foto'] = $fotoPath;
        }

        $model->insert($data);

        return redirect()->to('/staff/perangkat-desa')->with('success', 'Perangkat desa berhasil ditambahkan.');
    }

    public function updatePerangkatDesa($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        helper('upload');

        $model = new PerangkatDesaModel();
        $item  = $model->find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'nama'    => $this->request->getPost('nama'),
            'jabatan' => $this->request->getPost('jabatan'),
            'kontak'  => $this->request->getPost('kontak'),
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $imgError = validate_image_upload($foto);
            if ($imgError !== null) {
                return redirect()->back()->withInput()->with('error', 'Foto: ' . $imgError);
            }

            // Hapus foto lama
            if (!empty($item['foto'])) {
                $oldFile = FCPATH . ltrim($item['foto'], '/');
                if (is_file($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $fotoPath = $this->uploadToWebp($foto, 'uploads/perangkat_desa');
            if ($fotoPath === null) {
                return redirect()->back()->withInput()->with('error', 'Gagal memproses foto. Pastikan file adalah gambar yang valid.');
            }
            $data['foto'] = $fotoPath;
        }

        $model->update($id, $data);

        return redirect()->back()->with('success', 'Perangkat desa berhasil diperbarui.');
    }

    public function deletePerangkatDesa($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $model = new PerangkatDesaModel();
        $item = $model->find($id);

        if ($item) {
            // Hapus foto jika ada
            if ($item['foto']) {
                $file = FCPATH . ltrim($item['foto'], '/');
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            $model->delete($id);
            return redirect()->back()->with('success', 'Perangkat desa berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    public function inventaris()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/inventaris/index', [
            'title' => 'Inventaris Aset Desa',
        ]);
    }

    public function inventarisApi()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $page   = (int)($this->request->getPost('page') ?: 1);
        $length = (int)($this->request->getPost('length') ?: 10);
        $search = trim($this->request->getPost('search') ?? '');
        $jenis  = trim($this->request->getPost('jenis_filter') ?? '');
        $status = trim($this->request->getPost('status_filter') ?? '');
        $offset = ($page - 1) * $length;

        $model = new InventarisDesaModel();

        if ($search !== '') {
            $model->groupStart()
                ->like('nama_barang', $search)
                ->orLike('jenis', $search)
                ->groupEnd();
        }
        if ($jenis !== '') {
            $model->where('jenis', $jenis);
        }
        if ($status !== '') {
            $model->where('status', $status);
        }

        $total = $model->countAllResults(false);
        $list  = $model->orderBy('created_at', 'DESC')->findAll($length, $offset);

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id'          => $item['id'],
                'nama_barang' => $item['nama_barang'],
                'jenis'       => $item['jenis'],
                'total'       => $item['total'],
                'status'      => $item['status'],
                'foto_url'    => !empty($item['foto']) ? base_url($item['foto']) : null,
                'created_at'  => date('d M Y', strtotime($item['created_at'])),
            ];
        }

        return $this->response->setJSON([
            'success'     => true,
            'data'        => $data,
            'total'       => $total,
            'total_pages' => $length > 0 ? (int)ceil($total / $length) : 1,
            'page'        => $page,
        ]);
    }

    public function createInventaris()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/inventaris/create', [
            'title' => 'Tambah Inventaris'
        ]);
    }

    public function editInventaris($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $inventarisModel = new InventarisDesaModel();
        $item = $inventarisModel->find($id);

        if (!$item) {
            return redirect()->to('/staff/inventaris')->with('error', 'Data tidak ditemukan.');
        }

        return view('Staff/inventaris/edit', [
            'title' => 'Edit Inventaris',
            'item'  => $item,
        ]);
    }

    public function storeInventaris()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        helper('upload');
        $inventarisModel = new InventarisDesaModel();

        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jenis'       => $this->request->getPost('jenis'),
            'total'       => $this->request->getPost('total'),
            'status'      => $this->request->getPost('status'),
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $imgError = validate_image_upload($foto);
            if ($imgError !== null) {
                return redirect()->back()->withInput()->with('error', 'Foto: ' . $imgError);
            }
            $fotoPath = $this->uploadToWebp($foto, 'uploads/inventaris');
            if ($fotoPath === null) {
                return redirect()->back()->withInput()->with('error', 'Gagal memproses foto.');
            }
            $data['foto'] = $fotoPath;
        }

        $inventarisModel->insert($data);

        return redirect()->to('/staff/inventaris')->with('message', 'Data inventaris berhasil ditambahkan.');
    }

    public function updateInventaris($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        helper('upload');
        $inventarisModel = new InventarisDesaModel();
        $item = $inventarisModel->find($id);

        if (!$item) {
            return redirect()->to('/staff/inventaris')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jenis'       => $this->request->getPost('jenis'),
            'total'       => $this->request->getPost('total'),
            'status'      => $this->request->getPost('status'),
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $imgError = validate_image_upload($foto);
            if ($imgError !== null) {
                return redirect()->back()->withInput()->with('error', 'Foto: ' . $imgError);
            }
            if (!empty($item['foto'])) {
                $oldFile = FCPATH . ltrim($item['foto'], '/');
                if (is_file($oldFile)) @unlink($oldFile);
            }
            $fotoPath = $this->uploadToWebp($foto, 'uploads/inventaris');
            if ($fotoPath === null) {
                return redirect()->back()->withInput()->with('error', 'Gagal memproses foto.');
            }
            $data['foto'] = $fotoPath;
        }

        $inventarisModel->update($id, $data);

        return redirect()->to('/staff/inventaris/' . $id . '/edit')->with('message', 'Data inventaris berhasil diperbarui.');
    }

    public function deleteInventaris($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $inventarisModel = new InventarisDesaModel();
        $item = $inventarisModel->find($id);

        if ($item) {
            if (!empty($item['foto'])) {
                $file = FCPATH . $item['foto'];
                if (file_exists($file)) {
                    @unlink($file);
                }
            }
            $inventarisModel->delete($id);
        }

        return redirect()->to('/staff/inventaris')->with('message', 'Data inventaris berhasil dihapus');
    }

    // Pengumuman Methods
    public function pengumuman()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/pengumuman/index', [
            'title' => 'Pengumuman'
        ]);
    }

    public function pengumumanApi()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $page   = (int)($this->request->getPost('page') ?: 1);
        $limit  = 12;
        $search = trim($this->request->getPost('search') ?? '');
        $offset = ($page - 1) * $limit;

        $pengumumanModel = new PengumumanModel();

        if ($search !== '') {
            $pengumumanModel->groupStart()
                ->like('judul', $search)
                ->orLike('isi', $search)
                ->groupEnd();
        }

        $total = $pengumumanModel->countAllResults(false);

        $pengumuman = $pengumumanModel->orderBy('created_at', 'DESC')
            ->findAll($limit, $offset);

        $data = [];
        foreach ($pengumuman as $item) {
            $data[] = [
                'id'            => $item['id'],
                'judul'         => $item['judul'],
                'isi'           => strip_tags($item['isi'] ?? ''),
                'tanggal_waktu' => date('d M Y', strtotime($item['created_at'])),
                'thumbnail'     => !empty($item['thumbnail']) ? base_url($item['thumbnail']) : null,
            ];
        }

        return $this->response->setJSON([
            'success'     => true,
            'data'        => $data,
            'total'       => $total,
            'page'        => $page,
            'limit'       => $limit,
            'total_pages' => $limit > 0 ? (int)ceil($total / $limit) : 1,
        ]);
    }

    public function createPengumuman()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/pengumuman/create', [
            'title' => 'Tambah Pengumuman'
        ]);
    }

    public function storePengumuman()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        // Validasi thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid()) {
            $error = validate_image_upload($thumbnail);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Thumbnail tidak valid: maksimal 1MB dan format harus JPG/PNG.');
            }
        }

        // Validasi foto
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid()) {
            $error = validate_image_upload($foto);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Foto tidak valid: maksimal 1MB dan format harus JPG/PNG.');
            }
        }

        $pengumumanModel = new PengumumanModel();
        $data = [
            'judul' => $this->request->getPost('judul'),
            'isi'   => $this->request->getPost('isi'),
        ];

        // Proses thumbnail
        if ($thumbnail && $thumbnail->isValid()) {
            $data['thumbnail'] = $this->uploadToWebp($thumbnail, 'uploads/pengumuman');
            if ($data['thumbnail'] === null) {
                return redirect()->back()->with('error', 'Gagal memproses thumbnail. Pastikan file adalah gambar yang valid.');
            }
        }

        // Proses foto
        if ($foto && $foto->isValid()) {
            $data['foto'] = $this->uploadToWebp($foto, 'uploads/pengumuman');
            if ($data['foto'] === null) {
                return redirect()->back()->with('error', 'Gagal memproses foto. Pastikan file adalah gambar yang valid.');
            }
        }

        $pengumumanModel->save($data);

        return redirect()->to('/staff/pengumuman')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function editPengumuman($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pengumumanModel = new PengumumanModel();
        $pengumuman = $pengumumanModel->find($id);

        if (!$pengumuman) {
            return redirect()->to('/staff/pengumuman')->with('error', 'Pengumuman tidak ditemukan');
        }

        return view('Staff/pengumuman/edit', [
            'title' => 'Edit Pengumuman',
            'pengumuman' => $pengumuman
        ]);
    }

    public function updatePengumuman($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pengumumanModel = new PengumumanModel();
        $pengumuman      = $pengumumanModel->find($id);

        if (!$pengumuman) {
            return redirect()->to('/staff/pengumuman')->with('error', 'Pengumuman tidak ditemukan.');
        }

        // Validasi thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid()) {
            $error = validate_image_upload($thumbnail);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Thumbnail tidak valid: maksimal 1MB dan format harus JPG/PNG.');
            }
        }

        // Validasi foto
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid()) {
            $error = validate_image_upload($foto);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Foto tidak valid: maksimal 1MB dan format harus JPG/PNG.');
            }
        }

        $data = [
            'judul' => $this->request->getPost('judul'),
            'isi'   => $this->request->getPost('isi'),
        ];

        // Proses thumbnail baru
        if ($thumbnail && $thumbnail->isValid()) {
            // Hapus file lama
            if (!empty($pengumuman['thumbnail'])) {
                $oldFile = FCPATH . ltrim($pengumuman['thumbnail'], '/');
                if (is_file($oldFile)) @unlink($oldFile);
            }
            $data['thumbnail'] = $this->uploadToWebp($thumbnail, 'uploads/pengumuman');
            if ($data['thumbnail'] === null) {
                return redirect()->back()->with('error', 'Gagal memproses thumbnail. Pastikan file adalah gambar yang valid.');
            }
        }

        // Proses foto baru
        if ($foto && $foto->isValid()) {
            // Hapus file lama
            if (!empty($pengumuman['foto'])) {
                $oldFile = FCPATH . ltrim($pengumuman['foto'], '/');
                if (is_file($oldFile)) @unlink($oldFile);
            }
            $data['foto'] = $this->uploadToWebp($foto, 'uploads/pengumuman');
            if ($data['foto'] === null) {
                return redirect()->back()->with('error', 'Gagal memproses foto. Pastikan file adalah gambar yang valid.');
            }
        }

        $pengumumanModel->update($id, $data);

        return redirect()->to('/staff/pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function deletePengumuman($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pengumumanModel = new PengumumanModel();
        $pengumuman = $pengumumanModel->find($id);

        if ($pengumuman) {
            // Delete thumbnail
            if (!empty($pengumuman['thumbnail'])) {
                $file = FCPATH . $pengumuman['thumbnail'];
                if (file_exists($file)) {
                    @unlink($file);
                }
            }

            // Delete foto
            if (!empty($pengumuman['foto'])) {
                $file = FCPATH . $pengumuman['foto'];
                if (file_exists($file)) {
                    @unlink($file);
                }
            }

            $pengumumanModel->delete($id);
        }

        return redirect()->to('/staff/pengumuman')->with('message', 'Pengumuman berhasil dihapus');
    }

    // Pengaduan Methods
    public function pengaduan()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/pengaduan/index', [
            'title' => 'Pengaduan Masyarakat'
        ]);
    }

    public function detailPengaduan($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pengaduanModel = new PengaduanModel();
        $pengaduan = $pengaduanModel->find($id);

        if (!$pengaduan) {
            return redirect()->to('/staff/pengaduan')->with('error', 'Pengaduan tidak ditemukan');
        }

        return view('Staff/pengaduan/detail', [
            'title' => 'Detail Pengaduan',
            'pengaduan' => $pengaduan
        ]);
    }

    public function pengaduanApi()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $pengaduanModel = new PengaduanModel();

        $page      = (int)($this->request->getPost('page') ?: 1);
        $limit     = (int)($this->request->getPost('length') ?: 10);
        $search    = trim($this->request->getPost('search') ?? '');
        $dateStart = trim($this->request->getPost('date_start') ?? '');
        $dateEnd   = trim($this->request->getPost('date_end') ?? '');
        $offset    = ($page - 1) * $limit;

        $builder = $pengaduanModel->builder();

        if ($search !== '') {
            $builder->groupStart()
                    ->like('nama', $search)
                    ->orLike('perihal', $search)
                    ->orLike('isi', $search)
                    ->groupEnd();
        }

        if ($dateStart !== '') {
            $builder->where('DATE(created_at) >=', $dateStart);
        }

        if ($dateEnd !== '') {
            $builder->where('DATE(created_at) <=', $dateEnd);
        }

        $total    = $builder->countAllResults(false);
        $pengaduan = $builder->orderBy('created_at', 'DESC')
                             ->limit($limit, $offset)
                             ->get()
                             ->getResultArray();

        foreach ($pengaduan as &$item) {
            $item['tanggal_waktu'] = date('d M Y H:i', strtotime($item['created_at']));
        }

        return $this->response->setJSON([
            'success'      => true,
            'data'         => $pengaduan,
            'total'        => $total,
            'total_pages'  => $limit > 0 ? (int)ceil($total / $limit) : 1,
            'current_page' => $page,
        ]);
    }

    public function deletePengaduan($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pengaduanModel = new PengaduanModel();
        $pengaduan = $pengaduanModel->find($id);

        if ($pengaduan) {
            // Delete foto if exists
            if (!empty($pengaduan['foto'])) {
                $file = FCPATH . $pengaduan['foto'];
                if (file_exists($file)) {
                    @unlink($file);
                }
            }

            $pengaduanModel->delete($id);
        }

        return redirect()->to('/staff/pengaduan')->with('message', 'Pengaduan berhasil dihapus');
    }
}


