<?php
$currentUser = session()->get('user');
$isLoggedIn = !empty($currentUser);

// Jika user sudah login, ambil data profil
$userName = 'User';
$userPhoto = base_url('assets/img/guest.webp');
$userRole = '';

if ($isLoggedIn) {
    $userProfileModel = new \App\Models\UserProfileModel();
    $userId = $currentUser['id'] ?? null;
    $profile = $userId ? $userProfileModel->find($userId) : null;
    
    $userName = ($profile && !empty($profile['nama_lengkap'])) 
        ? $profile['nama_lengkap'] 
        : ($currentUser['username'] ?? 'User');
    
    $userPhoto = ($profile && !empty($profile['foto_profil'])) 
        ? base_url($profile['foto_profil']) 
        : base_url('assets/img/guest.webp');
    
    $userRole = $currentUser['role'] ?? '';
}

// Tentukan dashboard URL berdasarkan role
$dashboardUrl = match($userRole) {
    'admin' => base_url('/admin/dashboard'),
    'staf' => base_url('/staff/dashboard'),
    'user' => base_url('/user/dashboard'),
    default => base_url('/dashboard')
};

// Deteksi halaman Home secara akurat menggunakan base_url
$currentUrl = rtrim(current_url(), '/');
$baseUrl = rtrim(base_url(), '/');
$isHome = ($currentUrl === $baseUrl || $currentUrl === $baseUrl . '/index.php' || uri_string() == '');
?>
<!-- Preconnect & Font Import untuk tampilan lebih modern -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

<nav class="navbar navbar-expand-lg guest-navbar <?= $isHome ? 'navbar-transparent' : 'navbar-solid' ?> fixed-top" id="guestNavbar">
    <div class="container">
        <!-- Logo dan Nama Desa -->
        <a class="navbar-brand navbar-brand-link d-flex align-items-center gap-2" href="<?= base_url('/') ?>">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Desa Bontomarannu" class="navbar-logo-icon">
            <span class="navbar-brand-name">Bonto Marannu</span>
        </a>

        <!-- Hamburger Toggle untuk Mobile -->
        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guestNavbarCollapse" aria-controls="guestNavbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>

        <!-- Menu Navigasi -->
        <div class="collapse navbar-collapse" id="guestNavbarCollapse">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 main-menu-nav">
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() == '' || uri_string() == '/' ? 'active' : '' ?>" href="<?= base_url('/') ?>">Beranda</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= (strpos(current_url(), '/profil') !== false || strpos(current_url(), '/geografis') !== false || strpos(current_url(), '/perangkat-desa') !== false) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profil
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item <?= (strpos(current_url(), '/profil') !== false) ? 'active' : '' ?>" href="<?= base_url('/profil') ?>">Profil Desa</a></li>
                        <li><a class="dropdown-item <?= (strpos(current_url(), '/geografis') !== false) ? 'active' : '' ?>" href="<?= base_url('/geografis') ?>">Geografi Desa</a></li>
                        <li><a class="dropdown-item <?= (strpos(current_url(), '/perangkat-desa') !== false) ? 'active' : '' ?>" href="<?= base_url('/perangkat-desa') ?>">Perangkat Desa</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= (strpos(current_url(), '/berita') !== false || strpos(current_url(), '/pengumuman') !== false || strpos(current_url(), '/inventaris') !== false) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Informasi
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item <?= (strpos(current_url(), '/berita') !== false) ? 'active' : '' ?>" href="<?= base_url('/berita') ?>">Berita</a></li>
                        <li><a class="dropdown-item <?= (strpos(current_url(), '/pengumuman') !== false) ? 'active' : '' ?>" href="<?= base_url('/pengumuman') ?>">Pengumuman</a></li>
                        <li><a class="dropdown-item <?= (strpos(current_url(), '/inventaris') !== false) ? 'active' : '' ?>" href="<?= base_url('/inventaris') ?>">Inventaris Desa</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(uri_string(), 'galeri') !== false ? 'active' : '' ?>" href="<?= base_url('/galeri') ?>">Galeri</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= (strpos(current_url(), '/umkm') !== false || strpos(current_url(), '/pariwisata') !== false) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Potensi
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item <?= (strpos(current_url(), '/umkm') !== false) ? 'active' : '' ?>" href="<?= base_url('/umkm') ?>"><i class="bi bi-shop me-2"></i>UMKM</a></li>
                        <li><a class="dropdown-item <?= (strpos(current_url(), '/pariwisata') !== false) ? 'active' : '' ?>" href="<?= base_url('/pariwisata') ?>"><i class="bi bi-compass me-2"></i>Pariwisata</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), '/pengaduan') !== false) ? 'active' : '' ?>" href="<?= base_url('/pengaduan') ?>">Laporan</a>
                </li>
            </ul>

            <!-- Tombol Masuk / Profil User -->
            <div class="navbar-actions d-flex align-items-center mt-3 mt-lg-0">
                <?php if (!$isLoggedIn): ?>
                    <a href="<?= base_url('/login') ?>" class="btn-masuk w-100 text-center">Masuk</a>
                <?php else: ?>
                    <div class="nav-item dropdown user-profile-dropdown w-100 text-center text-lg-start">
                        <a class="nav-link dropdown-toggle user-profile-btn d-inline-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= esc($userPhoto) ?>" alt="Profile" class="user-profile-img">
                            <span class="user-profile-name fw-medium"><?= esc($userName) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg-end shadow-sm border-0 mt-2">
                            <li>
                                <a class="dropdown-item py-2" href="<?= $dashboardUrl ?>">
                                    <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="<?= base_url('/logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

