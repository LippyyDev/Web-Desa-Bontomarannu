<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Guest\LandingController::index');
$routes->get('/profil', 'Guest\LandingController::profil');
$routes->get('/galeri', 'Guest\LandingController::galeri');
$routes->post('/galeri/api', 'Guest\LandingController::galeriApi');
$routes->get('/galeri/(:num)', 'Guest\LandingController::galeriDetail/$1');
$routes->post('/galeri/(:num)/media-api', 'Guest\LandingController::galeriDetailMediaApi/$1');
$routes->get('/berita', 'Guest\LandingController::berita');
$routes->post('/berita/api', 'Guest\LandingController::beritaApi');
$routes->get('/berita/(:num)', 'Guest\LandingController::detailBerita/$1');
$routes->post('/berita/(:num)/media-api', 'Guest\LandingController::detailBeritaMediaApi/$1');
$routes->get('/geografis', 'Guest\LandingController::geografis');
$routes->get('/perangkat-desa', 'Guest\LandingController::perangkatDesa');
$routes->post('/perangkat-desa/api', 'Guest\LandingController::perangkatDesaApi');
$routes->get('/inventaris', 'Guest\LandingController::inventaris');
$routes->post('/inventaris/api', 'Guest\LandingController::inventarisApi');
$routes->get('/pengumuman', 'Guest\LandingController::pengumuman');
$routes->post('/pengumuman/api', 'Guest\LandingController::pengumumanApi');
$routes->get('/pengumuman/(:num)', 'Guest\LandingController::detailPengumuman/$1');
$routes->get('/pengaduan', 'Guest\LandingController::pengaduan');
$routes->post('/pengaduan', 'Guest\LandingController::submitPengaduan');
$routes->get('/pengaduan/captcha', 'Guest\LandingController::captcha');
$routes->post('/pengaduan/captcha/verify', 'Guest\LandingController::verifyCaptcha');
$routes->get('/umkm', 'Guest\LandingController::umkm');
$routes->post('/umkm/api', 'Guest\LandingController::umkmApi');
$routes->get('/umkm/(:num)', 'Guest\LandingController::umkmDetail/$1');
$routes->post('/umkm/(:num)/produk-api', 'Guest\LandingController::umkmProdukApi/$1');
$routes->get('/umkm/produk/(:num)', 'Guest\LandingController::umkmProdukDetail/$1');
$routes->get('/pariwisata', 'Guest\LandingController::pariwisata');
$routes->post('/pariwisata/api', 'Guest\LandingController::pariwisataApi');
$routes->get('/pariwisata/(:num)', 'Guest\LandingController::pariwisataDetail/$1');
$routes->post('/pariwisata/(:num)/gambar-api', 'Guest\LandingController::pariwisataGambarApi/$1');

$routes->get('/login', 'Guest\AuthController::login');
$routes->post('/login', 'Guest\AuthController::doLogin');
$routes->get('/register', 'Guest\AuthController::register');
$routes->post('/register', 'Guest\AuthController::doRegister');
$routes->get('/verify', 'Guest\AuthController::verify');
$routes->post('/verify', 'Guest\AuthController::doVerify');
$routes->get('/verify/(:segment)', 'Guest\AuthController::verifyByLink/$1');

// Email queue processing endpoint — dipanggil via cron job atau scheduler eksternal
// POST /api/email-queue/process  (dengan header X-Cron-Secret atau ?secret=xxx)
$routes->post('/api/email-queue/process', 'Api\EmailQueueController::process');
$routes->get('/api/email-queue/process',  'Api\EmailQueueController::process');

$routes->get('/forgot-password', 'Guest\AuthController::forgotPassword');
$routes->post('/forgot-password', 'Guest\AuthController::sendReset');
$routes->get('/reset-password/(:any)', 'Guest\AuthController::resetByLink/$1');
$routes->get('/verify-reset', 'Guest\AuthController::verifyReset');
$routes->post('/verify-reset', 'Guest\AuthController::doVerifyReset');
$routes->post('/resend-otp', 'Guest\AuthController::resendOtp');
$routes->get('/new-password', 'Guest\AuthController::newPassword');
$routes->post('/new-password', 'Guest\AuthController::doNewPassword');
$routes->get('/logout', 'Guest\AuthController::logout');

