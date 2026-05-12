<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            UMKM & PARIWISATA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Edit <span style="color: #15803d;">Pariwisata</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Perbarui informasi destinasi dan media pariwisata.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-danger" id="btnHapusPariwisata" title="Hapus Pariwisata">
            Hapus
        </button>
        <a href="<?= base_url('/staff/pariwisata') ?>" class="btn btn-outline-success">
            Kembali
        </a>
    </div>
</div>

<form method="POST" action="<?= base_url('/staff/pariwisata/' . $item['id']) ?>" enctype="multipart/form-data" id="pariwisataForm">
    <?= csrf_field() ?>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Dasar</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Tempat Wisata <span class="text-danger">*</span></label>
                    <input type="text" name="nama_tempat" class="form-control" value="<?= esc($item['nama_tempat']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="<?= esc($item['alamat']) ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="5"><?= esc($item['deskripsi']) ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Link Google Maps</label>
                    <input type="text" name="maps_embed_url" id="inputMaps" class="form-control" value="<?= esc($item['maps_embed_url']) ?>" placeholder="https://maps.app.goo.gl/...">
                    <div class="form-text text-muted">Opsional · Tempel link dari Google Maps. Buka Google Maps → klik lokasi → Bagikan → Salin link.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Media</h5>
            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label fw-medium">Thumbnail (opsional)</label>
                    <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div id="thumbnailPreview" class="mt-2">
                        <?php if ($item['thumbnail']): ?>
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card border shadow-sm overflow-hidden mb-0">
                                    <img src="<?= base_url($item['thumbnail']) ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="thumbnail">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Tambah Foto</label>
                    <input type="file" name="gambar[]" id="mediaInput" class="form-control" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div id="mediaPreview" class="row g-2 mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-4">
        <button class="btn btn-success" type="submit">
            Update Pariwisata
        </button>
    </div>
</form>

<?php if (!empty($gambar)): ?>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4 fw-bold">Media Saat Ini</h5>
        <div class="row g-3">
            <?php foreach ($gambar as $g): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card border shadow-sm position-relative overflow-hidden mb-0">
                        <button type="button" class="btn btn-danger position-absolute btn-hapus-media" data-url="<?= base_url('/staff/pariwisata/gambar/' . $g['id'] . '/hapus') ?>" style="top: 6px; right: 6px; z-index: 1000; width: 26px; height: 26px; padding: 0; line-height: 1; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" title="Hapus Gambar">
                            <i class="bi bi-trash" style="font-size: 14px;"></i>
                        </button>
                        <img src="<?= base_url($g['gambar_path']) ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="gambar pariwisata">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// ── Validasi Google Maps URL (frontend) ───────────────────────────────────────
const MAPS_PATTERNS = [
    /maps\.app\.goo\.gl/i,
    /goo\.gl\/maps/i,
    /google\.com\/maps/i,
    /maps\.google\.com/i,
];
function isValidGoogleMapsUrl(val) {
    if (!val || val.trim() === '') return true; // opsional
    return MAPS_PATTERNS.some(p => p.test(val));
}

document.addEventListener('DOMContentLoaded', function () {
    // Preview Thumbnail
    const thumbnailInput = document.getElementById('thumbnailInput');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const originalThumbnailHtml = thumbnailPreview.innerHTML;
    
    thumbnailInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (typeof validateImageFile === 'function' && !validateImageFile(file)) {
                thumbnailInput.value = '';
                showError('Format tidak didukung atau ukuran melebihi 1MB (Hanya JPG/PNG).');
                thumbnailPreview.innerHTML = originalThumbnailHtml;
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
            thumbnailPreview.innerHTML = originalThumbnailHtml;
        }
    });

    // Preview Media Foto — dikelola oleh upload_validator.js jika ada
    if (typeof initMediaUploader === 'function') {
        initMediaUploader('mediaInput', 'mediaPreview');
    }

    // Validasi Maps URL saat submit
    document.getElementById('pariwisataForm').addEventListener('submit', function(e) {
        const mapsVal = document.getElementById('inputMaps')?.value.trim() ?? '';
        if (!isValidGoogleMapsUrl(mapsVal)) {
            e.preventDefault();
            showError('Link Google Maps tidak valid. Gunakan link dari Google Maps (maps.app.goo.gl, google.com/maps, dsb.).');
        }
    });

    // Hapus pariwisata via SweetAlert
    const btnHapus = document.getElementById('btnHapusPariwisata');
    if (btnHapus) {
        btnHapus.addEventListener('click', function () {
            if (typeof showConfirm === 'function') {
                showConfirm('Pariwisata beserta semua media di dalamnya akan dihapus permanen.', 'Hapus Pariwisata?', 'Ya, Hapus', 'Batal').then(confirmed => {
                    if (confirmed) window.location.href = '<?= base_url('/staff/pariwisata/' . $item['id'] . '/hapus') ?>';
                });
            } else {
                if (confirm('Hapus pariwisata ini beserta semua fotonya?')) {
                    window.location.href = '<?= base_url('/staff/pariwisata/' . $item['id'] . '/hapus') ?>';
                }
            }
        });
    }

    // Hapus media via SweetAlert
    document.querySelectorAll('.btn-hapus-media').forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            if (typeof showConfirm === 'function') {
                showConfirm('Gambar ini akan dihapus permanen dari pariwisata.', 'Hapus Gambar?', 'Ya, Hapus', 'Batal').then(confirmed => {
                    if (confirmed) window.location.href = url;
                });
            } else {
                if (confirm('Hapus gambar ini?')) {
                    window.location.href = url;
                }
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
