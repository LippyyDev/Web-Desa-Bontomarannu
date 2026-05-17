<?= $this->extend('Admin/layout') ?>

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
.badge-role-admin { background-color: #dc3545; color: white; }
.badge-role-staf { background-color: #6a1b9a; color: white; }
.badge-role-user { background-color: #0d47a1; color: white; }
</style>

<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN PENGGUNA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Kelola <span style="color: #15803d;">Akun</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Buat dan atur role akun.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="d-flex align-items-center bg-white border px-3 search-bar-container" style="width: 260px; height: 38px; border-radius: 0.5rem;">
            <i class="bi bi-search text-muted"></i>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none bg-transparent px-2 w-100" placeholder="Cari akun..." style="height: 100%;">
            <button class="btn btn-link text-muted p-0 text-decoration-none shadow-none" type="button" id="clearSearchBtn" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
        <a href="<?= base_url('/admin/akun/tambah') ?>" class="btn btn-success">
            Buat Akun
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg">
                <label class="form-label">Role</label>
                <select id="filterRole" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="staf">Staf</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <label class="form-label">Status</label>
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
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
            <table id="accountsTable" class="table align-middle mb-0" style="width:100%">
                <thead>
                <tr>
                    <th>Foto</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody id="accountsTableBody">
                    <tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-success spinner-border-sm"></div></td></tr>
                </tbody>
            </table>
        </div>
        <div id="desktopPagination" class="custom-pagination mt-3" style="display:none;"></div>
    </div>
</div>

<!-- Mobile/Tablet Card View -->
<div class="mobile-card-view">
    <div id="accountsCardContainer"></div>
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
            'aktif':    'bg-success',
            'nonaktif': 'bg-secondary',
        };
        return `<span class="badge ${map[status] || 'bg-secondary'} text-capitalize">${escapeHtml(status)}</span>`;
    }

    function getRoleBadge(role) {
        const map = {
            'admin': 'badge-role-admin',
            'staf':  'badge-role-staf',
            'user':  'badge-role-user',
        };
        return `<span class="badge ${map[role] || 'bg-secondary'} text-capitalize">${escapeHtml(role)}</span>`;
    }

    function getLength() {
        const val = parseInt(document.getElementById('lengthSelect').value) || 10;
        return val === -1 ? 10000 : val;
    }

    function isDesktop() {
        return window.innerWidth > 991;
    }

    function loadAccounts(page = 1) {
        if (isLoading) return;
        isLoading = true;

        const length = getLength();

        // Show loading state
        if (isDesktop()) {
            document.getElementById('accountsTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-success spinner-border-sm"></div></td></tr>`;
            document.getElementById('desktopPagination').style.display = 'none';
        } else {
            document.getElementById('accountsCardContainer').innerHTML =
                `<div class="text-center py-5"><div class="spinner-border text-success" role="status"></div></div>`;
        }

        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append('length', length);
        formData.append('search', document.getElementById('searchInput').value.trim());
        formData.append('role_filter', document.getElementById('filterRole').value);
        formData.append('status_filter', document.getElementById('filterStatus').value);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch('<?= base_url('/admin/akun/api') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                showError('Gagal memuat data akun.');
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
            document.getElementById('accountsTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data akun.</td></tr>`;
            document.getElementById('desktopPagination').style.display = 'none';
        } else {
            document.getElementById('accountsCardContainer').innerHTML =
                `<div class="text-center py-5"><p class="text-muted">Tidak ada data akun.</p></div>`;
            document.getElementById('mobilePagination').innerHTML = '';
        }
    }

    function renderTable(accounts) {
        const tbody = document.getElementById('accountsTableBody');
        tbody.innerHTML = accounts.map(acc => {
            const fotoUrl = acc.foto_profil
                ? '<?= base_url() ?>' + acc.foto_profil
                : '<?= base_url('assets/img/guest.webp') ?>';
            return `
            <tr>
                <td><img src="${escapeHtml(fotoUrl)}" alt="Foto" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;"></td>
                <td class="fw-semibold">${escapeHtml(acc.username)}</td>
                <td class="small text-muted">${escapeHtml(acc.email)}</td>
                <td>${getRoleBadge(acc.role)}</td>
                <td>${getStatusBadge(acc.status)}</td>
                <td class="small text-muted">${escapeHtml(acc.created_at)}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('/admin/akun/') ?>${acc.id}/edit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-akun"
                            data-id="${acc.id}" data-username="${escapeHtml(acc.username)}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </td>
            </tr>`;
        }).join('');

        document.getElementById('desktopPagination').style.display = totalPages > 1 ? 'block' : 'none';
        attachDeleteEvents();
    }

    function renderCards(accounts) {
        const container = document.getElementById('accountsCardContainer');
        container.innerHTML = accounts.map(acc => {
            const fotoUrl = acc.foto_profil
                ? '<?= base_url() ?>' + acc.foto_profil
                : '<?= base_url('assets/img/guest.webp') ?>';
            return `
            <div class="account-card">
                <div class="account-card-header">
                    <div class="account-card-profile">
                        <img src="${escapeHtml(fotoUrl)}" alt="Foto Profil" class="account-card-photo">
                        <div class="account-card-title-wrapper">
                            <h5 class="account-card-title">${escapeHtml(acc.username)}</h5>
                            <div class="account-card-subtitle">${escapeHtml(acc.email)}</div>
                        </div>
                    </div>
                    <div class="account-card-badges">
                        ${getStatusBadge(acc.status)}
                        ${getRoleBadge(acc.role)}
                    </div>
                </div>
                <div class="account-card-body">
                    <div class="account-card-info">
                        <div class="account-card-item">
                            <i class="bi bi-calendar"></i>
                            <span>Dibuat: ${escapeHtml(acc.created_at)}</span>
                        </div>
                    </div>
                    <div class="account-card-actions">
                        <a href="<?= base_url('/admin/akun/') ?>${acc.id}/edit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-akun"
                            data-id="${acc.id}" data-username="${escapeHtml(acc.username)}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>`;
        }).join('');

        attachDeleteEvents();
    }

    function attachDeleteEvents() {
        document.querySelectorAll('.btn-hapus-akun').forEach(btn => {
            btn.addEventListener('click', function () {
                const id       = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');
                showConfirm(
                    `Hapus akun "${username}"? Tindakan ini tidak dapat dibatalkan.`,
                    'Hapus Akun', 'Ya, Hapus'
                ).then(confirmed => {
                    if (confirmed) window.location.href = `<?= base_url('/admin/akun/') ?>${id}/hapus`;
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

    // Event: pagination click (event delegation)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.pagination-btn:not(.disabled):not(.active)');
        if (!btn) return;
        const page = parseInt(btn.getAttribute('data-page'));
        if (page && page !== currentPage) {
            loadAccounts(page);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    // Event: search
    document.getElementById('searchInput').addEventListener('input', function () {
        const val = this.value.trim();
        document.getElementById('clearSearchBtn').style.display = val ? 'inline-block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadAccounts(1), 500);
    });

    document.getElementById('clearSearchBtn').addEventListener('click', function () {
        document.getElementById('searchInput').value = '';
        this.style.display = 'none';
        loadAccounts(1);
    });

    // Event: filters
    ['filterRole', 'filterStatus'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => loadAccounts(1));
    });

    // Event: length (items per page)
    document.getElementById('lengthSelect').addEventListener('change', () => loadAccounts(1));

    // Event: resize
    window.addEventListener('resize', function () {
        checkViewMode();
        loadAccounts(currentPage);
    });

    // Init
    checkViewMode();
    loadAccounts(1);
});
</script>
<?= $this->endSection() ?>
