<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>

<style>
.perangkat-card {
    border: 1px solid #edf2f7;
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
}
.perangkat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    border-color: #e2e8f0;
}
.perangkat-header-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 80px;
    background: #15803d;
    z-index: 1;
}
.perangkat-img-wrapper {
    position: relative;
    width: 100%;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    z-index: 2;
}
.perangkat-img-wrapper img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    background-color: #fff;
}
.perangkat-card:hover .perangkat-img-wrapper img {
    transform: scale(1.05);
}
.perangkat-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #f1f5f9;
    border: 4px solid #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
.perangkat-card:hover .perangkat-placeholder {
    transform: scale(1.05);
}
.perangkat-card-body {
    padding: 0 1.25rem 1.25rem 1.25rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    text-align: center;
}
.perangkat-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}
.perangkat-role {
    font-size: 0.8rem;
    font-weight: 600;
    color: #059669;
    background: #ecfdf5;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 0.75rem;
}
.perangkat-contact {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-grow: 1;
}
.perangkat-footer {
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}
.perangkat-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
    flex-grow: 1;
    text-align: center;
}
.perangkat-action-edit {
    color: #10b981;
    background: #ecfdf5;
    margin-right: 8px;
}
.perangkat-action-edit:hover {
    background: #d1fae5;
    color: #059669;
}
.perangkat-action-delete {
    color: #ef4444;
    background: #fef2f2;
}
.perangkat-action-delete:hover {
    background: #fee2e2;
    color: #dc2626;
}
.search-bar-container {
    transition: all 0.2s ease;
}
.search-bar-container:focus-within {
    border-color: #198754 !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
}
.custom-pagination {
    text-align: center;
}
.pagination-wrapper {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.pagination-btn {
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #475569;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s;
    cursor: pointer;
}
.pagination-btn:hover:not(.disabled):not(.active) {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
}
.pagination-btn.active {
    background: #10b981;
    border-color: #10b981;
    color: #fff;
}
.pagination-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8fafc;
}
</style>

<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN DESA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Perangkat <span style="color: #15803d;">Desa</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Kelola data struktur organisasi dan perangkat desa.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="d-flex align-items-center bg-white border px-3 search-bar-container" style="width: 260px; height: 38px; border-radius: 0.5rem;">
            <i class="bi bi-search text-muted"></i>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none bg-transparent px-2 w-100" placeholder="Cari perangkat desa..." style="height: 100%;">
            <button class="btn btn-link text-muted p-0 text-decoration-none shadow-none" type="button" id="clearSearchBtn" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
        <a href="<?= base_url('/staff/perangkat-desa/tambah') ?>" class="btn btn-success">
            <i class="bi bi-person-plus me-1"></i> Tambah
        </a>
    </div>
</div>

<div id="perangkatContainer" class="row g-3">
    <!-- Data akan di-load via AJAX -->
</div>

<div id="loadingIndicator" class="text-center py-4">
    <div class="spinner-border text-success" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div id="customPagination" class="custom-pagination mt-4" style="display: none;"></div>

