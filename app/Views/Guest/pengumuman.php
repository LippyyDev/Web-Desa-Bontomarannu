<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5" style="margin-top: 90px;">
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2">Pengaduan Masyarakat</h2>
        <p class="text-muted">Sampaikan keluhan atau laporan Anda kepada pemerintah desa</p>
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

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="<?= base_url('/pengaduan') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row g-3">
                            <?php if (!session()->get('isLoggedIn')): ?>
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama" required value="<?= old('nama') ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="<?= session()->get('isLoggedIn') ? 'col-12' : 'col-md-6' ?>">
                                <label class="form-label">Kontak (No. HP/Email) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kontak" required value="<?= old('kontak') ?>">
                                <div class="form-text">Nomor HP atau email untuk dihubungi</div>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Perihal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="perihal" required value="<?= old('perihal') ?>" placeholder="Judul singkat pengaduan Anda">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Isi Pengaduan <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="isi" rows="6" required placeholder="Jelaskan pengaduan Anda secara detail..."><?= old('isi') ?></textarea>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Foto Pendukung (Opsional)</label>
                                <input type="file" class="form-control" name="foto" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB.</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send me-2"></i> Kirim Pengaduan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Catatan:</strong> Pengaduan Anda akan ditinjau oleh petugas desa. Pastikan informasi yang Anda berikan akurat dan lengkap.
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
