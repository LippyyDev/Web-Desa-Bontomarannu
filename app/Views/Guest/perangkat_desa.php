<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h2 class="fw-bold mb-3">Perangkat Desa</h2>
            <?php if (!empty($perangkatDesa)): ?>
                <div class="row g-4">
                    <?php foreach ($perangkatDesa as $perangkat): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm text-center">
                                <div class="card-body p-4">
                                    <div class="mb-3">
                                        <img src="<?= esc($perangkat['foto_url']) ?>" 
                                                alt="<?= esc($perangkat['nama']) ?>" 
                                                class="rounded-circle" 
                                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #e9ecef;">
                                    </div>
                                    <h5 class="card-title fw-bold mb-1"><?= esc($perangkat['nama']) ?></h5>
                                    <p class="text-primary fw-semibold mb-2"><?= esc($perangkat['jabatan']) ?></p>
                                    <?php if ($perangkat['kontak']): ?>
                                        <div class="text-muted small">
                                            <i class="bi bi-telephone"></i> <?= esc($perangkat['kontak']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-3">
                    <i class="bi bi-people fs-4 d-block mb-2"></i>
                    <p class="mb-0">Belum ada data perangkat desa.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
