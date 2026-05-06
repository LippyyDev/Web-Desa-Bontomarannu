<?= $this->extend('Admin/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Dashboard</h4>
        <div class="text-muted small">Ringkasan data dan aktivitas sistem.</div>
    </div>
    <div class="page-header-icon">
        <i class="bi bi-speedometer2"></i>
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


