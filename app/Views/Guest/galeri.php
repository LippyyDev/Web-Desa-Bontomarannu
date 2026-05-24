<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/galeri.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">
        
        <div class="profil-desa-header mt-5 mb-4 text-center reveal-up">
            <span class="profil-desa-subtitle">Galeri Desa</span>
            <h2 class="profil-desa-title">Kumpulan <span>Album</span></h2>
        </div>

        <div class="row g-4 mb-5">
            <?php foreach ($albums as $index => $album): ?>
                <?php 
                // Add delay for stagger effect
                $delay = ($index % 3) * 100;
                ?>
                <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay: <?= $delay ?>ms;">
                    <div class="galeri-card">
                        <div class="galeri-img-wrapper">
                            <div class="galeri-date-pill">
                                <i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($album['tanggal_waktu'])) ?>
                            </div>
                            <?php if (!empty($albumMedia[$album['id']])): ?>
                                <img src="<?= esc(base_url($albumMedia[$album['id']])) ?>" alt="<?= esc($album['nama_album']) ?>" onerror="this.onerror=null; this.outerHTML='<div class=\'galeri-placeholder\'><i class=\'bi bi-images\'></i></div>';">
                            <?php else: ?>
                                <div class="galeri-placeholder">
                                    <i class="bi bi-images"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="galeri-card-body">
                            <h5 class="galeri-title"><?= esc($album['nama_album']) ?></h5>
                            <div class="galeri-excerpt">
                                <?= esc($album['deskripsi'] ?? '') ?>
                            </div>
                            
                            <a href="<?= base_url('/galeri/' . $album['id']) ?>" class="galeri-read-more stretched-link mt-2">Lihat Album <span class="arrow">→</span></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($albums)): ?>
                <div class="col-12 text-center text-muted py-5 reveal-up">
                    <i class="bi bi-images d-block mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                    <p class="mb-0">Belum ada album yang ditambahkan.</p>
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