$routes->get('/dashboard', 'Home::dashboard');

$routes->group('user', static function ($routes) {
    $routes->get('dashboard', 'User\DashboardController::index');
    $routes->get('profil', 'User\ProfileController::index');
    $routes->post('profil', 'User\ProfileController::update');
    $routes->post('profil/ubah-password', 'User\ProfileController::changePassword');

    $routes->get('surat', 'User\LetterController::index');
    $routes->post('surat/api', 'User\LetterController::api');
    $routes->get('surat/buat', 'User\LetterController::create');
    $routes->post('surat', 'User\LetterController::store');
    $routes->get('surat/(:num)', 'User\LetterController::show/$1');
    $routes->get('surat/(:num)/edit', 'User\LetterController::edit/$1');
    $routes->post('surat/(:num)', 'User\LetterController::update/$1');
    $routes->get('surat/(:num)/hapus', 'User\LetterController::delete/$1');
    $routes->get('surat/lampiran/(:num)/hapus', 'User\LetterController::deleteAttachment/$1');

    $routes->get('pengaduan', 'User\PengaduanController::index');
    $routes->get('pengaduan/captcha', 'User\PengaduanController::captcha');
    $routes->post('pengaduan/captcha/verify', 'User\PengaduanController::verifyCaptcha');
    $routes->post('pengaduan', 'User\PengaduanController::store');

    $routes->get('umkm', 'User\UmkmController::index');
    $routes->post('umkm/api', 'User\UmkmController::api');
    $routes->get('umkm/tambah', 'User\UmkmController::create');
    $routes->post('umkm', 'User\UmkmController::store');
    $routes->get('umkm/(:num)', 'User\UmkmController::show/$1');
    $routes->get('umkm/(:num)/edit', 'User\UmkmController::edit/$1');
    $routes->post('umkm/(:num)', 'User\UmkmController::update/$1');
    $routes->get('umkm/(:num)/hapus', 'User\UmkmController::delete/$1');
    $routes->post('umkm/(:num)/produk-api', 'User\UmkmController::produkApi/$1');
    $routes->get('umkm/produk/(:num)', 'User\UmkmController::showProduk/$1');
    $routes->get('umkm/produk/(:num)/edit', 'User\UmkmController::editProduk/$1');
    $routes->post('umkm/produk/(:num)', 'User\UmkmController::updateProduk/$1');
    $routes->get('umkm/produk/(:num)/hapus', 'User\UmkmController::deleteProduk/$1');
    $routes->get('umkm/gambar-produk/(:num)/hapus', 'User\UmkmController::deleteGambarProduk/$1');

    $routes->get('notifikasi', 'User\NotificationController::index');
    $routes->post('notifikasi/data', 'User\NotificationController::data');
    $routes->post('notifikasi/read-all', 'User\NotificationController::markAllRead');
    $routes->post('notifikasi/delete-all', 'User\NotificationController::deleteAll');
    $routes->post('notifikasi/(:num)/read', 'User\NotificationController::markRead/$1');
});

