<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<style>
.search-bar-container {
    transition: all 0.2s ease;
}
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
            LAYANAN SURAT
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Surat <span style="color: #15803d;">Saya</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Kelola pengajuan surat ke staf desa.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="d-flex align-items-center bg-white border px-3 search-bar-container" style="width: 260px; height: 38px; border-radius: 0.5rem;">
            <i class="bi bi-search text-muted"></i>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none bg-transparent px-2 w-100" placeholder="Cari surat..." style="height: 100%;">
            <button class="btn btn-link text-muted p-0 text-decoration-none shadow-none" type="button" id="clearSearchBtn" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
        <a href="<?= base_url('/user/surat/buat') ?>" class="btn btn-success">
            Buat Surat
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" id="filterDateStart" class="form-control">
            </div>
            <div class="col-6 col-md-4 col-lg">
                <label class="form-label">Tanggal Sampai</label>
                <input type="date" id="filterDateEnd" class="form-control">
            </div>
            <div class="col-12 col-md-4 col-lg">
                <label class="form-label">Tipe Surat</label>
                <select id="filterTipeSurat" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="Keterangan Usaha">Keterangan Usaha</option>
                    <option value="Keterangan Tidak Mampu">Keterangan Tidak Mampu</option>
                    <option value="Keterangan Belum Menikah">Keterangan Belum Menikah</option>
                    <option value="Keterangan Domisili">Keterangan Domisili</option>
                    <option value="Undangan">Undangan</option>
                    <option value="Lain Lain">Lain Lain</option>
                </select>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <label class="form-label">Status</label>
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Dibaca">Dibaca</option>
                    <option value="Diterima">Diterima</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <label class="form-label">Tampilkan</label>
                <select id="lengthSelect" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="-1">Semua</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Table View -->
<div class="card desktop-table-view">
    <div class="card-body">
        <div class="table-responsive">
            <table id="lettersTable" class="table align-middle mb-0" style="width:100%">
                <thead>
                <tr>
                    <th>Kode Unik</th>
                    <th>Perihal</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Terkirim</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody id="lettersTableBody">
                    <tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-success spinner-border-sm"></div></td></tr>
                </tbody>
            </table>
        </div>
        <div id="desktopPagination" class="custom-pagination mt-3" style="display:none;"></div>
    </div>
</div>

