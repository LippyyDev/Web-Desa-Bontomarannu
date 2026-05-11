<?php

namespace App\Controllers;

class ProtectedController extends BaseController
{
    /**
     * Pastikan user login dan memiliki role yang sesuai.
     */
    protected function guard(array $roles = [])
    {
        if (!$this->currentUser) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!empty($roles) && !in_array($this->currentUser['role'], $roles, true)) {
            return redirect()->to('/login')->with('error', 'Akses ditolak untuk role ini.');
        }

        return null;
    }

    /**
     * Membuat folder upload jika belum ada.
     */
    protected function ensureUploadPath(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }
    /**
     * Upload satu file gambar ke direktori tertentu dan konversi ke WebP.
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file       File yang sudah divalidasi
     * @param string                              $subPath    Relative path dari FCPATH, mis. 'uploads/pengumuman'
     * @param int                                 $quality    WebP quality (default 85)
     * @return string|null  Path relatif dari FCPATH jika berhasil, null jika gagal
     */
    protected function uploadToWebp(\CodeIgniter\HTTP\Files\UploadedFile $file, string $subPath, int $quality = 85): ?string
    {
        $path = FCPATH . $subPath;
        $this->ensureUploadPath($path);

        $tempName = $file->getRandomName();
        $file->move($path, $tempName);
        $tempPath = $path . '/' . $tempName;

        $image    = \Config\Services::image();
        $webpName = pathinfo($tempName, PATHINFO_FILENAME) . '.webp';
        $webpPath = $path . '/' . $webpName;

        try {
            $image->withFile($tempPath)->convert(IMAGETYPE_WEBP)->save($webpPath, $quality);
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return rtrim($subPath, '/') . '/' . $webpName;
        } catch (\Exception $e) {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return null;
        }
    }
}


