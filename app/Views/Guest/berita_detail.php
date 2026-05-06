<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Berita</p>
            <h2 class="fw-bold mb-0"><?= esc($item['judul']) ?></h2>
            <div class="small text-muted"><?= date('d M Y', strtotime($item['tanggal_waktu'])) ?></div>
        </div>
        <a href="<?= base_url('/berita') ?>" class="btn btn-outline-primary btn-sm">Kembali</a>
    </div>
    <img src="<?= $item['thumbnail'] ? base_url($item['thumbnail']) : 'https://via.placeholder.com/900x420?text=Berita' ?>" class="rounded-3 shadow-sm mb-4 w-100" alt="<?= esc($item['judul']) ?>">
    <article class="mb-4">
        <?= nl2br($item['isi']) ?>
    </article>
    <?php if (!empty($media)): ?>
        <div class="row g-3">
            <?php foreach ($media as $m): ?>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <?php if (isset($m['media_type']) && $m['media_type'] === 'video_link' && isset($m['embed_url'])): ?>
                            <div class="ratio ratio-16x9">
                                <iframe src="<?= esc($m['embed_url']) ?>" allowfullscreen></iframe>
                            </div>
                        <?php else: ?>
                            <img src="<?= base_url($m['media_path']) ?>" class="card-img-top" alt="Media">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>


