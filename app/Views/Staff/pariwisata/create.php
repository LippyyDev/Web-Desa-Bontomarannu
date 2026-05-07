<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($title) ?></h4>
        <div class="text-muted small">Isi informasi destinasi wisata baru.</div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('/staff/pariwisata') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form method="POST" action="<?= base_url('/staff/pariwisata') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Kolom Kiri -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold">Informasi Pariwisata</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Tempat Wisata <span class="text-danger">*</span></label>
                        <input type="text" name="nama_tempat" class="form-control" placeholder="Contoh: Pantai Batu Karang" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="5" placeholder="Ceritakan tentang tempat wisata ini..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap destinasi wisata"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Link Embedded Google Maps</label>
                        <textarea name="maps_embed_url" class="form-control" rows="3" placeholder="Paste link embed dari Google Maps atau kode iframe lengkapnya..."></textarea>
                        <div class="form-text">Bisa paste link biasa maupun seluruh kode iframe dari Google Maps.</div>
                    </div>
                </div>
            </div>

            <!-- Galeri Foto -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header fw-semibold">Galeri Foto</div>
                <div class="card-body">
                    <label class="form-label fw-medium">Upload Foto (bisa lebih dari 1)</label>
                    <input type="file" name="gambar[]" class="form-control" multiple accept="image/*">
                    <div class="form-text">Format: JPG, PNG, WebP. Akan dikonversi ke WebP otomatis.</div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold">Thumbnail Utama</div>
                <div class="card-body">
                    <div id="thumbnailPreview" class="mb-2 text-center" style="display:none;">
                        <img id="thumbImg" src="" class="img-fluid rounded" style="max-height:200px;">
                    </div>
                    <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept="image/*">
                    <div class="form-text mt-1">Gambar utama untuk kartu pariwisata.</div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> Simpan Pariwisata
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.getElementById('thumbnailInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('thumbImg').src = ev.target.result;
            document.getElementById('thumbnailPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
