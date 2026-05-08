<?= $this->extend('Admin/layout') ?>

<?= $this->section('content') ?>
<?php
$currentUser = session('user');
$userProfileModel = new \App\Models\UserProfileModel();
$userId = $currentUser['id'] ?? null;
$profile = $userId ? $userProfileModel->find($userId) : null;
$userName = ($profile && !empty($profile['nama_lengkap'])) ? $profile['nama_lengkap'] : ($currentUser['username'] ?? 'User');

$hour = (int)date('H');
if ($hour < 12) {
    $greeting = 'Selamat pagi';
} elseif ($hour < 15) {
    $greeting = 'Selamat siang';
} elseif ($hour < 19) {
    $greeting = 'Selamat sore';
} else {
    $greeting = 'Selamat malam';
}
?>
<div class="mb-5 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            Overview
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            <?= $greeting ?>, <span style="color: #15803d;"><?= esc($userName) ?></span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Ringkasan data dan aktivitas sistem terkini.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/admin/akun/tambah') ?>" class="btn btn-primary shadow-sm rounded-3 px-3 py-2 fw-medium d-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i> Tambah Akun
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-value text-primary"><?= $userCount ?></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="stat-label">Surat</div>
            <div class="stat-value"><?= $letterCount ?></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-newspaper"></i>
            </div>
            <div class="stat-label">Berita</div>
            <div class="stat-value"><?= $newsCount ?></div>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-info-circle"></i>
        Ringkasan Konten
    </div>
    <div class="card-body">
        <div class="text-muted">Album Galeri: <strong><?= $albumCount ?></strong> · Total Berita: <strong><?= $newsCount ?></strong></div>
    </div>
</div>
<?= $this->endSection() ?>


