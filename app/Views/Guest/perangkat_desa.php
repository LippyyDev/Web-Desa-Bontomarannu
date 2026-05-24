<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/perangkatdesa.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5">
        
        <div class="profil-desa-header mt-5 mb-5 text-center reveal-up">
            <span class="profil-desa-subtitle">Struktur Organisasi</span>
            <h2 class="profil-desa-title">Perangkat <span>Desa</span></h2>
        </div>

        <?php if (!empty($perangkatDesa)): ?>
            <div class="row g-4 justify-content-center">
                <?php 
                $delay = 100;
                foreach ($perangkatDesa as $index => $perangkat): 
                    // Reset delay if more than 4 items per row to keep animation fluid
                    if ($index % 4 == 0 && $index != 0) $delay = 100;
                ?>
                    <div class="col-md-6 col-lg-4 col-xl-3 reveal-up delay-<?= $delay ?>">
                        <div class="bento-team-card">
                            <div class="team-img-wrapper">
                                <?php if (!empty($perangkat['foto_url'])): ?>
                                    <img src="<?= esc($perangkat['foto_url']) ?>" 
                                         alt="<?= esc($perangkat['nama']) ?>" 
                                         class="team-img"
                                         onerror="this.onerror=null; this.outerHTML='<div class=\'team-placeholder\'><i class=\'bi bi-person\'></i></div>';">
                                <?php else: ?>
                                    <div class="team-placeholder">
                                        <i class="bi bi-person"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h5 class="team-name"><?= esc($perangkat['nama']) ?></h5>
                            <div>
                                <span class="team-role"><?= esc($perangkat['jabatan']) ?></span>
                            </div>
                            
                            <div class="team-contact mt-auto pt-3">
                                <?php if ($perangkat['kontak']): ?>
                                    <i class="bi bi-person-lines-fill"></i> <?= esc($perangkat['kontak']) ?>
                                <?php else: ?>
                                    &nbsp;
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php 
                $delay += 100;
                endforeach; 
                ?>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-5 reveal-up delay-100">
                <i class="bi bi-people fs-1 d-block mb-3" style="color: #cbd5e1;"></i>
                <p class="mb-0 fs-5">Belum ada data perangkat desa.</p>
            </div>
        <?php endif; ?>

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
