<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/pariwisata.css?v=' . time()) ?>">
<style>
.pariwisata-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #334155;
}
.pariwisata-content p {
    margin-bottom: 1.5rem;
}

/* ── Skeleton ── */
.skeleton-foto-card {
    border-radius: 16px;
    background: #fff;
    border: 1px solid #edf2f7;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.skeleton-foto-img {
    width: 100%;
    height: 180px;
    background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
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

/* ── Foto card hover ── */
#gambarContainer .foto-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
#gambarContainer .foto-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">
        
        <div class="row">
            <div class="col-12 reveal-up">
                <a href="<?= base_url('/pariwisata') ?>" class="btn btn-outline-success btn-sm mb-4 rounded-pill px-3 fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Pariwisata
                </a>
                
                <h1 class="fw-bold mb-3" style="color: #111827; font-size: 2.5rem; line-height: 1.3;"><?= esc($item['nama_tempat']) ?></h1>
                
                <div class="d-flex align-items-center gap-3 text-muted mb-4 pb-3 border-bottom">
                    <?php if ($item['alamat']): ?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-success"></i>
                        <span><?= esc($item['alamat']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Hero thumbnail (thumbnail statis dari field utama) -->
                <?php if (!empty($item['thumbnail'])): ?>
                <div style="border-radius: 24px; overflow: hidden; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                    <img src="<?= base_url($item['thumbnail']) ?>"
                         class="img-fluid w-100"
                         style="max-height: 500px; object-fit: cover;"
                         alt="<?= esc($item['nama_tempat']) ?>"
                         loading="lazy">
                </div>
                <?php endif; ?>

                <article class="pariwisata-content">
                    <?php if ($item['deskripsi']): ?>
                        <?= nl2br(esc($item['deskripsi'])) ?>
                    <?php else: ?>
                        <p class="text-muted fst-italic">Belum ada deskripsi untuk destinasi pariwisata ini.</p>
                    <?php endif; ?>
                </article>

                <?php if (!empty($item['maps_embed_url'])): ?>
                    <h4 class="fw-bold mt-5 mb-4">Lokasi di Peta</h4>
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                        <iframe src="<?= esc($item['maps_embed_url']) ?>" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                <?php endif; ?>

                <!-- ── Galeri Foto header ── -->
                <div id="gambarSection" class="d-flex align-items-center gap-2 mt-5 mb-4">
                    <div style="width: 5px; height: 28px; background-color: #2E7D32; border-radius: 4px;"></div>
                    <h4 class="fw-bold mb-0">Galeri Foto</h4>
                </div>

                <!-- Skeleton loader -->
                <div id="gambarSkeletonLoader" class="row g-3">
                    <?php for ($s = 0; $s < 8; $s++): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="skeleton-foto-card">
                            <div class="skeleton-foto-img"></div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- Actual gambar container -->
                <div id="gambarContainer" class="row g-3" style="display:none;"></div>

                <!-- Empty state -->
                <div id="gambarEmpty" style="display:none;">
                    <div class="text-center text-muted py-5 mt-2 bg-light rounded-4">
                        <i class="bi bi-images d-block mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mb-0">Belum ada galeri foto untuk destinasi ini.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div id="gambarPagination" class="custom-pagination mt-5" style="display:none;"></div>

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

    /* ── State ── */
    let currentPage = 1;
    let totalPages  = 1;
    let isLoading   = false;
    const pariwisataId = <?= (int)$item['id'] ?>;

    const skeletonLoader   = document.getElementById('gambarSkeletonLoader');
    const gambarContainer  = document.getElementById('gambarContainer');
    const gambarEmpty      = document.getElementById('gambarEmpty');
    const gambarPagination = document.getElementById('gambarPagination');
    const gambarSection    = document.getElementById('gambarSection');

    /* ── Load gambar via AJAX POST ── */
    function loadGambar(page) {
        if (isLoading) return;
        isLoading = true;

        skeletonLoader.style.display   = '';
        gambarContainer.style.display  = 'none';
        gambarContainer.innerHTML      = '';
        gambarEmpty.style.display      = 'none';
        gambarPagination.style.display = 'none';

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch(`<?= base_url('/pariwisata/') ?>${pariwisataId}/gambar-api`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
            skeletonLoader.style.display = 'none';

            if (!response.success || !response.data || response.data.length === 0) {
                gambarEmpty.style.display = 'block';
                return;
            }

            let html = '';
            response.data.forEach(function (g) {
                html += `
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="${escapeHtml(g.gambar_path)}" target="_blank"
                       class="foto-card card border-0 shadow-sm rounded-4 overflow-hidden text-decoration-none d-block">
                        <img src="${escapeHtml(g.gambar_path)}"
                             class="card-img-top w-100 object-fit-cover"
                             style="height: 180px;"
                             alt="Galeri Foto"
                             loading="lazy"
                             onerror="this.onerror=null;this.style.display='none';">
                    </a>
                </div>`;
            });

            gambarContainer.innerHTML  = html;
            gambarContainer.style.display = '';

            currentPage = response.page;
            totalPages  = response.total_pages;

            renderPagination();
            if (totalPages > 1) gambarPagination.style.display = 'block';
        })
        .catch(() => {
            skeletonLoader.style.display = 'none';
            gambarEmpty.style.display    = 'block';
        })
        .finally(() => {
            isLoading = false;
        });
    }

    /* ── Render pagination ── */
    function renderPagination() {
        gambarPagination.innerHTML = '';
        if (totalPages <= 1) {
            gambarPagination.style.display = 'none';
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
        gambarPagination.innerHTML = html;
    }

    /* ── Pagination click ── */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.pagination-btn:not(.disabled):not(.active)');
        if (btn && gambarPagination.contains(btn)) {
            const page = parseInt(btn.getAttribute('data-page'));
            if (page && page !== currentPage) {
                loadGambar(page);
                gambarSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });

    /* ── Initial load ── */
    loadGambar(1);
});
</script>
<?= $this->endSection() ?>
