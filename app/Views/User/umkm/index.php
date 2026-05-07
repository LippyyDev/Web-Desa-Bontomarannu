<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>UMKM Saya</h4>
        <div class="text-muted small">Daftar toko UMKM yang Anda kelola.</div>
    </div>
    <div class="page-header-actions">
        <div class="page-header-icon"><i class="bi bi-shop"></i></div>
        <a href="<?= base_url('/user/umkm/tambah') ?>" class="page-header-icon page-header-icon-add" title="Daftarkan Toko">
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>
</div>

<?php if (empty($list)): ?>
<div class="text-center py-5">
    <i class="bi bi-shop fs-1 text-muted d-block mb-3"></i>
    <h5 class="text-muted">Belum ada toko UMKM</h5>
    <p class="text-muted">Daftarkan toko UMKM Anda dan tunggu persetujuan dari perangkat desa.</p>
    <a href="<?= base_url('/user/umkm/tambah') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Daftarkan Toko
    </a>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($list as $item): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0"><?= esc($item['nama_toko']) ?></h5>
                    <?php if ($item['status'] === 'approved'): ?>
                        <span class="badge bg-success">Aktif</span>
                    <?php elseif ($item['status'] === 'pending'): ?>
                        <span class="badge bg-warning text-dark">Menunggu</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Ditolak</span>
                    <?php endif; ?>
                </div>
                <p class="card-text text-muted small"><?= esc(mb_strimwidth($item['alamat'] ?? '', 0, 80, '...')) ?></p>
                <?php if ($item['status'] === 'rejected' && $item['alasan_tolak']): ?>
                <div class="alert alert-danger py-1 px-2 small mb-2">
                    <i class="bi bi-exclamation-triangle"></i> <?= esc($item['alasan_tolak']) ?>
                </div>
                <?php endif; ?>
                <?php if ($item['status'] === 'pending'): ?>
                <div class="text-muted small mb-2"><i class="bi bi-clock"></i> Sedang ditinjau oleh perangkat desa.</div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-transparent border-0 pb-3">
                <div class="d-flex gap-2">
                    <a href="<?= base_url('/user/umkm/' . $item['id']) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                    <a href="<?= base_url('/user/umkm/' . $item['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Edit</a>
                    <a href="<?= base_url('/user/umkm/' . $item['id'] . '/hapus') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus toko ini?')"><i class="bi bi-trash"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?= $this->endSection() ?>
