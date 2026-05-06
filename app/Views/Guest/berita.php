<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Berita Desa</p>
            <h2 class="fw-bold mb-0">Kabar Terbaru</h2>
        </div>
    </div>
    <div class="row g-4">
        <?php foreach ($news as $item): ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="<?= $item['thumbnail'] ? base_url($item['thumbnail']) : 'https://via.placeholder.com/600x400?text=Berita' ?>" class="card-img-top" alt="<?= esc($item['judul']) ?>">
                    <div class="card-body">
                        <div class="small text-muted"><?= date('d M Y', strtotime($item['tanggal_waktu'])) ?></div>
                        <h5 class="card-title mt-1"><?= esc($item['judul']) ?></h5>
                        <p class="card-text text-muted small"><?= word_limiter(strip_tags($item['isi']), 25) ?></p>
                        <a href="<?= base_url('/berita/' . $item['id']) ?>" class="stretched-link">Baca selengkapnya</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($news)): ?>
            <div class="col-12 text-center text-muted">Belum ada berita.</div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>


