<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<style>

.produk-foto-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1rem;
}
.produk-foto-item {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,.08);
    aspect-ratio: 1 / 1;
    background: #f1f5f9;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.produk-foto-item:hover {
    transform: scale(1.03);
    box-shadow: 0 8px 16px -2px rgba(0,0,0,.14);
}
.produk-foto-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
}
.no-photo-box {
    aspect-ratio: 1/1;
    background: #f8fafc;
    border-radius: 12px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: #94a3b8; gap: .5rem;
    border: 2px dashed #e2e8f0;
}
.no-photo-box i { font-size: 3rem; }
.info-label { font-size: .8rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .6px; margin-bottom: .2rem; }
.info-value { font-size: 1rem; font-weight: 600; color: #1e293b; }
/* Lightbox */
.lightbox-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.88);
    z-index: 9999;
    align-items: center; justify-content: center;
}
.lightbox-overlay.active { display: flex; }
.lightbox-img {
    max-width: 90vw;
    max-height: 85vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 20px 60px rgba(0,0,0,.5);
}
.lightbox-close {
    position: absolute; top: 1rem; right: 1.25rem;
    background: rgba(255,255,255,.15); border: none; color: #fff;
    font-size: 1.8rem; border-radius: 50%; width: 44px; height: 44px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .2s;
}
.lightbox-close:hover { background: rgba(255,255,255,.3); }
.lightbox-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,.15); border: none; color: #fff;
    font-size: 1.4rem; border-radius: 50%; width: 44px; height: 44px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .2s;
}
.lightbox-nav:hover { background: rgba(255,255,255,.3); }
.lightbox-prev { left: 1rem; }
.lightbox-next { right: 1rem; }
</style>

