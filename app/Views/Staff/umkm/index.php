<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($title) ?></h4>
        <div class="text-muted small">Kelola semua UMKM termasuk pengajuan dari warga.</div>
    </div>
    <div class="page-header-actions">
        <div class="page-header-icon"><i class="bi bi-shop"></i></div>
        <a href="<?= base_url('/staff/umkm/tambah') ?>" class="page-header-icon page-header-icon-add" title="Tambah UMKM">
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>
</div>

<!-- Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm border-start border-warning border-4">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-warning"><?= $totalPending ?></div>
                <div class="small text-muted">Menunggu Persetujuan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm border-start border-success border-4">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-success"><?= $totalApproved ?></div>
                <div class="small text-muted">Disetujui & Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm border-start border-danger border-4">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-danger"><?= $totalRejected ?></div>
                <div class="small text-muted">Ditolak</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= base_url('/staff/umkm') ?>" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Cari</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nama toko..." value="<?= esc($search) ?>">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="approved" <?= $statusFilter === 'approved' ? 'selected' : '' ?>>Disetujui</option>
                    <option value="rejected" <?= $statusFilter === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Daftar UMKM -->
<?php if (empty($list)): ?>
<div class="text-center text-muted py-5">
    <i class="bi bi-shop fs-1 d-block mb-2"></i>
    Tidak ada data UMKM.
</div>
<?php else: ?>
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Toko</th>
                    <th>Pemilik</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Didaftarkan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $i => $item): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        <strong><?= esc($item['nama_toko']) ?></strong>
                        <?php if ($item['alamat']): ?>
                        <div class="small text-muted"><i class="bi bi-geo-alt"></i> <?= esc(mb_strimwidth($item['alamat'], 0, 50, '...')) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($item['pemilik']) ?></td>
                    <td><?= esc($item['kontak'] ?? '-') ?></td>
                    <td>
                        <?php if ($item['status'] === 'approved'): ?>
                            <span class="badge bg-success">Disetujui</span>
                        <?php elseif ($item['status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Ditolak</span>
                        <?php endif; ?>
                    </td>
                    <td class="small text-muted"><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="<?= base_url('/staff/umkm/' . $item['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                            <a href="<?= base_url('/staff/umkm/' . $item['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <?php if ($item['status'] === 'pending'): ?>
                            <button type="button" class="btn btn-sm btn-success" onclick="approveUmkm(<?= $item['id'] ?>, '<?= esc($item['nama_toko']) ?>')" title="Setujui"><i class="bi bi-check-circle"></i></button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="rejectUmkm(<?= $item['id'] ?>, '<?= esc($item['nama_toko']) ?>')" title="Tolak"><i class="bi bi-x-circle"></i></button>
                            <?php endif; ?>
                            <a href="<?= base_url('/staff/umkm/' . $item['id'] . '/hapus') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus UMKM ini?')" title="Hapus"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Setujui</button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle"></i> Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveUmkm(id, nama) {
    document.getElementById('approveName').textContent = nama;
    document.getElementById('approveForm').action = '<?= base_url('/staff/umkm/') ?>' + id + '/approve';
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

function rejectUmkm(id, nama) {
    document.getElementById('rejectName').textContent = nama;
    document.getElementById('rejectForm').action = '<?= base_url('/staff/umkm/') ?>' + id + '/reject';
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
<?= $this->endSection() ?>
