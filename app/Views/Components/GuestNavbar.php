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
?>
<nav class="guest-navbar">
    <div class="container">
        <div class="navbar-content">
            <!-- Logo dan Nama Desa -->
            <div class="navbar-brand-section">
                <a href="<?= base_url('/') ?>" class="navbar-logo-link">
                    <img src="<?= base_url('assets/img/logolandscape.webp') ?>" alt="Logo Desa Bontomarannu" class="navbar-logo-landscape">
                </a>
            </div>

            <!-- Menu Navigasi -->
            <div class="navbar-menu">
                <ul class="navbar-nav" id="navbarNav">
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == '' || uri_string() == '/' ? 'active' : '' ?>" href="<?= base_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Tentang
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?= (strpos(current_url(), '/profil') !== false) ? 'active' : '' ?>" href="<?= base_url('/profil') ?>">Profil Desa</a></li>
                            <li><a class="dropdown-item <?= (strpos(current_url(), '/perangkat-desa') !== false) ? 'active' : '' ?>" href="<?= base_url('/perangkat-desa') ?>">Perangkat Desa</a></li>
                            <li><a class="dropdown-item <?= (strpos(current_url(), '/inventaris') !== false) ? 'active' : '' ?>" href="<?= base_url('/inventaris') ?>">Inventaris Desa</a></li>
                            <li><a class="dropdown-item <?= (strpos(current_url(), '/geografis') !== false) ? 'active' : '' ?>" href="<?= base_url('/geografis') ?>">Geografi Desa</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(current_url(), '/pengumuman') !== false) ? 'active' : '' ?>" href="<?= base_url('/pengumuman') ?>">Pengumuman</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(current_url(), '/pengaduan') !== false) ? 'active' : '' ?>" href="<?= base_url('/pengaduan') ?>">Pengaduan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'galeri') !== false ? 'active' : '' ?>" href="<?= base_url('/galeri') ?>">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'berita') !== false ? 'active' : '' ?>" href="<?= base_url('/berita') ?>">Berita</a>
                    </li>

                </ul>
            </div>

            <!-- Tombol Masuk / Profil User -->
            <div class="navbar-actions">
                <?php if (!$isLoggedIn): ?>
                    <a href="<?= base_url('/login') ?>" class="btn-masuk">Masuk</a>
                <?php else: ?>
                    <div class="user-profile-dropdown">
                        <button class="user-profile-btn" type="button" id="userProfileBtn">
                            <img src="<?= esc($userPhoto) ?>" alt="Profile" class="user-profile-img">
                            <span class="user-profile-name"><?= esc($userName) ?></span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="user-profile-menu" id="userProfileMenu">
                            <a href="<?= $dashboardUrl ?>" class="profile-menu-item">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                            <a href="<?= base_url('/logout') ?>" class="profile-menu-item">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

