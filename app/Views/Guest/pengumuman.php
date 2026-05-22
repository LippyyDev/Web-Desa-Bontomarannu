<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5" style="margin-top: 90px;">
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2">Pengumuman Desa</h2>
        <p class="text-muted">Informasi resmi dan pengumuman terbaru dari Pemerintah Desa Bonto Marannu</p>
    </div>

    <div class="row g-4">
        <?php if (!empty($pengumuman)): ?>
            <?php foreach ($pengumuman as $item): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-3 p-2 me-3" style="background: rgba(23, 105, 224, 0.08);">
                                    <i class="bi bi-megaphone-fill text-primary fs-5"></i>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= date('d M Y', strtotime($item['created_at'])) ?>
                                </small>
                            </div>
                            <h5 class="fw-bold mb-2"><?= esc($item['judul']) ?></h5>
                            <p class="text-muted mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                                <?= esc(word_limiter(strip_tags($item['isi']), 20)) ?>
                            </p>
                        </div>
                        <?php if (!empty($item['isi'])): ?>
                        <div class="card-footer bg-transparent border-0 px-4 pb-4">
                            <a href="#pengumuman-<?= $item['id'] ?>" class="text-primary text-decoration-none small fw-semibold" data-bs-toggle="collapse">
                                Baca selengkapnya <i class="bi bi-chevron-down ms-1"></i>
                            </a>
                            <div class="collapse mt-3" id="pengumuman-<?= $item['id'] ?>">
                                <p class="text-muted mb-0" style="font-size: 0.9rem; line-height: 1.7;">
                                    <?= nl2br(esc($item['isi'])) ?>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-megaphone text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <p class="text-muted mt-3">Belum ada pengumuman yang tersedia saat ini.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
