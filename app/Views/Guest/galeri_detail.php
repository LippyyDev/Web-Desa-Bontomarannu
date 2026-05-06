<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Galeri</p>
            <h2 class="fw-bold mb-0"><?= esc($album['nama_album']) ?></h2>
            <div class="text-muted small"><?= date('d M Y', strtotime($album['tanggal_waktu'])) ?></div>
        </div>
        <a href="<?= base_url('/galeri') ?>" class="btn btn-outline-primary btn-sm">Kembali</a>
    </div>
    <p class="text-muted"><?= esc($album['deskripsi'] ?? '') ?></p>
    <div class="row g-3">
        <?php foreach ($media as $item): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <?php if ($item['media_type'] === 'video_link'): ?>
                        <div class="ratio ratio-16x9">
                            <iframe src="<?= esc($item['embed_url'] ?? $item['media_path']) ?>" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    <?php else: ?>
                        <img src="<?= base_url($item['media_path']) ?>" class="card-img-top" alt="Media">
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($media)): ?>
            <div class="col-12 text-muted text-center">Belum ada media di album ini.</div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>


