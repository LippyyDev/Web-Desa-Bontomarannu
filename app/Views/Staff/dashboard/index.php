<?= $this->extend('Staff/layout') ?>

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
</style>

<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            OVERVIEW STAFF PANEL
        </div>
        <h2 class="fw-bold text-dark mb-0" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            <?= $greeting ?>, <span style="color: #15803d;"><?= esc($userName) ?></span>
        </h2>
        <div class="text-muted mt-2">Ringkasan aktivitas dan data desa terkini — <?= date('l, d F Y') ?></div>
    </div>
</div>

<div class="bento-grid mb-4">
    <!-- ROW 1 -->
    <div class="card bento-card bc-8 border-0 shadow-sm" style="background-color: #15803d; color: #fff;">
        <div class="card-body d-flex flex-column justify-content-center position-relative">
            <i class="bi bi-envelope-fill position-absolute" style="font-size: 8rem; right: -20px; bottom: -20px; opacity: 0.1;"></i>
            <h6 class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 1px; color: #dcfce7;">Surat Masuk</h6>
            <div class="fw-bold lh-1 mb-2" style="font-size: 4rem;"><?= $incoming ?></div>
            <div class="small" style="color: #bbf7d0;">Total surat dari warga yang perlu diproses</div>
            <div class="mt-auto pt-3">
                <a href="<?= base_url('/staff/surat') ?>" class="btn btn-light btn-sm text-success fw-bold rounded-pill px-3">Kelola Surat <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="card bento-card bc-4 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi Cepat</h6>
            <div class="row g-2">
                <div class="col-6"><a href="<?= base_url('/staff/surat') ?>" class="btn btn-outline-success w-100 text-start px-2 py-2" style="font-size: 0.8rem;"><i class="bi bi-envelope me-1"></i> Surat</a></div>
                <div class="col-6"><a href="<?= base_url('/staff/galeri/tambah') ?>" class="btn btn-outline-success w-100 text-start px-2 py-2" style="font-size: 0.8rem;"><i class="bi bi-images me-1"></i> Galeri</a></div>
                <div class="col-6"><a href="<?= base_url('/staff/berita/tambah') ?>" class="btn btn-outline-success w-100 text-start px-2 py-2" style="font-size: 0.8rem;"><i class="bi bi-newspaper me-1"></i> Berita</a></div>
                <div class="col-6"><a href="<?= base_url('/staff/pengumuman/tambah') ?>" class="btn btn-outline-success w-100 text-start px-2 py-2" style="font-size: 0.8rem;"><i class="bi bi-megaphone me-1"></i> Umum</a></div>
                <div class="col-6"><a href="<?= base_url('/staff/umkm') ?>" class="btn btn-outline-success w-100 text-start px-2 py-2" style="font-size: 0.8rem;"><i class="bi bi-shop me-1"></i> UMKM</a></div>
                <div class="col-6"><a href="<?= base_url('/staff/pengaduan') ?>" class="btn btn-outline-success w-100 text-start px-2 py-2" style="font-size: 0.8rem;"><i class="bi bi-chat-dots me-1"></i> Aduan</a></div>
            </div>
        </div>
    </div>

    <!-- ROW 2: Stat Cards yang tersisa -->
    <?php
    function renderBentoStat($colSize, $title, $value, $desc, $icon, $link, $iconColor) {
        return '
        <div class="card bento-card bc-'.$colSize.' border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon-bento" style="background-color: #dcfce7; color: '.$iconColor.';">
                        <i class="bi '.$icon.'"></i>
                    </div>
                    <a href="'.$link.'" class="text-success" title="Kelola"><i class="bi bi-arrow-right-circle fs-5"></i></a>
                </div>
                <div class="text-uppercase fw-semibold mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">'.$title.'</div>
                <div class="fw-bold text-dark lh-1 mb-1" style="font-size: 1.8rem;">'.$value.'</div>
                <div class="text-muted" style="font-size: 0.75rem;">'.$desc.'</div>
            </div>
        </div>';
    }
    ?>
    <?= renderBentoStat(6, 'Inventaris Desa', $inventarisTotal, 'Item tercatat', 'bi-archive-fill', base_url('/staff/inventaris'), '#064e3b') ?>
    <?= renderBentoStat(6, 'Perangkat Desa', $perangkatTotal, 'Staf aktif', 'bi-people-fill', base_url('/staff/perangkat-desa'), '#34d399') ?>

    <!-- ROW 3: Modern Charts -->
    <div class="card bento-card bc-8 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Statistik Konten Publikasi</h6>
            <div id="kontenBarChart" style="min-height:240px;"></div>
        </div>
    </div>
    
    <div class="card bento-card bc-4 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Status Pengajuan UMKM</h6>
            <div id="umkmRadialChart" style="min-height:240px;"></div>
        </div>
    </div>

    <!-- ROW 4: Surat & Pengaduan Charts -->
    <div class="card bento-card bc-8 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Surat Masuk 6 Bulan Terakhir</h6>
            <div id="letterChart" style="min-height:280px;"></div>
        </div>
    </div>

    <div class="card bento-card bc-4 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Pengaduan 6 Bulan Terakhir</h6>
            <div id="pengaduanBarChart" style="min-height:280px;"></div>
        </div>
    </div>

    <!-- ROW 5: Lists -->
    <div class="card bento-card bc-7 border-0 shadow-sm">
        <div class="card-body pb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Surat Masuk Terbaru</h6>
                <a href="<?= base_url('/staff/surat') ?>" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size:0.75rem;">Lihat Semua</a>
            </div>
        </div>
        <div class="list-group list-group-flush rounded-bottom" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <?php if (!empty($recentLetters)): ?>
                <?php foreach ($recentLetters as $letter): ?>
                    <a href="<?= base_url('/staff/surat/' . $letter['id']) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start py-3">
                        <div>
                            <div class="fw-semibold text-dark mb-1"><?= esc($letter['judul_perihal']) ?></div>
                            <div class="small text-muted">Dari: <?= esc($letter['sender_name']) ?></div>
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
                <div class="list-group-item text-muted text-center py-4 small border-0">Belum ada surat masuk</div>
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
                <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Pengaduan Terbaru</h6>
                <a href="<?= base_url('/staff/pengaduan') ?>" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size:0.75rem;">Lihat Semua</a>
            </div>
        </div>
        <div class="list-group list-group-flush rounded-bottom" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <?php if (!empty($recentPengaduan)): ?>
                <?php foreach ($recentPengaduan as $p): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-start py-3">
                        <div>
                            <div class="fw-semibold text-dark mb-1"><?= esc($p['perihal'] ?? ($p['isi'] ? mb_strimwidth($p['isi'], 0, 45, '...') : 'Tanpa Perihal')) ?></div>
                            <div class="small text-muted">Dari: <?= esc($p['sender_name']) ?></div>
                        </div>
                        <div class="text-end">
                            <?php
                            $pStat = $p['status'] ?? '';
                            $pBadge = match($pStat) {
                                'pending'   => 'bg-warning text-dark',
                                'processed' => 'bg-success text-white',
                                default     => 'bg-secondary text-white',
                            };
                            $pLabel = match($pStat) {
                                'pending'   => 'Menunggu',
                                'processed' => 'Selesai',
                                default     => ucfirst($pStat ?: 'Baru'),
                            };
                            ?>
                            <span class="badge <?= $pBadge ?> mb-1"><?= esc($pLabel) ?></span>
                            <div class="small text-muted" style="font-size: 0.7rem;"><?= date('d M Y', strtotime($p['created_at'])) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item text-muted text-center py-4 small border-0">Belum ada pengaduan</div>
            <?php endif; ?>
            <?php
            $emptySlotsPengaduan = 5 - count($recentPengaduan);
            for ($i = 0; $i < $emptySlotsPengaduan; $i++):
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
    // 1. Area Chart Surat Masuk
    var areaOpts = {
        series: [{ name: 'Surat Masuk', data: <?= json_encode($chartIncoming) ?> }, { name: 'Surat Diputuskan', data: <?= json_encode($chartReplied) ?> }],
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
    new ApexCharts(document.querySelector('#letterChart'), areaOpts).render();

    // 2. Bar Chart Statistik Konten Publikasi
    var kontenBarOpts = {
        series: [{ name: 'Total', data: [<?= $galleryTotal ?>, <?= $newsTotal ?>, <?= $pengumumanTotal ?>, <?= $pariwisataTotal ?>] }],
        chart: { height: 240, type: 'bar', fontFamily: 'inherit', toolbar: { show: false } },
        colors: ['#16a34a'],
        plotOptions: { bar: { borderRadius: 6, horizontal: true, dataLabels: { position: 'center' } } },
        dataLabels: { enabled: true, style: { fontSize: '13px', fontWeight: 700, colors: ['#ffffff'] } },
        xaxis: { categories: ['Galeri', 'Berita', 'Pengumuman', 'Pariwisata'], axisBorder: { show: false }, axisTicks: { show: false } },
        grid: { show: false },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector('#kontenBarChart'), kontenBarOpts).render();

    // 3. Radial Bar Chart UMKM
    var umkmTotal = <?= $umkmTotal ?>;
    var umkmPct = umkmTotal > 0 ? Math.round((<?= $umkmApproved ?> / umkmTotal) * 100) : 0;
    var umkmPendingPct = umkmTotal > 0 ? Math.round((<?= $umkmPending ?> / umkmTotal) * 100) : 0;
    var umkmRadialOpts = {
        series: [umkmPct, umkmPendingPct],
        chart: { height: 280, type: 'radialBar', fontFamily: 'inherit' },
        plotOptions: {
            radialBar: {
                hollow: { size: '45%' },
                track: { background: '#f1f5f9', margin: 5 },
                dataLabels: {
                    name: { show: true, fontSize: '13px', color: '#64748b' },
                    value: { show: true, fontSize: '24px', fontWeight: 700, color: '#0f172a', formatter: function (val) { return val + "%" } },
                    total: { show: true, label: 'Total UMKM', fontSize: '12px', color: '#64748b', formatter: function (w) { return umkmTotal } }
                }
            }
        },
        colors: ['#0d9488', '#f59e0b'],
        labels: ['Disetujui', 'Menunggu'],
        stroke: { lineCap: 'round' }
    };
    new ApexCharts(document.querySelector('#umkmRadialChart'), umkmRadialOpts).render();

    // 4. Bar Chart Pengaduan per bulan
    var pengaduanBarOpts = {
        series: [{ name: 'Pengaduan Masuk', data: <?= json_encode($chartPengaduan) ?> }],
        chart: { height: 280, type: 'bar', fontFamily: 'inherit', toolbar: { show: false } },
        colors: ['#065f46'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '50%', dataLabels: { position: 'center' } } },
        dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 700, colors: ['#ffffff'] } },
        xaxis: { categories: <?= json_encode($chartLabels) ?>, axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { fontSize: '11px' } } },
        yaxis: { min: 0, forceNiceScale: true, labels: { formatter: v => Math.round(v) } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { theme: 'light', y: { formatter: v => v + ' Laporan' } }
    };
    new ApexCharts(document.querySelector('#pengaduanBarChart'), pengaduanBarOpts).render();
});
</script>
<?= $this->endSection() ?>
