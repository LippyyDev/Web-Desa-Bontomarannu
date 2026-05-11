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
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" required 
                                       oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" 
                                       value="<?= old('nama') ?>">
                            </div>
                            
                            <div class="col-md-6">
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
                                <input type="file" class="form-control" name="foto" id="guestPengaduanFoto" accept=".jpg,.jpeg,.png">
                                <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 1MB.</div>
                                <div id="guestFotoPreview" class="mt-2 d-flex flex-column gap-2"></div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Kirim Pengaduan
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

<script>
(function () {
    const ALLOWED_EXTS  = ['jpg', 'jpeg', 'png'];
    const ALLOWED_MIMES = ['image/jpeg', 'image/png'];
    const MAX_BYTES     = 1 * 1024 * 1024; // 1MB

    function validateFoto(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        if (!ALLOWED_EXTS.includes(ext) || !ALLOWED_MIMES.includes(file.type)) {
            return 'Format tidak didukung. Hanya JPG/JPEG/PNG.';
        }
        if (file.size > MAX_BYTES) {
            return 'Ukuran file melebihi batas 1MB.';
        }
        return null;
    }

    const fileInput  = document.getElementById('guestPengaduanFoto');
    const previewDiv = document.getElementById('guestFotoPreview');
    if (!fileInput || !previewDiv) return;

    fileInput.addEventListener('change', function () {
        previewDiv.innerHTML = '';
        const file = fileInput.files[0];
        if (!file) return;

        const err = validateFoto(file);
        if (err) {
            alert('Foto tidak valid: ' + err);
            fileInput.value = '';
            return;
        }

        const item = document.createElement('div');
        item.className = 'd-flex align-items-center justify-content-between p-2 border rounded bg-light';
        item.innerHTML = `
            <div class="d-flex align-items-center gap-2 text-truncate" style="max-width:85%;">
                <i class="bi bi-file-image text-info fs-5"></i>
                <span class="text-truncate" title="${file.name}">${file.name}</span>
                <span class="text-muted small ms-1">(${(file.size / 1024).toFixed(1)} KB)</span>
            </div>
            <button type="button"
                style="width:28px;height:28px;border-radius:6px;background:#ef4444;color:white;border:none;display:flex;align-items:center;justify-content:center;cursor:pointer;"
                title="Hapus" onclick="this.closest('div.d-flex').remove(); document.getElementById('guestPengaduanFoto').value='';">
                <i class="bi bi-trash" style="font-size:1rem;"></i>
            </button>
        `;
        previewDiv.appendChild(item);
    });
})();
</script>
<?= $this->endSection() ?>
