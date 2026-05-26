<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * SecurityHeadersFilter
 *
 * Menyuntikkan HTTP security headers ke setiap response untuk
 * melindungi aplikasi dari XSS, clickjacking, MIME sniffing, dll.
 *
 * Headers yang diterapkan:
 *   - X-Frame-Options
 *   - X-Content-Type-Options
 *   - Referrer-Policy
 *   - Content-Security-Policy
 *   - Permissions-Policy
 *   - Strict-Transport-Security (hanya jika koneksi HTTPS)
 */
class SecurityHeadersFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Tidak ada aksi sebelum request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ----------------------------------------------------------------
        // 1. X-Frame-Options
        //    Mencegah halaman dimuat di dalam <iframe> situs lain (clickjacking).
        // ----------------------------------------------------------------
        $response->setHeader('X-Frame-Options', 'SAMEORIGIN');

        // ----------------------------------------------------------------
        // 2. X-Content-Type-Options
        //    Mencegah browser "menebak" tipe MIME (MIME-sniffing attack).
        // ----------------------------------------------------------------
        $response->setHeader('X-Content-Type-Options', 'nosniff');

        // ----------------------------------------------------------------
        // 3. Referrer-Policy
        //    Mengontrol informasi referrer yang dikirim saat navigasi keluar.
        //    'strict-origin-when-cross-origin': kirim full URL untuk same-origin,
        //    hanya origin untuk cross-origin HTTPS, tidak ada untuk HTTP.
        // ----------------------------------------------------------------
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ----------------------------------------------------------------
        // 4. Content-Security-Policy (CSP)
        //    Membatasi sumber daya yang boleh dimuat browser.
        //    Disesuaikan dengan aset proyek ini:
        //      - Bootstrap CDN (css + js)
        //      - Bootstrap Icons CDN (font/css)
        //      - ApexCharts CDN (js)
        //      - SweetAlert2 CDN (js + css)
        //      - Google Fonts (font + style)
        //      - OpenStreetMap / Leaflet (frame)
        //      - Gambar dari aset lokal + data URI (WebP/foto profil)
        // ----------------------------------------------------------------
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.jsdelivr.net/npm/apexcharts https://cdn.jsdelivr.net/npm/sweetalert2@11 https://code.jquery.com",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com",
            "font-src 'self' data: https://cdn.jsdelivr.net https://fonts.gstatic.com",
            "img-src 'self' data: blob:",
            "connect-src 'self' https://cdn.jsdelivr.net https://api.open-meteo.com https://air-quality-api.open-meteo.com",
            "frame-src 'self' https://www.openstreetmap.org https://maps.google.com https://www.google.com https://www.youtube.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ]);
        $response->setHeader('Content-Security-Policy', $csp);

        // ----------------------------------------------------------------
        // 5. Permissions-Policy
        //    Menonaktifkan fitur browser yang tidak digunakan aplikasi ini
        //    untuk membatasi attack surface (geolocation, kamera, mikrofon, dll).
        // ----------------------------------------------------------------
        $permissions = implode(', ', [
            'accelerometer=()',
            'autoplay=()',
            'camera=()',
            'display-capture=()',
            'encrypted-media=()',
            'fullscreen=(self)',
            'geolocation=()',
            'gyroscope=()',
            'magnetometer=()',
            'microphone=()',
            'midi=()',
            'payment=()',
            'picture-in-picture=()',
            'publickey-credentials-get=()',
            'screen-wake-lock=()',
            'sync-xhr=()',
            'usb=()',
            'xr-spatial-tracking=()',
        ]);
        $response->setHeader('Permissions-Policy', $permissions);

        // ----------------------------------------------------------------
        // 6. Strict-Transport-Security (HSTS)
        //    Memaksa browser menggunakan HTTPS untuk 1 tahun ke depan.
        //    Hanya aktif jika request masuk via HTTPS (aman untuk dev/HTTP).
        // ----------------------------------------------------------------
        if ($request->isSecure()) {
            $response->setHeader(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
