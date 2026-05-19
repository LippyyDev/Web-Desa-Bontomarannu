<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <!-- Hero -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                <div class="text-white">
                    <h2 class="fw-bold"><i class="bi bi-compass me-2"></i>Pariwisata Desa Padang Loang</h2>
                    <p class="mb-0 opacity-75">Keindahan dan destinasi wisata di desa kami</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="<?= base_url('/pariwisata') ?>">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari destinasi wisata..." value="<?= esc($search) ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <?php if (!empty($search)): ?>
                    <a href="<?= base_url('/pariwisata') ?>" class="btn btn-outline-secondary">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-end">
            <span class="text-muted"><?= count($list) ?> destinasi ditemukan</span>
        </div>
    </div>

    <!-- Pariwisata Grid -->
    <?php if (empty($list)): ?>
    <div class="text-center py-5">
        <i class="bi bi-compass fs-1 text-muted d-block mb-3"></i>
        <h5 class="text-muted">Belum ada data pariwisata<?= !empty($search) ? ' untuk "' . esc($search) . '"' : '' ?></h5>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($list as $item): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-card" style="transition:transform .2s,box-shadow .2s;">
                <?php if ($item['thumbnail_display']): ?>
                <img src="<?= base_url($item['thumbnail_display']) ?>" class="card-img-top" style="height:220px;object-fit:cover;" alt="<?= esc($item['nama_tempat']) ?>">
                <?php else: ?>
                <div class="d-flex align-items-center justify-content-center bg-light" style="height:220px;">
                    <i class="bi bi-image fs-1 text-muted"></i>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title fw-bold text-break"><?= esc($item['nama_tempat']) ?></h5>
                    <?php if ($item['alamat']): ?>
                    <p class="small text-muted mb-2 text-break"><i class="bi bi-geo-alt"></i> <?= esc(mb_strimwidth($item['alamat'], 0, 70, '...')) ?></p>
                    <?php endif; ?>
                    <p class="card-text text-muted small text-break"><?= esc(mb_strimwidth(strip_tags($item['deskripsi'] ?? ''), 0, 100, '...')) ?></p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="<?= base_url('/pariwisata/' . $item['id']) ?>" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.hover-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important; }
</style>
<?= $this->endSection() ?>
