<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Dashboard</h4>
        <div class="text-muted small">Ringkasan surat dan notifikasi Anda.</div>
    </div>
    <div class="page-header-icon">
        <i class="bi bi-speedometer2"></i>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="stat-label">Total Surat</div>
            <div class="stat-value text-primary"><?= $totalLetters ?></div>
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
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-bell-fill"></i>
            <div>
                <div class="fw-semibold">Notifikasi Terbaru</div>
                <div class="small text-muted">Update status surat Anda</div>
            </div>
        </div>
        <a href="<?= base_url('/user/notifikasi') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-right"></i> Lihat Semua
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


