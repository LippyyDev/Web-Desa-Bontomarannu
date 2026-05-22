<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<style>
.gallery-card {
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
}
.gallery-img-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background-color: #f1f5f9;
}
.gallery-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.gallery-card:hover .gallery-img-wrapper img {
    transform: scale(1.05);
}
.gallery-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cbd5e1;
    background-color: #f8fafc;
}
.gallery-placeholder i {
    font-size: 3rem;
}
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-uppercase text-success fw-semibold small mb-1" style="letter-spacing: 1px;">Galeri Desa</p>
            <h2 class="fw-bold mb-0 text-dark">Kumpulan Album</h2>
        </div>
    </div>
    <div class="row g-4">
        <?php foreach ($albums as $album): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card gallery-card h-100 border-0 shadow-sm bg-white">
                    <div class="gallery-img-wrapper">
                        <?php if (!empty($albumMedia[$album['id']])): ?>
                            <img src="<?= base_url($albumMedia[$album['id']]) ?>" class="card-img-top" alt="<?= esc($album['nama_album']) ?>" onerror="this.onerror=null;this.outerHTML='<div class=\'gallery-placeholder\'><i class=\'bi bi-images\'></i></div>'">
                        <?php else: ?>
                            <div class="gallery-placeholder"><i class="bi bi-images"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="small text-muted mb-2"><i class="bi bi-calendar-event me-1"></i><?= date('d M Y', strtotime($album['tanggal_waktu'])) ?></div>
                        <h5 class="card-title fw-bold text-dark mb-2"><?= esc($album['nama_album']) ?></h5>
                        <p class="card-text text-muted small flex-grow-1"><?= esc($album['deskripsi'] ?? '') ?></p>
                        <div class="mt-3 pt-3 border-top d-flex align-items-center text-success fw-semibold small">
                            Lihat Album <i class="bi bi-arrow-right ms-1"></i>
                        </div>
                        <a href="<?= base_url('/galeri/' . $album['id']) ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($albums)): ?>
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-images text-muted d-block mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                Belum ada album yang ditambahkan.
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>


