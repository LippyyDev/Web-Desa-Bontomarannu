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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-uppercase text-success fw-semibold small mb-1" style="letter-spacing: 1px;">Galeri</p>
            <h2 class="fw-bold mb-0 text-dark"><?= esc($album['nama_album']) ?></h2>
            <div class="text-muted small mt-1"><i class="bi bi-calendar-event me-1"></i><?= date('d M Y', strtotime($album['tanggal_waktu'])) ?></div>
        </div>
        <a href="<?= base_url('/galeri') ?>" class="btn btn-outline-success border-2 fw-semibold px-3" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
    <p class="text-muted mb-4"><?= esc($album['deskripsi'] ?? '') ?></p>
    <div class="row g-4">
        <?php foreach ($media as $item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card gallery-card border-0 shadow-sm bg-white h-100">
                    <?php if ($item['media_type'] === 'video_link'): ?>
                        <div class="ratio ratio-16x9">
                            <iframe src="<?= esc($item['embed_url'] ?? $item['media_path']) ?>" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    <?php else: ?>
                        <div class="gallery-img-wrapper">
                            <img src="<?= base_url($item['media_path']) ?>" class="card-img-top" alt="Media" onerror="this.onerror=null;this.outerHTML='<div class=\'gallery-placeholder\'><i class=\'bi bi-images\'></i></div>'">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($media)): ?>
            <div class="col-12 text-muted text-center py-5">
                <i class="bi bi-images text-muted d-block mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                Belum ada media di album ini.
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>


