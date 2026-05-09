<?php
$currentUser = session('user');
$role = $currentUser['role'] ?? '';
$currentUrl = current_url();
$basePath = base_url();

$userProfileModel = new \App\Models\UserProfileModel();
$userId = $currentUser['id'] ?? null;
$profile = $userId ? $userProfileModel->find($userId) : null;
$userName = ($profile && !empty($profile['nama_lengkap'])) ? $profile['nama_lengkap'] : ($currentUser['username'] ?? 'User');
$userPhoto = ($profile && !empty($profile['foto_profil'])) 
    ? base_url($profile['foto_profil']) 
    : base_url('assets/img/guest.webp');

// Menentukan menu berdasarkan role
$menus = match($role) {
    'admin' => [
        ['header' => 'UMUM'],
        ['label' => 'Dashboard', 'url' => '/admin/dashboard', 'icon' => 'bi-speedometer2'],
        ['label' => 'Profil', 'url' => '/admin/profil', 'icon' => 'bi-person'],
        ['label' => 'Notifikasi', 'url' => '/admin/notifikasi', 'icon' => 'bi-bell'],
        
        ['header' => 'MANAJEMEN PENGGUNA'],
        ['label' => 'Kelola Akun', 'url' => '/admin/akun', 'icon' => 'bi-people'],
    ],
    'staf' => [
        ['header' => 'UMUM'],
        ['label' => 'Dashboard', 'url' => '/staff/dashboard', 'icon' => 'bi-speedometer2'],
        ['label' => 'Profil', 'url' => '/staff/profil', 'icon' => 'bi-person'],
        ['label' => 'Notifikasi', 'url' => '/staff/notifikasi', 'icon' => 'bi-bell'],

        ['header' => 'MANAJEMEN KONTEN'],
        ['label' => 'Galeri', 'url' => '/staff/galeri', 'icon' => 'bi-images'],
        ['label' => 'Berita', 'url' => '/staff/berita', 'icon' => 'bi-newspaper'],
        ['label' => 'Pengumuman', 'url' => '/staff/pengumuman', 'icon' => 'bi-megaphone'],

        ['header' => 'LAYANAN SURAT'],
        ['label' => 'Surat Masuk', 'url' => '/staff/surat', 'icon' => 'bi-envelope'],
        ['label' => 'Pengaduan', 'url' => '/staff/pengaduan', 'icon' => 'bi-chat-left-text'],

        ['header' => 'UMKM & PARIWISATA'],
        ['label' => 'UMKM', 'url' => '/staff/umkm', 'icon' => 'bi-shop'],
        ['label' => 'Pariwisata', 'url' => '/staff/pariwisata', 'icon' => 'bi-compass'],

        ['header' => 'MANAJEMEN DESA'],
        ['label' => 'Perangkat Desa', 'url' => '/staff/perangkat-desa', 'icon' => 'bi-people'],
        ['label' => 'Profil Desa', 'url' => '/staff/desa', 'icon' => 'bi-building'],
        ['label' => 'Geografi Desa', 'url' => '/staff/geografi', 'icon' => 'bi-map'],
        ['label' => 'Inventaris Desa', 'url' => '/staff/inventaris', 'icon' => 'bi-box-seam'],
    ],
    'user' => [
        ['header' => 'UMUM'],
        ['label' => 'Dashboard', 'url' => '/user/dashboard', 'icon' => 'bi-speedometer2'],
        ['label' => 'Notifikasi', 'url' => '/user/notifikasi', 'icon' => 'bi-bell'],
        
        ['header' => 'LAYANAN WARGA'],
        ['label' => 'Surat', 'url' => '/user/surat', 'icon' => 'bi-envelope'],
        ['label' => 'Pengaduan', 'url' => '/user/pengaduan', 'icon' => 'bi-chat-left-text'],
        
        ['header' => 'USAHA & EKONOMI'],
        ['label' => 'UMKM Saya', 'url' => '/user/umkm', 'icon' => 'bi-shop'],
        
        ['header' => 'PENGATURAN'],
        ['label' => 'Profil', 'url' => '/user/profil', 'icon' => 'bi-person'],
    ],
    default => []
};
?>
<div class="sidebar" id="sidebar">
    <script>
        // Prevent FOUC (Flash of Unstyled Content) & animation on load
        document.body.classList.add('sidebar-preload');
        if (localStorage.getItem('sidebarMinimized') === 'true' && window.innerWidth > 1400) {
            document.getElementById('sidebar').classList.add('minimized');
            document.body.classList.add('sidebar-is-minimized');
        }
        
        // Remove preload class after page has rendered
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.body.classList.remove('sidebar-preload');
            }, 100);
        });
    </script>
    
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Toggle Sidebar">
        <i class="bi bi-arrow-left-short" id="sidebarToggleIcon"></i>
        <script>
            if (localStorage.getItem('sidebarMinimized') === 'true' && window.innerWidth > 1400) {
                document.getElementById('sidebarToggleIcon').classList.replace('bi-arrow-left-short', 'bi-arrow-right-short');
            }
        </script>
    </button>

    <div class="sidebar-header">
        <div class="sidebar-user-profile">
            <img src="<?= esc($userPhoto) ?>" alt="Profile" class="sidebar-profile-img">
            <div class="sidebar-user-info">
                <span class="sidebar-user-name" title="<?= esc($userName) ?>"><?= esc($userName) ?></span>
                <span class="sidebar-user-role"><?= esc(ucfirst($role)) ?></span>
            </div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <?php foreach ($menus as $menu): ?>
            <?php if (isset($menu['header'])): ?>
                <div class="sidebar-header-section" title="<?= esc($menu['header']) ?>">
                    <span><?= esc($menu['header']) ?></span>
                </div>
            <?php else: ?>
                <?php
                $menuUrl = base_url($menu['url']);
                // Check if current URL matches menu URL
                $isActive = (strpos($currentUrl, $menu['url']) !== false) || 
                           (strpos($currentUrl, $menuUrl) !== false);
                ?>
                <a href="<?= esc($menuUrl) ?>" class="sidebar-item <?= $isActive ? 'active' : '' ?>" title="<?= esc($menu['label']) ?>">
                    <i class="<?= esc($menu['icon']) ?>"></i>
                    <span class="sidebar-item-label"><?= esc($menu['label']) ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= base_url('/logout') ?>" class="sidebar-logout" title="Logout">
            <i class="bi bi-box-arrow-right"></i>
            <span class="sidebar-item-label">Logout</span>
        </a>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarOpenBtn = document.getElementById('sidebarOpenBtn'); // from topbar
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const toggleIcon = document.getElementById('sidebarToggleIcon');
    
    function toggleSidebarMinimize() {
        if (window.innerWidth <= 1400) {
            closeSidebar();
            return;
        }
        sidebar.classList.toggle('minimized');
        const minimized = sidebar.classList.contains('minimized');
        localStorage.setItem('sidebarMinimized', minimized);
        
        if (minimized) {
            document.body.classList.add('sidebar-is-minimized');
            toggleIcon.classList.replace('bi-arrow-left-short', 'bi-arrow-right-short');
        } else {
            document.body.classList.remove('sidebar-is-minimized');
            toggleIcon.classList.replace('bi-arrow-right-short', 'bi-arrow-left-short');
        }
    }

    function openSidebar() {
        sidebar.classList.add('active');
        sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeSidebar() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', toggleSidebarMinimize);
    }

    if (sidebarOpenBtn) {
        sidebarOpenBtn.addEventListener('click', openSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }
    
    // Handle resize events
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1400) {
            closeSidebar(); // Remove mobile active state
            // Restore desktop state
            const shouldBeMinimized = localStorage.getItem('sidebarMinimized') === 'true';
            if (shouldBeMinimized) {
                sidebar.classList.add('minimized');
                document.body.classList.add('sidebar-is-minimized');
                toggleIcon.classList.replace('bi-arrow-left-short', 'bi-arrow-right-short');
            } else {
                sidebar.classList.remove('minimized');
                document.body.classList.remove('sidebar-is-minimized');
                toggleIcon.classList.replace('bi-arrow-right-short', 'bi-arrow-left-short');
            }
        } else {
            sidebar.classList.remove('minimized'); // Don't minimize on mobile
            document.body.classList.remove('sidebar-is-minimized');
        }
    });
});
</script>

