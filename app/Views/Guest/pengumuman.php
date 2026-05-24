<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/pengumuman.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">
        
        <div class="profil-desa-header mt-5 mb-4 text-center reveal-up">
            <span class="profil-desa-subtitle">Informasi Desa</span>
            <h2 class="profil-desa-title">Pengumuman <span>Desa</span></h2>
        </div>

        <div class="row g-4 mb-5">
            <?php if (!empty($pengumuman)): ?>
                <?php 
                $delay = 100;
                foreach ($pengumuman as $item): 
                ?>
                    <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay: <?= $delay ?>ms;">
                        <div class="pengumuman-card">
                            <div class="pengumuman-img-wrapper">
                                <div class="pengumuman-date-pill">
                                    <i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($item['created_at'])) ?>
                                </div>
                                <?php if (!empty($item['thumbnail'])): ?>
                                    <img src="<?= esc(base_url($item['thumbnail'])) ?>" alt="<?= esc($item['judul']) ?>" onerror="this.onerror=null; this.outerHTML='<div class=\'pengumuman-placeholder\'><i class=\'bi bi-megaphone\'></i></div>';">
                                <?php else: ?>
                                    <div class="pengumuman-placeholder">
                                        <i class="bi bi-megaphone"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="pengumuman-card-body">
                                <h5 class="pengumuman-title"><?= esc($item['judul']) ?></h5>
                                <div class="pengumuman-excerpt">
                                    <?= esc(word_limiter(strip_tags($item['isi']), 20)) ?>
                                </div>
                                
                                <a href="<?= base_url('/pengumuman/' . $item['id']) ?>" class="pengumuman-read-more stretched-link mt-2">Baca selengkapnya</a>
                            </div>
                        </div>
                    </div>
                <?php 
                $delay += 100;
                endforeach; 
                ?>
            <?php else: ?>
                <div class="col-12 reveal-up">
                    <div class="text-center py-5">
                        <i class="bi bi-megaphone text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Belum ada pengumuman yang tersedia saat ini.</p>
                    </div>
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
