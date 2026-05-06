<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Galeri Desa</p>
            <h2 class="fw-bold mb-0">Kumpulan Album</h2>
        </div>
    </div>
    <div class="row g-4">
        <?php foreach ($albums as $album): ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="<?= $albumMedia[$album['id']] ? base_url($albumMedia[$album['id']]) : 'https://via.placeholder.com/600x400?text=Album' ?>" class="card-img-top" alt="<?= esc($album['nama_album']) ?>">
                    <div class="card-body">
                        <div class="small text-muted"><?= date('d M Y', strtotime($album['tanggal_waktu'])) ?></div>
                        <h5 class="card-title"><?= esc($album['nama_album']) ?></h5>
                        <p class="card-text text-muted small"><?= esc($album['deskripsi'] ?? '') ?></p>
                        <a href="<?= base_url('/galeri/' . $album['id']) ?>" class="stretched-link">Lihat album</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($albums)): ?>
            <div class="col-12 text-center text-muted">Belum ada album yang ditambahkan.</div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>


