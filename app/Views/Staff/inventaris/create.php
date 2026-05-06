<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Tambah Inventaris Baru</h4>
        <div class="text-muted small">Tambahkan barang inventaris aset desa.</div>
    </div>
    <div class="page-header-icon">
        <i class="bi bi-box-seam"></i>
    </div>
</div>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= base_url('/staff/inventaris') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama_barang" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Jenis Barang <span class="text-danger">*</span></label>
                    <select class="form-select" name="jenis" required>
                        <option value="">Pilih Jenis</option>
                        <option value="Tanah">Tanah</option>
                        <option value="Bangunan / Gedung">Bangunan / Gedung</option>
                        <option value="Peralatan & Mesin">Peralatan & Mesin</option>
                        <option value="Kendaraan">Kendaraan</option>
                        <option value="Jalan, Irigasi & Jaringan">Jalan, Irigasi & Jaringan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Total <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="total" min="1" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="">Pilih Status</option>
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Foto (Opsional)</label>
                    <input type="file" class="form-control" name="foto" accept="image/*">
                    <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB.</div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="<?= base_url('/staff/inventaris') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
