<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h2 class="fw-bold mb-3">Perangkat Desa</h2>
            <?php if (!empty($perangkatDesa)): ?>
                <div class="row g-4">
                    <?php foreach ($perangkatDesa as $perangkat): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="card h-100 border-0 text-center perangkat-card-guest">
                                <div class="perangkat-img-wrapper-guest">
                                    <img src="<?= esc($perangkat['foto_url']) ?>" 
                                         alt="<?= esc($perangkat['nama']) ?>" 
                                         class="rounded-circle perangkat-img-guest">
                                </div>
                                <div class="card-body px-4 pb-4 pt-0 d-flex flex-column">
                                    <h5 class="card-title fw-bold mb-1" style="font-size: 1.15rem; color: #1e293b;"><?= esc($perangkat['nama']) ?></h5>
                                    <div>
                                        <span class="perangkat-role-guest"><?= esc($perangkat['jabatan']) ?></span>
                                    </div>
                                    <?php if ($perangkat['kontak']): ?>
                                        <div class="text-muted small mt-auto pt-3 d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-telephone"></i> <?= esc($perangkat['kontak']) ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="mt-auto pt-3">&nbsp;</div>
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

<style>
.perangkat-card-guest {
    border-radius: 16px !important;
    background: #ffffff;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025) !important;
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9 !important;
}
.perangkat-card-guest:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}
.perangkat-img-wrapper-guest {
    display: flex;
    justify-content: center;
    padding-top: 2rem;
    padding-bottom: 1.5rem;
    background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
}
.perangkat-img-guest {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border: 3px solid #10b981;
    box-shadow: 0 8px 16px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}
.perangkat-card-guest:hover .perangkat-img-guest {
    transform: scale(1.05);
}
.perangkat-role-guest {
    font-size: 0.75rem;
    font-weight: 600;
    color: #059669;
    background: #ecfdf5;
    padding: 5px 14px;
    border-radius: 20px;
    display: inline-block;
    margin-top: 0.5rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
</style>
</div>
<?= $this->endSection() ?>
