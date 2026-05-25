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
/* ── Skeleton ── */
.skeleton-produk-card {
    border-radius: 16px;
    background: #fff;
    border: 1px solid #edf2f7;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.skeleton-produk-img {
    width: 100%;
    height: 160px;
    background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
.skeleton-produk-body {
    padding: 12px;
}
.skeleton-line {
    height: 12px;
    border-radius: 6px;
    background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    margin-bottom: 8px;
}
.skeleton-line.short { width: 60%; }
@keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
/* ── Pagination ── */
.custom-pagination { text-align: center; }
.pagination-wrapper { display: inline-flex; align-items: center; gap: 6px; }
.pagination-btn {
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #475569;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s;
    cursor: pointer;
}
.pagination-btn:hover:not(.disabled):not(.active) {
    background: #f0fdf4;
    border-color: #16a34a;
    color: #16a34a;
}
.pagination-btn.active {
    background: #16a34a;
    border-color: #16a34a;
    color: #fff;
}
.pagination-btn.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    background: #f8fafc;
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

        <div class="row g-4 mb-4">
            <!-- Left: Store Image -->
            <div class="col-lg-6 reveal-up delay-100">
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden h-100">
                    <div class="position-relative d-flex align-items-end p-4" style="height: 100%; min-height: 350px; background-color: #f8fafc;">
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
                        <div class="store-header-content w-100 position-absolute bottom-0 start-0 p-3 p-md-4 d-flex justify-content-center">
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

            <!-- Right: Contact & Map (Horizontal) -->
            <div class="col-lg-6 reveal-up delay-200 d-flex flex-column">
                <div class="row g-3 flex-grow-1">
                    <!-- Contact -->
                    <div class="col-md-6 d-flex flex-column">
                        <div class="card border-0 rounded-4 shadow-sm flex-grow-1">
                            <div class="card-body p-4 d-flex flex-column">
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
                                
                                <div class="mt-auto pt-3">
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $umkm['kontak']) ?>" target="_blank" class="btn w-100 rounded-pill fw-semibold py-2 d-flex align-items-center justify-content-center gap-2" style="background-color: #2E7D32; border-color: #2E7D32; color: #ffffff;">
                                        <i class="bi bi-whatsapp fs-5"></i> Hubungi Penjual
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="col-md-6 d-flex flex-column">
                        <?php if (!empty($umkm['maps_embed_url'])): ?>
                        <div class="card border-0 rounded-4 shadow-sm overflow-hidden flex-grow-1 p-0">
                            <iframe src="<?= esc($umkm['maps_embed_url']) ?>" class="w-100 h-100" style="border:0; min-height: 250px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <?php else: ?>
                        <div class="card border-0 rounded-4 shadow-sm flex-grow-1 d-flex align-items-center justify-content-center bg-light text-muted">
                            <div class="text-center">
                                <i class="bi bi-geo-alt-fill fs-1 d-block mb-2 opacity-50"></i>
                                <span class="small">Peta tidak tersedia</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Content (Full Width) -->
            <div class="col-12 reveal-up delay-100">

                <!-- Description & E-commerce -->
                <?php if (!empty($umkm['deskripsi']) || !empty($ecommerce)): ?>
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0" style="width: 40px; height: 40px; background: rgba(46,125,50,0.1); color: #2E7D32;">
                                <i class="bi bi-info-circle-fill fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: #111827;">Tentang Toko</h5>
                        </div>
                        
                        <?php if (!empty($umkm['deskripsi'])): ?>
                        <article class="article-content" style="color: #4b5563; line-height: 1.8; font-size: 1.05rem;">
                            <?= nl2br(esc($umkm['deskripsi'])) ?>
                        </article>
                        <?php endif; ?>

                        <!-- E-commerce Links at the bottom of description -->
                        <?php if (!empty($ecommerce)): ?>
                        <?php if (!empty($umkm['deskripsi'])): ?><hr class="my-4 border-light"><?php endif; ?>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <span class="fw-medium text-muted"><i class="bi bi-cart-check-fill text-success me-1"></i> Tersedia di:</span>
                            <?php foreach ($ecommerce as $e): ?>
                            <a href="<?= esc($e['url']) ?>" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-4 py-2 fw-medium d-flex align-items-center gap-2 transition-all shadow-sm">
                                <i class="bi bi-shop"></i> <?= esc($e['platform'] ?: 'Toko Online') ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Product Grid -->
                <div id="produkSection" class="d-flex align-items-center gap-2 mt-5 mb-4">
                    <div style="width: 5px; height: 28px; background-color: #2E7D32; border-radius: 4px;"></div>
                    <h4 class="fw-bold mb-0" style="color: #111827;">Daftar Produk</h4>
                </div>

                <!-- Skeleton loader -->
                <div id="produkSkeletonLoader" class="row g-3">
                    <?php for ($s = 0; $s < 8; $s++): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="skeleton-produk-card">
                            <div class="skeleton-produk-img"></div>
                            <div class="skeleton-produk-body">
                                <div class="skeleton-line"></div>
                                <div class="skeleton-line short"></div>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- Actual product container -->
                <div id="produkContainer" class="row g-3" style="display:none;"></div>

                <!-- Empty state -->
                <div id="produkEmpty" style="display:none;">
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm text-muted mb-4">
                        <i class="bi bi-box-seam d-block mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mb-0">Belum ada produk yang ditambahkan ke toko ini.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div id="produkPagination" class="custom-pagination mt-5" style="display:none;"></div>

            </div>
        </div>

    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Reveal-up observer ── */
    const revealObserver = new IntersectionObserver(function (entries, obs) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    document.querySelectorAll('.reveal-up').forEach(el => revealObserver.observe(el));

    /* ── CSRF helpers (standard project pattern) ── */
    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    function escapeHtml(str) {
        return (str || '').toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatRupiah(num) {
        return 'Rp ' + parseInt(num || 0).toLocaleString('id-ID');
    }

    /* ── State ── */
    let currentPage = 1;
    let totalPages  = 1;
    let isLoading   = false;
    const umkmId    = <?= (int)$umkm['id'] ?>;

    const skeletonLoader  = document.getElementById('produkSkeletonLoader');
    const produkContainer = document.getElementById('produkContainer');
    const produkEmpty     = document.getElementById('produkEmpty');
    const produkPagination = document.getElementById('produkPagination');

    /* ── Load products via AJAX POST ── */
    function loadProduk(page) {
        if (isLoading) return;
        isLoading = true;

        skeletonLoader.style.display  = '';
        produkContainer.style.display = 'none';
        produkContainer.innerHTML     = '';
        produkEmpty.style.display     = 'none';
        produkPagination.style.display = 'none';

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch(`<?= base_url('/umkm/') ?>${umkmId}/produk-api`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
            skeletonLoader.style.display = 'none';

            if (!response.success || !response.data || response.data.length === 0) {
                produkEmpty.style.display = 'block';
                return;
            }

            let html = '';
            response.data.forEach(function (p) {
                const imgHtml = p.gambar_url
                    ? `<img src="${escapeHtml(p.gambar_url)}"
                           class="d-block w-100 h-100 object-fit-cover"
                           loading="lazy"
                           alt="${escapeHtml(p.nama_produk)}"
                           onerror="this.onerror=null;this.style.opacity='0.2';">`
                    : `<div class="umkm-placeholder"><i class="bi bi-box"></i></div>`;

                const deskripsiHtml = p.deskripsi
                    ? `<p class="small text-muted mb-3 flex-grow-1"
                          style="font-size: 0.8rem; line-height: 1.4;
                                 display: -webkit-box; -webkit-line-clamp: 2;
                                 -webkit-box-orient: vertical; overflow: hidden;">
                          ${escapeHtml(p.deskripsi)}
                       </p>`
                    : `<div class="flex-grow-1"></div>`;

                html += `
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="${escapeHtml(p.detail_url)}"
                       class="umkm-card h-100 text-decoration-none d-flex flex-column"
                       style="color:inherit;">
                        <div class="umkm-img-wrapper">${imgHtml}</div>
                        <div class="umkm-card-body">
                            <h6 class="umkm-title text-truncate d-block w-100 mb-2 fw-bold"
                                style="font-size: 0.95rem;"
                                title="${escapeHtml(p.nama_produk)}">
                                ${escapeHtml(p.nama_produk)}
                            </h6>
                            ${deskripsiHtml}
                            <div class="umkm-price-row mt-auto">
                                <span class="umkm-price">${escapeHtml(formatRupiah(p.harga))}</span>
                                <span class="umkm-action-icon"><i class="bi bi-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                </div>`;
            });

            produkContainer.innerHTML   = html;
            produkContainer.style.display = '';

            currentPage = response.page;
            totalPages  = response.total_pages;

            renderPagination();
            if (totalPages > 1) produkPagination.style.display = 'block';
        })
        .catch(() => {
            skeletonLoader.style.display = 'none';
            produkEmpty.style.display    = 'block';
        })
        .finally(() => {
            isLoading = false;
        });
    }

    /* ── Render pagination ── */
    function renderPagination() {
        produkPagination.innerHTML = '';
        if (totalPages <= 1) {
            produkPagination.style.display = 'none';
            return;
        }

        let html = '<div class="pagination-wrapper">';

        html += currentPage > 1
            ? `<button class="pagination-btn" data-page="${currentPage - 1}"><i class="bi bi-chevron-left"></i></button>`
            : `<button class="pagination-btn disabled" disabled><i class="bi bi-chevron-left"></i></button>`;

        let startPage = Math.max(1, currentPage - 1);
        let endPage   = Math.min(totalPages, startPage + 2);
        if (endPage - startPage < 2) startPage = Math.max(1, endPage - 2);

        for (let i = startPage; i <= endPage; i++) {
            html += i === currentPage
                ? `<button class="pagination-btn active">${i}</button>`
                : `<button class="pagination-btn" data-page="${i}">${i}</button>`;
        }

        html += currentPage < totalPages
            ? `<button class="pagination-btn" data-page="${currentPage + 1}"><i class="bi bi-chevron-right"></i></button>`
            : `<button class="pagination-btn disabled" disabled><i class="bi bi-chevron-right"></i></button>`;

        html += '</div>';
        produkPagination.innerHTML = html;
    }

    /* ── Pagination click ── */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.pagination-btn:not(.disabled):not(.active)');
        if (btn && produkPagination.contains(btn)) {
            const page = parseInt(btn.getAttribute('data-page'));
            if (page && page !== currentPage) {
                loadProduk(page);
                document.getElementById('produkSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });

    /* ── Initial load ── */
    loadProduk(1);
});
</script>
<?= $this->endSection() ?>
