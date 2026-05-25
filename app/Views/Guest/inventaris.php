<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/inventaris.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5">

        <div class="profil-desa-header mt-5 mb-4 text-center reveal-up active">
            <span class="profil-desa-subtitle">Transparansi Desa</span>
            <h2 class="profil-desa-title">Inventaris <span>Aset Desa</span></h2>
        </div>

        <!-- Search Box -->
        <div class="inv-search-box reveal-up active delay-100">
            <i class="bi bi-search text-muted ms-2"></i>
            <input type="text" id="searchInput" placeholder="Cari nama barang atau jenis...">
            <button type="button" class="btn-reset" id="clearSearchBtn" style="display:none;" title="Hapus pencarian">
                <i class="bi bi-x-lg"></i>
            </button>
            <button type="button" class="btn-search" id="btnCari">Cari</button>
        </div>

        <!-- Data Container -->
        <div id="inventarisContainer" class="row g-4">
            <!-- Loaded via AJAX -->
        </div>

        <!-- Skeleton Loader -->
        <div id="loadingIndicator" class="row g-4">
            <?php for ($s = 0; $s < 9; $s++): ?>
            <div class="col-md-6 col-lg-4">
                <div class="skeleton-inv-card">
                    <div class="skeleton-img-block"></div>
                    <div class="skeleton-body">
                        <div class="skeleton-line" style="width:70%;"></div>
                        <div class="skeleton-line" style="width:45%;"></div>
                        <div class="skeleton-line" style="width:60%; margin-top:1rem;"></div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Empty State -->
        <div id="emptyMessage" class="text-center text-muted py-5" style="display:none;">
            <i class="bi bi-box-seam fs-1 d-block mb-3" style="color:#cbd5e1;"></i>
            <p class="mb-0 fs-5">Belum ada data inventaris aset desa.</p>
        </div>

        <!-- No Results State -->
        <div id="noResultsMessage" class="text-center text-muted py-5" style="display:none;">
            <i class="bi bi-search fs-1 d-block mb-3" style="color:#cbd5e1;"></i>
            <p class="mb-0 fs-5">Tidak ada inventaris yang cocok dengan pencarian.</p>
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

    const inventarisContainer = document.getElementById('inventarisContainer');
    const loadingIndicator    = document.getElementById('loadingIndicator');
    const customPagination    = document.getElementById('customPagination');
    const emptyMessage        = document.getElementById('emptyMessage');
    const noResultsMessage    = document.getElementById('noResultsMessage');
    const searchInput         = document.getElementById('searchInput');
    const clearSearchBtn      = document.getElementById('clearSearchBtn');
    const btnCari             = document.getElementById('btnCari');

    function escapeHtml(str) {
        return (str || '').toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function loadData(page, search) {
        if (isLoading) return;
        isLoading = true;

        loadingIndicator.style.display  = '';
        inventarisContainer.innerHTML   = '';
        customPagination.style.display  = 'none';
        emptyMessage.style.display      = 'none';
        noResultsMessage.style.display  = 'none';

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append('limit', limit);
        formData.append('search', search);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch('<?= base_url('/inventaris/api') ?>', {
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
                const imgHtml = item.foto_url
                    ? `<img src="${escapeHtml(item.foto_url)}"
                            alt="${escapeHtml(item.nama_barang)}"
                            loading="lazy"
                            onerror="this.onerror=null;this.outerHTML='<div class=\\'inv-placeholder\\'><i class=\\'bi bi-image\\'></i></div>'">`
                    : `<div class="inv-placeholder"><i class="bi bi-image"></i></div>`;

                html += `
                <div class="col-md-6 col-lg-4 reveal-up">
                    <div class="inv-card">
                        <div class="inv-img-wrapper">
                            <div class="inv-status-pill ${escapeHtml(item.status_class)}">
                                ${escapeHtml(item.status)}
                            </div>
                            ${imgHtml}
                        </div>
                        <div class="inv-card-body">
                            <h5 class="inv-title">${escapeHtml(item.nama_barang)}</h5>
                            <div class="inv-type"><i class="bi bi-tags"></i> ${escapeHtml(item.jenis)}</div>
                            <div class="inv-footer">
                                <span class="text-muted small">Total Kuantitas</span>
                                <span class="inv-total">${escapeHtml(item.total)} Unit</span>
                            </div>
                        </div>
                    </div>
                </div>`;
            });

            inventarisContainer.innerHTML = html;

            currentPage   = page;
            totalPages    = response.total_pages;
            currentSearch = search;

            renderPagination();
            if (totalPages > 1) customPagination.style.display = 'block';

            /* Observe newly inserted cards for reveal-up animation */
            inventarisContainer.querySelectorAll('.reveal-up').forEach(el => revealObserver.observe(el));
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

    /* Search: only fire on button click or Enter */
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

    /* Observe static elements that already have reveal-up */
    document.querySelectorAll('.reveal-up:not(.active)').forEach(el => revealObserver.observe(el));

    /* Initial load */
    loadData(1, '');
});
</script>
<?= $this->endSection() ?>
