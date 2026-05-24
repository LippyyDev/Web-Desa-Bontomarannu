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
            'title' => 'Kelola Pariwisata | Website Desa Bonto Marannu',
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
            'title' => 'Tambah Destinasi | Website Desa Bonto Marannu',
        ]);
    }

    public function store()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $pariwisataModel = new PariwisataModel();

        helper('upload');

        // ── Validasi Google Maps URL (opsional, jika diisi harus valid) ───────
        $mapsRaw   = $this->request->getPost('maps_embed_url');
        $mapsError = validate_maps_url($mapsRaw);
        if ($mapsError !== null) {
            return redirect()->back()->withInput()->with('error', $mapsError);
        }
        $mapsUrl = $this->parseMapsUrl($mapsRaw);

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
            'title'  => 'Detail Destinasi | Website Desa Bonto Marannu',
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
            'title'  => 'Edit Destinasi | Website Desa Bonto Marannu',
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

        helper('upload');

        // ── Validasi Google Maps URL (opsional) ───────────────────────────────
        $mapsRaw   = $this->request->getPost('maps_embed_url');
        $mapsError = validate_maps_url($mapsRaw);
        if ($mapsError !== null) {
            return redirect()->back()->withInput()->with('error', $mapsError);
        }
        $mapsUrl = $this->parseMapsUrl($mapsRaw);

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
     * Resolve dan konversi URL Google Maps ke format embed.
     * Mendukung: link share pendek (maps.app.goo.gl), URL panjang, dan kode iframe.
     */
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
