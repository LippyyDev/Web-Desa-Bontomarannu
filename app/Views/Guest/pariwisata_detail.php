<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/pariwisata.css?v=' . time()) ?>">
<style>
.pariwisata-thumbnail-wrapper {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 2.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}
.pariwisata-thumbnail-wrapper img {
    width: 100%;
    height: auto;
    object-fit: cover;
    max-height: 500px;
}
.pariwisata-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #334155;
}
.pariwisata-content p {
    margin-bottom: 1.5rem;
}
#heroCarousel .carousel-item img {
    height: 500px;
    object-fit: cover;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">
        
        <div class="row">
            <div class="col-12 reveal-up">
                <a href="<?= base_url('/pariwisata') ?>" class="btn btn-outline-success btn-sm mb-4 rounded-pill px-3 fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Pariwisata
                </a>
                
                <h1 class="fw-bold mb-3" style="color: #111827; font-size: 2.5rem; line-height: 1.3;"><?= esc($item['nama_tempat']) ?></h1>
                
                <div class="d-flex align-items-center gap-3 text-muted mb-4 pb-3 border-bottom">
                    <?php if ($item['alamat']): ?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-success"></i>
                        <span><?= esc($item['alamat']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="pariwisata-thumbnail-wrapper">
                    <?php if (!empty($gambar)): ?>
                        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach ($gambar as $gi => $g): ?>
                                <div class="carousel-item <?= $gi === 0 ? 'active' : '' ?>">
                                    <img src="<?= base_url($g['gambar_path']) ?>" class="d-block w-100" alt="<?= esc($item['nama_tempat']) ?>">
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($gambar) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                            <div class="carousel-indicators">
                                <?php foreach ($gambar as $gi => $g): ?>
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $gi ?>" <?= $gi === 0 ? 'class="active"' : '' ?>></button>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($item['thumbnail']): ?>
                        <img src="<?= base_url($item['thumbnail']) ?>" class="img-fluid w-100" alt="<?= esc($item['nama_tempat']) ?>">
                    <?php else: ?>
                        <div class="py-5" style="height: 400px; background-color: #f8fafc; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                            <i class="bi bi-image" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <article class="pariwisata-content">
                    <?php if ($item['deskripsi']): ?>
                        <?= nl2br(esc($item['deskripsi'])) ?>
                    <?php else: ?>
                        <p class="text-muted fst-italic">Belum ada deskripsi untuk destinasi pariwisata ini.</p>
                    <?php endif; ?>
                </article>

                <?php if (!empty($item['maps_embed_url'])): ?>
                    <h4 class="fw-bold mt-5 mb-4">Lokasi di Peta</h4>
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                        <iframe src="<?= esc($item['maps_embed_url']) ?>" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                <?php endif; ?>

                <?php if (count($gambar) > 1): ?>
                    <h4 class="fw-bold mt-5 mb-4">Galeri Foto</h4>
                    <div class="row g-3">
                        <?php foreach ($gambar as $g): ?>
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="<?= base_url($g['gambar_path']) ?>" target="_blank" class="card border-0 shadow-sm rounded-4 overflow-hidden text-decoration-none d-block">
                                    <img src="<?= base_url($g['gambar_path']) ?>" class="card-img-top w-100 object-fit-cover" style="height: 180px;" alt="Galeri">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
</section>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const reveals = document.querySelectorAll(".reveal-up");
    const revealOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealOnScroll = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
                observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    reveals.forEach(reveal => {
        revealOnScroll.observe(reveal);
    });
});
</script>
<?= $this->endSection() ?>
