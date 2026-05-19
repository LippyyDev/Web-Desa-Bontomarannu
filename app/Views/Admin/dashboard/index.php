<?= $this->extend('Admin/layout') ?>

<?= $this->section('content') ?>
<?php
$currentUser = session('user');
$userProfileModel = new \App\Models\UserProfileModel();
$userId = $currentUser['id'] ?? null;
$profile = $userId ? $userProfileModel->find($userId) : null;
$userName = ($profile && !empty($profile['nama_lengkap'])) ? $profile['nama_lengkap'] : ($currentUser['username'] ?? 'Admin');

$hour = (int)date('H');
if ($hour < 12)      { $greeting = 'Selamat pagi'; }
elseif ($hour < 15)  { $greeting = 'Selamat siang'; }
elseif ($hour < 19)  { $greeting = 'Selamat sore'; }
else                 { $greeting = 'Selamat malam'; }
?>

<style>
/* ===== BENTO GRID ===== */
.bento-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 1rem; }
.bc-3  { grid-column: span 3; }
.bc-4  { grid-column: span 4; }
.bc-5  { grid-column: span 5; }
.bc-6  { grid-column: span 6; }
.bc-7  { grid-column: span 7; }
.bc-8  { grid-column: span 8; }
.bc-12 { grid-column: span 12; }

@media (max-width: 991px) {
    .bc-3, .bc-4, .bc-5, .bc-6, .bc-7, .bc-8 { grid-column: span 6; }
}
@media (max-width: 767px) {
    .bento-grid { display: flex; flex-direction: column; gap: 1rem; }
}

/* ===== CARD STYLES ===== */
.bento-card {
    border-radius: 16px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.bento-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12) !important;
}
.card-arrow-icon {
    transition: transform 0.3s ease;
    display: inline-block;
}
.bento-card:hover .card-arrow-icon {
    transform: rotate(-45deg) scale(1.1);
}
.stat-icon-bento {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    margin-bottom: 1rem;
}