<div id="emptyMessage" class="col-12 text-center text-muted py-4" style="display: none;">
    Belum ada data perangkat desa.
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentPage   = 1;
    let isLoading     = false;
    let currentSearch = '';
    let totalPages    = 1;
    const limit       = 12;

    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    const perangkatContainer = document.getElementById('perangkatContainer');
    const loadingIndicator   = document.getElementById('loadingIndicator');
    const customPagination   = document.getElementById('customPagination');
    const emptyMessage       = document.getElementById('emptyMessage');

    function escapeHtml(str) {
        return (str || '').toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function loadData(page, search = '') {
        if (isLoading) return;
        isLoading = true;

        loadingIndicator.style.display = 'block';
        perangkatContainer.innerHTML   = '';
        customPagination.style.display = 'none';
        emptyMessage.style.display     = 'none';

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append('limit', limit);
        formData.append('search', search);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch('<?= base_url('/staff/perangkat-desa/api') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
            if (response.success && response.data && response.data.length > 0) {
                let html = '';
                response.data.forEach(function (item) {
                    const imgHtml = item.foto_url
                        ? `<img src="${escapeHtml(item.foto_url)}" alt="${escapeHtml(item.nama)}" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\\'perangkat-placeholder\\'><i class=\\'bi bi-person\\' style=\\'font-size:2.5rem;\\'></i></div>'">`
                        : `<div class="perangkat-placeholder"><i class="bi bi-person" style="font-size:2.5rem;"></i></div>`;

                    html += `
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="perangkat-card h-100">
                            <div class="perangkat-header-bg"></div>
                            <div class="perangkat-img-wrapper">
                                ${imgHtml}
                            </div>
                            <div class="perangkat-card-body">
                                <h5 class="perangkat-title">${escapeHtml(item.nama)}</h5>
                                <div><span class="perangkat-role">${escapeHtml(item.jabatan)}</span></div>
                                <div class="perangkat-contact">
                                    ${item.kontak ? `<i class="bi bi-telephone"></i> ${escapeHtml(item.kontak)}` : '&nbsp;'}
                                </div>
                                <div class="perangkat-footer">
                                    <a href="<?= base_url('/staff/perangkat-desa/') ?>${item.id}/edit" class="perangkat-action perangkat-action-edit stretched-link">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button type="button"
                                        class="perangkat-action perangkat-action-delete btn-hapus-perangkat"
                                        data-id="${item.id}"
                                        data-nama="${escapeHtml(item.nama)}"
                                        style="position: relative; z-index: 2; border: none; cursor: pointer;">
                                        <i class="bi bi-trash3"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });

                perangkatContainer.innerHTML = html;

                currentPage   = page;
                totalPages    = response.total_pages;
                currentSearch = search;

                renderPagination();
                if (totalPages > 1) {
                    customPagination.style.display = 'block';
                }

                attachDeleteEvents();

            } else {
                emptyMessage.style.display     = 'block';
                customPagination.style.display = 'none';
            }
        })
        .catch(() => showError('Terjadi kesalahan saat memuat data.'))
        .finally(() => {
            isLoading = false;
            loadingIndicator.style.display = 'none';
        });
    }

    function attachDeleteEvents() {
        document.querySelectorAll('.btn-hapus-perangkat').forEach(btn => {
            btn.addEventListener('click', function () {
                const id   = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                if (typeof showConfirm === 'function') {
                    showConfirm(`Hapus data "${nama}"? Data yang dihapus tidak dapat dikembalikan.`, 'Hapus Data', 'Ya, Hapus').then(confirmed => {
                        if (!confirmed) return;
                        window.location.href = `<?= base_url('/staff/perangkat-desa/') ?>${id}/hapus`;
                    });
                } else {
                    if (confirm(`Hapus data "${nama}"?`)) {
                        window.location.href = `<?= base_url('/staff/perangkat-desa/') ?>${id}/hapus`;
                    }
                }
            });
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

    function goToPage(page) {
        if (page < 1 || page > totalPages) return;
        loadData(page, currentSearch);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.pagination-btn:not(.disabled):not(.active)');
        if (btn && customPagination.contains(btn)) {
            const page = parseInt(btn.getAttribute('data-page'));
            if (page && page !== currentPage) goToPage(page);
        }
    });

    let searchTimeout;
    function performSearch() {
        const search  = document.getElementById('searchInput').value.trim();
        currentSearch = search;
        document.getElementById('clearSearchBtn').style.display = search ? 'inline-block' : 'none';
        loadData(1, search);
    }

    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });

    document.getElementById('clearSearchBtn').addEventListener('click', function () {
        document.getElementById('searchInput').value = '';
        this.style.display = 'none';
        performSearch();
    });

    // Load awal
    loadData(1);
});
</script>
<?= $this->endSection() ?>
