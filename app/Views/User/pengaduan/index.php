<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Buat Pengaduan</h4>
        <div class="text-muted small">Sampaikan keluhan atau laporan Anda.</div>
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

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('/user/pengaduan') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" required 
                       value="<?= old('nama_lengkap', $profile['nama_lengkap'] ?? '') ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kontak <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="kontak" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Perihal <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="perihal" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Isi Pengaduan <span class="text-danger">*</span></label>
                <textarea class="form-control" name="isi" rows="6" required></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Foto (Opsional)</label>
                <input type="file" class="form-control" name="foto" accept="image/*">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Kirim</button>
                <a href="<?= base_url('/user/dashboard') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