/* ===== ROLE DISTRIBUTION BARS ===== */
.role-bar-wrap { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.role-bar-label { width: 46px; font-size: 0.75rem; font-weight: 600; color: #64748b; }
.role-bar-track { flex: 1; background: #f1f5f9; border-radius: 99px; height: 10px; overflow: hidden; }
.role-bar-fill  { height: 100%; border-radius: 99px; transition: width 0.6s cubic-bezier(.4,0,.2,1); }
.role-bar-count { width: 24px; text-align: right; font-size: 0.8rem; font-weight: 700; color: #0f172a; }

/* ===== ONLINE PULSE ===== */
.notif-dot { width: 8px; height: 8px; border-radius: 50%; background: #dc2626; display: inline-block; margin-left: 4px; vertical-align: middle; animation: pulse-dot 1.5s infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.3)} }
.online-pulse { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #22c55e; animation: pulse-green 2s infinite; }
@keyframes pulse-green { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(34,197,94,.4)} 50%{opacity:.7;box-shadow:0 0 0 6px rgba(34,197,94,0)} }
</style>

<!-- HEADER -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            DASHBOARD ADMIN
        </div>
        <h2 class="fw-bold text-dark mb-0" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            <?= $greeting ?>, <span style="color: #15803d;"><?= esc($userName) ?></span>
        </h2>
        <div class="text-muted mt-2">Pantau dan kelola akun pengguna — <?= date('l, d F Y') ?></div>
    </div>
</div>

<div class="bento-grid mb-4">

    <!-- ===== ROW 1 ===== -->

    <!-- HERO: Total Akun -->
    <div class="card bento-card bc-8 border-0 shadow-sm" style="background: linear-gradient(135deg, #15803d 0%, #166534 100%); color: #fff; min-height: 160px;">
        <div class="card-body d-flex flex-column justify-content-between position-relative overflow-hidden" style="padding: 1.5rem;">
            <i class="bi bi-people-fill position-absolute" style="font-size: 9rem; right: -24px; bottom: -24px; opacity: 0.1;"></i>
            <div>
                <div class="text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px; color: #dcfce7;">Total Akun Terdaftar</div>
                <div class="fw-bold lh-1" style="font-size: 4.5rem;"><?= $totalAkun ?></div>
                <div class="mt-1" style="color: #bbf7d0; font-size: 0.85rem;">Seluruh pengguna sistem</div>
            </div>
            <div class="mt-3">
                <a href="<?= base_url('/admin/akun') ?>" class="btn btn-light btn-sm text-success fw-bold rounded-pill px-3">
                    Kelola Akun <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- AKSI CEPAT -->
    <div class="card bento-card bc-4 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi Cepat</h6>
            <div class="d-flex flex-column gap-2">
                <a href="<?= base_url('/admin/akun') ?>" class="btn btn-outline-success text-start py-2" style="font-size: 0.85rem;">
                    <i class="bi bi-people me-2"></i> Kelola Akun
                </a>
                <a href="<?= base_url('/admin/akun/tambah') ?>" class="btn btn-outline-success text-start py-2" style="font-size: 0.85rem;">
                    <i class="bi bi-person-plus me-2"></i> Tambah Akun Baru
                </a>
                <a href="<?= base_url('/admin/profil') ?>" class="btn btn-outline-success text-start py-2" style="font-size: 0.85rem;">
                    <i class="bi bi-person-circle me-2"></i> Profil Saya
                </a>
                <a href="<?= base_url('/admin/notifikasi') ?>" class="btn btn-outline-success text-start py-2" style="font-size: 0.85rem;">
                    <i class="bi bi-bell me-2"></i> Notifikasi
                </a>
            </div>
        </div>
    </div>

    <!-- ===== ROW 2: 4 STAT CARDS ===== -->

    <!-- Akun Aktif -->
    <a href="<?= base_url('/admin/akun') ?>" class="card bento-card bc-3 border-0 shadow-sm text-decoration-none">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon-bento" style="background:#dcfce7; color:#15803d;">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div class="text-success" title="Lihat">
                    <i class="bi bi-arrow-right-circle fs-4 card-arrow-icon"></i>
                </div>
            </div>
            <div class="text-uppercase fw-semibold mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Akun Aktif</div>
            <div class="fw-bold text-dark lh-1 mb-1" style="font-size: 1.8rem;"><?= $aktifAkun ?></div>
            <div class="text-muted" style="font-size: 0.75rem;">Dapat login ke sistem</div>
        </div>
    </a>

    <!-- Akun Nonaktif -->
    <a href="<?= base_url('/admin/akun') ?>" class="card bento-card bc-3 border-0 shadow-sm text-decoration-none">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon-bento" style="background:#dcfce7; color:#16a34a;">
                    <i class="bi bi-person-x-fill"></i>
                </div>
                <div class="text-success" title="Lihat">
                    <i class="bi bi-arrow-right-circle fs-4 card-arrow-icon"></i>
                </div>
            </div>
            <div class="text-uppercase fw-semibold mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Akun Nonaktif</div>
            <div class="fw-bold text-dark lh-1 mb-1" style="font-size: 1.8rem;"><?= $nonaktifAkun ?></div>
            <div class="text-muted" style="font-size: 0.75rem;">Login diblokir</div>
        </div>
    </a>

    <!-- Sedang Online -->
    <a href="<?= base_url('/admin/akun') ?>" class="card bento-card bc-3 border-0 shadow-sm text-decoration-none">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon-bento" style="background:#dcfce7; color:#059669;">
                    <i class="bi bi-wifi"></i>
                </div>
                <div class="text-success" title="Lihat">
                    <i class="bi bi-arrow-right-circle fs-4 card-arrow-icon"></i>
                </div>
            </div>
            <div class="text-uppercase fw-semibold mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Sedang Online</div>
            <div class="fw-bold text-dark lh-1 mb-1" style="font-size: 1.8rem;"><?= $onlineAkun ?></div>
            <div class="text-muted" style="font-size: 0.75rem;">Aktif &le; 5 menit lalu</div>
        </div>
    </a>

    <!-- Notifikasi -->
    <a href="<?= base_url('/admin/notifikasi') ?>" class="card bento-card bc-3 border-0 shadow-sm text-decoration-none">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon-bento" style="background:#dcfce7; color:#10b981;">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div class="text-success" title="Lihat">
                    <i class="bi bi-arrow-right-circle fs-4 card-arrow-icon"></i>
                </div>
            </div>
            <div class="text-uppercase fw-semibold mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">
                Notifikasi
            </div>
            <div class="fw-bold text-dark lh-1 mb-1" style="font-size: 1.8rem;"><?= $unreadNotif ?></div>
            <div class="text-muted" style="font-size: 0.75rem;">Belum dibaca</div>
        </div>
    </a>

    <!-- ===== ROW 3 ===== -->

    <!-- DISTRIBUSI ROLE + CHART DONUT -->
    <div class="card bento-card bc-4 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Distribusi Role</h6>
            <!-- Donut -->
            <div id="roleDonutChart" style="min-height: 200px;"></div>
            <!-- Bars -->
            <div class="mt-3">
                <?php
                $maxRole = max($adminCount, $stafCount, $userCount, 1);
                $roles = [
                    ['label' => 'Admin', 'count' => $adminCount, 'color' => '#dc2626'],
                    ['label' => 'Staf',  'count' => $stafCount,  'color' => '#7c3aed'],
                    ['label' => 'User',  'count' => $userCount,  'color' => '#1d4ed8'],
                ];
                foreach ($roles as $r):
                    $pct = round(($r['count'] / $maxRole) * 100);
                ?>
                <div class="role-bar-wrap">
                    <div class="role-bar-label"><?= $r['label'] ?></div>
                    <div class="role-bar-track">
                        <div class="role-bar-fill" style="width: <?= $pct ?>%; background: <?= $r['color'] ?>;"></div>
                    </div>
                    <div class="role-bar-count"><?= $r['count'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- CHART: Registrasi Akun 6 Bulan -->
    <div class="card bento-card bc-8 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">
                Registrasi Akun (6 Bulan Terakhir)
            </h6>
            <div id="registrasiChart" style="min-height: 260px;"></div>
        </div>
    </div>

    <!-- ===== ROW 4 ===== -->

    <!-- AKUN TERBARU -->
    <div class="card bento-card bc-7 border-0 shadow-sm">
        <div class="card-body pb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Akun Terbaru</h6>
                <a href="<?= base_url('/admin/akun') ?>" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size: 0.75rem;">Lihat Semua</a>
            </div>
        </div>
        <div class="list-group list-group-flush rounded-bottom" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <?php if (!empty($recentAccounts)): ?>
                <?php foreach ($recentAccounts as $acc):
                    $fotoUrl  = !empty($acc['foto_profil']) ? base_url($acc['foto_profil']) : base_url('assets/img/guest.webp');
                    $isOnline = !empty($acc['last_seen_at']) && (time() - strtotime($acc['last_seen_at'])) <= 300;
                    $roleBadge = match($acc['role']) {
                        'admin' => 'bg-danger',
                        'staf'  => 'bg-purple text-white',
                        default => 'bg-primary',
                    };
                    $statusBadge = $acc['status'] === 'aktif' ? 'bg-success' : 'bg-secondary';
                ?>
                <a href="<?= base_url('/admin/akun/' . $acc['id'] . '/edit') ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                    <div class="position-relative flex-shrink-0">
                        <img src="<?= esc($fotoUrl) ?>" alt="Foto" class="rounded-circle" style="width:38px;height:38px;object-fit:cover;">
                        <?php if ($isOnline): ?>
                            <span class="position-absolute bottom-0 end-0 rounded-circle border border-2 border-white" style="width:10px;height:10px;background:#22c55e;"></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-dark small"><?= esc($acc['username']) ?></div>
                        <div class="text-muted" style="font-size: 0.72rem;"><?= esc($acc['email']) ?></div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <span class="badge <?= $roleBadge ?> text-capitalize mb-1 d-block" style="<?= $acc['role'] === 'staf' ? 'background:#7c3aed;' : '' ?>"><?= esc($acc['role']) ?></span>
                        <span class="badge <?= $statusBadge ?> text-capitalize d-block" style="font-size:0.65rem;"><?= esc($acc['status']) ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item text-muted text-center py-4 small border-0">Belum ada akun terdaftar</div>
            <?php endif; ?>
            <?php
            $emptySlots = 5 - count($recentAccounts);
            for ($i = 0; $i < $emptySlots; $i++):
            ?>
                <div class="list-group-item d-flex justify-content-center align-items-center py-4 bg-light" style="border-style: dashed; border-color: #e2e8f0; opacity: 0.5;">
                    <span class="small text-muted" style="font-size: 0.7rem;">— Slot Kosong —</span>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- NOTIFIKASI TERBARU -->
    <div class="card bento-card bc-5 border-0 shadow-sm">
        <div class="card-body pb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">
                    Notifikasi Terbaru
                </h6>
                <a href="<?= base_url('/admin/notifikasi') ?>" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size: 0.75rem;">Lihat Semua</a>
            </div>
        </div>
        <div class="list-group list-group-flush rounded-bottom" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notif): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-start py-3 <?= !$notif['is_read'] ? 'bg-light' : '' ?>">
                        <div>
                            <div class="fw-semibold text-dark small"><?= esc($notif['title']) ?></div>
                            <div class="text-muted" style="font-size: 0.72rem;"><?= esc(mb_strimwidth($notif['message'], 0, 55, '...')) ?></div>
                        </div>
                        <div class="text-end text-muted ms-2 flex-shrink-0" style="font-size: 0.7rem; white-space: nowrap;">
                            <?= date('d M H:i', strtotime($notif['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item text-muted text-center py-4 small border-0">Belum ada notifikasi</div>
            <?php endif; ?>
            <?php
            $emptySlotsNotif = 4 - count($notifications);
            for ($i = 0; $i < $emptySlotsNotif; $i++):
            ?>
                <div class="list-group-item d-flex justify-content-center align-items-center py-4 bg-light" style="border-style: dashed; border-color: #e2e8f0; opacity: 0.5;">
                    <span class="small text-muted" style="font-size: 0.7rem;">— Slot Kosong —</span>
                </div>
            <?php endfor; ?>
        </div>
    </div>

</div><!-- /bento-grid -->

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Donut Chart Distribusi Role
    var donutOpts = {
        series: [<?= $adminCount ?>, <?= $stafCount ?>, <?= $userCount ?>],
        chart: { height: 200, type: 'donut', fontFamily: 'inherit' },
        colors: ['#dc2626', '#7c3aed', '#1d4ed8'],
        labels: ['Admin', 'Staf', 'User'],
        plotOptions: {
            pie: { donut: { size: '65%', labels: {
                show: true,
                total: { show: true, label: 'Total', fontSize: '12px', fontWeight: 700, color: '#0f172a',
                    formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                }
            }}}
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: { y: { formatter: v => v + ' Akun' } }
    };
    new ApexCharts(document.querySelector('#roleDonutChart'), donutOpts).render();

    // 2. Bar/Area Chart Registrasi Akun
    var regLabels = <?= json_encode(count($chartLabels) > 0 ? $chartLabels : ['(Kosong)']) ?>;
    var regData   = <?= json_encode(count($chartData)   > 0 ? $chartData   : [0]) ?>;
    var regOpts = {
        series: [{ name: 'Akun Baru', data: regData }],
        chart: { height: 260, type: 'bar', fontFamily: 'inherit', toolbar: { show: false }, zoom: { enabled: false } },
        colors: ['#15803d'],
        plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
        dataLabels: { enabled: false },
        xaxis: {
            categories: regLabels,
            axisBorder: { show: false }, axisTicks: { show: false },
            labels: { style: { fontSize: '11px' } }
        },
        yaxis: { min: 0, forceNiceScale: true, labels: { formatter: v => Math.round(v) } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { theme: 'light', y: { formatter: v => v + ' Akun Baru' } }
    };
    new ApexCharts(document.querySelector('#registrasiChart'), regOpts).render();

});
</script>
<?= $this->endSection() ?>
