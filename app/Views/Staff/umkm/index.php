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

<!-- Page Header -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            UMKM & PARIWISATA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Daftar <span style="color: #15803d;">UMKM</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Kelola semua UMKM termasuk pengajuan dari warga.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="d-flex align-items-center bg-white border px-3 search-bar-container" style="width: 260px; height: 38px; border-radius: 0.5rem;">
            <i class="bi bi-search text-muted"></i>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none bg-transparent px-2 w-100" placeholder="Cari toko..." style="height: 100%;">
            <button class="btn btn-link text-muted p-0 text-decoration-none shadow-none" type="button" id="clearSearchBtn" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
        <a href="<?= base_url('/staff/umkm/tambah') ?>" class="btn btn-success">
            Tambah UMKM
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-label">Menunggu Persetujuan</div>
            <div class="stat-value"><?= $totalPending ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-label">Disetujui & Aktif</div>
            <div class="stat-value"><?= $totalApproved ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
            <div class="stat-label">Ditolak</div>
            <div class="stat-value"><?= $totalRejected ?></div>
        </div>
    </div>
</div>

<!-- Filter Card -->
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
                <label class="form-label">Filter Status</label>
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
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
            <table class="table align-middle mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Toko</th>
                        <th>Pemilik</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Didaftarkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="umkmTableBody">
                    <tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-success spinner-border-sm"></div></td></tr>
                </tbody>
            </table>
        </div>
        <div id="desktopPagination" class="custom-pagination mt-3" style="display:none;"></div>
    </div>
</div>

<!-- Mobile Card View -->
<div class="mobile-card-view">
    <div id="umkmCardContainer"></div>
    <div id="mobilePagination" class="custom-pagination"></div>
</div>

