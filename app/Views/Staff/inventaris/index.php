<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<style>
.search-bar-container { transition: all 0.2s ease; }
.search-bar-container:focus-within {
    border-color: #198754 !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
}
.custom-pagination { text-align: center; }
.pagination-wrapper {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
}
</style>

<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN DESA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Inventaris <span style="color: #15803d;">Aset Desa</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Kelola inventaris aset milik desa.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="d-flex align-items-center bg-white border px-3 search-bar-container" style="width: 260px; height: 38px; border-radius: 0.5rem;">
            <i class="bi bi-search text-muted"></i>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none bg-transparent px-2 w-100" placeholder="Cari barang..." style="height: 100%;">
            <button class="btn btn-link text-muted p-0 text-decoration-none shadow-none" type="button" id="clearSearchBtn" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
        <a href="<?= base_url('/staff/inventaris/tambah') ?>" class="btn btn-success">
            Tambah
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-4 col-lg">
                <label class="form-label">Jenis Barang</label>
                <select id="filterJenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="Tanah">Tanah</option>
                    <option value="Bangunan / Gedung">Bangunan / Gedung</option>
                    <option value="Peralatan & Mesin">Peralatan & Mesin</option>
                    <option value="Kendaraan">Kendaraan</option>
                    <option value="Jalan, Irigasi & Jaringan">Jalan, Irigasi & Jaringan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="col-12 col-md-4 col-lg">
                <label class="form-label">Status</label>
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Ringan">Rusak Ringan</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                </select>
            </div>
            <div class="col-12 col-md-4 col-lg">
                <label class="form-label">Tampilkan</label>
                <select id="lengthSelect" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Table View -->
<div class="card desktop-table-view">
    <div class="card-body">
        <div class="table-responsive">
            <table id="inventarisTable" class="table align-middle mb-0" style="width:100%">
                <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ditambahkan</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody id="inventarisTableBody">
                    <tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-success spinner-border-sm"></div></td></tr>
                </tbody>
            </table>
        </div>
        <div id="desktopPagination" class="custom-pagination mt-3" style="display:none;"></div>
    </div>
</div>

