<?= $this->extend('Guest/layout') ?>

<?php
$pemilikText = 'Pemilik UMKM';
if (!empty($umkm['pemilik_role'])) {
    $pemilikText = ($umkm['pemilik_role'] === 'staff') ? 'Staff Desa' : esc($umkm['pemilik_username']);
}
$hasGambar   = !empty($produk['gambar']);
$gambarList  = $hasGambar ? $produk['gambar'] : [];
?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/produk_detail.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="produk-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">

        <!-- Back Button Row -->
        <div class="row mb-4">
            <div class="col-12 reveal-up">
                <a href="javascript:history.back()" class="btn btn-outline-success btn-sm mb-2 rounded-pill px-3 fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row mb-4">
            <div class="col-12 reveal-up delay-100">
                <!-- Detail Card -->
                <div class="card mb-4 shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="bi bi-box-seam fs-5"></i>
                            </div>
                            <h5 class="card-title mb-0 fw-bold" style="color:#111827;">Informasi Produk</h5>
                        </div>
                        
                        <div class="row g-4 g-xl-5 align-items-stretch">
                            <!-- Foto Utama -->
                            <div class="col-md-5 col-lg-4 d-flex flex-column">
                                <?php if (!empty($produk['gambar'])): ?>
                                    <a href="<?= base_url($produk['gambar'][0]['gambar_path']) ?>" target="_blank" class="produk-foto-item shadow-sm w-100 h-100 d-block" style="border: 1px solid #f1f5f9;">
                                        <img src="<?= base_url($produk['gambar'][0]['gambar_path']) ?>" alt="<?= esc($produk['nama_produk']) ?>"
                                             onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'no-photo-box\'><i class=\'bi bi-images\'></i><span>Foto tidak tersedia</span></div>'" style="object-fit: cover; width: 100%; height: 100%;">
                                    </a>
                                <?php else: ?>
                                    <div class="no-photo-box w-100 h-100">
                                        <i class="bi bi-box-seam"></i>
                                        <span class="small text-muted">Belum ada foto produk</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Info Produk -->
                            <div class="col-md-7 col-lg-8 d-flex flex-column">
                                <h3 class="fw-bold mb-2 text-break" style="color:#1e293b; font-size: clamp(1.4rem, 4vw, 2rem);"><?= esc($produk['nama_produk']) ?></h3>
                                
                                <?php if ($produk['harga']): ?>
                                <div class="mb-4">
                                    <span class="fs-3 fw-bold" style="color:#16a34a;">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></span>
                                </div>
                                <?php else: ?>
                                <div class="mb-4 text-muted fst-italic">Harga tidak ditentukan</div>
                                <?php endif; ?>

                                <!-- Info Grid -->
                                <div class="row g-4 mb-4 bg-light rounded-4 p-3 border border-light flex-grow-0">
                                    <!-- Full Width Toko -->
                                    <div class="col-12">
                                        <div class="info-label">Toko</div>
                                        <div class="info-value">
                                            <a href="<?= base_url('/umkm/' . $umkm['id']) ?>" class="text-success text-decoration-none">
                                                <i class="bi bi-shop me-1"></i> <?= esc($umkm['nama_toko']) ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="info-label">Pemilik</div>
                                        <div class="info-value"><?= esc($pemilikText ?? '-') ?></div>
                                    </div>
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="info-label">Kontak Toko</div>
                                        <div class="info-value"><?= esc($umkm['kontak'] ?? '-') ?></div>
                                    </div>
                                </div>
                                
                                <!-- Guest Action Buttons -->
                                <div class="d-flex flex-column flex-sm-row gap-3 mt-auto">
                                    <?php if (!empty($umkm['kontak'])): ?>
                                    <?php
                                    $kontak = preg_replace('/\D/', '', $umkm['kontak']);
                                    if (substr($kontak, 0, 1) === '0') { $kontak = '62' . substr($kontak, 1); }
                                    $waLink = "https://wa.me/{$kontak}?text=" . urlencode("Halo, saya tertarik dengan produk *{$produk['nama_produk']}* dari toko {$umkm['nama_toko']}");
                                    ?>
                                    <a href="<?= $waLink ?>" target="_blank" class="btn btn-success rounded-pill px-4 py-2 fw-medium flex-grow-1 d-flex align-items-center justify-content-center gap-2 shadow-sm" style="font-size: 1.05rem;">
                                        <i class="bi bi-whatsapp fs-5"></i> Hubungi Penjual
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('/umkm/' . $umkm['id']) ?>" class="btn btn-outline-success rounded-pill px-4 py-2 fw-medium flex-grow-1 d-flex align-items-center justify-content-center gap-2" style="font-size: 1.05rem;">
                                        <i class="bi bi-shop fs-5"></i> Kunjungi Toko
                                    </a>
                                </div>

                            </div>

                            <?php if (!empty($produk['deskripsi'])): ?>
                            <div class="col-12 mt-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-text-paragraph text-success fs-5"></i>
                                    <span class="info-label mb-0" style="font-size: 0.9rem;">Deskripsi Produk</span>
                                </div>
                                <div class="text-dark p-4 bg-light rounded-4 text-break" style="line-height:1.8; font-size: 0.95rem;">
                                    <?= nl2br(esc($produk['deskripsi'])) ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Galeri Foto -->
                <?php if (!empty($produk['gambar']) && count($produk['gambar']) > 1): ?>
                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="bi bi-images fs-5"></i>
                            </div>
                            <h5 class="card-title mb-0 fw-bold" style="color:#111827;">Semua Foto Produk</h5>
                        </div>
                        <div class="produk-foto-grid">
                            <?php foreach ($produk['gambar'] as $gIdx => $g): ?>
                            <a href="<?= base_url($g['gambar_path']) ?>" target="_blank" class="produk-foto-item border d-block">
                                <img src="<?= base_url($g['gambar_path']) ?>" alt="Foto <?= $gIdx + 1 ?>"
                                     onerror="this.onerror=null;this.style.background='#f1f5f9';this.style.display='none';">
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</section>

<?= $this->section('scripts') ?>
<script>
// Scroll Reveal
const revealEls = document.querySelectorAll('.reveal-up');
const observer  = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); observer.unobserve(e.target); } });
}, { threshold: 0.1 });
revealEls.forEach(el => observer.observe(el));
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
