<?php

namespace App\Controllers\Staff;

use App\Controllers\ProtectedController;
use App\Models\UmkmModel;
use App\Models\UmkmEcommerceModel;
use App\Models\UmkmProdukModel;
use App\Models\UmkmProdukGambarModel;
use App\Models\NotificationModel;
use App\Models\UserProfileModel;

class UmkmController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $umkmModel = new UmkmModel();

        $totalPending  = $umkmModel->where('status', 'pending')->countAllResults();
        $totalApproved = $umkmModel->where('status', 'approved')->countAllResults();
        $totalRejected = $umkmModel->where('status', 'rejected')->countAllResults();

        return view('Staff/umkm/index', [
            'title'         => 'Kelola UMKM',
            'totalPending'  => $totalPending,
            'totalApproved' => $totalApproved,
            'totalRejected' => $totalRejected,
        ]);
    }

    public function api()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $umkmModel    = new UmkmModel();
        $profileModel = new UserProfileModel();

        $page      = (int)($this->request->getPost('page') ?: 1);
        $limit     = (int)($this->request->getPost('length') ?: 10);
        $search    = trim($this->request->getPost('search') ?? '');
        $status    = trim($this->request->getPost('status_filter') ?? '');
        $dateStart = trim($this->request->getPost('date_start') ?? '');
        $dateEnd   = trim($this->request->getPost('date_end') ?? '');
        $offset    = ($page - 1) * $limit;

        $builder = $umkmModel->builder();

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
            if ($item['user_id']) {
                $profile = $profileModel->find($item['user_id']);
                $item['pemilik'] = $profile['nama_lengkap'] ?? 'User';
            } else {
                $item['pemilik'] = 'Staff';
            }
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

    /**
     * AJAX endpoint: daftar produk UMKM untuk infinite scroll di tab edit.
     */
    public function produkApi($umkmId)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Bad Request'])->setStatusCode(400);
        }

        $umkmModel   = new UmkmModel();
        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();

        $umkm = $umkmModel->find($umkmId);
        if (!$umkm) {
            return $this->response->setJSON(['success' => false, 'error' => 'UMKM tidak ditemukan'])->setStatusCode(404);
        }

        $limit  = 8;
        $page   = (int)($this->request->getPost('page') ?: 1);
        $offset = ($page - 1) * $limit;

        $total = $produkModel->where('umkm_id', $umkmId)->countAllResults();
        $list  = $produkModel->where('umkm_id', $umkmId)
                             ->orderBy('created_at', 'ASC')
                             ->limit($limit, $offset)
                             ->findAll();

        foreach ($list as &$p) {
            $gambar = $gambarModel->where('produk_id', $p['id'])->findAll();
            $p['gambar_path'] = !empty($gambar) ? $gambar[0]['gambar_path'] : null;
            $p['harga_fmt']   = $p['harga'] ? 'Rp ' . number_format((float)$p['harga'], 0, ',', '.') : null;
        }
        unset($p);

        return $this->response->setJSON([
            'success'     => true,
            'data'        => $list,
            'total'       => $total,
            'page'        => $page,
            'has_more'    => ($offset + $limit) < $total,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/umkm/create', [
            'title' => 'Tambah UMKM',
        ]);
    }

    public function store()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        helper('upload');

        // ── Validasi field wajib ──────────────────────────────────────────────
        $namaToko  = trim($this->request->getPost('nama_toko') ?? '');
        $deskripsi = trim($this->request->getPost('deskripsi') ?? '');
        $alamat    = trim($this->request->getPost('alamat') ?? '');
        $kontak    = trim($this->request->getPost('kontak') ?? '');

        if ($namaToko === '') {
            return redirect()->back()->withInput()->with('error', 'Nama toko wajib diisi.');
        }
        if ($deskripsi === '') {
            return redirect()->back()->withInput()->with('error', 'Deskripsi toko wajib diisi.');
        }
        if ($alamat === '') {
            return redirect()->back()->withInput()->with('error', 'Alamat toko wajib diisi.');
        }
        if ($kontak === '') {
            return redirect()->back()->withInput()->with('error', 'Nomor kontak wajib diisi.');
        }

        // ── Validasi Google Maps URL (opsional, jika diisi harus valid) ───────
        $mapsRaw   = $this->request->getPost('maps_embed_url');
        $mapsError = validate_maps_url($mapsRaw);
        if ($mapsError !== null) {
            return redirect()->back()->withInput()->with('error', $mapsError);
        }
        $mapsUrl = $this->parseMapsUrl($mapsRaw);

        // ── Validasi minimal 1 produk ─────────────────────────────────────────
        $namaProdukList = (array)($this->request->getPost('produk_nama') ?? []);
        $hasProduk = false;
        foreach ($namaProdukList as $np) {
            if (trim($np) !== '') {
                $hasProduk = true;
                break;
            }
        }
        if (!$hasProduk) {
            return redirect()->back()->withInput()->with('error', 'Minimal 1 produk harus didaftarkan saat membuat toko.');
        }

        // ── Validasi foto toko (wajib saat create) ────────────────────────────
        $fotoToko = $this->request->getFile('foto_toko');
        if (!$fotoToko || !$fotoToko->isValid() || $fotoToko->hasMoved()) {
            return redirect()->back()->withInput()->with('error', 'Foto toko wajib diupload.');
        }
        $fotoError = validate_image_upload($fotoToko);
        if ($fotoError !== null) {
            return redirect()->back()->withInput()->with('error', 'Foto Toko: ' . $fotoError);
        }
        $fotoPath = $this->uploadToWebp($fotoToko, 'uploads/umkm/toko');
        if ($fotoPath === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses foto toko.');
        }

        $umkmModel = new UmkmModel();
        $data = [
            'user_id'        => null,
            'nama_toko'      => $namaToko,
            'deskripsi'      => $deskripsi,
            'alamat'         => $alamat,
            'maps_embed_url' => $mapsUrl,
            'kontak'         => $kontak,
            'foto_toko'      => $fotoPath,
            'status'         => 'approved',
            'created_by'     => $this->currentUser['id'],
            'approved_by'    => $this->currentUser['id'],
            'approved_at'    => date('Y-m-d H:i:s'),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        $umkmId = $umkmModel->insert($data, true);

        $this->saveEcommerce($umkmId);

        $error = $this->saveProduk($umkmId);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        return redirect()->to('/staff/umkm')->with('success', 'UMKM berhasil ditambahkan.');
    }

    public function show($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $data = $this->loadUmkmData($id);
        if (!$data) {
            return redirect()->to('/staff/umkm')->with('error', 'UMKM tidak ditemukan.');
        }

        return view('Staff/umkm/show', array_merge($data, ['title' => 'Detail UMKM']));
    }

    public function edit($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $umkmModel    = new UmkmModel();
        $ecomModel    = new UmkmEcommerceModel();
        $profileModel = new UserProfileModel();

        $umkm = $umkmModel->find($id);
        if (!$umkm) {
            return redirect()->to('/staff/umkm')->with('error', 'UMKM tidak ditemukan.');
        }

        // UMKM dengan status pending hanya bisa ditinjau, bukan diedit oleh Staff
        if ($umkm['status'] === 'pending') {
            return redirect()->to('/staff/umkm/' . $id)->with('info', 'UMKM ini masih menunggu persetujuan. Gunakan section Tindak Lanjut untuk menyetujui atau menolak.');
        }

        if ($umkm['user_id']) {
            $profile = $profileModel->find($umkm['user_id']);
            $umkm['pemilik'] = $profile['nama_lengkap'] ?? 'User';
        } else {
            $umkm['pemilik'] = 'Staff';
        }

        $ecommerce = $ecomModel->where('umkm_id', $id)->findAll();

        return view('Staff/umkm/edit', [
            'title'     => 'Edit UMKM',
            'umkm'      => $umkm,
            'ecommerce' => $ecommerce,
        ]);
    }

    public function update($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        helper('upload');
        $umkmModel = new UmkmModel();
        $umkm = $umkmModel->find($id);
        if (!$umkm) {
            return redirect()->to('/staff/umkm')->with('error', 'UMKM tidak ditemukan.');
        }

        // ── Validasi field wajib ──────────────────────────────────────────────
        $namaToko  = trim($this->request->getPost('nama_toko') ?? '');
        $deskripsi = trim($this->request->getPost('deskripsi') ?? '');
        $alamat    = trim($this->request->getPost('alamat') ?? '');
        $kontak    = trim($this->request->getPost('kontak') ?? '');

        if ($namaToko === '') {
            return redirect()->back()->withInput()->with('error', 'Nama toko wajib diisi.');
        }
        if ($deskripsi === '') {
            return redirect()->back()->withInput()->with('error', 'Deskripsi toko wajib diisi.');
        }
        if ($alamat === '') {
            return redirect()->back()->withInput()->with('error', 'Alamat toko wajib diisi.');
        }
        if ($kontak === '') {
            return redirect()->back()->withInput()->with('error', 'Nomor kontak wajib diisi.');
        }

        // ── Validasi Google Maps URL (opsional) ───────────────────────────────
        $mapsRaw   = $this->request->getPost('maps_embed_url');
        $mapsError = validate_maps_url($mapsRaw);
        if ($mapsError !== null) {
            return redirect()->back()->withInput()->with('error', $mapsError);
        }
        $mapsUrl = $this->parseMapsUrl($mapsRaw);

        $updateData = [
            'nama_toko'      => $namaToko,
            'deskripsi'      => $deskripsi,
            'alamat'         => $alamat,
            'maps_embed_url' => $mapsUrl,
            'kontak'         => $kontak,
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
            if (!empty($umkm['foto_toko'])) {
                $oldPath = FCPATH . ltrim($umkm['foto_toko'], '/');
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $updateData['foto_toko'] = $path;
        }

        $umkmModel->update($id, $updateData);

        $ecommerceModel = new UmkmEcommerceModel();
        $ecommerceModel->where('umkm_id', $id)->delete();
        $this->saveEcommerce($id);

        $error = $this->saveProduk($id);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        return redirect()->to('/staff/umkm/' . $id . '/edit')->with('success', 'UMKM berhasil diperbarui.');
    }

    public function delete($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $umkmModel   = new UmkmModel();
        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();
        $ecomModel   = new UmkmEcommerceModel();
        $notifModel  = new NotificationModel();

        $umkm = $umkmModel->find($id);
        if (!$umkm) {
            return redirect()->to('/staff/umkm')->with('error', 'UMKM tidak ditemukan.');
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

        return redirect()->to('/staff/umkm')->with('success', 'UMKM berhasil dihapus.');
    }

    public function approve($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $umkmModel  = new UmkmModel();
        $notifModel = new NotificationModel();

        $umkm = $umkmModel->find($id);
        if (!$umkm || $umkm['status'] !== 'pending') {
            return redirect()->to('/staff/umkm')->with('error', 'UMKM tidak ditemukan atau bukan dalam status pending.');
        }

        $umkmModel->update($id, [
            'status'       => 'approved',
            'alasan_tolak' => null,
            'approved_by'  => $this->currentUser['id'],
            'approved_at'  => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        if ($umkm['user_id']) {
            $notifModel->insert([
                'user_id'         => $umkm['user_id'],
                'type'            => 'umkm_approved',
                'title'           => 'UMKM Anda Disetujui',
                'message'         => 'Toko "' . $umkm['nama_toko'] . '" telah disetujui dan kini tampil di halaman publik.',
                'related_umkm_id' => $id,
                'is_read'         => 0,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            try {
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($umkm['user_id']);
                if ($user) {
                    $emailService = new \App\Libraries\EmailService();
                    $emailService->sendNotification(
                        $user['email'],
                        $user['username'],
                        'UMKM Anda Disetujui',
                        'Toko "' . $umkm['nama_toko'] . '" telah disetujui.',
                        'umkm_approved',
                        base_url('/umkm/' . $id),
                        $umkm['nama_toko'],
                        'UMKM'
                    );
                }
            } catch (\Exception $e) {
                log_message('error', 'Gagal kirim email approve UMKM: ' . $e->getMessage());
            }
        }

        return redirect()->to('/staff/umkm')->with('success', 'UMKM berhasil disetujui.');
    }

    public function reject($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $umkmModel  = new UmkmModel();
        $notifModel = new NotificationModel();

        $umkm = $umkmModel->find($id);
        if (!$umkm || $umkm['status'] !== 'pending') {
            return redirect()->to('/staff/umkm')->with('error', 'UMKM tidak ditemukan atau bukan dalam status pending.');
        }

        $alasan = trim($this->request->getPost('alasan') ?? '');
        if (empty($alasan)) {
            return redirect()->back()->with('error', 'Alasan penolakan wajib diisi.');
        }

        $umkmModel->update($id, [
            'status'       => 'rejected',
            'alasan_tolak' => $alasan,
            'approved_by'  => $this->currentUser['id'],
            'approved_at'  => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        if ($umkm['user_id']) {
            $notifModel->insert([
                'user_id'         => $umkm['user_id'],
                'type'            => 'umkm_rejected',
                'title'           => 'UMKM Anda Ditolak',
                'message'         => 'Toko "' . $umkm['nama_toko'] . '" ditolak. Alasan: ' . $alasan,
                'related_umkm_id' => $id,
                'is_read'         => 0,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            try {
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($umkm['user_id']);
                if ($user) {
                    $emailService = new \App\Libraries\EmailService();
                    $emailService->sendNotification(
                        $user['email'],
                        $user['username'],
                        'UMKM Anda Ditolak',
                        'Toko "' . $umkm['nama_toko'] . '" ditolak. Alasan: ' . $alasan,
                        'umkm_rejected',
                        base_url('/user/umkm/' . $id),
                        $umkm['nama_toko'],
                        'UMKM'
                    );
                }
            } catch (\Exception $e) {
                log_message('error', 'Gagal kirim email reject UMKM: ' . $e->getMessage());
            }
        }

        return redirect()->to('/staff/umkm')->with('success', 'UMKM berhasil ditolak.');
    }

    public function deleteProduk($produkId)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();

        $produk = $produkModel->find($produkId);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $umkmId = $produk['umkm_id'];

        $gambarList = $gambarModel->where('produk_id', $produkId)->findAll();
        foreach ($gambarList as $g) {
            $filePath = FCPATH . ltrim($g['gambar_path'], '/');
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
        $gambarModel->where('produk_id', $produkId)->delete();
        $produkModel->delete($produkId);

        return redirect()->to('/staff/umkm/' . $umkmId . '/edit')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Form edit produk yang sudah ada (staff)
     */
    public function editProduk($produkId)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();

        $produk = $produkModel->find($produkId);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $produk['gambar'] = $gambarModel->where('produk_id', $produkId)->findAll();

        return view('Staff/umkm/edit_produk', [
            'title'  => 'Edit Produk',
            'produk' => $produk,
        ]);
    }

    /**
     * Proses update produk yang sudah ada (staff)
     */
    public function updateProduk($produkId)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $produkModel = new UmkmProdukModel();
        $gambarModel = new UmkmProdukGambarModel();

        $produk = $produkModel->find($produkId);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $umkmId = $produk['umkm_id'];

        $hargaRaw   = $this->request->getPost('harga');
        $hargaClean = !empty($hargaRaw) ? (float) str_replace([',', '.'], '', $hargaRaw) : null;

        $produkModel->update($produkId, [
            'nama_produk' => trim($this->request->getPost('nama_produk')),
            'harga'       => $hargaClean,
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        // Upload gambar baru — validasi via validate_image_upload() + uploadToWebp()
        helper('upload');
        $gambarFiles = $this->request->getFileMultiple('gambar_baru');
        if ($gambarFiles) {
            foreach ($gambarFiles as $file) {
                if (!$file->isValid() || $file->hasMoved()) {
                    continue;
                }
                $imgError = validate_image_upload($file);
                if ($imgError !== null) {
                    continue; // skip file tidak valid
                }
                $path = $this->uploadToWebp($file, 'uploads/umkm');
                if ($path !== null) {
                    $gambarModel->insert([
                        'produk_id'   => $produkId,
                        'gambar_path' => $path,
                        'created_at'  => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        return redirect()->to('/staff/umkm/' . $umkmId . '/edit')->with('success', 'Produk berhasil diperbarui.');
    }

    public function deleteGambarProduk($gambarId)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $gambarModel = new UmkmProdukGambarModel();
        $produkModel = new UmkmProdukModel();

        $gambar = $gambarModel->find($gambarId);
        if (!$gambar) {
            return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
        }

        $produk = $produkModel->find($gambar['produk_id']);
        $umkmId = $produk ? $produk['umkm_id'] : 0;

        $filePath = FCPATH . ltrim($gambar['gambar_path'], '/');
        if (is_file($filePath)) {
            @unlink($filePath);
        }
        $gambarModel->delete($gambarId);

        return redirect()->to('/staff/umkm/' . $umkmId . '/edit')->with('success', 'Gambar produk berhasil dihapus.');
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function loadUmkmData(int $id): ?array
    {
        $umkmModel    = new UmkmModel();
        $ecomModel    = new UmkmEcommerceModel();
        $produkModel  = new UmkmProdukModel();
        $gambarModel  = new UmkmProdukGambarModel();
        $profileModel = new UserProfileModel();

        $umkm = $umkmModel->find($id);
        if (!$umkm) {
            return null;
        }

        if ($umkm['user_id']) {
            $profile = $profileModel->find($umkm['user_id']);
            $umkm['pemilik'] = $profile['nama_lengkap'] ?? 'User';
        } else {
            $umkm['pemilik'] = 'Staff';
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

        $raw = strip_tags(trim($raw));

        // Jika user paste kode iframe, ekstrak src-nya
        if (preg_match('/src=["\']([^"\']+)["\']/', $raw, $m)) {
            $raw = $m[1];
        }

        // Jika sudah berupa embed URL, kembalikan langsung
        if (str_contains($raw, '/maps/embed') || str_contains($raw, 'output=embed')) {
            return $raw;
        }

        // Resolve URL pendek (maps.app.goo.gl atau goo.gl/maps)
        if (preg_match('/maps\.app\.goo\.gl|goo\.gl\/maps/i', $raw)) {
            $ch = curl_init($raw);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 5,
                CURLOPT_TIMEOUT        => 6,
                CURLOPT_NOBODY         => true,
                CURLOPT_USERAGENT      => 'Mozilla/5.0',
            ]);
            curl_exec($ch);
            $resolved = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);
            if ($resolved) {
                $raw = $resolved;
            }
        }

        // Ekstrak koordinat dari URL Google Maps
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $raw, $coords)) {
            $lat = $coords[1];
            $lng = $coords[2];
            return "https://maps.google.com/maps?q={$lat},{$lng}&z=15&output=embed";
        }

        // Coba ekstrak q= dari URL search Google Maps
        if (preg_match('/\/maps\/search\/([^@?&\/]+)/i', $raw, $sq)) {
            $q = urlencode(urldecode($sq[1]));
            return "https://maps.google.com/maps?q={$q}&output=embed";
        }

        // Coba ekstrak place name dari URL Google Maps
        if (preg_match('/\/maps\/place\/([^@?&\/]+)/i', $raw, $pl)) {
            $q = urlencode(urldecode($pl[1]));
            return "https://maps.google.com/maps?q={$q}&output=embed";
        }

        // Fallback: kembalikan URL apa adanya
        return $raw;
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
        helper('upload');
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

            // Upload gambar produk — validasi via validate_image_upload() + uploadToWebp()
            $gambarFiles = $this->request->getFileMultiple("produk_gambar_{$i}");
            if ($gambarFiles) {
                foreach ($gambarFiles as $file) {
                    if (!$file->isValid() || $file->hasMoved()) {
                        continue;
                    }
                    $imgError = validate_image_upload($file);
                    if ($imgError !== null) {
                        continue; // skip file tidak valid
                    }
                    $path = $this->uploadToWebp($file, 'uploads/umkm');
                    if ($path !== null) {
                        $gambarModel->insert([
                            'produk_id'   => $produkId,
                            'gambar_path' => $path,
                            'created_at'  => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }

        return null;
    }

}