<!-- Page Header -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size:.75rem;letter-spacing:2px;color:#64748b;">
            <span style="display:inline-block;width:24px;height:2px;background:#cbd5e1;margin-bottom:4px;margin-right:8px;"></span>
            KELOLA UMKM
        </div>
        <h2 class="fw-bold text-dark mb-1" style="font-size:2.2rem;letter-spacing:-.5px;">
            Detail <span style="color:#15803d;">Produk</span>
        </h2>
        <p class="text-muted fs-6 mb-0"><?= esc($umkm['nama_toko']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <?php if ($umkm['status'] === 'approved'): ?>
        <a href="<?= base_url('/staff/umkm/produk/' . $produk['id'] . '/edit') ?>" class="btn btn-success">
            <i class="bi bi-pencil-square me-1"></i>Edit Produk
        </a>
        <?php endif; ?>
        <a href="<?= base_url('/staff/umkm/' . $umkm['id']) ?>" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Toko
        </a>
    </div>
</div>

<!-- Detail Card -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <h5 class="card-title mb-4 fw-bold">Informasi Produk</h5>
        <div class="row g-4 align-items-start">
        <!-- Foto Utama -->
        <div class="col-md-4">
            <?php if (!empty($produk['gambar'])): ?>
                <div class="produk-foto-item" onclick="openLightbox(0)" style="max-width:280px;margin:0 auto;">
                    <img src="<?= base_url($produk['gambar'][0]['gambar_path']) ?>" alt="<?= esc($produk['nama_produk']) ?>"
                         onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'no-photo-box\'><i class=\'bi bi-images\'></i><span>Foto tidak tersedia</span></div>'">
                </div>
                <?php if (count($produk['gambar']) > 1): ?>
                <div class="text-center mt-2 small text-muted"><i class="bi bi-images me-1"></i><?= count($produk['gambar']) ?> foto tersedia · Klik untuk lihat semua</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-photo-box" style="max-width:280px;margin:0 auto;">
                    <i class="bi bi-box-seam"></i>
                    <span class="small text-muted">Belum ada foto produk</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Info Produk -->
        <div class="col-md-8">
            <h3 class="fw-bold mb-3 text-break" style="color:#1e293b;"><?= esc($produk['nama_produk']) ?></h3>
            
            <?php if ($produk['harga']): ?>
            <div class="mb-3">
                <span class="fs-4 fw-bold" style="color:#16a34a;">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></span>
            </div>
            <?php else: ?>
            <div class="mb-3 text-muted fst-italic small">Harga tidak ditentukan</div>
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="info-label">Toko</div>
                    <div class="info-value">
                        <a href="<?= base_url('/staff/umkm/' . $umkm['id']) ?>" class="text-success text-decoration-none">
                            <?= esc($umkm['nama_toko']) ?>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="info-label">Pemilik</div>
                    <div class="info-value"><?= esc($pemilik ?? '-') ?></div>
                </div>
                <div class="col-sm-6">
                    <div class="info-label">Kontak Toko</div>
                    <div class="info-value"><?= esc($umkm['kontak'] ?? '-') ?></div>
                </div>
                <div class="col-sm-6">
                    <div class="info-label">Ditambahkan</div>
                    <div class="info-value"><?= date('d M Y', strtotime($produk['created_at'])) ?></div>
                </div>
                <?php if ($produk['updated_at'] && $produk['updated_at'] !== $produk['created_at']): ?>
                <div class="col-sm-6">
                    <div class="info-label">Terakhir Diperbarui</div>
                    <div class="info-value"><?= date('d M Y', strtotime($produk['updated_at'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($produk['deskripsi'])): ?>
        <div class="col-12 mt-5">
            <div class="info-label mb-2">Deskripsi Produk</div>
            <div class="text-dark p-3 bg-light rounded border border-light text-break" style="line-height:1.7;"><?= nl2br(esc($produk['deskripsi'])) ?></div>
        </div>
        <?php endif; ?>
    </div>
    </div>
</div>

<!-- Galeri Foto -->
<?php if (!empty($produk['gambar']) && count($produk['gambar']) > 1): ?>
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="card-title fw-bold mb-4">Semua Foto Produk</h5>
        <div class="produk-foto-grid">
            <?php foreach ($produk['gambar'] as $gIdx => $g): ?>
            <div class="produk-foto-item" onclick="openLightbox(<?= $gIdx ?>)">
                <img src="<?= base_url($g['gambar_path']) ?>" alt="Foto <?= $gIdx + 1 ?>"
                     onerror="this.onerror=null;this.style.background='#f1f5f9';this.style.display='none';">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php elseif (!empty($produk['gambar'])): ?>
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="card-title fw-bold mb-4">Foto Produk</h5>
        <div class="produk-foto-grid">
            <div class="produk-foto-item" onclick="openLightbox(0)">
                <img src="<?= base_url($produk['gambar'][0]['gambar_path']) ?>" alt="Foto Produk"
                     onerror="this.onerror=null;this.style.background='#f1f5f9';this.style.display='none';">
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Lightbox -->
<div class="lightbox-overlay" id="lightboxOverlay" onclick="closeLightbox(event)">
    <button class="lightbox-close" onclick="closeLightbox()"><i class="bi bi-x"></i></button>
    <?php if (!empty($produk['gambar']) && count($produk['gambar']) > 1): ?>
    <button class="lightbox-nav lightbox-prev" onclick="event.stopPropagation();navLightbox(-1)"><i class="bi bi-chevron-left"></i></button>
    <button class="lightbox-nav lightbox-next" onclick="event.stopPropagation();navLightbox(1)"><i class="bi bi-chevron-right"></i></button>
    <?php endif; ?>
    <img class="lightbox-img" id="lightboxImg" src="" alt="Foto Produk" onclick="event.stopPropagation();">
</div>

<script>
const lightboxImages = [
    <?php foreach ($produk['gambar'] ?? [] as $g): ?>
    '<?= base_url($g['gambar_path']) ?>',
    <?php endforeach; ?>
];
let lightboxCurrent = 0;

function openLightbox(idx) {
    lightboxCurrent = idx;
    document.getElementById('lightboxImg').src = lightboxImages[idx];
    document.getElementById('lightboxOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox(e) {
    if (e && e.target !== document.getElementById('lightboxOverlay') && !e.target.classList.contains('lightbox-close')) {
        return;
    }
    document.getElementById('lightboxOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

function navLightbox(dir) {
    lightboxCurrent = (lightboxCurrent + dir + lightboxImages.length) % lightboxImages.length;
    document.getElementById('lightboxImg').src = lightboxImages[lightboxCurrent];
}

document.addEventListener('keydown', function(e) {
    if (!document.getElementById('lightboxOverlay').classList.contains('active')) return;
    if (e.key === 'Escape') { document.getElementById('lightboxOverlay').classList.remove('active'); document.body.style.overflow = ''; }
    if (e.key === 'ArrowLeft') navLightbox(-1);
    if (e.key === 'ArrowRight') navLightbox(1);
});
</script>
<?= $this->endSection() ?>
