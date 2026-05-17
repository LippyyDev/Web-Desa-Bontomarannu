<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<?php
$currentUser = session('user');
$userProfileModel = new \App\Models\UserProfileModel();
$userId = $currentUser['id'] ?? null;
$profile = $userId ? $userProfileModel->find($userId) : null;
$userName = ($profile && !empty($profile['nama_lengkap'])) ? $profile['nama_lengkap'] : ($currentUser['username'] ?? 'User');

$hour = (int)date('H');
if ($hour < 12) { $greeting = 'Selamat pagi'; }
elseif ($hour < 15) { $greeting = 'Selamat siang'; }
elseif ($hour < 19) { $greeting = 'Selamat sore'; }
else { $greeting = 'Selamat malam'; }
?>

<style>
/* Grid System Bento */
.bento-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 1rem; }
.bc-2 { grid-column: span 2; }
.bc-3 { grid-column: span 3; }
.bc-4 { grid-column: span 4; }
.bc-5 { grid-column: span 5; }
.bc-6 { grid-column: span 6; }
.bc-7 { grid-column: span 7; }
.bc-8 { grid-column: span 8; }
.bc-9 { grid-column: span 9; }
.bc-10 { grid-column: span 10; }
.bc-12 { grid-column: span 12; }

@media (max-width: 991px) {
    .bc-3, .bc-4, .bc-5, .bc-6, .bc-7, .bc-8 { grid-column: span 6; }
}
@media (max-width: 767px) {
    .bento-grid { display: flex; flex-direction: column; gap: 1rem; }
}

.stat-icon-bento { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1rem; }
.bento-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 16px; }
.bento-card:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }

