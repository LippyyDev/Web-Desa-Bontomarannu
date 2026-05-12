<?php

/**
 * upload_helper.php
 *
 * Reusable helpers untuk validasi file upload.
 * Load di controller dengan: helper('upload');
 *
 * Fungsi tersedia:
 *   - validate_image_upload()        → hanya JPG/JPEG/PNG (untuk foto profil, galeri, dll)
 *   - validate_letter_attachment()   → PDF, Word, JPG/JPEG/PNG (untuk lampiran surat)
 *
 * @see CLAUDE.md §17 — File Upload Validation Pattern
 */

if (!function_exists('validate_image_upload')) {
    /**
     * Validasi file gambar yang diupload (JPG/JPEG/PNG, maks 1MB).
     *
     * Melakukan 4 layer validasi:
     *   1. Extension whitelist  (jpg, jpeg, png)
     *   2. MIME type check      (image/jpeg, image/png)
     *   3. File size            (≤ 1MB)
     *   4. Magic bytes          (FF D8 FF untuk JPEG, 89 50 4E 47 untuk PNG)
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file      File yang diupload
     * @param int                                  $maxBytes  Batas ukuran dalam bytes (default 1MB)
     *
     * @return string|null  null jika valid, string pesan error jika tidak valid
     */
    function validate_image_upload(\CodeIgniter\HTTP\Files\UploadedFile $file, int $maxBytes = 1048576): ?string
    {
        $allowedExts  = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/png'];

        // Layer 1: Extension whitelist
        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, $allowedExts, true)) {
            return 'Format tidak didukung. Hanya JPG/JPEG/PNG.';
        }

        // Layer 2: MIME type
        $mime = $file->getMimeType();
        if (!in_array($mime, $allowedMimes, true)) {
            return 'Tipe file tidak valid.';
        }

        // Layer 3: File size
        if ($file->getSize() > $maxBytes) {
            $limitMb = round($maxBytes / 1048576, 1);
            return "Ukuran file melebihi batas {$limitMb}MB.";
        }

        // Layer 4: Magic bytes — cegah file berbahaya yang rename extension-nya
        $tempPath = $file->getTempName();
        if ($tempPath && is_file($tempPath)) {
            $handle = fopen($tempPath, 'rb');
            if ($handle) {
                $header = fread($handle, 4);
                fclose($handle);

                $isJpeg = (substr($header, 0, 3) === "\xFF\xD8\xFF");
                $isPng  = (substr($header, 0, 4) === "\x89PNG");
                if (!$isJpeg && !$isPng) {
                    return 'Header file tidak valid (bukan JPEG/PNG asli).';
                }
            }
        }

        return null;
    }
}

if (!function_exists('validate_letter_attachment')) {
    /**
     * Validasi file lampiran surat (PDF, Word, JPG/JPEG/PNG, maks 1MB per file).
     *
     * Melakukan 3 layer validasi:
     *   1. Extension whitelist  (pdf, doc, docx, jpg, jpeg, png)
     *   2. MIME type check      (sesuai ekstensi yang diizinkan)
     *   3. File size            (≤ 1MB per file)
     *
     * Catatan: magic bytes tidak dilakukan untuk PDF/Word karena formatnya kompleks;
     *          MIME type server-side sudah cukup untuk dokumen.
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file     File yang diupload
     * @param int                                 $maxBytes Batas ukuran dalam bytes (default 1MB)
     *
     * @return string|null  null jika valid, string pesan error jika tidak valid
     */
    function validate_letter_attachment(\CodeIgniter\HTTP\Files\UploadedFile $file, int $maxBytes = 1048576): ?string
    {
        $allowedExts = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $allowedMimes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
        ];

        // Layer 1: Extension whitelist
        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, $allowedExts, true)) {
            return 'Format tidak didukung. Hanya PDF, Word (.doc/.docx), JPG, JPEG, atau PNG.';
        }

        // Layer 2: MIME type check (server-side)
        $mime = $file->getMimeType();
        $expectedMime = $allowedMimes[$ext] ?? null;
        if ($expectedMime && $mime !== $expectedMime) {
            return 'Tipe file tidak sesuai dengan ekstensi. Pastikan file tidak diubah.';
        }

        // Layer 3: File size
        if ($file->getSize() > $maxBytes) {
            $limitMb = round($maxBytes / 1048576, 1);
            return "Ukuran file melebihi batas {$limitMb}MB.";
        }

        return null;
    }
}

if (!function_exists('validate_maps_url')) {
    /**
     * Validasi URL Google Maps yang diinput pengguna.
     *
     * Field ini bersifat OPSIONAL. Jika kosong, return null (valid).
     * Jika diisi, harus mengandung salah satu pola domain Google Maps.
     *
     * Pola yang diterima:
     *   - maps.app.goo.gl/...     (link share pendek)
     *   - goo.gl/maps/...         (link share lama)
     *   - google.com/maps/...     (URL lengkap)
     *   - maps.google.com/...     (URL embed/lama)
     *
     * @param string|null $url Input dari form
     * @return string|null  null jika valid (atau kosong), string pesan error jika tidak valid
     */
    function validate_maps_url(?string $url): ?string
    {
        if (!$url || trim($url) === '') {
            return null; // opsional — kosong = valid
        }

        $url = trim($url);

        $validPatterns = [
            'maps.app.goo.gl',
            'goo.gl/maps',
            'google.com/maps',
            'maps.google.com',
        ];

        foreach ($validPatterns as $pattern) {
            if (stripos($url, $pattern) !== false) {
                return null; // valid
            }
        }

        return 'Link Google Maps tidak valid. Gunakan link dari Google Maps (maps.app.goo.gl, google.com/maps, dsb.).';
    }
}