$routes->group('staff', static function ($routes) {
    $routes->get('dashboard', 'Staff\DashboardController::index');
    $routes->get('profil', 'Staff\ProfileController::index');
    $routes->post('profil', 'Staff\ProfileController::update');
    $routes->post('profil/ubah-password', 'Staff\ProfileController::changePassword');

    $routes->get('surat', 'Staff\LetterController::index');
    $routes->post('surat/api', 'Staff\LetterController::api');
    $routes->get('surat/(:num)', 'Staff\LetterController::show/$1');
    $routes->get('surat/(:num)/hapus', 'Staff\LetterController::delete/$1');
    $routes->post('surat/(:num)/balas', 'Staff\LetterController::reply/$1');
    $routes->post('surat/(:num)/terima', 'Staff\LetterController::accept/$1');
    $routes->post('surat/(:num)/tolak', 'Staff\LetterController::reject/$1');
    $routes->get('surat/(:num)/balasan/(:num)/hapus', 'Staff\LetterController::deleteReply/$1/$2');
    $routes->get('surat/(:num)/word', 'Staff\PdfWordController::generateWordFromLetter/$1');
    $routes->get('surat/template/(:segment)', 'Staff\PdfWordController::downloadTemplate/$1');

    $routes->get('desa', 'Staff\ContentController::desaProfile');
    $routes->post('desa', 'Staff\ContentController::updateDesaProfile');
    $routes->get('geografi', 'Staff\ContentController::geografis');
    $routes->post('geografi', 'Staff\ContentController::updateGeografis');
    
    $routes->get('inventaris', 'Staff\ContentController::inventaris');
    $routes->post('inventaris/api', 'Staff\ContentController::inventarisApi');
    $routes->get('inventaris/tambah', 'Staff\ContentController::createInventaris');
    $routes->get('inventaris/(:num)/edit', 'Staff\ContentController::editInventaris/$1');
    $routes->post('inventaris', 'Staff\ContentController::storeInventaris');
    $routes->post('inventaris/(:num)', 'Staff\ContentController::updateInventaris/$1');
    $routes->get('inventaris/(:num)/hapus', 'Staff\ContentController::deleteInventaris/$1');

    $routes->get('pengumuman', 'Staff\ContentController::pengumuman');
    $routes->get('pengumuman/tambah', 'Staff\ContentController::createPengumuman');
    $routes->post('pengumuman/api', 'Staff\ContentController::pengumumanApi');
    $routes->post('pengumuman', 'Staff\ContentController::storePengumuman');
    $routes->get('pengumuman/(:num)/edit', 'Staff\ContentController::editPengumuman/$1');
    $routes->put('pengumuman/(:num)', 'Staff\ContentController::updatePengumuman/$1');
    $routes->post('pengumuman/(:num)', 'Staff\ContentController::updatePengumuman/$1');
    $routes->delete('pengumuman/(:num)', 'Staff\ContentController::deletePengumuman/$1');
    $routes->get('pengumuman/(:num)/hapus', 'Staff\ContentController::deletePengumuman/$1');

    $routes->get('pengaduan', 'Staff\ContentController::pengaduan');
    $routes->post('pengaduan/api', 'Staff\ContentController::pengaduanApi');
    $routes->get('pengaduan/(:num)', 'Staff\ContentController::detailPengaduan/$1');
    $routes->get('pengaduan/(:num)/hapus', 'Staff\ContentController::deletePengaduan/$1');

    // Perangkat Desa Routes
    $routes->get('galeri', 'Staff\ContentController::gallery');
    $routes->post('galeri/api', 'Staff\ContentController::galleryApi');
    $routes->get('galeri/tambah', 'Staff\ContentController::createGallery');
    $routes->get('galeri/(:num)/edit', 'Staff\ContentController::editGallery/$1');
    $routes->post('galeri', 'Staff\ContentController::storeGallery');
    $routes->post('galeri/(:num)', 'Staff\ContentController::updateGallery/$1');
    $routes->get('galeri/(:num)/hapus', 'Staff\ContentController::deleteGallery/$1');
    $routes->get('galeri/media/(:num)/hapus', 'Staff\ContentController::deleteGalleryMedia/$1');

    $routes->get('berita', 'Staff\ContentController::news');
    $routes->post('berita/api', 'Staff\ContentController::newsApi');
    $routes->get('berita/tambah', 'Staff\ContentController::createNews');
    $routes->get('berita/(:num)/edit', 'Staff\ContentController::editNews/$1');
    $routes->post('berita', 'Staff\ContentController::storeNews');
    $routes->post('berita/(:num)', 'Staff\ContentController::updateNews/$1');
    $routes->get('berita/(:num)/hapus', 'Staff\ContentController::deleteNews/$1');
    $routes->get('berita/media/(:num)/hapus', 'Staff\ContentController::deleteNewsMedia/$1');



    $routes->get('perangkat-desa', 'Staff\ContentController::perangkatDesa');
    $routes->post('perangkat-desa/api', 'Staff\ContentController::perangkatDesaApi');
    $routes->get('perangkat-desa/tambah', 'Staff\ContentController::createPerangkatDesa');
    $routes->get('perangkat-desa/(:num)/edit', 'Staff\ContentController::editPerangkatDesa/$1');
    $routes->post('perangkat-desa', 'Staff\ContentController::storePerangkatDesa');
    $routes->post('perangkat-desa/(:num)', 'Staff\ContentController::updatePerangkatDesa/$1');
    $routes->get('perangkat-desa/(:num)/hapus', 'Staff\ContentController::deletePerangkatDesa/$1');

    $routes->get('umkm', 'Staff\UmkmController::index');
    $routes->post('umkm/api', 'Staff\UmkmController::api');
    $routes->get('umkm/tambah', 'Staff\UmkmController::create');
    $routes->post('umkm', 'Staff\UmkmController::store');
    $routes->get('umkm/(:num)', 'Staff\UmkmController::show/$1');
    $routes->get('umkm/(:num)/edit', 'Staff\UmkmController::edit/$1');
    $routes->post('umkm/(:num)', 'Staff\UmkmController::update/$1');
    $routes->get('umkm/(:num)/hapus', 'Staff\UmkmController::delete/$1');
    $routes->post('umkm/(:num)/approve', 'Staff\UmkmController::approve/$1');
    $routes->post('umkm/(:num)/reject', 'Staff\UmkmController::reject/$1');
    $routes->post('umkm/(:num)/produk-api', 'Staff\UmkmController::produkApi/$1');
    $routes->get('umkm/produk/(:num)', 'Staff\UmkmController::showProduk/$1');
    $routes->get('umkm/produk/(:num)/edit', 'Staff\UmkmController::editProduk/$1');
    $routes->post('umkm/produk/(:num)', 'Staff\UmkmController::updateProduk/$1');
    $routes->get('umkm/produk/(:num)/hapus', 'Staff\UmkmController::deleteProduk/$1');
    $routes->get('umkm/gambar-produk/(:num)/hapus', 'Staff\UmkmController::deleteGambarProduk/$1');

    $routes->get('pariwisata', 'Staff\PariwisataController::index');
    $routes->post('pariwisata/api', 'Staff\PariwisataController::api');
    $routes->get('pariwisata/tambah', 'Staff\PariwisataController::create');
    $routes->post('pariwisata', 'Staff\PariwisataController::store');
    $routes->get('pariwisata/(:num)', 'Staff\PariwisataController::show/$1');
    $routes->get('pariwisata/(:num)/edit', 'Staff\PariwisataController::edit/$1');
    $routes->post('pariwisata/(:num)', 'Staff\PariwisataController::update/$1');
    $routes->get('pariwisata/(:num)/hapus', 'Staff\PariwisataController::delete/$1');
    $routes->get('pariwisata/gambar/(:num)/hapus', 'Staff\PariwisataController::deleteGambar/$1');

    $routes->get('notifikasi', 'Staff\NotificationController::index');
    $routes->post('notifikasi/data', 'Staff\NotificationController::data');
    $routes->post('notifikasi/read-all', 'Staff\NotificationController::markAllRead');
    $routes->post('notifikasi/delete-all', 'Staff\NotificationController::deleteAll');
    $routes->post('notifikasi/(:num)/read', 'Staff\NotificationController::markRead/$1');
});

