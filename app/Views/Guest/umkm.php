<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/umkm.css?v=' . time()) ?>">
<style>
/* Search box — same style as Berita & Galeri */
.umkm-search-box {
    background: #ffffff;
    border-radius: 50px;
    padding: 6px 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: 1px solid #edf2f7;
    margin: 0 auto 3rem auto;
    max-width: 600px;
    display: flex;
    align-items: center;
    transition: box-shadow 0.3s ease;
}
.umkm-search-box:focus-within {
    box-shadow: 0 4px 20px rgba(46, 125, 50, 0.15);
    border-color: #a5d6a7;
}
.umkm-search-box input {
    border: none;
    box-shadow: none !important;
    background: transparent;
    padding: 10px 15px;
    font-size: 0.95rem;
    color: #1e293b;
    flex: 1;
    outline: none;
}
.umkm-search-box input::placeholder { color: #94a3b8; }
.umkm-search-box .btn-search {
    border-radius: 50px;
    padding: 8px 24px;
    background: #2E7D32;
    color: #fff;
    border: none;
    font-weight: 600;
    transition: background 0.2s ease;
    white-space: nowrap;
    cursor: pointer;
}
.umkm-search-box .btn-search:hover { background: #1b5e20; }
.umkm-search-box .btn-reset {
    background: #f1f5f9;
    color: #64748b;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 4px;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    flex-shrink: 0;
}
.umkm-search-box .btn-reset:hover { background: #e2e8f0; color: #ef4444; }

/* Skeleton */
.skeleton-umkm-card {
    border-radius: 24px;
    background: #fff;
    border: 1px solid #edf2f7;
    overflow: hidden;
    height: 100%;
}
.skeleton-img-block {
    width: 100%;
    aspect-ratio: 16 / 10;
    background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
.skeleton-body { padding: 1.25rem; }
.skeleton-line {
    height: 12px;
    border-radius: 6px;
    background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    margin-bottom: 10px;
}
@keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Pagination */
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
    <div class="container pb-5">

        <div class="profil-desa-header mt-5 mb-4 text-center reveal-up active">
            <span class="profil-desa-subtitle">Informasi UMKM</span>
            <h2 class="profil-desa-title">UMKM <span>Desa</span></h2>
        </div>

        <!-- Search Box -->
        <div class="umkm-search-box reveal-up active delay-100">
            <i class="bi bi-search text-muted ms-2"></i>
            <input type="text" id="searchInput" placeholder="Cari nama toko UMKM...">
            <button type="button" class="btn-reset" id="clearSearchBtn" style="display:none;" title="Hapus pencarian">
                <i class="bi bi-x-lg"></i>
            </button>
            <button type="button" class="btn-search" id="btnCari">Cari</button>
        </div>

        <!-- Data Container -->
        <div id="umkmContainer" class="row g-4">
            <!-- Loaded via AJAX -->
        </div>

        <!-- Skeleton Loader -->
        <div id="loadingIndicator" class="row g-4">
            <?php for ($s = 0; $s < 9; $s++): ?>
            <div class="col-md-6 col-lg-4">
                <div class="skeleton-umkm-card">
                    <div class="skeleton-img-block"></div>
                    <div class="skeleton-body">
                        <div class="skeleton-line" style="width:70%;"></div>
                        <div class="skeleton-line" style="width:45%;"></div>
                        <div class="skeleton-line" style="width:85%;"></div>
                        <div class="skeleton-line" style="width:40%;"></div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Empty State -->
        <div id="emptyMessage" class="text-center text-muted py-5" style="display:none;">
            <i class="bi bi-shop fs-1 d-block mb-3" style="color:#cbd5e1;"></i>
            <p class="mb-0 fs-5">Belum ada UMKM yang terdaftar.</p>
        </div>

        <!-- No Results State -->
        <div id="noResultsMessage" class="text-center text-muted py-5" style="display:none;">
            <i class="bi bi-search fs-1 d-block mb-3" style="color:#cbd5e1;"></i>
            <p class="mb-0 fs-5">Tidak ada UMKM yang cocok dengan pencarian.</p>
        </div>

        <!-- Pagination -->
        <div id="customPagination" class="custom-pagination mt-5" style="display:none;"></div>

    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentPage   = 1;
    let isLoading     = false;
    let currentSearch = '';
    let totalPages    = 1;
    const limit       = 9;

    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    const umkmContainer    = document.getElementById('umkmContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const customPagination = document.getElementById('customPagination');
    const emptyMessage     = document.getElementById('emptyMessage');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const searchInput      = document.getElementById('searchInput');
    const clearSearchBtn   = document.getElementById('clearSearchBtn');
    const btnCari          = document.getElementById('btnCari');

    function escapeHtml(str) {
        return (str || '').toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function truncate(str, max) {
        if (!str) return '';
        return str.length > max ? str.substring(0, max) + '...' : str;
    }

    function loadData(page, search) {
        if (isLoading) return;
        isLoading = true;

        loadingIndicator.style.display = '';
        umkmContainer.innerHTML        = '';
        customPagination.style.display = 'none';
        emptyMessage.style.display     = 'none';
        noResultsMessage.style.display = 'none';

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append('limit', limit);
        formData.append('search', search);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch('<?= base_url('/umkm/api') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
            loadingIndicator.style.display = 'none';

            if (!response.success) {
                noResultsMessage.style.display = 'block';
                return;
            }

            if (!response.data || response.data.length === 0) {
                if (search !== '') {
                    noResultsMessage.style.display = 'block';
                } else {
                    emptyMessage.style.display = 'block';
                }
                return;
            }

            let html = '';
            response.data.forEach(function (item) {
                const imgHtml = item.cover_url
                    ? `<img src="${escapeHtml(item.cover_url)}"
                            alt="${escapeHtml(item.nama_toko)}"
                            loading="lazy"
                            onerror="this.onerror=null;this.outerHTML='<div class=\\'umkm-placeholder\\'><i class=\\'bi bi-shop\\'></i><span>Foto Toko</span></div>'">`
                    : `<div class="umkm-placeholder"><i class="bi bi-shop"></i><span>Belum ada foto</span></div>`;

                const alamatHtml = item.alamat
                    ? `<div class="d-flex align-items-start gap-2 text-muted" style="font-size:0.85rem;">
                            <i class="bi bi-geo-alt mt-1 flex-shrink-0" style="color:#10b981;"></i>
                            <span>${escapeHtml(truncate(item.alamat, 60))}</span>
                       </div>`
                    : '';

                const kontakHtml = item.kontak
                    ? `<div class="d-flex align-items-center gap-2 text-muted" style="font-size:0.85rem;">
                            <i class="bi bi-telephone flex-shrink-0" style="color:#10b981;"></i>
                            <span>${escapeHtml(item.kontak)}</span>
                       </div>`
                    : '';

                html += `
                <div class="col-md-6 col-lg-4 reveal-up">
                    <div class="umkm-list-card">
                        <div class="umkm-list-img-wrapper">
                            ${imgHtml}
                        </div>
                        <div class="umkm-list-card-body">
                            <h5 class="umkm-list-title">${escapeHtml(item.nama_toko)}</h5>
                            <div class="d-flex flex-column gap-2 mb-3 mt-1">
                                ${alamatHtml}
                                ${kontakHtml}
                            </div>
                            <div class="umkm-excerpt">${escapeHtml(truncate(item.deskripsi, 100))}</div>
                            <a href="<?= base_url('/umkm/') ?>${item.id}" class="umkm-read-more stretched-link mt-3">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>`;
            });

            umkmContainer.innerHTML = html;

            currentPage   = page;
            totalPages    = response.total_pages;
            currentSearch = search;

            renderPagination();
            if (totalPages > 1) customPagination.style.display = 'block';

            /* Observe newly inserted cards for reveal-up animation */
            umkmContainer.querySelectorAll('.reveal-up').forEach(el => revealObserver.observe(el));
        })
        .catch(() => {
            loadingIndicator.style.display = 'none';
            noResultsMessage.style.display = 'block';
        })
        .finally(() => {
            isLoading = false;
        });
    }

    function renderPagination() {
        customPagination.innerHTML = '';
        if (totalPages <= 1) {
            customPagination.style.display = 'none';
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
        customPagination.innerHTML = html;
    }

    /* Pagination click */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.pagination-btn:not(.disabled):not(.active)');
        if (btn && customPagination.contains(btn)) {
            const page = parseInt(btn.getAttribute('data-page'));
            if (page && page !== currentPage) {
                loadData(page, currentSearch);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    });

    /* Search: hanya dieksekusi saat klik tombol Cari atau tekan Enter */
    function doSearch() {
        const val = searchInput.value.trim();
        loadData(1, val);
    }

    searchInput.addEventListener('input', function () {
        clearSearchBtn.style.display = this.value.length > 0 ? 'flex' : 'none';
    });

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
    });

    btnCari.addEventListener('click', doSearch);

    clearSearchBtn.addEventListener('click', function () {
        searchInput.value = '';
        this.style.display = 'none';
        loadData(1, '');
        searchInput.focus();
    });

    /* Reveal-up IntersectionObserver */
    const revealObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    /* Observe static elements yang sudah punya reveal-up */
    document.querySelectorAll('.reveal-up:not(.active)').forEach(el => revealObserver.observe(el));

    /* Initial load */
    loadData(1, '');
});
</script>
<?= $this->endSection() ?>
