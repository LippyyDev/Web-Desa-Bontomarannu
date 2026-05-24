<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/pariwisata.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">
        
        <div class="profil-desa-header mt-5 mb-4 text-center reveal-up">
            <span class="profil-desa-subtitle">Potensi Desa</span>
            <h2 class="profil-desa-title">Pariwisata <span>Desa</span></h2>
        </div>

        <form method="GET" action="<?= base_url('/pariwisata') ?>" class="reveal-up delay-100">
            <div class="pariwisata-search-box">
                <i class="bi bi-search text-muted ms-3"></i>
                <input type="text" name="search" class="form-control" placeholder="Cari destinasi wisata..." value="<?= esc($search) ?>">
                <?php if (!empty($search)): ?>
                    <a href="<?= base_url('/pariwisata') ?>" class="btn-reset" title="Reset Pencarian"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
                <button type="submit" class="btn-search">Cari</button>
            </div>
        </form>

        <?php if (!empty($search)): ?>
            <div class="text-center mb-4 reveal-up text-muted">
                Ditemukan <?= count($list) ?> destinasi untuk pencarian "<strong><?= esc($search) ?></strong>"
            </div>
        <?php endif; ?>

        <div class="row g-4 mb-5">
            <?php if (!empty($list)): ?>
                <?php 
                $delay = 100;
                foreach ($list as $item): 
                ?>
                    <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay: <?= $delay ?>ms;">
                        <div class="pariwisata-card">
                            <div class="pariwisata-img-wrapper">
                                <?php if (!empty($item['alamat'])): ?>
                                <div class="pariwisata-location-pill">
                                    <i class="bi bi-geo-alt-fill"></i> <span><?= esc($item['alamat']) ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['thumbnail_display'])): ?>
                                    <img src="<?= esc(base_url($item['thumbnail_display'])) ?>" alt="<?= esc($item['nama_tempat']) ?>" onerror="this.onerror=null; this.outerHTML='<div class=\'pariwisata-placeholder\'><i class=\'bi bi-image\'></i></div>';">
                                <?php else: ?>
                                    <div class="pariwisata-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="pariwisata-card-body">
                                <h5 class="pariwisata-title"><?= esc($item['nama_tempat']) ?></h5>
                                <div class="pariwisata-excerpt">
                                    <?= esc(mb_strimwidth(strip_tags($item['deskripsi'] ?? ''), 0, 120, '...')) ?>
                                </div>
                                
                                <a href="<?= base_url('/pariwisata/' . $item['id']) ?>" class="pariwisata-read-more stretched-link mt-2">Baca selengkapnya</a>
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
                        <i class="bi bi-compass text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Belum ada data pariwisata<?= !empty($search) ? ' untuk "' . esc($search) . '"' : '' ?>.</p>
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
