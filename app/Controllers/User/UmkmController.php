<?php

namespace App\Controllers\User;

use App\Controllers\ProtectedController;
use App\Models\UmkmModel;
use App\Models\UmkmEcommerceModel;
use App\Models\UmkmProdukModel;
use App\Models\UmkmProdukGambarModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Models\UserProfileModel;

class UmkmController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        return view('User/umkm/index', [
            'title' => 'UMKM Saya',
        ]);
    }

    public function api()
    {
        if ($redirect = $this->guard(['user'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $umkmModel = new UmkmModel();

        $page      = (int)($this->request->getPost('page') ?: 1);
        $limit     = (int)($this->request->getPost('length') ?: 10);
        $search    = trim($this->request->getPost('search') ?? '');
        $status    = trim($this->request->getPost('status_filter') ?? '');
        $dateStart = trim($this->request->getPost('date_start') ?? '');
        $dateEnd   = trim($this->request->getPost('date_end') ?? '');
        $offset    = ($page - 1) * $limit;

        $builder = $umkmModel->builder();
        $builder->where('user_id', $this->currentUser['id']);

        if ($search !== '') {
            $builder->groupStart()
                    ->like('nama_toko', $search)
                    ->orLike('kontak', $search)
                    ->groupEnd();
        }

        if ($status !== '') {
            $builder->where('status', $status);
        }

        if ($dateStart !== '') {
            $builder->where('DATE(created_at) >=', $dateStart);
        }

        if ($dateEnd !== '') {
            $builder->where('DATE(created_at) <=', $dateEnd);
        }

        $total = $builder->countAllResults(false);
        $list  = $builder->orderBy('created_at', 'DESC')
                         ->limit($limit, $offset)
                         ->get()
                         ->getResultArray();

        foreach ($list as &$item) {
            $item['didaftarkan'] = date('d M Y', strtotime($item['created_at']));
        }
        unset($item);

        return $this->response->setJSON([
            'success'      => true,
            'data'         => $list,
            'total'        => $total,
            'total_pages'  => $limit > 0 ? (int)ceil($total / $limit) : 1,
            'current_page' => $page,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        return view('User/umkm/create', [
            'title' => 'Daftarkan Toko UMKM',
        ]);
    }

    public function store()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        helper('upload');
        $mapsUrl = $this->parseMapsUrl($this->request->getPost('maps_embed_url'));

        $umkmModel = new UmkmModel();
        $data = [
            'user_id'        => $this->currentUser['id'],
            'nama_toko'      => $this->request->getPost('nama_toko'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'alamat'         => $this->request->getPost('alamat'),
            'maps_embed_url' => $mapsUrl,
            'kontak'         => $this->request->getPost('kontak'),
            'status'         => 'pending',
            'created_by'     => $this->currentUser['id'],
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        // Handle foto toko
        $fotoToko = $this->request->getFile('foto_toko');
        if ($fotoToko && $fotoToko->isValid() && !$fotoToko->hasMoved()) {
            $error = validate_image_upload($fotoToko);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Foto Toko: ' . $error);
            }
            $path = $this->uploadToWebp($fotoToko, 'uploads/umkm/toko');
            if ($path === null) {
                return redirect()->back()->withInput()->with('error', 'Gagal memproses foto toko.');
            }
            $data['foto_toko'] = $path;
        }

        $umkmId = $umkmModel->insert($data, true);

        // Simpan link ecommerce
        $this->saveEcommerce($umkmId);

        // Simpan produk + gambar
        $error = $this->saveProduk($umkmId);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        // Kirim notifikasi ke semua staf
        $this->notifyStaff($umkmId, $data['nama_toko']);

        return redirect()->to('/user/umkm')->with('success', 'Toko UMKM berhasil didaftarkan. Menunggu persetujuan dari staff.');
    }

    public function show($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $data = $this->loadUmkmData($id);
        if (!$data) {
            return redirect()->to('/user/umkm')->with('error', 'UMKM tidak ditemukan.');
        }

        return view('User/umkm/show', array_merge($data, ['title' => 'Detail Toko']));
    }

    public function edit($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $umkmModel = new UmkmModel();
        $umkm = $umkmModel->where('user_id', $this->currentUser['id'])->find($id);
        if (!$umkm) {
            return redirect()->to('/user/umkm')->with('error', 'UMKM tidak ditemukan.');
        }

        // User bisa edit toko yang approved, pending, atau rejected
        $data = $this->loadUmkmData($id);
        return view('User/umkm/edit', array_merge($data, ['title' => 'Edit Toko']));
    }

    public function update($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        helper('upload');
        $umkmModel = new UmkmModel();
        $umkm = $umkmModel->where('user_id', $this->currentUser['id'])->find($id);
        if (!$umkm) {
            return redirect()->to('/user/umkm')->with('error', 'UMKM tidak ditemukan.');
        }

        $mapsUrl = $this->parseMapsUrl($this->request->getPost('maps_embed_url'));

        $updateData = [
            'nama_toko'      => $this->request->getPost('nama_toko'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'alamat'         => $this->request->getPost('alamat'),
            'maps_embed_url' => $mapsUrl,
            'kontak'         => $this->request->getPost('kontak'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        // Handle foto toko
        $fotoToko = $this->request->getFile('foto_toko');
        if ($fotoToko && $fotoToko->isValid() && !$fotoToko->hasMoved()) {
            $error = validate_image_upload($fotoToko);
            if ($error !== null) {
                return redirect()->back()->withInput()->with('error', 'Foto Toko: ' . $error);
            }
            $path = $this->uploadToWebp($fotoToko, 'uploads/umkm/toko');
            if ($path === null) {
                return redirect()->back()->withInput()->with('error', 'Gagal memproses foto toko.');
            }
            // Hapus foto lama
            if (!empty($umkm['foto_toko'])) {
                $oldPath = FCPATH . ltrim($umkm['foto_toko'], '/');
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $updateData['foto_toko'] = $path;
        }

        // Hanya jika sebelumnya rejected → kembali ke pending (resubmit)
        $wasRejected = ($umkm['status'] === 'rejected');

        if ($wasRejected) {
            $updateData['status']       = 'pending';
            $updateData['alasan_tolak'] = null;
        }
        // Jika approved atau pending → status TIDAK berubah (langsung disimpan)

        $umkmModel->update($id, $updateData);

        // Update ecommerce (hapus semua dulu)
        $ecomModel = new UmkmEcommerceModel();
        $ecomModel->where('umkm_id', $id)->delete();
        $this->saveEcommerce($id);

        // Simpan produk baru
        $error = $this->saveProduk($id);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        // Jika resubmit setelah ditolak → kirim notif baru ke staff
        if ($wasRejected) {
            $this->notifyStaff($id, $updateData['nama_toko']);
            return redirect()->to('/user/umkm')->with('success', 'Toko berhasil diperbarui dan dikirim ulang untuk peninjauan.');
        }

        return redirect()->to('/user/umkm/' . $id)->with('success', 'Toko berhasil diperbarui.');
    }

    public function delete($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $umkmModel   = new UmkmModel();
        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();
        $ecomModel   = new UmkmEcommerceModel();
        $notifModel  = new NotificationModel();

        $umkm = $umkmModel->where('user_id', $this->currentUser['id'])->find($id);
        if (!$umkm) {
            return redirect()->to('/user/umkm')->with('error', 'UMKM tidak ditemukan.');
        }

        // Hapus foto toko
        if (!empty($umkm['foto_toko'])) {
            $filePath = FCPATH . ltrim($umkm['foto_toko'], '/');
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }

        // Hapus semua gambar produk
        $produkList = $produkModel->where('umkm_id', $id)->findAll();
        foreach ($produkList as $produk) {
            $gambarList = $gambarModel->where('produk_id', $produk['id'])->findAll();
            foreach ($gambarList as $g) {
                $filePath = FCPATH . ltrim($g['gambar_path'], '/');
                if (is_file($filePath)) {
                    @unlink($filePath);
                }
            }
            $gambarModel->where('produk_id', $produk['id'])->delete();
        }

        $produkModel->where('umkm_id', $id)->delete();
        $ecomModel->where('umkm_id', $id)->delete();
        $notifModel->where('related_umkm_id', $id)->delete();
        $umkmModel->delete($id);

        return redirect()->to('/user/umkm')->with('success', 'Toko UMKM berhasil dihapus.');
    }

    /**
     * Form edit produk yang sudah ada (user)
     */
    public function editProduk($produkId)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();
        $umkmModel   = new UmkmModel();

        $produk = $produkModel->find($produkId);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Pastikan produk ini milik user yang login
        $umkm = $umkmModel->where('user_id', $this->currentUser['id'])->find($produk['umkm_id']);
        if (!$umkm) {
            return redirect()->to('/user/umkm')->with('error', 'Akses ditolak.');
        }

        $produk['gambar'] = $gambarModel->where('produk_id', $produkId)->findAll();

        return view('User/umkm/edit_produk', [
            'title'  => 'Edit Produk',
            'produk' => $produk,
            'umkm'   => $umkm,
        ]);
    }

    /**
     * Proses update produk yang sudah ada (user)
     */
    public function updateProduk($produkId)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();
        $umkmModel   = new UmkmModel();

        $produk = $produkModel->find($produkId);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $umkmId = $produk['umkm_id'];

        // Pastikan produk ini milik user yang login
        $umkm = $umkmModel->where('user_id', $this->currentUser['id'])->find($umkmId);
        if (!$umkm) {
            return redirect()->to('/user/umkm')->with('error', 'Akses ditolak.');
        }

        $hargaRaw   = $this->request->getPost('harga');
        $hargaClean = !empty($hargaRaw) ? (float) str_replace([',', '.'], '', $hargaRaw) : null;

        $produkModel->update($produkId, [
            'nama_produk' => trim($this->request->getPost('nama_produk')),
            'harga'       => $hargaClean,
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        // Upload gambar baru jika ada
        $gambarFiles = $this->request->getFileMultiple('gambar_baru');
        if ($gambarFiles) {
            foreach ($gambarFiles as $file) {
                if (!$file->isValid() || $file->hasMoved()) {
                    continue;
                }
                $result = $this->processImageUpload($file, 'uploads/umkm');
                if ($result['success']) {
                    $gambarModel->insert([
                        'produk_id'   => $produkId,
                        'gambar_path' => $result['path'],
                        'created_at'  => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        return redirect()->to('/user/umkm/' . $umkmId . '/edit')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk (user)
     */
    public function deleteProduk($produkId)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();
        $umkmModel   = new UmkmModel();

        $produk = $produkModel->find($produkId);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $umkmId = $produk['umkm_id'];

        // Pastikan produk ini milik user yang login
        $umkm = $umkmModel->where('user_id', $this->currentUser['id'])->find($umkmId);
        if (!$umkm) {
            return redirect()->to('/user/umkm')->with('error', 'Akses ditolak.');
        }

        // Hapus semua gambar produk
        $gambarList = $gambarModel->where('produk_id', $produkId)->findAll();
        foreach ($gambarList as $g) {
            $filePath = FCPATH . ltrim($g['gambar_path'], '/');
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
        $gambarModel->where('produk_id', $produkId)->delete();
        $produkModel->delete($produkId);

        return redirect()->to('/user/umkm/' . $umkmId . '/edit')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Hapus gambar produk (user)
     */
    public function deleteGambarProduk($gambarId)
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $gambarModel = new UmkmProdukGambarModel();
        $produkModel = new UmkmProdukModel();
        $umkmModel   = new UmkmModel();

        $gambar = $gambarModel->find($gambarId);
        if (!$gambar) {
            return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
        }

        $produk = $produkModel->find($gambar['produk_id']);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $umkmId = $produk['umkm_id'];

        // Pastikan ini milik user yang login
        $umkm = $umkmModel->where('user_id', $this->currentUser['id'])->find($umkmId);
        if (!$umkm) {
            return redirect()->to('/user/umkm')->with('error', 'Akses ditolak.');
        }

        $filePath = FCPATH . ltrim($gambar['gambar_path'], '/');
        if (is_file($filePath)) {
            @unlink($filePath);
        }
        $gambarModel->delete($gambarId);

        return redirect()->to('/user/umkm/' . $umkmId . '/edit')->with('success', 'Gambar produk berhasil dihapus.');
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function loadUmkmData(int $id): ?array
    {
        $umkmModel   = new UmkmModel();
        $ecomModel   = new UmkmEcommerceModel();
        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();

        $umkm = $umkmModel->where('user_id', $this->currentUser['id'])->find($id);
        if (!$umkm) {
            return null;
        }

        $ecommerce  = $ecomModel->where('umkm_id', $id)->findAll();
        $produkList = $produkModel->where('umkm_id', $id)->findAll();

        foreach ($produkList as &$produk) {
            $produk['gambar'] = $gambarModel->where('produk_id', $produk['id'])->findAll();
        }
        unset($produk);

        return [
            'umkm'      => $umkm,
            'ecommerce' => $ecommerce,
            'produk'    => $produkList,
        ];
    }

    private function parseMapsUrl(?string $raw): string
    {
        if (!$raw) {
            return '';
        }
        if (preg_match('/src=["\']([^"\']+)["\']/', $raw, $matches)) {
            $raw = $matches[1];
        }
        return strip_tags(trim($raw));
    }

    private function saveEcommerce(int $umkmId): void
    {
        $ecommerceModel = new UmkmEcommerceModel();
        $platforms = $this->request->getPost('ecommerce_platform') ?? [];
        $urls      = $this->request->getPost('ecommerce_url') ?? [];

        if (!is_array($platforms)) {
            return;
        }

        foreach ($platforms as $i => $platform) {
            $url = $urls[$i] ?? '';
            if (empty(trim($url))) {
                continue;
            }
            $ecommerceModel->insert([
                'umkm_id'    => $umkmId,
                'platform'   => trim($platform),
                'url'        => trim($url),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function saveProduk(int $umkmId): ?string
    {
        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();

        $namaProduk  = $this->request->getPost('produk_nama') ?? [];
        $hargaProduk = $this->request->getPost('produk_harga') ?? [];
        $deskProduk  = $this->request->getPost('produk_deskripsi') ?? [];

        if (!is_array($namaProduk)) {
            return null;
        }

        foreach ($namaProduk as $i => $nama) {
            if (empty(trim($nama))) {
                continue;
            }

            $harga = $hargaProduk[$i] ?? null;
            $hargaClean = !empty($harga) ? (float) str_replace([',', '.'], '', $harga) : null;

            $produkId = $produkModel->insert([
                'umkm_id'     => $umkmId,
                'nama_produk' => trim($nama),
                'harga'       => $hargaClean,
                'deskripsi'   => $deskProduk[$i] ?? null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ], true);

            $gambarFiles = $this->request->getFileMultiple("produk_gambar_{$i}");
            if ($gambarFiles) {
                foreach ($gambarFiles as $file) {
                    if (!$file->isValid()) {
                        continue;
                    }
                    $result = $this->processImageUpload($file, 'uploads/umkm');
                    if ($result['success']) {
                        $gambarModel->insert([
                            'produk_id'   => $produkId,
                            'gambar_path' => $result['path'],
                            'created_at'  => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }

        return null;
    }

    private function processImageUpload($file, string $folder): array
    {
        $ext = strtolower($file->getClientExtension());
        if ($ext === 'gif') {
            return ['success' => false, 'message' => 'Format .GIF tidak diperbolehkan.'];
        }

        $path = FCPATH . $folder;
        $this->ensureUploadPath($path);

        $tempName = $file->getRandomName();
        $file->move($path, $tempName);
        $tempPath = $path . '/' . $tempName;

        $webpName = pathinfo($tempName, PATHINFO_FILENAME) . '.webp';
        $webpPath = $path . '/' . $webpName;

        try {
            \Config\Services::image()
                ->withFile($tempPath)
                ->convert(IMAGETYPE_WEBP)
                ->save($webpPath, 85);

            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }

            return ['success' => true, 'path' => $folder . '/' . $webpName];
        } catch (\Exception $e) {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return ['success' => false, 'message' => 'Gagal memproses gambar.'];
        }
    }

    private function notifyStaff(int $umkmId, string $namaToko): void
    {
        $userModel    = new UserModel();
        $profileModel = new UserProfileModel();
        $notifModel   = new NotificationModel();

        $userProfile = $profileModel->find($this->currentUser['id']);
        $userName = ($userProfile && !empty($userProfile['nama_lengkap']))
            ? $userProfile['nama_lengkap']
            : $this->currentUser['username'];

        $staffList = $userModel->where('role', 'staf')->findAll();
        $umkmUrl   = base_url('/staff/umkm/' . $umkmId);

        foreach ($staffList as $staff) {
            $notifModel->insert([
                'user_id'         => $staff['id'],
                'type'            => 'new_umkm',
                'title'           => 'Pengajuan UMKM Baru',
                'message'         => $userName . ' mengajukan toko UMKM: "' . $namaToko . '"',
                'related_umkm_id' => $umkmId,
                'is_read'         => 0,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            try {
                $emailService = new \App\Libraries\EmailService();
                $emailService->sendNotification(
                    $staff['email'],
                    $staff['username'],
                    'Pengajuan UMKM Baru',
                    $userName . ' mengajukan toko UMKM: "' . $namaToko . '"',
                    'new_umkm',
                    $umkmUrl,
                    $namaToko,
                    'UMKM'
                );
            } catch (\Exception $e) {
                log_message('error', 'Gagal kirim email notif UMKM: ' . $e->getMessage());
            }
        }
    }
}
