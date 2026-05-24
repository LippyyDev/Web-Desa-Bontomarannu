<?= $this->extend('Guest/layout') ?>

<?php
$pemilikText = 'Pemilik UMKM';
if (!empty($umkm['pemilik_role'])) {
    if ($umkm['pemilik_role'] === 'staff') {
        $pemilikText = 'Staff Desa';
    } else {
        $pemilikText = $umkm['pemilik_username'];
    }
}
?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/umkm.css?v=' . time()) ?>">
<style>
.article-content p { margin-bottom: 1rem; }
.article-content { text-align: justify; }
.store-header-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    z-index: 1;
}
.store-header-content {
    position: relative;
    z-index: 2;
}
/* Glassmorphism Popup */
.glass-popup {
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(11px);
  -webkit-backdrop-filter: blur(11px);
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 
    0 8px 32px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.5),
    inset 0 -1px 0 rgba(255, 255, 255, 0.1),
    inset 0 0 22px 11px rgba(255, 255, 255, 1.1);
  position: relative;
  overflow: hidden;
}
.glass-popup::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
}
.glass-popup::after {
  content: '';
  position: absolute;
  top: 0; left: 0; width: 1px; height: 100%;
  background: linear-gradient(180deg, rgba(255, 255, 255, 0.8), transparent, rgba(255, 255, 255, 0.3));
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">
        
        <div class="row mb-4">
            <div class="col-12 reveal-up">
                <a href="<?= base_url('/umkm') ?>" class="btn btn-outline-success btn-sm mb-4 rounded-pill px-3 fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 reveal-up delay-100">
                <!-- Store Header Banner -->
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4">
                    <div class="position-relative d-flex align-items-end p-4" style="height: 350px; background-color: #f8fafc;">
                        <!-- Background Image -->
                        <div class="store-header-bg">
                            <?php if (!empty($umkm['foto_toko'])): ?>
                                <img src="<?= base_url($umkm['foto_toko']) ?>" alt="<?= esc($umkm['nama_toko']) ?>" class="w-100 h-100 object-fit-cover">
                            <?php else: ?>
                                <div class="d-flex h-100 align-items-center justify-content-center text-muted bg-light">
                                    <i class="bi bi-shop" style="font-size: 8rem; opacity: 0.2;"></i>
                                </div>
                            <?php endif; ?>
                            <!-- Gradient Overlay -->
                            <div class="position-absolute bottom-0 w-100" style="height: 150px; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);"></div>
                        </div>

                        <!-- Content over background -->
                        <div class="store-header-content w-100 position-absolute bottom-0 end-0 p-3 p-md-4 d-flex justify-content-end">
                            <div class="glass-popup shadow-lg px-4 py-3 d-inline-flex align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1 text-white-50" style="letter-spacing: 1px; text-transform: uppercase; font-size: 0.7rem;"><i class="bi bi-person-circle me-1"></i><?= esc($pemilikText) ?></h6>
                                    <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2" style="font-size: 1rem; margin-top: 2px;"><?= esc($umkm['nama_toko']) ?> <i class="bi bi-patch-check-fill" style="color: #4ade80; font-size: 0.85rem;" title="UMKM Resmi Desa"></i></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Side: Header, Photo, Description, Products -->
            <div class="col-lg-8 reveal-up delay-100">

                <!-- Description -->
                <?php if (!empty($umkm['deskripsi'])): ?>
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0" style="width: 40px; height: 40px; background: rgba(46,125,50,0.1); color: #2E7D32;">
                                <i class="bi bi-info-circle-fill fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: #111827;">Tentang Toko</h5>
                        </div>
                        <article class="article-content" style="color: #4b5563; line-height: 1.8; font-size: 1.05rem;">
                            <?= nl2br(esc($umkm['deskripsi'])) ?>
                        </article>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Product Grid -->
                <h4 class="fw-bold mb-3 mt-4" style="color: #111827;">Daftar Produk</h4>
                <?php if (!empty($produk)): ?>
                    <div class="row g-3">
                        <?php foreach ($produk as $p): ?>
                        <div class="col-6 col-md-4 col-lg-4">
                            <a href="<?= base_url('/umkm/produk/' . $p['id']) ?>" class="umkm-card h-100 text-decoration-none d-flex flex-column" style="color:inherit;">
                                <div class="umkm-img-wrapper">
                                    <?php if (!empty($p['gambar'])): ?>
                                        <img src="<?= base_url($p['gambar'][0]['gambar_path']) ?>" class="d-block w-100 h-100 object-fit-cover" loading="lazy" alt="<?= esc($p['nama_produk']) ?>" onerror="this.onerror=null;this.style.opacity='0.2';">
                                    <?php else: ?>
                                        <div class="umkm-placeholder"><i class="bi bi-box"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="umkm-card-body">
                                    <h5 class="umkm-title text-truncate d-block w-100 mb-2" title="<?= esc($p['nama_produk']) ?>"><?= esc($p['nama_produk']) ?></h5>
                                    
                                    <?php if (!empty($p['deskripsi'])): ?>
                                    <p class="small text-muted mb-3 flex-grow-1" style="font-size: 0.8rem; line-height: 1.4;"><?= esc(mb_strimwidth($p['deskripsi'], 0, 60, '...')) ?></p>
                                    <?php else: ?>
                                    <div class="flex-grow-1"></div>
                                    <?php endif; ?>
                                    
                                    <div class="umkm-price-row mt-auto">
                                        <span class="umkm-price">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></span>
                                        <span class="umkm-action-icon">
                                            <i class="bi bi-arrow-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm text-muted mb-4">
                        <i class="bi bi-box-seam d-block mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mb-0">Belum ada produk yang ditambahkan ke toko ini.</p>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right Side: Sidebar -->
            <div class="col-lg-4 reveal-up delay-200 order-first order-lg-last">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    
                    <!-- Store Contact Card -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="d-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0" style="width: 40px; height: 40px; background: rgba(46,125,50,0.1); color: #2E7D32;">
                                    <i class="bi bi-person-lines-fill fs-5"></i>
                                </div>
                                <h5 class="fw-bold mb-0" style="color: #111827;">Informasi Kontak</h5>
                            </div>
                            
                            <?php if ($umkm['alamat']): ?>
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                    <i class="bi bi-geo-alt fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: #1e293b; font-size: 0.95rem;">Alamat Lokasi</h6>
                                    <p class="text-muted small mb-0" style="line-height: 1.5;"><?= esc($umkm['alamat']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($umkm['kontak']): ?>
                            <div class="d-flex align-items-start gap-3 mb-4">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                    <i class="bi bi-telephone fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: #1e293b; font-size: 0.95rem;">Telepon / WhatsApp</h6>
                                    <p class="text-muted small mb-0"><?= esc($umkm['kontak']) ?></p>
                                </div>
                            </div>
                            
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $umkm['kontak']) ?>" target="_blank" class="btn w-100 rounded-pill fw-semibold py-2 d-flex align-items-center justify-content-center gap-2" style="background-color: #2E7D32; border-color: #2E7D32; color: #ffffff;">
                                <i class="bi bi-whatsapp fs-5"></i> Hubungi Penjual
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Online Store Links -->
                    <?php if (!empty($ecommerce)): ?>
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0" style="width: 40px; height: 40px; background: rgba(46,125,50,0.1); color: #2E7D32;">
                                    <i class="bi bi-cart-check-fill fs-5"></i>
                                </div>
                                <h5 class="fw-bold mb-0" style="color: #111827;">Tersedia di E-Commerce</h5>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($ecommerce as $e): ?>
                                <a href="<?= esc($e['url']) ?>" target="_blank" class="btn btn-outline-success rounded-3 text-start px-3 py-2 fw-medium d-flex align-items-center gap-2 transition-all">
                                    <i class="bi bi-shop"></i> <?= esc($e['platform'] ?: 'Toko Online') ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Maps Embed -->
                    <?php if (!empty($umkm['maps_embed_url'])): ?>
                    <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4 p-0">
                        <iframe src="<?= esc($umkm['maps_embed_url']) ?>" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
