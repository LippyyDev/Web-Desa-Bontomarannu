<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/umkm.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">
        
        <div class="profil-desa-header mt-5 mb-4 text-center reveal-up">
            <span class="profil-desa-subtitle">Informasi UMKM</span>
            <h2 class="profil-desa-title">UMKM <span>Desa</span></h2>
            <p class="text-muted mt-3 mb-0" style="max-width: 600px; margin-left: auto; margin-right: auto;">Produk dan usaha unggulan dari warga desa kami</p>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center mb-5 reveal-up delay-100">
            <div class="col-md-8 col-lg-6">
                <form method="GET" action="<?= base_url('/umkm') ?>">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white" style="border: 1px solid #e2e8f0;">
                        <span class="input-group-text bg-transparent border-0 ps-4 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-0 shadow-none py-3 px-3" placeholder="Cari nama toko..." value="<?= esc($search) ?>">
                        <button type="submit" class="btn btn-success px-4 fw-semibold border-0" style="background-color: #2E7D32;">Cari</button>
                    </div>
                    <?php if (!empty($search)): ?>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('/umkm') ?>" class="text-success text-decoration-none small fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Reset Pencarian
                        </a>
                        <div class="text-muted small mt-1"><?= count($list) ?> UMKM ditemukan</div>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <?php foreach ($list as $index => $item): ?>
                <?php 
                // Add delay for stagger effect
                $delay = ($index % 3) * 100;
                ?>
                <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay: <?= $delay ?>ms;">
                    <div class="umkm-list-card">
                        <div class="umkm-list-img-wrapper">
                            <?php if (!empty($item['foto_toko'])): ?>
                                <img src="<?= esc(base_url($item['foto_toko'])) ?>" alt="<?= esc($item['nama_toko']) ?>" onerror="this.onerror=null; this.outerHTML='<div class=\'umkm-placeholder\'><i class=\'bi bi-shop\'></i><span>Foto Toko</span></div>';">
                            <?php else: ?>
                                <div class="umkm-placeholder">
                                    <i class="bi bi-shop"></i>
                                    <span>Belum ada foto</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="umkm-list-card-body">
                            <h5 class="umkm-list-title"><?= esc($item['nama_toko']) ?></h5>
                            
                            <div class="d-flex flex-column gap-2 mb-3 mt-1">
                                <?php if (!empty($item['alamat'])): ?>
                                <div class="d-flex align-items-start gap-2 text-muted" style="font-size: 0.85rem;">
                                    <i class="bi bi-geo-alt mt-1 flex-shrink-0" style="color: #10b981;"></i>
                                    <span><?= esc(mb_strimwidth($item['alamat'], 0, 60, '...')) ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['kontak'])): ?>
                                <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.85rem;">
                                    <i class="bi bi-telephone flex-shrink-0" style="color: #10b981;"></i>
                                    <span><?= esc($item['kontak']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="umkm-excerpt">
                                <?= esc(mb_strimwidth(strip_tags($item['deskripsi'] ?? ''), 0, 100, '...')) ?>
                            </div>
                            <a href="<?= base_url('/umkm/' . $item['id']) ?>" class="umkm-read-more stretched-link mt-3">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($list)): ?>
                <div class="col-12 text-center text-muted py-5 reveal-up">
                    <i class="bi bi-shop d-block mb-3" style="font-size: 3.5rem; opacity: 0.5;"></i>
                    <p class="mb-0 fw-medium">Belum ada UMKM yang terdaftar<?= !empty($search) ? ' untuk pencarian "' . esc($search) . '"' : '' ?>.</p>
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
