<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/galeri.css?v=' . time()) ?>">
<style>
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #334155;
}
.article-content p {
    margin-bottom: 1.5rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">
        
        <div class="row">
            <div class="col-12 reveal-up">
                <a href="<?= base_url('/galeri') ?>" class="btn btn-outline-success btn-sm mb-4 rounded-pill px-3 fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Galeri
                </a>
                
                <h1 class="fw-bold mb-3" style="color: #111827; font-size: 2.5rem; line-height: 1.3;"><?= esc($album['nama_album']) ?></h1>
                
                <div class="d-flex align-items-center gap-3 text-muted mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-event text-success"></i>
                        <span><?= date('d F Y', strtotime($album['tanggal_waktu'])) ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-success"></i>
                        <span>Admin Desa</span>
                    </div>
                </div>

                <?php if (!empty($album['deskripsi'])): ?>
                <article class="article-content">
                    <p><?= nl2br(esc($album['deskripsi'])) ?></p>
                </article>
                <?php endif; ?>

                <?php if (!empty($media)): ?>
                    <h4 class="fw-bold mt-5 mb-4">Media Album</h4>
                    <div class="row g-3">
                        <?php foreach ($media as $m): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                    <?php if (isset($m['media_type']) && $m['media_type'] === 'video_link' && isset($m['embed_url'])): ?>
                                        <div class="ratio ratio-16x9 h-100">
                                            <iframe src="<?= esc($m['embed_url']) ?>" allowfullscreen></iframe>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?= base_url($m['media_path']) ?>" target="_blank" class="d-block h-100">
                                            <img src="<?= base_url($m['media_path']) ?>" class="card-img-top h-100 w-100 object-fit-cover" style="aspect-ratio: 16/10;" alt="Media">
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="col-12 text-center text-muted py-5 mt-4 bg-light rounded-4">
                        <i class="bi bi-images d-block mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mb-0">Belum ada media di album ini.</p>
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
