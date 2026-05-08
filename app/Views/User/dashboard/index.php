<?= $this->extend('User/layout') ?>

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
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            Overview
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            <?= $greeting ?>, <span style="color: #15803d;"><?= esc($userName) ?></span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Ringkasan surat dan notifikasi Anda terkini.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= base_url('/user/surat/buat') ?>" class="btn btn-success">
            Ajukan Surat
        </a>
        <a href="<?= base_url('/user/pengaduan') ?>" class="btn btn-outline-success">
            Lapor/Aduan
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="stat-label">Total Surat</div>
            <div class="stat-value"><?= $totalLetters ?></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-send-fill"></i>
            </div>
            <div class="stat-label">Menunggu</div>
            <div class="stat-value"><?= $sentCount ?></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-eye-fill"></i>
            </div>
            <div class="stat-label">Dibaca</div>
            <div class="stat-value"><?= $readCount ?></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-reply-fill"></i>
            </div>
            <div class="stat-label">Diputuskan</div>
            <div class="stat-value"><?= $repliedCount ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <div class="fw-semibold">Notifikasi Terbaru</div>
            <div class="small text-muted fw-normal">Update status surat Anda</div>
        </div>
        <a href="<?= base_url('/user/notifikasi') ?>" class="btn btn-outline-success btn-sm">
            Lihat Semua
        </a>
    </div>
    <div class="list-group list-group-flush">
        <?php foreach ($notifications as $notif): ?>
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="fw-semibold"><?= esc($notif['title']) ?></div>
                        <div class="small text-muted"><?= esc($notif['message']) ?></div>
                    </div>
                    <div class="small text-muted"><?= date('d M Y', strtotime($notif['created_at'])) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($notifications)): ?>
            <div class="list-group-item text-muted small">Belum ada notifikasi.</div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>


