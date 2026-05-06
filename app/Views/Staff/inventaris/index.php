<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Inventaris Aset Desa</h4>
        <div class="text-muted small">Kelola inventaris aset milik desa.</div>
    </div>
    <div class="page-header-actions">
        <div class="page-header-icon">
            <i class="bi bi-box-seam"></i>
        </div>
        <a href="<?= base_url('/staff/inventaris/tambah') ?>" class="page-header-icon page-header-icon-add" title="Tambah Barang">
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <?php if (!empty($inventaris)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Foto</th>
                            <th width="25%">Nama Barang</th>
                            <th width="15%">Jenis</th>
                            <th width="10%">Total</th>
                            <th width="15%">Status</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventaris as $index => $item): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <?php if (!empty($item['foto'])): ?>
                                        <img src="<?= base_url($item['foto']) ?>" alt="Foto" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= esc($item['nama_barang']) ?></td>
                                <td><?= esc($item['jenis']) ?></td>
                                <td><?= esc($item['total']) ?></td>
                                <td>
                                    <?php
                                        $badgeClass = 'bg-secondary';
                                        $status = strtolower($item['status']);
                                        if ($status === 'baik') $badgeClass = 'bg-success';
                                        elseif ($status === 'rusak ringan') $badgeClass = 'bg-warning text-dark';
                                        elseif ($status === 'rusak berat') $badgeClass = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= esc($item['status']) ?></span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editInventarisModal<?= $item['id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="<?= base_url('/staff/inventaris/' . $item['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editInventarisModal<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Inventaris</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="<?= base_url('/staff/inventaris/' . $item['id']) ?>" method="post" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="PUT">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Barang</label>
                                                    <input type="text" class="form-control" name="nama_barang" value="<?= esc($item['nama_barang']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jenis Barang</label>
                                                    <select class="form-select" name="jenis" required>
                                                        <option value="Tanah" <?= $item['jenis'] == 'Tanah' ? 'selected' : '' ?>>Tanah</option>
                                                        <option value="Bangunan / Gedung" <?= $item['jenis'] == 'Bangunan / Gedung' ? 'selected' : '' ?>>Bangunan / Gedung</option>
                                                        <option value="Peralatan & Mesin" <?= $item['jenis'] == 'Peralatan & Mesin' ? 'selected' : '' ?>>Peralatan & Mesin</option>
                                                        <option value="Kendaraan" <?= $item['jenis'] == 'Kendaraan' ? 'selected' : '' ?>>Kendaraan</option>
                                                        <option value="Jalan, Irigasi & Jaringan" <?= $item['jenis'] == 'Jalan, Irigasi & Jaringan' ? 'selected' : '' ?>>Jalan, Irigasi & Jaringan</option>
                                                        <option value="Lainnya" <?= $item['jenis'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Total</label>
                                                    <input type="number" class="form-control" name="total" value="<?= esc($item['total']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status" required>
                                                        <option value="Baik" <?= $item['status'] == 'Baik' ? 'selected' : '' ?>>Baik</option>
                                                        <option value="Rusak Ringan" <?= $item['status'] == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                                                        <option value="Rusak Berat" <?= $item['status'] == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Foto (Opsional)</label>
                                                    <input type="file" class="form-control" name="foto" accept="image/*">
                                                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                <p class="mb-0">Belum ada data inventaris.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
