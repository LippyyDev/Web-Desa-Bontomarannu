<?= $this->extend('Staff/layout') ?>

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
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Ringkasan aktivitas dan data desa terkini.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= base_url('/staff/berita/tambah') ?>" class="btn btn-primary shadow-sm rounded-3 px-3 py-2 fw-medium d-flex align-items-center gap-2">
            <i class="bi bi-pencil-square"></i> Tulis Berita
        </a>
        <a href="<?= base_url('/staff/pengumuman/tambah') ?>" class="btn bg-white border text-dark shadow-sm rounded-3 px-3 py-2 fw-medium d-flex align-items-center gap-2">
            <i class="bi bi-megaphone-fill text-warning"></i> Buat Pengumuman
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-4 col-xl">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="stat-label">Surat Masuk</div>
            <div class="stat-value text-primary"><?= $incoming ?></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xl">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-images"></i>
            </div>
            <div class="stat-label">Galeri</div>
            <div class="stat-value"><?= $galleryTotal ?></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xl">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-newspaper"></i>
            </div>
            <div class="stat-label">Berita</div>
            <div class="stat-value"><?= $newsTotal ?></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 col-xl">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-label">Perangkat Desa</div>
            <div class="stat-value"><?= $perangkatTotal ?></div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-clock-history"></i>
            Riwayat Surat Masuk
        </span>
        <a href="<?= base_url('/staff/surat') ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-right"></i> Lihat Semua
        </a>
    </div>
    <div class="list-group list-group-flush">
        <?php if (!empty($recentLetters)): ?>
            <?php foreach ($recentLetters as $letter): ?>
                <a href="<?= base_url('/staff/surat/' . $letter['id']) ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="fw-semibold"><?= esc($letter['judul_perihal']) ?></div>
                            <div class="small text-muted">Dari: <?= esc($letter['sender_name']) ?></div>
                        </div>
                        <div class="text-end">
                            <?php
                            $badgeCls = match($letter['status']) {
                                'Menunggu' => 'warning',
                                'Dibaca'   => 'info',
                                'Diterima' => 'success',
                                'Ditolak'  => 'danger',
                                default    => 'secondary',
                            };
                            ?>
                            <span class="badge bg-<?= $badgeCls ?> mb-2">
                                <?= esc($letter['status']) ?>
                            </span>
                            <div class="small text-muted"><?= date('d M Y', strtotime($letter['created_at'])) ?></div>
                </div>
            </div>
                </a>
        <?php endforeach; ?>
        <?php else: ?>
            <div class="list-group-item text-muted small">Belum ada surat masuk.</div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header fw-semibold d-flex align-items-center gap-2">
        <i class="bi bi-bar-chart-fill"></i>
        Grafik Jumlah Surat Masuk (6 Bulan Terakhir)
    </div>
    <div class="card-body">
        <canvas id="letterChart" style="max-height: 400px;"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('letterChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chartLabels) ?>,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: <?= json_encode($chartIncoming) ?>,
                        borderColor: 'rgb(13, 110, 253)',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Surat Diputuskan',
                        data: <?= json_encode($chartReplied) ?>,
                        borderColor: 'rgb(25, 135, 84)',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>