.notif-dot { width: 8px; height: 8px; border-radius: 50%; background: #dc2626; display: inline-block; margin-left: 4px; vertical-align: middle; animation: pulse-dot 1.5s infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.3)} }
</style>

<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            DASHBOARD WARGA
        </div>
        <h2 class="fw-bold text-dark mb-0" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            <?= $greeting ?>, <span style="color: #15803d;"><?= esc($userName) ?></span>
        </h2>
        <div class="text-muted mt-2">Pantau surat dan UMKM Anda — <?= date('l, d F Y') ?></div>
    </div>
</div>

<div class="bento-grid mb-4">
    <!-- ROW 1 -->
    <div class="card bento-card bc-8 border-0 shadow-sm" style="background-color: #15803d; color: #fff;">
        <div class="card-body d-flex flex-column justify-content-center position-relative">
            <i class="bi bi-envelope-fill position-absolute" style="font-size: 8rem; right: -20px; bottom: -20px; opacity: 0.1;"></i>
            <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 1px; color: #dcfce7;">Total Surat Saya</div>
            <div class="fw-bold lh-1 mb-2" style="font-size: 4rem;"><?= $totalLetters ?></div>
            <div class="small" style="color: #bbf7d0;">Semua surat yang pernah diajukan</div>
            <div class="mt-auto pt-3">
                <a href="<?= base_url('/user/surat') ?>" class="btn btn-light btn-sm text-success fw-bold rounded-pill px-3">Lihat Surat <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="card bento-card bc-4 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi Cepat</h6>
            <div class="d-flex flex-column gap-2">
                <a href="<?= base_url('/user/surat/buat') ?>" class="btn btn-outline-success text-start py-2" style="font-size: 0.85rem;"><i class="bi bi-file-earmark-plus me-2"></i> Buat Surat Baru</a>
                <a href="<?= base_url('/user/surat') ?>" class="btn btn-outline-success text-start py-2" style="font-size: 0.85rem;"><i class="bi bi-envelope-open me-2"></i> Cek Status Surat</a>
                <a href="<?= base_url('/user/umkm') ?>" class="btn btn-outline-success text-start py-2" style="font-size: 0.85rem;"><i class="bi bi-shop me-2"></i> Kelola UMKM</a>
                <a href="<?= base_url('/user/notifikasi') ?>" class="btn btn-outline-success text-start py-2" style="font-size: 0.85rem;"><i class="bi bi-bell me-2"></i> Notifikasi</a>
            </div>
        </div>
    </div>

    <?php
    function renderBentoStat($colSize, $title, $value, $desc, $icon, $link, $iconColor, $titleExtra = '') {
        $linkHtml = $link ? '<a href="'.$link.'" class="text-success" title="Kelola"><i class="bi bi-arrow-right-circle fs-5"></i></a>' : '<div style="width:24px;"></div>';
        return '
        <div class="card bento-card bc-'.$colSize.' border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon-bento" style="background-color: #dcfce7; color: '.$iconColor.';">
                        <i class="bi '.$icon.'"></i>
                    </div>
                    '.$linkHtml.'
                </div>
                <div class="text-uppercase fw-semibold mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">'.$title.' '.$titleExtra.'</div>
                <div class="fw-bold text-dark lh-1 mb-1" style="font-size: 1.8rem;">'.$value.'</div>
                <div class="text-muted" style="font-size: 0.75rem;">'.$desc.'</div>
            </div>
        </div>';
    }
    ?>

    <!-- ROW 2 -->
    <?= renderBentoStat(4, 'Menunggu', $sentCount, 'Belum diproses staff', 'bi-hourglass-split', null, '#15803d') ?>
    <?= renderBentoStat(4, 'Dibaca', $readCount, 'Sedang diproses', 'bi-eye-fill', null, '#16a34a') ?>
    <?= renderBentoStat(4, 'Diputuskan', $repliedCount, 'Diterima / ditolak', 'bi-patch-check-fill', null, '#065f46') ?>

    <!-- ROW 3: Stats & UMKM Chart -->
    <?php $notifExtra = $unreadNotif > 0 ? '<span class="notif-dot"></span>' : ''; ?>
    <?= renderBentoStat(4, 'Notifikasi', $unreadNotif, 'Notifikasi belum dibaca', 'bi-bell-fill', base_url('/user/notifikasi'), '#10b981', $notifExtra) ?>

    <div class="card bento-card bc-8 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Status Surat Saya</h6>
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div id="userDonutChart" style="min-height:240px;"></div>
                </div>
                <div class="col-md-5 border-start">
                    <div class="d-flex flex-column gap-3 ps-3" style="font-size:0.85rem;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><span style="color:#f59e0b" class="me-2 fs-5 lh-1">●</span> Menunggu</span>
                            <b class="fs-6"><?= $statusMenunggu ?></b>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><span style="color:#0ea5e9" class="me-2 fs-5 lh-1">●</span> Dibaca</span>
                            <b class="fs-6"><?= $statusDibaca ?></b>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><span style="color:#16a34a" class="me-2 fs-5 lh-1">●</span> Diterima</span>
                            <b class="fs-6"><?= $statusDiterima ?></b>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><span style="color:#dc2626" class="me-2 fs-5 lh-1">●</span> Ditolak</span>
                            <b class="fs-6"><?= $statusDitolak ?></b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 4: Surat Charts -->
    <div class="card bento-card bc-8 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Aktivitas Surat Saya (6 Bulan)</h6>
            <div id="userLetterChart" style="min-height:280px;"></div>
        </div>
    </div>
    
    <div class="card bento-card bc-4 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Status UMKM Saya</h6>
            <div id="umkmRadialChart" style="min-height:240px;"></div>
        </div>
    </div>

    <!-- ROW 5: LISTS -->
    <div class="card bento-card bc-7 border-0 shadow-sm">
        <div class="card-body pb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Surat Terbaru Saya</h6>
                <a href="<?= base_url('/user/surat') ?>" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size:0.75rem;">Lihat Semua</a>
            </div>
        </div>
        <div class="list-group list-group-flush rounded-bottom" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <?php if (!empty($recentLetters)): ?>
                <?php foreach ($recentLetters as $letter): ?>
                    <a href="<?= base_url('/user/surat/' . $letter['id']) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start py-3">
                        <div>
                            <div class="fw-semibold text-dark mb-1"><?= esc($letter['judul_perihal']) ?></div>
                            <div class="small text-muted"><?= esc($letter['tipe_surat'] ?? '-') ?></div>
                        </div>
                        <div class="text-end">
                            <?php
                            $badgeCls = match($letter['status']) {
                                'Menunggu' => 'bg-warning text-dark',
                                'Dibaca'   => 'bg-primary text-white',
                                'Diterima' => 'bg-success text-white',
                                'Ditolak'  => 'bg-danger text-white',
                                default    => 'bg-secondary text-white',
                            };
                            ?>
                            <span class="badge <?= $badgeCls ?> mb-1"><?= esc($letter['status']) ?></span>
                            <div class="small text-muted" style="font-size: 0.7rem;"><?= date('d M Y H:i', strtotime($letter['created_at'])) ?> WIB</div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item text-muted text-center py-4 small border-0">Belum ada surat diajukan</div>
            <?php endif; ?>
            <?php
            $emptySlotsSurat = 5 - count($recentLetters);
            for ($i = 0; $i < $emptySlotsSurat; $i++):
            ?>
                <div class="list-group-item d-flex justify-content-center align-items-center py-4 bg-light" style="border-style: dashed; border-color: #e2e8f0; opacity: 0.5;">
                    <span class="small text-muted" style="font-size: 0.7rem;">— Slot Kosong —</span>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <div class="card bento-card bc-5 border-0 shadow-sm">
        <div class="card-body pb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">
                    Notifikasi Terbaru
                    <?php if ($unreadNotif > 0): ?>
                        <span class="badge bg-danger ms-1"><?= $unreadNotif ?> baru</span>
                    <?php endif; ?>
                </h6>
                <a href="<?= base_url('/user/notifikasi') ?>" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size:0.75rem;">Lihat Semua</a>
            </div>
        </div>
        <div class="list-group list-group-flush rounded-bottom" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notif): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-start py-3">
                        <div>
                            <div class="fw-semibold text-dark mb-1"><?= esc($notif['title']) ?></div>
                            <div class="small text-muted"><?= esc(mb_strimwidth($notif['message'], 0, 60, '...')) ?></div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.7rem; white-space: nowrap;">
                            <?= date('d M H:i', strtotime($notif['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item text-muted text-center py-4 small border-0">Belum ada notifikasi</div>
            <?php endif; ?>
            <?php
            $emptySlotsNotif = 5 - count($notifications);
            for ($i = 0; $i < $emptySlotsNotif; $i++):
            ?>
                <div class="list-group-item d-flex justify-content-center align-items-center py-4 bg-light" style="border-style: dashed; border-color: #e2e8f0; opacity: 0.5;">
                    <span class="small text-muted" style="font-size: 0.7rem;">— Slot Kosong —</span>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Area Chart Surat Saya
    var areaOpts = {
        series: [{ name: 'Surat Dikirim', data: <?= json_encode($chartSent) ?> }, { name: 'Surat Diputuskan', data: <?= json_encode($chartDecided) ?> }],
        chart: { height: 280, type: 'area', fontFamily: 'inherit', toolbar: { show: false }, zoom: { enabled: false } },
        colors: ['#15803d', '#22c55e'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.03, stops: [0, 90, 100] } },
        xaxis: { categories: <?= json_encode($chartLabels) ?>, axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { fontSize: '11px' } } },
        yaxis: { min: 0, forceNiceScale: true, labels: { formatter: v => Math.round(v) } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        legend: { position: 'top', horizontalAlign: 'right', fontSize: '12px' },
        tooltip: { theme: 'light', y: { formatter: v => v + ' Surat' } }
    };
    new ApexCharts(document.querySelector('#userLetterChart'), areaOpts).render();

    // 2. Donut Chart Status Surat
    var donutOpts = {
        series: [<?= $statusMenunggu ?>, <?= $statusDibaca ?>, <?= $statusDiterima ?>, <?= $statusDitolak ?>],
        chart: { height: 240, type: 'donut', fontFamily: 'inherit' },
        colors: ['#f59e0b', '#0ea5e9', '#16a34a', '#dc2626'],
        labels: ['Menunggu', 'Dibaca', 'Diterima', 'Ditolak'],
        plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '13px', fontWeight: 700, color: '#0f172a', formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0) } } } } },
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: { y: { formatter: v => v + ' Surat' } }
    };
    new ApexCharts(document.querySelector('#userDonutChart'), donutOpts).render();

    // 3. Radial Bar Chart UMKM Saya
    var umkmTotal = <?= $umkmTotal ?>;
    var umkmPct = umkmTotal > 0 ? Math.round((<?= $umkmApproved ?> / umkmTotal) * 100) : 0;
    var umkmPendingPct = umkmTotal > 0 ? Math.round((<?= $umkmPending ?> / umkmTotal) * 100) : 0;
    var umkmRejectedPct = umkmTotal > 0 ? Math.round((<?= $umkmRejected ?> / umkmTotal) * 100) : 0;
    var umkmRadialOpts = {
        series: [umkmPct, umkmPendingPct, umkmRejectedPct],
        chart: { height: 260, type: 'radialBar', fontFamily: 'inherit' },
        plotOptions: {
            radialBar: {
                hollow: { size: '30%' },
                track: { background: '#f1f5f9', margin: 4 },
                dataLabels: {
                    name: { show: true, fontSize: '12px', color: '#64748b' },
                    value: { show: true, fontSize: '20px', fontWeight: 700, color: '#0f172a', formatter: function (val) { return val + "%" } },
                    total: { show: true, label: 'Total UMKM', fontSize: '11px', color: '#64748b', formatter: function (w) { return umkmTotal } }
                }
            }
        },
        colors: ['#0d9488', '#f59e0b', '#dc2626'],
        labels: ['Disetujui', 'Menunggu', 'Ditolak'],
        stroke: { lineCap: 'round' }
    };
    new ApexCharts(document.querySelector('#umkmRadialChart'), umkmRadialOpts).render();


});
</script>
<?= $this->endSection() ?>
