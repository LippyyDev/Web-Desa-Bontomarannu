<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<style>
.umkm-card {
    border: 1px solid #edf2f7;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,.05), 0 2px 4px -1px rgba(0,0,0,.03);
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    display: flex;
    flex-direction: column;
    position: relative;
}
.umkm-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -3px rgba(0,0,0,.09), 0 4px 6px -2px rgba(0,0,0,.04);
    border-color: #e2e8f0;
}
.umkm-img-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 3;
    overflow: hidden;
    background-color: #f1f5f9;
}
.umkm-img-wrapper img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4,0,0.2,1);
}
.umkm-card:hover .umkm-img-wrapper img { transform: scale(1.05); }
.umkm-placeholder {
    width: 100%; height: 100%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: #cbd5e1; background: #f8fafc; gap: .4rem;
}
.umkm-placeholder i { font-size: 2.8rem; }
.umkm-placeholder span { font-size: .7rem; letter-spacing: .5px; }
.umkm-status-badge {
    position: absolute; top: 10px; right: 10px; z-index: 2;
}
.umkm-card-body {
    padding: 1.1rem 1.2rem 1rem;
    display: flex; flex-direction: column; flex-grow: 1;
}
.umkm-title {
    font-size: .95rem; font-weight: 700; color: #1e293b;
    margin-bottom: .6rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.umkm-meta { display: flex; flex-direction: column; gap: .3rem; flex-grow: 1; margin-bottom: .85rem; }
.umkm-meta-item {
    display: flex; align-items: center; gap: .45rem;
    font-size: .8rem; color: #64748b;
}
.umkm-meta-item i { color: #94a3b8; flex-shrink: 0; }
.umkm-notice {
    padding: .5rem .75rem;
    border-radius: 8px;
    font-size: .78rem;
    margin-bottom: .85rem;
}
.umkm-footer {
    padding-top: .85rem; border-top: 1px solid #f1f5f9;
    display: flex; flex-wrap: wrap; gap: 5px; margin-top: auto;
}
.ua {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .78rem; font-weight: 600; padding: 5px 9px;
    border-radius: 8px; transition: all 0.2s ease;
    text-decoration: none; border: none; cursor: pointer;
}
.ua-detail { color: #3b82f6; background: #eff6ff; }
.ua-detail:hover { background: #dbeafe; color: #2563eb; }
.ua-edit   { color: #10b981; background: #ecfdf5; }
.ua-edit:hover   { background: #d1fae5; color: #059669; }
.ua-delete { color: #ef4444; background: #fef2f2; }
.ua-delete:hover { background: #fee2e2; color: #dc2626; }
.search-bar-container { transition: all 0.2s ease; }
.search-bar-container:focus-within {
    border-color: #198754 !important;
    box-shadow: 0 0 0 0.2rem rgba(25,135,84,.15);
}
.custom-pagination { text-align: center; }
.pagination-wrapper { display: inline-flex; align-items: center; gap: 6px; }
</style>

<!-- Page Header -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size:.75rem;letter-spacing:2px;color:#64748b;">
            <span style="display:inline-block;width:24px;height:2px;background:#cbd5e1;margin-bottom:4px;margin-right:8px;"></span>
            UMKM SAYA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size:2.2rem;letter-spacing:-.5px;">
            Daftar <span style="color:#15803d;">Toko</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width:600px;">Daftar toko UMKM yang Anda kelola.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="d-flex align-items-center bg-white border px-3 search-bar-container" style="width:240px;height:38px;border-radius:.5rem;">
            <i class="bi bi-search text-muted"></i>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none bg-transparent px-2 w-100" placeholder="Cari toko..." style="height:100%;">
            <button class="btn btn-link text-muted p-0 shadow-none" type="button" id="clearSearchBtn" style="display:none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
        <a href="<?= base_url('/user/umkm/tambah') ?>" class="btn btn-success">Daftarkan Toko</a>
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
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                    <option value="-1">Semua</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Card Grid -->
<div id="umkmContainer" class="row g-3"></div>

<div id="loadingIndicator" class="text-center py-5">
    <div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div>
</div>

<div id="customPagination" class="custom-pagination mt-4" style="display:none;"></div>

<div id="emptyMessage" class="text-center text-muted py-5" style="display:none;">
    <i class="bi bi-shop fs-1 d-block mb-2"></i>
    Anda belum mendaftarkan toko. <a href="<?= base_url('/user/umkm/tambah') ?>">Daftarkan sekarang</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const siteUrl     = '<?= rtrim(base_url(), '/') ?>';
    const baseUmkm    = '<?= base_url('/user/umkm/') ?>';
    let currentPage   = 1;
    let totalPages    = 1;
    let isLoading     = false;
    let searchTimeout;

    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    const container        = document.getElementById('umkmContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const customPagination = document.getElementById('customPagination');
    const emptyMessage     = document.getElementById('emptyMessage');

    function escapeHtml(str) {
        return (str || '').toString()
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function getStatusBadge(status) {
        const map   = { pending:'bg-warning text-dark', approved:'bg-success', rejected:'bg-danger' };
        const label = { pending:'Menunggu', approved:'Disetujui', rejected:'Ditolak' };
        return `<span class="badge ${map[status]||'bg-secondary'}">${label[status]||escapeHtml(status)}</span>`;
    }

    function getLength() {
        const v = parseInt(document.getElementById('lengthSelect').value) || 12;
        return v === -1 ? 10000 : v;
    }

    function loadUmkm(page = 1) {
        if (isLoading) return;
        isLoading = true;

        container.innerHTML            = '';
        loadingIndicator.style.display = 'block';
        customPagination.style.display = 'none';
        emptyMessage.style.display     = 'none';

        const formData = new URLSearchParams();
        formData.append('page',          page);
        formData.append('length',        getLength());
        formData.append('search',        document.getElementById('searchInput').value.trim());
        formData.append('date_start',    document.getElementById('filterDateStart').value);
        formData.append('date_end',      document.getElementById('filterDateEnd').value);
        formData.append('status_filter', document.getElementById('filterStatus').value);
        formData.append(csrfHeaderName,  getCsrfHash());

        fetch('<?= base_url('/user/umkm/api') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success || !data.data || data.data.length === 0) {
                emptyMessage.style.display = 'block';
                return;
            }
            currentPage = page;
            totalPages  = data.total_pages || 1;
            renderCards(data.data);
            renderPagination();
            if (totalPages > 1) customPagination.style.display = 'block';
        })
        .catch(() => showError('Terjadi kesalahan saat memuat data.'))
        .finally(() => {
            isLoading = false;
            loadingIndicator.style.display = 'none';
        });
    }

    function renderCards(list) {
        let html = '';
        list.forEach(item => {
            const imgHtml = item.foto_toko
                ? `<img src="${siteUrl}/${escapeHtml(item.foto_toko)}" alt="${escapeHtml(item.nama_toko)}" loading="lazy"
                       onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\\'umkm-placeholder\\'><i class=\\'bi bi-shop\\'></i><span>Foto Toko</span></div>'">`
                : `<div class="umkm-placeholder"><i class="bi bi-shop"></i><span>Belum ada foto</span></div>`;

            let noticeHtml = '';
            if (item.status === 'pending') {
                noticeHtml = `<div class="umkm-notice bg-warning bg-opacity-10 text-warning-emphasis">
                    <i class="bi bi-clock"></i> Sedang ditinjau oleh staff.</div>`;
            } else if (item.status === 'rejected' && item.alasan_tolak) {
                noticeHtml = `<div class="umkm-notice bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-info-circle"></i> ${escapeHtml(item.alasan_tolak)}</div>`;
            }

            const actionBtns = `
                <a href="${baseUmkm}${item.id}" class="ua ua-detail"><i class="bi bi-eye"></i> Detail</a>
                <a href="${baseUmkm}${item.id}/edit" class="ua ua-edit"><i class="bi bi-pencil"></i> Edit</a>
                <a href="${baseUmkm}${item.id}/hapus" class="ua ua-delete btn-hapus"
                    data-nama="${escapeHtml(item.nama_toko)}">
                    <i class="bi bi-trash3"></i> Hapus</a>`;

            html += `
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="umkm-card h-100">
                    <div class="umkm-img-wrapper">
                        ${imgHtml}
                        <div class="umkm-status-badge">${getStatusBadge(item.status)}</div>
                    </div>
                    <div class="umkm-card-body">
                        <div class="umkm-title" title="${escapeHtml(item.nama_toko)}">${escapeHtml(item.nama_toko)}</div>
                        <div class="umkm-meta">
                            <div class="umkm-meta-item"><i class="bi bi-geo-alt"></i><span>${escapeHtml(item.alamat || '-')}</span></div>
                            <div class="umkm-meta-item"><i class="bi bi-calendar3"></i><span>${escapeHtml(item.didaftarkan)}</span></div>
                        </div>
                        ${noticeHtml}
                        <div class="umkm-footer">${actionBtns}</div>
                    </div>
                </div>
            </div>`;
        });
        container.innerHTML = html;
        attachDeleteEvents();
    }

    function attachDeleteEvents() {
        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                const nama = this.dataset.nama;
                showConfirm(`Hapus toko "${nama}"?`, 'Hapus Toko', 'Ya, Hapus')
                    .then(ok => { if (ok) window.location.href = href; });
            });
        });
    }

    function renderPagination() {
        if (totalPages <= 1) { customPagination.innerHTML = ''; return; }
        let html = '<div class="pagination-wrapper">';
        html += currentPage > 1
            ? `<button class="pagination-btn" data-page="${currentPage - 1}"><i class="bi bi-chevron-left"></i></button>`
            : `<button class="pagination-btn disabled" disabled><i class="bi bi-chevron-left"></i></button>`;
        let s = Math.max(1, currentPage - 1);
        let e = Math.min(totalPages, s + 2);
        if (e - s < 2) s = Math.max(1, e - 2);
        for (let i = s; i <= e; i++) {
            html += i === currentPage
                ? `<button class="pagination-btn active">${i}</button>`
                : `<button class="pagination-btn" data-page="${i}">${i}</button>`;
        }
        html += currentPage < totalPages
            ? `<button class="pagination-btn" data-page="${currentPage + 1}"><i class="bi bi-chevron-right"></i></button>`
            : `<button class="pagination-btn disabled" disabled><i class="bi bi-chevron-right"></i></button>`;
        customPagination.innerHTML = html + '</div>';
    }

    // Events
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.pagination-btn:not(.disabled):not(.active)');
        if (btn && customPagination.contains(btn)) {
            const p = parseInt(btn.dataset.page);
            if (p && p !== currentPage) { loadUmkm(p); window.scrollTo({ top: 0, behavior: 'smooth' }); }
        }
    });

    document.getElementById('searchInput').addEventListener('input', function () {
        document.getElementById('clearSearchBtn').style.display = this.value.trim() ? 'inline-block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadUmkm(1), 500);
    });

    document.getElementById('clearSearchBtn').addEventListener('click', function () {
        document.getElementById('searchInput').value = '';
        this.style.display = 'none';
        loadUmkm(1);
    });

    ['filterDateStart', 'filterDateEnd', 'filterStatus', 'lengthSelect'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => loadUmkm(1));
    });

    // Init
    loadUmkm(1);
});
</script>
<?= $this->endSection() ?>
