<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Pengaduan Masyarakat</h4>
        <div class="text-muted small">Kelola pengaduan dari masyarakat.</div>
    </div>
    <div class="page-header-icon">
        <i class="bi bi-chat-left-text"></i>
    </div>
</div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" id="filterDateStart" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Sampai</label>
                <input type="date" id="filterDateEnd" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tampilkan</label>
                <select class="form-select" id="lengthSelect">
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="36">36</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="mb-4">
    <div class="news-search-container">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Cari pengaduan...">
            <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn" style="display: none;">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Nama</th>
                        <th width="20%">Kontak</th>
                        <th width="25%">Perihal</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pengaduanContainer">
                    <!-- Data akan di-load via AJAX -->
                </tbody>
            </table>
        </div>
        
        <div id="loadingIndicator" class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
        
        <div id="emptyMessage" class="text-center text-muted py-4" style="display: none;">
            Belum ada pengaduan.
        </div>
    </div>
</div>

<div id="customPagination" class="custom-pagination mt-4" style="display: none;"></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    let currentPage = 1;
    let totalPages = 1;
    let limit = 12;

    function loadPengaduan(page = 1, search = '') {
        $('#loadingIndicator').show();
        $('#pengaduanContainer').empty();
        $('#emptyMessage').hide();
        
        $.ajax({
            url: '<?= base_url('/staff/pengaduan/api') ?>',
            data: {
                page: page,
                limit: limit,
                search: search,
                date_start: $('#filterDateStart').val(),
                date_end: $('#filterDateEnd').val()
            },
            success: function(response) {
                $('#loadingIndicator').hide();
                if (response.data && response.data.length > 0) {
                    let html = '';
                    let startNo = (page - 1) * limit + 1;
                    response.data.forEach(function(item, index) {
                        html += '<tr>' +
                            '<td>' + (startNo + index) + '</td>' +
                            '<td>' + item.tanggal_waktu + '</td>' +
                            '<td>' + item.nama + '</td>' +
                            '<td>' + item.kontak + '</td>' +
                            '<td>' + item.perihal + '</td>' +
                            '<td>' +
                            '<div class="btn-group" role="group">' +
                            '<a href="<?= base_url('/staff/pengaduan/') ?>' + item.id + '" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Detail</a>' +
                            '<button class="btn btn-sm btn-outline-danger" onclick="deletePengaduan(' + item.id + ')"><i class="bi bi-trash"></i> Hapus</button>' +
                            '</div>' +
                            '</td>' +
                            '</tr>';
                    });
                    $('#pengaduanContainer').html(html);
                    
                    currentPage = page;
                    totalPages = response.total_pages;
                    renderPagination();
                    if (totalPages > 1) $('#customPagination').show();
                } else {
                    $('#emptyMessage').show();
                }
            }
        });
    }

    function renderPagination() {
        if (totalPages <= 1) return;
        let html = '<div class="pagination-wrapper">';
        if (currentPage > 1) {
            html += `<button class="pagination-btn" data-page="${currentPage - 1}"><i class="bi bi-chevron-left"></i></button>`;
        }
        for (let i = Math.max(1, currentPage - 1); i <= Math.min(totalPages, currentPage + 1); i++) {
            html += `<button class="pagination-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }
        if (currentPage < totalPages) {
            html += `<button class="pagination-btn" data-page="${currentPage + 1}"><i class="bi bi-chevron-right"></i></button>`;
        }
        html += '</div>';
        $('#customPagination').html(html);
    }

    $(document).on('click', '.pagination-btn:not(.active)', function() {
        const page = $(this).data('page');
        if (page) loadPengaduan(page, $('#searchInput').val());
    });

    $('#searchInput').on('input', function() {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => {
            loadPengaduan(1, $(this).val());
            $(this).val() ? $('#clearSearchBtn').show() : $('#clearSearchBtn').hide();
        }, 500);
    });

    $('#clearSearchBtn').on('click', function() {
        $('#searchInput').val('');
        $(this).hide();
        loadPengaduan(1);
    });

    $('#lengthSelect, #filterDateStart, #filterDateEnd').on('change', function() {
        limit = parseInt($('#lengthSelect').val());
        loadPengaduan(1, $('#searchInput').val());
    });

    window.deletePengaduan = function(id) {
        if (confirm('Hapus pengaduan ini?')) {
            $.ajax({
                url: '<?= base_url('/staff/pengaduan/') ?>' + id,
                type: 'DELETE',
                headers: { '<?= csrf_header() ?>': '<?= csrf_hash() ?>' },
                success: function() {
                    alert('Pengaduan berhasil dihapus');
                    loadPengaduan(currentPage, $('#searchInput').val());
                }
            });
        }
    };

    loadPengaduan();
});
</script>
<?= $this->endSection() ?>
