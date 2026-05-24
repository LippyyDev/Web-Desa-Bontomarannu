<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/berita.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5">
        
        <div class="profil-desa-header mt-5 mb-5 text-center reveal-up">
            <span class="profil-desa-subtitle">Berita Desa</span>
            <h2 class="profil-desa-title">Kabar <span>Terbaru</span></h2>
        </div>

        <div class="row g-4">
            <?php 
            $delay = 100;
            foreach ($news as $index => $item): 
                if ($index % 3 == 0 && $index != 0) $delay = 100;
            ?>
                <div class="col-md-6 col-lg-4 reveal-up delay-<?= $delay ?>">
                    <div class="news-card">
                        <div class="news-img-wrapper">
                            <div class="news-date-pill">
                                <i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($item['tanggal_waktu'])) ?>
                            </div>
                            <?php if (!empty($item['thumbnail'])): ?>
                                <img src="<?= esc(base_url($item['thumbnail'])) ?>" 
                                     alt="<?= esc($item['judul']) ?>" 
                                     onerror="this.onerror=null; this.outerHTML='<div class=\'news-placeholder\'><i class=\'bi bi-newspaper\'></i></div>';">
                            <?php else: ?>
                                <div class="news-placeholder">
                                    <i class="bi bi-newspaper"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="news-card-body">
                            <h5 class="news-title"><?= esc($item['judul']) ?></h5>
                            <p class="news-excerpt"><?= esc(strip_tags($item['isi'])) ?></p>
                            <a href="<?= base_url('/berita/' . $item['id']) ?>" class="news-read-more stretched-link">Baca selengkapnya <span class="arrow">→</span></a>
                        </div>
                    </div>
                </div>
            <?php 
            $delay += 100;
            endforeach; 
            ?>
            
            <?php if (empty($news)): ?>
                <div class="col-12 text-center text-muted py-5 reveal-up">
                    <i class="bi bi-newspaper fs-1 d-block mb-3" style="color: #cbd5e1;"></i>
                    <p class="mb-0 fs-5">Belum ada berita.</p>
                </div>
            <?php endif; ?>
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
