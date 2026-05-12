<?php

namespace App\Controllers\Staff;

use App\Controllers\ProtectedController;
use App\Models\PariwisataModel;
use App\Models\PariwisataGambarModel;

class PariwisataController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/pariwisata/index', [
            'title' => 'Kelola Pariwisata',
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

        $page   = (int)($this->request->getPost('page') ?: 1);
        $limit  = 12;
        $search = trim($this->request->getPost('search') ?? '');
        $offset = ($page - 1) * $limit;

        $pariwisataModel = new PariwisataModel();

        if ($search !== '') {
            $pariwisataModel->groupStart()
                ->like('nama_tempat', $search)
                ->orLike('alamat', $search)
                ->orLike('deskripsi', $search)
                ->groupEnd();
        }

        $total = $pariwisataModel->countAllResults(false);

        $list = $pariwisataModel->orderBy('created_at', 'DESC')
            ->findAll($limit, $offset);

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id'          => $item['id'],
                'nama_tempat' => $item['nama_tempat'],
                'alamat'      => $item['alamat'] ?? '',
                'deskripsi'   => strip_tags($item['deskripsi'] ?? ''),
                'thumbnail'   => $item['thumbnail'] ? base_url($item['thumbnail']) : null,
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

    public function create()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        return view('Staff/pariwisata/create', [
            'title' => 'Tambah Pariwisata',
        ]);
    }

    public function store()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pariwisataModel = new PariwisataModel();

        $mapsUrl = $this->request->getPost('maps_embed_url');
        if ($mapsUrl && preg_match('/src=["\']([^"\']+)["\']/', $mapsUrl, $matches)) {
            $mapsUrl = $matches[1];
        }
        $mapsUrl = strip_tags(trim((string) $mapsUrl));

        $data = [
            'nama_tempat'    => $this->request->getPost('nama_tempat'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'alamat'         => $this->request->getPost('alamat'),
            'maps_embed_url' => $mapsUrl,
            'created_by'     => $this->currentUser['id'],
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        // Handle thumbnail
        $thumb = $this->request->getFile('thumbnail');
        if ($thumb && $thumb->isValid()) {
            $result = $this->processImageUpload($thumb, 'uploads/pariwisata');
            if ($result['success']) {
                $data['thumbnail'] = $result['path'];
            } else {
                return redirect()->back()->withInput()->with('error', $result['message']);
            }
        }

        $pariwisataId = $pariwisataModel->insert($data, true);

        // Handle multiple gambar
        $this->saveGambar($pariwisataId);

        return redirect()->to('/staff/pariwisata')->with('success', 'Data pariwisata berhasil ditambahkan.');
    }

    public function show($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pariwisataModel = new PariwisataModel();
        $gambarModel     = new PariwisataGambarModel();

        $item = $pariwisataModel->find($id);
        if (!$item) {
            return redirect()->to('/staff/pariwisata')->with('error', 'Data tidak ditemukan.');
        }

        $gambar = $gambarModel->where('pariwisata_id', $id)->findAll();

        return view('Staff/pariwisata/show', [
            'title'  => 'Detail Pariwisata',
            'item'   => $item,
            'gambar' => $gambar,
        ]);
    }

    public function edit($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pariwisataModel = new PariwisataModel();
        $gambarModel     = new PariwisataGambarModel();

        $item = $pariwisataModel->find($id);
        if (!$item) {
            return redirect()->to('/staff/pariwisata')->with('error', 'Data tidak ditemukan.');
        }

        $gambar = $gambarModel->where('pariwisata_id', $id)->findAll();

        return view('Staff/pariwisata/edit', [
            'title'  => 'Edit Pariwisata',
            'item'   => $item,
            'gambar' => $gambar,
        ]);
    }

    public function update($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pariwisataModel = new PariwisataModel();
        $item = $pariwisataModel->find($id);
        if (!$item) {
            return redirect()->to('/staff/pariwisata')->with('error', 'Data tidak ditemukan.');
        }

        $mapsUrl = $this->request->getPost('maps_embed_url');
        if ($mapsUrl && preg_match('/src=["\']([^"\']+)["\']/', $mapsUrl, $matches)) {
            $mapsUrl = $matches[1];
        }
        $mapsUrl = strip_tags(trim((string) $mapsUrl));

        $data = [
            'nama_tempat'    => $this->request->getPost('nama_tempat'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'alamat'         => $this->request->getPost('alamat'),
            'maps_embed_url' => $mapsUrl,
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        // Handle thumbnail
        $thumb = $this->request->getFile('thumbnail');
        if ($thumb && $thumb->isValid()) {
            // Hapus thumbnail lama
            if (!empty($item['thumbnail'])) {
                $oldPath = FCPATH . ltrim($item['thumbnail'], '/');
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $result = $this->processImageUpload($thumb, 'uploads/pariwisata');
            if ($result['success']) {
                $data['thumbnail'] = $result['path'];
            } else {
                return redirect()->back()->withInput()->with('error', $result['message']);
            }
        }

        $pariwisataModel->update($id, $data);

        // Handle multiple gambar baru
        $this->saveGambar($id);

        return redirect()->to('/staff/pariwisata/' . $id . '/edit')->with('success', 'Data pariwisata berhasil diperbarui.');
    }

    public function delete($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pariwisataModel = new PariwisataModel();
        $gambarModel     = new PariwisataGambarModel();

        $item = $pariwisataModel->find($id);
        if (!$item) {
            return redirect()->to('/staff/pariwisata')->with('error', 'Data tidak ditemukan.');
        }

        // Hapus semua gambar
        $gambarList = $gambarModel->where('pariwisata_id', $id)->findAll();
        foreach ($gambarList as $g) {
            $filePath = FCPATH . ltrim($g['gambar_path'], '/');
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
        $gambarModel->where('pariwisata_id', $id)->delete();

        // Hapus thumbnail
        if (!empty($item['thumbnail'])) {
            $thumbPath = FCPATH . ltrim($item['thumbnail'], '/');
            if (is_file($thumbPath)) {
                @unlink($thumbPath);
            }
        }

        $pariwisataModel->delete($id);

        return redirect()->to('/staff/pariwisata')->with('success', 'Data pariwisata berhasil dihapus.');
    }

    public function deleteGambar($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $gambarModel = new PariwisataGambarModel();
        $gambar = $gambarModel->find($id);
        if ($gambar) {
            $filePath = FCPATH . ltrim($gambar['gambar_path'], '/');
            if (is_file($filePath)) {
                @unlink($filePath);
            }
            $pariwisataId = $gambar['pariwisata_id'];
            $gambarModel->delete($id);
            return redirect()->to('/staff/pariwisata/' . $pariwisataId . '/edit')->with('success', 'Gambar berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
    }

    // ─── Private Helpers ────────────────────────────────────────────────────

    /**
     * Proses upload gambar: validasi & konversi ke WebP.
     */
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
            return ['success' => false, 'message' => 'Gagal memproses gambar. Pastikan file adalah gambar yang valid.'];
        }
    }

    /**
     * Simpan gambar-gambar pariwisata dari request.
     */
    private function saveGambar(int $pariwisataId): void
    {
        $files = $this->request->getFileMultiple('gambar');
        if (!$files) {
            return;
        }

        $gambarModel = new PariwisataGambarModel();

        foreach ($files as $file) {
            if (!$file->isValid()) {
                continue;
            }

            $result = $this->processImageUpload($file, 'uploads/pariwisata');
            if ($result['success']) {
                $gambarModel->insert([
                    'pariwisata_id' => $pariwisataId,
                    'gambar_path'   => $result['path'],
                    'created_at'    => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
