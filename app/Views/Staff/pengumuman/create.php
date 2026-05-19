<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Tambah <span style="color: #15803d;">Pengumuman</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Buat pengumuman baru untuk warga desa.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/staff/pengumuman') ?>" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<form action="<?= base_url('/staff/pengumuman') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Informasi Pengumuman -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Pengumuman</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Judul Pengumuman <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="judul" required placeholder="Masukkan judul pengumuman...">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Isi Pengumuman <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="isi" rows="7" required placeholder="Jelaskan isi pengumuman dengan detail..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Media -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Gambar</h5>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Thumbnail <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="file" class="form-control" id="thumbnailInput" name="thumbnail" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div class="form-text">Format JPG/PNG, maksimal 1MB.</div>
                    <div id="thumbnailPreview" class="mt-2"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Foto Pendukung <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="file" class="form-control" id="fotoInput" name="foto" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div class="form-text">Format JPG/PNG, maksimal 1MB.</div>
                    <div id="fotoPreview" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-4">
        <button class="btn btn-success" type="submit">
            <i class="bi bi-save me-1"></i> Simpan Pengumuman
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function setupSingleImagePreview(inputId, previewId) {
        const input   = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        if (!input || !preview) return;

        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) { preview.innerHTML = ''; return; }

            if (!validateImageFile(file)) {
                input.value       = '';
                preview.innerHTML = '';
                showError('Format tidak didukung atau ukuran melebihi 1MB (Hanya JPG/PNG).');
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = `
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                        <div class="card border shadow-sm overflow-hidden mb-0">
                            <img src="${e.target.result}" class="w-100 bg-light"
                                style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="preview">
                        </div>
                    </div>`;
            };
            reader.readAsDataURL(file);
        });
    }

    setupSingleImagePreview('thumbnailInput', 'thumbnailPreview');
    setupSingleImagePreview('fotoInput', 'fotoPreview');
});
</script>
<?= $this->endSection() ?>