<!-- Mobile Card View -->
<div class="mobile-card-view">
    <div id="inventarisCardContainer"></div>
    <div id="mobilePagination" class="custom-pagination"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentPage = 1;
    let totalPages  = 1;
    let isLoading   = false;
    let searchTimeout;

    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    function escapeHtml(str) {
        return (str || '').toString()
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function getStatusBadge(status) {
        const map = {
            'Baik':        'bg-success',
            'Rusak Ringan':'bg-warning text-dark',
            'Rusak Berat': 'bg-danger',
        };
        return `<span class="badge ${map[status] || 'bg-secondary'}">${escapeHtml(status)}</span>`;
    }

    function getLength() {
        return parseInt(document.getElementById('lengthSelect').value) || 10;
    }

    function isDesktop() {
        return window.innerWidth > 991;
    }

    function loadInventaris(page = 1) {
        if (isLoading) return;
        isLoading = true;

        if (isDesktop()) {
            document.getElementById('inventarisTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-success spinner-border-sm"></div></td></tr>`;
            document.getElementById('desktopPagination').style.display = 'none';
        } else {
            document.getElementById('inventarisCardContainer').innerHTML =
                `<div class="text-center py-5"><div class="spinner-border text-success" role="status"></div></div>`;
        }

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append('length', getLength());
        formData.append('search', document.getElementById('searchInput').value.trim());
        formData.append('jenis_filter', document.getElementById('filterJenis').value);
        formData.append('status_filter', document.getElementById('filterStatus').value);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch('<?= base_url('/staff/inventaris/api') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) { showError('Gagal memuat data inventaris.'); renderEmpty(); return; }

            currentPage = page;
            totalPages  = data.total_pages || 1;

            if (!data.data || data.data.length === 0) { renderEmpty(); return; }

            if (isDesktop()) { renderTable(data.data); }
            else { renderCards(data.data); }

            renderPagination();
        })
        .catch(() => showError('Terjadi kesalahan saat memuat data.'))
        .finally(() => { isLoading = false; });
    }

    function renderEmpty() {
        if (isDesktop()) {
            document.getElementById('inventarisTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data inventaris.</td></tr>`;
            document.getElementById('desktopPagination').style.display = 'none';
        } else {
            document.getElementById('inventarisCardContainer').innerHTML =
                `<div class="text-center py-5"><p class="text-muted">Tidak ada data inventaris.</p></div>`;
            document.getElementById('mobilePagination').innerHTML = '';
        }
    }

    function renderTable(items) {
        const tbody = document.getElementById('inventarisTableBody');
        tbody.innerHTML = items.map(item => {
            const fotoHtml = item.foto_url
                ? `<img src="${escapeHtml(item.foto_url)}" alt="foto" class="rounded" style="width:40px;height:40px;object-fit:cover;">`
                : `<div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width:40px;height:40px;"><i class="bi bi-image"></i></div>`;
            return `
            <tr>
                <td>${fotoHtml}</td>
                <td class="fw-semibold">${escapeHtml(item.nama_barang)}</td>
                <td>${escapeHtml(item.jenis)}</td>
                <td>${escapeHtml(item.total)}</td>
                <td>${getStatusBadge(item.status)}</td>
                <td class="small text-muted">${escapeHtml(item.created_at)}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('/staff/inventaris/') ?>${item.id}/edit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-inventaris"
                            data-id="${item.id}" data-nama="${escapeHtml(item.nama_barang)}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </td>
            </tr>`;
        }).join('');
        document.getElementById('desktopPagination').style.display = totalPages > 1 ? 'block' : 'none';
        attachDeleteEvents();
    }

    function renderCards(items) {
        document.getElementById('inventarisCardContainer').innerHTML = items.map(item => {
            const fotoHtml = item.foto_url
                ? `<img src="${escapeHtml(item.foto_url)}" alt="foto" class="rounded" style="width:56px;height:56px;object-fit:cover;flex-shrink:0;">`
                : `<div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width:56px;height:56px;flex-shrink:0;"><i class="bi bi-image fs-4"></i></div>`;
            return `
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        ${fotoHtml}
                        <div class="flex-grow-1">
                            <div class="fw-semibold">${escapeHtml(item.nama_barang)}</div>
                            <div class="small text-muted">${escapeHtml(item.jenis)}</div>
                        </div>
                        ${getStatusBadge(item.status)}
                    </div>
                    <div class="row g-2 small text-muted mb-3">
                        <div class="col-6"><i class="bi bi-boxes me-1"></i>Total: <strong>${escapeHtml(item.total)}</strong></div>
                        <div class="col-6"><i class="bi bi-calendar me-1"></i>${escapeHtml(item.created_at)}</div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('/staff/inventaris/') ?>${item.id}/edit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-inventaris"
                            data-id="${item.id}" data-nama="${escapeHtml(item.nama_barang)}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>`;
        }).join('');
        attachDeleteEvents();
    }

    function attachDeleteEvents() {
        document.querySelectorAll('.btn-hapus-inventaris').forEach(btn => {
            btn.addEventListener('click', function () {
                const id   = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                showConfirm(
                    `Hapus barang "${nama}"? Tindakan ini tidak dapat dibatalkan.`,
                    'Hapus Inventaris', 'Ya, Hapus'
                ).then(confirmed => {
                    if (confirmed) window.location.href = `<?= base_url('/staff/inventaris/') ?>${id}/hapus`;
                });
            });
        });
    }

    function renderPagination() {
        const html = buildPaginationHTML(currentPage, totalPages);
        if (isDesktop()) {
            document.getElementById('desktopPagination').innerHTML = html;
        } else {
            document.getElementById('mobilePagination').innerHTML = html;
        }
    }

    function buildPaginationHTML(current, total) {
        if (total <= 1) return '';
        let html = '<div class="pagination-wrapper">';
        html += current > 1
            ? `<button class="pagination-btn" data-page="${current - 1}"><i class="bi bi-chevron-left"></i></button>`
            : `<button class="pagination-btn disabled" disabled><i class="bi bi-chevron-left"></i></button>`;

        let start = Math.max(1, current - 1);
        let end   = Math.min(total, start + 2);
        if (end - start < 2) start = Math.max(1, end - 2);

        for (let i = start; i <= end; i++) {
            html += i === current
                ? `<button class="pagination-btn active">${i}</button>`
                : `<button class="pagination-btn" data-page="${i}">${i}</button>`;
        }

        html += current < total
            ? `<button class="pagination-btn" data-page="${current + 1}"><i class="bi bi-chevron-right"></i></button>`
            : `<button class="pagination-btn disabled" disabled><i class="bi bi-chevron-right"></i></button>`;

        return html + '</div>';
    }

    function checkViewMode() {
        const desktop = isDesktop();
        document.querySelector('.desktop-table-view').style.display = desktop ? '' : 'none';
        document.querySelector('.mobile-card-view').style.display   = desktop ? 'none' : '';
    }

    // Pagination click
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.pagination-btn:not(.disabled):not(.active)');
        if (!btn) return;
        const page = parseInt(btn.getAttribute('data-page'));
        if (page && page !== currentPage) {
            loadInventaris(page);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    // Search
    document.getElementById('searchInput').addEventListener('input', function () {
        const val = this.value.trim();
        document.getElementById('clearSearchBtn').style.display = val ? 'inline-block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadInventaris(1), 500);
    });

    document.getElementById('clearSearchBtn').addEventListener('click', function () {
        document.getElementById('searchInput').value = '';
        this.style.display = 'none';
        loadInventaris(1);
    });

    // Filters
    ['filterJenis', 'filterStatus'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => loadInventaris(1));
    });
    document.getElementById('lengthSelect').addEventListener('change', () => loadInventaris(1));

    // Resize
    window.addEventListener('resize', function () { checkViewMode(); loadInventaris(currentPage); });

    // Init
    checkViewMode();
    loadInventaris(1);
});
</script>
<?= $this->endSection() ?>