$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('akun', 'Admin\AccountController::index');
    $routes->post('akun/api', 'Admin\AccountController::api');
    $routes->get('akun/tambah', 'Admin\AccountController::create');
    $routes->get('akun/(:num)/edit', 'Admin\AccountController::edit/$1');
    $routes->post('akun', 'Admin\AccountController::store');
    $routes->post('akun/(:num)', 'Admin\AccountController::update/$1');
    $routes->post('akun/(:num)/ubah-password', 'Admin\AccountController::changePassword/$1');
    $routes->get('akun/(:num)/hapus', 'Admin\AccountController::delete/$1');
    $routes->get('akun/(:num)/toggle-status', 'Admin\AccountController::toggleStatus/$1');
    $routes->get('profil', 'Admin\ProfileController::index');
    $routes->post('profil', 'Admin\ProfileController::update');
    $routes->post('profil/ubah-password', 'Admin\ProfileController::changePassword');
    
    $routes->get('notifikasi', 'Admin\NotificationController::index');
    $routes->post('notifikasi/data', 'Admin\NotificationController::data');
    $routes->post('notifikasi/read-all', 'Admin\NotificationController::markAllRead');
    $routes->post('notifikasi/delete-all', 'Admin\NotificationController::deleteAll');
    $routes->post('notifikasi/(:num)/read', 'Admin\NotificationController::markRead/$1');
});

