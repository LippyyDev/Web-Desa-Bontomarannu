<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/pengumuman.css?v=' . time()) ?>">
<style>
/* Additional styles for article detail */
.article-thumbnail-wrapper {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 2.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}
.article-thumbnail-wrapper img {
    width: 100%;
    height: auto;
    object-fit: cover;
    max-height: 500px;
}
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
        
        <!-- Main Article Section -->
        <div class="row">
            <div class="col-12 reveal-up">
                <a href="<?= base_url('/pengumuman') ?>" class="btn btn-outline-success btn-sm mb-4 rounded-pill px-3 fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Pengumuman
                </a>
                
                <h1 class="fw-bold mb-3" style="color: #111827; font-size: 2.5rem; line-height: 1.3;"><?= esc($item['judul']) ?></h1>
                
                <div class="d-flex align-items-center gap-3 text-muted mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-event text-success"></i>
                        <span><?= date('d F Y', strtotime($item['created_at'])) ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-success"></i>
                        <span>Admin Desa</span>
                    </div>
                </div>

                <div class="article-thumbnail-wrapper">
                    <?php if (!empty($item['thumbnail'])): ?>
                        <a href="<?= esc(base_url($item['thumbnail'])) ?>" target="_blank">
                            <img src="<?= esc(base_url($item['thumbnail'])) ?>" alt="<?= esc($item['judul']) ?>" onerror="this.onerror=null; this.outerHTML='<div class=\'pengumuman-placeholder py-5\' style=\'height: 400px;\'><i class=\'bi bi-megaphone\'></i></div>';">
                        </a>
                    <?php else: ?>
                        <div class="pengumuman-placeholder" style="height: 400px;">
                            <i class="bi bi-megaphone"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <article class="article-content">
                    <?= $item['isi'] ?> <!-- Raw HTML support if from editor -->
                </article>

                <?php if (!empty($item['foto'])): ?>
                    <h4 class="fw-bold mt-5 mb-4">Foto Pendukung</h4>
                    <div class="row g-3">
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                <a href="<?= base_url($item['foto']) ?>" target="_blank" class="d-block">
                                    <img src="<?= base_url($item['foto']) ?>" class="card-img-top w-100 object-fit-cover" style="height: 180px;" alt="Foto Pendukung">
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Latest News Section (Bottom) -->
        <div class="row mt-5 pt-5 border-top reveal-up delay-200">
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width: 5px; height: 28px; background-color: #2E7D32; border-radius: 4px;"></div>
                    <h4 class="fw-bold mb-0">Pengumuman Terbaru Lainnya</h4>
                </div>

                <div class="row g-4 justify-content-center">
                    <?php foreach ($other_pengumuman as $other): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="pengumuman-card">
                                <div class="pengumuman-img-wrapper">
                                    <div class="pengumuman-date-pill">
                                        <i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($other['created_at'])) ?>
                                    </div>
                                    <?php if (!empty($other['thumbnail'])): ?>
                                        <img src="<?= esc(base_url($other['thumbnail'])) ?>" alt="<?= esc($other['judul']) ?>" onerror="this.onerror=null; this.outerHTML='<div class=\'pengumuman-placeholder\'><i class=\'bi bi-megaphone\'></i></div>';">
                                    <?php else: ?>
                                        <div class="pengumuman-placeholder">
                                            <i class="bi bi-megaphone"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="pengumuman-card-body">
                                    <h6 class="pengumuman-title" style="font-size: 1.05rem;"><?= esc($other['judul']) ?></h6>
                                    <a href="<?= base_url('/pengumuman/' . $other['id']) ?>" class="pengumuman-read-more stretched-link mt-2">Baca selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($other_pengumuman)): ?>
                        <div class="col-12">
                            <div class="text-muted text-center py-4 bg-light rounded-4">
                                <i class="bi bi-megaphone fs-2 mb-2 d-block"></i>
                                Belum ada pengumuman terbaru lainnya
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
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
