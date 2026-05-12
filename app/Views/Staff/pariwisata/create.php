<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            UMKM & PARIWISATA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Tambah <span style="color: #15803d;">Pariwisata</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Isi informasi destinasi wisata baru.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/staff/pariwisata') ?>" class="btn btn-outline-success">
            Kembali
        </a>
    </div>
</div>

<form method="POST" action="<?= base_url('/staff/pariwisata') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Dasar</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Tempat Wisata <span class="text-danger">*</span></label>
                    <input type="text" name="nama_tempat" class="form-control" placeholder="Contoh: Pantai Batu Karang" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Alamat</label>
                    <input type="text" name="alamat" class="form-control" placeholder="Alamat lengkap destinasi wisata">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="5" placeholder="Ceritakan tentang tempat wisata ini..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Link Embedded Google Maps</label>
                    <textarea name="maps_embed_url" class="form-control" rows="3" placeholder="Paste link embed dari Google Maps atau kode iframe lengkapnya..."></textarea>
                    <div class="form-text mt-1 text-muted">Bisa paste link biasa maupun seluruh kode iframe dari Google Maps.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Media</h5>
            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label fw-medium">Thumbnail Utama</label>
                    <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div id="thumbnailPreview" class="mt-2"></div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Upload Foto Tambahan</label>
                    <input type="file" name="gambar[]" id="mediaInput" class="form-control" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div id="mediaPreview" class="row g-2 mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-4">
        <button class="btn btn-success" type="submit">
            Simpan Pariwisata
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Preview Thumbnail
    const thumbnailInput = document.getElementById('thumbnailInput');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    
    thumbnailInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (typeof validateImageFile === 'function' && !validateImageFile(file)) {
                thumbnailInput.value = '';
                thumbnailPreview.innerHTML = '';
                showError('Format tidak didukung atau ukuran melebihi 1MB (Hanya JPG/PNG).');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                thumbnailPreview.innerHTML = `
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                        <div class="card border shadow-sm overflow-hidden mb-0">
                            <img src="${e.target.result}" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="thumbnail">
                        </div>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            thumbnailPreview.innerHTML = '';
        }
    });

    // Preview Media Foto — dikelola oleh upload_validator.js jika ada
    if (typeof initMediaUploader === 'function') {
        initMediaUploader('mediaInput', 'mediaPreview');
    }
});
</script>
<?= $this->endSection() ?>
