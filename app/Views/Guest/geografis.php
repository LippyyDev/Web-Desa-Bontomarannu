<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/geografis.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container">
        
        <div class="profil-desa-header mt-5 mb-4 text-center reveal-up">
            <span class="profil-desa-subtitle">Informasi Wilayah</span>
            <h2 class="profil-desa-title">Geografi <span>Desa</span></h2>
        </div>

        <div class="row g-4 mb-5">
            <!-- Map Full Width dengan Overlay Luas Wilayah -->
            <div class="col-lg-12 reveal-up delay-100">
                <div class="bento-card p-0 border-0 rounded-4 shadow-sm overflow-hidden position-relative h-100" style="min-height: 550px;">
                    <?php 
                    $mapsEmbed = $geografi['maps_embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d70000!2d120.245!3d-5.435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMjYnMDYuMCJTIDEyMMKwMTQnNDIuMCJF!5e0!3m2!1sen!2sid!4v1704090000';
                    // Force zoom out dengan mengganti parameter 1d di Google Maps pb string
                    $mapsEmbed = preg_replace('/!1d[\d\.]+/', '!1d12000', $mapsEmbed);
                    ?>
                    <iframe class="map-tint-green" src="<?= esc($mapsEmbed) ?>" style="border:0; width:100%; height:100%; min-height: 550px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    
                    <!-- Overlay Info Luas Wilayah -->
                    <div class="position-absolute bottom-0 end-0 m-3 m-md-4">
                        <div class="glass-popup shadow-lg p-3 p-md-4 d-flex align-items-center gap-3" style="animation: float 6s ease-in-out infinite;">
                            <div class="flex-shrink-0" style="width: 50px; height: 50px; background: rgba(46, 125, 50, 0.1); color: #2E7D32; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="bi bi-arrows-fullscreen"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="color: #6b7280; letter-spacing: 1px; text-transform: uppercase; font-size: 0.75rem;">Luas Wilayah</h6>
                                <h4 class="fw-bold text-dark mb-0"><?= esc($geografi['luas_wilayah'] ?? '-') ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bawah: Batas Wilayah & Kondisi Geografis -->
        <div class="row g-4 mb-0">
            <!-- Batas Wilayah -->
            <div class="col-12 reveal-up delay-100">
                <div class="bento-card p-4 p-md-5">
                    <h4 class="section-title"><i class="bi bi-compass"></i> Batas Wilayah</h4>
                    <div class="history-text">
                        <?= !empty($geografi['batas_wilayah']) ? nl2br(esc($geografi['batas_wilayah'])) : 'Data batas wilayah belum tersedia.' ?>
                    </div>
                </div>
            </div>

            <!-- Kondisi Geografis -->
            <div class="col-12 reveal-up delay-200">
                <div class="bento-card p-4 p-md-5">
                    <h4 class="section-title"><i class="bi bi-geo-alt"></i> Kondisi Geografis</h4>
                    <div class="history-text">
                        <?= !empty($geografi['kondisi_geografis']) ? nl2br(esc($geografi['kondisi_geografis'])) : 'Data kondisi geografis belum tersedia.' ?>
                    </div>
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
        threshold: 0.15,
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