<!-- Mobile/Tablet Card View -->
<div class="mobile-card-view">
    <div id="lettersCardContainer"></div>
    <div id="mobilePagination" class="custom-pagination"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentPage  = 1;
    let totalPages   = 1;
    let isLoading    = false;
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
            'Menunggu': 'bg-warning text-dark',
            'Dibaca':   'bg-info text-white',
            'Diterima': 'bg-success',
            'Ditolak':  'bg-danger',
        };
        return `<span class="badge ${map[status] || 'bg-secondary'}">${escapeHtml(status)}</span>`;
    }

    function getLength() {
        const val = parseInt(document.getElementById('lengthSelect').value) || 10;
        return val === -1 ? 10000 : val;
    }

    function isDesktop() {
        return window.innerWidth > 991;
    }

    function loadLetters(page = 1) {
        if (isLoading) return;
        isLoading = true;

        const length = getLength();

        if (isDesktop()) {
            document.getElementById('lettersTableBody').innerHTML =
                `<tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-success spinner-border-sm"></div></td></tr>`;
            document.getElementById('desktopPagination').style.display = 'none';
        } else {
            document.getElementById('lettersCardContainer').innerHTML =
                `<div class="text-center py-5"><div class="spinner-border text-success" role="status"></div></div>`;
        }

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append('length', length);
        formData.append('search', document.getElementById('searchInput').value.trim());
        formData.append('date_start', document.getElementById('filterDateStart').value);
        formData.append('date_end', document.getElementById('filterDateEnd').value);
        formData.append('tipe_surat_filter', document.getElementById('filterTipeSurat').value);
        formData.append('status_filter', document.getElementById('filterStatus').value);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch('<?= base_url('/user/surat/api') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                showError('Gagal memuat data surat.');
                renderEmpty();
                return;
            }

            currentPage = page;
            totalPages  = data.total_pages || 1;

            if (!data.data || data.data.length === 0) {
                renderEmpty();
                return;
            }

            if (isDesktop()) {
                renderTable(data.data);
            } else {
                renderCards(data.data);
            }

            renderPagination();
        })
        .catch(() => showError('Terjadi kesalahan saat memuat data.'))
        .finally(() => { isLoading = false; });
    }

    function renderEmpty() {
        if (isDesktop()) {
            document.getElementById('lettersTableBody').innerHTML =
                `<tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data surat.</td></tr>`;
            document.getElementById('desktopPagination').style.display = 'none';
        } else {
            document.getElementById('lettersCardContainer').innerHTML =
                `<div class="text-center py-5"><p class="text-muted">Tidak ada data surat.</p></div>`;
            document.getElementById('mobilePagination').innerHTML = '';
        }
    }

    function renderTable(letters) {
        const tbody = document.getElementById('lettersTableBody');
        tbody.innerHTML = letters.map(letter => `
            <tr>
                <td class="small text-muted fw-bold">${escapeHtml(letter.kode_unik)}</td>
                <td class="fw-semibold">${escapeHtml(letter.judul_perihal)}</td>
                <td>${escapeHtml(letter.tipe_surat)}</td>
                <td>${getStatusBadge(letter.status)}</td>
                <td class="small text-muted">${escapeHtml(letter.sent_at)}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('/user/surat/') ?>${letter.id}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-surat"
                            data-id="${letter.id}" data-perihal="${escapeHtml(letter.judul_perihal)}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        document.getElementById('desktopPagination').style.display = totalPages > 1 ? 'block' : 'none';
        attachDeleteEvents();
    }

    function renderCards(letters) {
        const container = document.getElementById('lettersCardContainer');
        container.innerHTML = letters.map(letter => `
            <div class="letter-card">
                <div class="letter-card-header">
                    <h5 class="letter-card-title">${escapeHtml(letter.judul_perihal)}</h5>
                    <div class="letter-card-badge">${getStatusBadge(letter.status)}</div>
                </div>
                <div class="letter-card-body">
                    <div class="letter-card-info">
                        <div class="letter-card-item"><i class="bi bi-hash"></i><span class="fw-bold">${escapeHtml(letter.kode_unik)}</span></div>
                        <div class="letter-card-item"><i class="bi bi-file-text"></i><span>${escapeHtml(letter.tipe_surat)}</span></div>
                        <div class="letter-card-item"><i class="bi bi-clock"></i><span>${escapeHtml(letter.sent_at)}</span></div>
                    </div>
                    <div class="letter-card-actions">
                        <a href="<?= base_url('/user/surat/') ?>${letter.id}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-surat"
                            data-id="${letter.id}" data-perihal="${escapeHtml(letter.judul_perihal)}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `).join('');

        attachDeleteEvents();
    }

    function attachDeleteEvents() {
        document.querySelectorAll('.btn-hapus-surat').forEach(btn => {
            btn.addEventListener('click', function () {
                const id      = this.getAttribute('data-id');
                const perihal = this.getAttribute('data-perihal');
                showConfirm(
                    `Hapus surat "${perihal}"? Semua balasan dan lampiran juga akan dihapus.`,
                    'Hapus Surat', 'Ya, Hapus'
                ).then(confirmed => {
                    if (confirmed) window.location.href = `<?= base_url('/user/surat/') ?>${id}/hapus`;
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

    // Event: pagination
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.pagination-btn:not(.disabled):not(.active)');
        if (!btn) return;
        const page = parseInt(btn.getAttribute('data-page'));
        if (page && page !== currentPage) {
            loadLetters(page);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    // Event: search
    document.getElementById('searchInput').addEventListener('input', function () {
        const val = this.value.trim();
        document.getElementById('clearSearchBtn').style.display = val ? 'inline-block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadLetters(1), 500);
    });

    document.getElementById('clearSearchBtn').addEventListener('click', function () {
        document.getElementById('searchInput').value = '';
        this.style.display = 'none';
        loadLetters(1);
    });

    // Event: filters
    ['filterDateStart', 'filterDateEnd', 'filterTipeSurat', 'filterStatus'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => loadLetters(1));
    });

    // Event: length
    document.getElementById('lengthSelect').addEventListener('change', () => loadLetters(1));

    // Event: resize
    window.addEventListener('resize', function () {
        checkViewMode();
        loadLetters(currentPage);
    });

    // Init
    checkViewMode();
    loadLetters(1);
});
</script>
<?= $this->endSection() ?>
