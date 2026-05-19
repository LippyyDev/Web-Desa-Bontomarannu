<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>

<style>
.gallery-card {
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
.gallery-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    border-color: #e2e8f0;
}
.gallery-img-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background-color: #f1f5f9;
}
.gallery-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
.gallery-card:hover .gallery-img-wrapper img {
    transform: scale(1.05);
}
.gallery-date-pill {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
}
.gallery-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cbd5e1;
    background-color: #f8fafc;
}
.gallery-placeholder i {
    font-size: 3rem;
}
.gallery-card-body {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.gallery-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}
.gallery-desc {
    font-size: 0.875rem;
    color: #64748b;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
    margin-bottom: 1rem;
    flex-grow: 1;
}
.gallery-footer {
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}
.gallery-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.gallery-action-edit {
    color: #10b981;
    background: #ecfdf5;
}
.gallery-action-edit:hover {
    background: #d1fae5;
    color: #059669;
}
.gallery-action-delete {
    color: #ef4444;
    background: #fef2f2;
}
.gallery-action-delete:hover {
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
</style>

<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            Manajemen Konten
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Galeri <span style="color: #15803d;">Desa</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Kelola dokumentasi foto dan video kegiatan desa.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="d-flex align-items-center bg-white border px-3 search-bar-container" style="width: 260px; height: 38px; border-radius: 0.5rem;">
            <i class="bi bi-search text-muted"></i>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none bg-transparent px-2 w-100" placeholder="Cari album..." style="height: 100%;">
            <button class="btn btn-link text-muted p-0 text-decoration-none shadow-none" type="button" id="clearSearchBtn" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
        <a href="<?= base_url('/staff/galeri/tambah') ?>" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Buat Album
        </a>
    </div>
</div>

<div id="galleryContainer" class="row g-3">
    <!-- Album akan di-load via AJAX -->
</div>

<div id="loadingIndicator" class="text-center py-4">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div id="customPagination" class="custom-pagination mt-4" style="display: none;"></div>

<div id="emptyMessage" class="col-12 text-center text-muted py-4" style="display: none;">
    Belum ada album.
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentPage   = 1;
    let isLoading     = false;
    let currentSearch = '';
    let totalPages    = 1;
    let limit         = 12;

    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    const galleryContainer  = document.getElementById('galleryContainer');
    const loadingIndicator  = document.getElementById('loadingIndicator');
    const customPagination  = document.getElementById('customPagination');
    const emptyMessage      = document.getElementById('emptyMessage');

    function escapeHtml(str) {
        return (str || '').toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function loadAlbums(page, search = '', itemsPerPage = limit) {
        if (isLoading) return;
        isLoading = true;

        loadingIndicator.style.display = 'block';
        galleryContainer.innerHTML     = '';
        customPagination.style.display = 'none';
        emptyMessage.style.display     = 'none';

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append('limit', itemsPerPage);
        formData.append('search', search);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch('<?= base_url('/staff/galeri/api') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
            if (response.success && response.data && response.data.length > 0) {
                let html = '';
                response.data.forEach(function (album) {
                    const imgHtml = album.thumbnail
                        ? `<img src="${escapeHtml(album.thumbnail)}" alt="${escapeHtml(album.nama_album)}" loading="lazy" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\\'gallery-placeholder\\'><i class=\\'bi bi-images\\'></i></div>'">`
                        : `<div class="gallery-placeholder"><i class="bi bi-images"></i></div>`;

                    const descHtml = album.deskripsi
                        ? escapeHtml(album.deskripsi)
                        : '<span class="text-muted fst-italic">Tidak ada deskripsi</span>';

                    html += `
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="gallery-card h-100">
                            <div class="gallery-img-wrapper">
                                <div class="gallery-date-pill">
                                    <i class="bi bi-calendar-event"></i> ${escapeHtml(album.tanggal_waktu)}
                                </div>
                                ${imgHtml}
                            </div>
                            <div class="gallery-card-body">
                                <h5 class="gallery-title">${escapeHtml(album.nama_album)}</h5>
                                <p class="gallery-desc">${descHtml}</p>
                                <div class="gallery-footer">
                                    <a href="<?= base_url('/staff/galeri/') ?>${album.id}/edit" class="gallery-action gallery-action-edit stretched-link">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button type="button"
                                        class="gallery-action gallery-action-delete btn-hapus-album"
                                        data-id="${album.id}"
                                        data-nama="${escapeHtml(album.nama_album)}"
                                        style="position: relative; z-index: 2; border: none; cursor: pointer;">
                                        <i class="bi bi-trash3"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });

                galleryContainer.innerHTML = html;

                currentPage   = page;
                totalPages    = response.total_pages;
                currentSearch = search;
                limit         = itemsPerPage;

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
        document.querySelectorAll('.btn-hapus-album').forEach(btn => {
            btn.addEventListener('click', function () {
                const id   = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                showConfirm(`Hapus album "${nama}"? Semua media di dalamnya akan ikut terhapus.`, 'Hapus Album', 'Ya, Hapus').then(confirmed => {
                    if (!confirmed) return;
                    window.location.href = `<?= base_url('/staff/galeri/') ?>${id}/hapus`;
                });
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
        loadAlbums(page, currentSearch, limit);
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
        loadAlbums(1, search, limit);
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
    loadAlbums(1);
});
</script>
<?= $this->endSection() ?>