<!-- Modal Approve -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Setujui UMKM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui toko <strong id="approveName"></strong>? Toko akan langsung tampil di halaman publik.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak UMKM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <p>Tolak toko <strong id="rejectName"></strong>?</p>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control" rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
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
            'pending':  'bg-warning text-dark',
            'approved': 'bg-success',
            'rejected': 'bg-danger',
        };
        const label = { 'pending': 'Menunggu', 'approved': 'Disetujui', 'rejected': 'Ditolak' };
        return `<span class="badge ${map[status] || 'bg-secondary'}">${label[status] || escapeHtml(status)}</span>`;
    }

    function getLength() {
        const val = parseInt(document.getElementById('lengthSelect').value) || 10;
        return val === -1 ? 10000 : val;
    }

    function isDesktop() { return window.innerWidth > 991; }

    function loadUmkm(page = 1) {
        if (isLoading) return;
        isLoading = true;

        if (isDesktop()) {
            document.getElementById('umkmTableBody').innerHTML =
                `<tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-success spinner-border-sm"></div></td></tr>`;
            document.getElementById('desktopPagination').style.display = 'none';
        } else {
            document.getElementById('umkmCardContainer').innerHTML =
                `<div class="text-center py-5"><div class="spinner-border text-success" role="status"></div></div>`;
        }

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append('length', getLength());
        formData.append('search', document.getElementById('searchInput').value.trim());
        formData.append('date_start', document.getElementById('filterDateStart').value);
        formData.append('date_end', document.getElementById('filterDateEnd').value);
        formData.append('status_filter', document.getElementById('filterStatus').value);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch('<?= base_url('/staff/umkm/api') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) { showError('Gagal memuat data UMKM.'); renderEmpty(); return; }

            currentPage = page;
            totalPages  = data.total_pages || 1;

            if (!data.data || data.data.length === 0) { renderEmpty(); return; }

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
            document.getElementById('umkmTableBody').innerHTML =
                `<tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data UMKM.</td></tr>`;
            document.getElementById('desktopPagination').style.display = 'none';
        } else {
            document.getElementById('umkmCardContainer').innerHTML =
                `<div class="text-center py-5"><p class="text-muted">Tidak ada data UMKM.</p></div>`;
            document.getElementById('mobilePagination').innerHTML = '';
        }
    }

    function getActionButtons(item) {
        const baseUrl = '<?= base_url('/staff/umkm/') ?>';
        let btns = `
            <a href="${baseUrl}${item.id}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Detail</a>
            <a href="${baseUrl}${item.id}/edit" class="btn btn-sm btn-outline-info"><i class="bi bi-pencil"></i> Edit</a>`;
        if (item.status === 'pending') {
            btns += `
            <button type="button" class="btn btn-sm btn-outline-success btn-approve"
                data-id="${item.id}" data-nama="${escapeHtml(item.nama_toko)}"><i class="bi bi-check-circle"></i> Setujui</button>
            <button type="button" class="btn btn-sm btn-outline-danger btn-reject"
                data-id="${item.id}" data-nama="${escapeHtml(item.nama_toko)}"><i class="bi bi-x-circle"></i> Tolak</button>`;
        }
        btns += `<a href="${baseUrl}${item.id}/hapus" class="btn btn-sm btn-outline-danger btn-hapus-umkm"
            data-nama="${escapeHtml(item.nama_toko)}"><i class="bi bi-trash"></i> Hapus</a>`;
        return btns;
    }

    function renderTable(list) {
        const tbody = document.getElementById('umkmTableBody');
        tbody.innerHTML = list.map(item => `
            <tr>
                <td class="fw-semibold">${escapeHtml(item.nama_toko)}</td>
                <td>${escapeHtml(item.pemilik)}</td>
                <td>${escapeHtml(item.kontak || '-')}</td>
                <td>${getStatusBadge(item.status)}</td>
                <td class="small text-muted">${escapeHtml(item.didaftarkan)}</td>
                <td><div class="btn-group" role="group">${getActionButtons(item)}</div></td>
            </tr>
        `).join('');
        document.getElementById('desktopPagination').style.display = totalPages > 1 ? 'block' : 'none';
        attachEvents();
    }

    function renderCards(list) {
        const container = document.getElementById('umkmCardContainer');
        container.innerHTML = list.map(item => `
            <div class="letter-card">
                <div class="letter-card-header">
                    <h5 class="letter-card-title">${escapeHtml(item.nama_toko)}</h5>
                    <div class="letter-card-badge">${getStatusBadge(item.status)}</div>
                </div>
                <div class="letter-card-body">
                    <div class="letter-card-info">
                        <div class="letter-card-item"><i class="bi bi-person"></i><span>${escapeHtml(item.pemilik)}</span></div>
                        <div class="letter-card-item"><i class="bi bi-phone"></i><span>${escapeHtml(item.kontak || '-')}</span></div>
                        <div class="letter-card-item"><i class="bi bi-clock"></i><span>${escapeHtml(item.didaftarkan)}</span></div>
                    </div>
                    <div class="letter-card-actions">
                        <div class="btn-group" role="group">${getActionButtons(item)}</div>
                    </div>
                </div>
            </div>
        `).join('');
        attachEvents();
    }

    function attachEvents() {
        document.querySelectorAll('.btn-hapus-umkm').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                const nama = this.getAttribute('data-nama');
                showConfirm(`Hapus UMKM "${nama}"?`, 'Hapus UMKM', 'Ya, Hapus')
                    .then(confirmed => { if (confirmed) window.location.href = href; });
            });
        });
        document.querySelectorAll('.btn-approve').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('approveName').textContent = this.dataset.nama;
                document.getElementById('approveForm').action = '<?= base_url('/staff/umkm/') ?>' + this.dataset.id + '/approve';
                new bootstrap.Modal(document.getElementById('approveModal')).show();
            });
        });
        document.querySelectorAll('.btn-reject').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('rejectName').textContent = this.dataset.nama;
                document.getElementById('rejectForm').action = '<?= base_url('/staff/umkm/') ?>' + this.dataset.id + '/reject';
                new bootstrap.Modal(document.getElementById('rejectModal')).show();
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

    // Events
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.pagination-btn:not(.disabled):not(.active)');
        if (!btn) return;
        const page = parseInt(btn.getAttribute('data-page'));
        if (page && page !== currentPage) { loadUmkm(page); window.scrollTo({ top: 0, behavior: 'smooth' }); }
    });

    document.getElementById('searchInput').addEventListener('input', function () {
        const val = this.value.trim();
        document.getElementById('clearSearchBtn').style.display = val ? 'inline-block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadUmkm(1), 500);
    });

    document.getElementById('clearSearchBtn').addEventListener('click', function () {
        document.getElementById('searchInput').value = '';
        this.style.display = 'none';
        loadUmkm(1);
    });

    ['filterDateStart', 'filterDateEnd', 'filterStatus'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => loadUmkm(1));
    });

    document.getElementById('lengthSelect').addEventListener('change', () => loadUmkm(1));

    window.addEventListener('resize', function () { checkViewMode(); loadUmkm(currentPage); });

    // Init
    checkViewMode();
    loadUmkm(1);
});
</script>
<?= $this->endSection() ?>
