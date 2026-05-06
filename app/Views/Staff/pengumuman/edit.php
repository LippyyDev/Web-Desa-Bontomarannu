<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Edit Pengumuman</h4>
        <div class="text-muted small">Perbarui pengumuman desa.</div>
    </div>
    <div class="page-header-icon">
        <i class="bi bi-megaphone"></i>
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
        <form action="<?= base_url('/staff/pengumuman/' . $pengumuman['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="judul" value="<?= esc($pengumuman['judul']) ?>" required>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="isi" rows="8" required><?= esc($pengumuman['isi']) ?></textarea>
                    <div class="form-text">Jelaskan isi pengumuman dengan detail.</div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Thumbnail (Opsional)</label>
                    <?php if (!empty($pengumuman['thumbnail'])): ?>
                        <div class="mb-2">
                            <img src="<?= base_url($pengumuman['thumbnail']) ?>" alt="Current Thumbnail" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="thumbnail" accept="image/*">
                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah thumbnail.</div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Foto (Opsional)</label>
                    <?php if (!empty($pengumuman['foto'])): ?>
                        <div class="mb-2">
                            <img src="<?= base_url($pengumuman['foto']) ?>" alt="Current Foto" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="foto" accept="image/*">
                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="<?= base_url('/staff/pengumuman') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
