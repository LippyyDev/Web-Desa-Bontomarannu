<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN DESA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Geografi <span style="color: #15803d;">Desa</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Kelola informasi geografis desa.</p>
    </div>
</div>

<form id="geografiForm" method="post" action="<?= base_url('/staff/geografi') ?>">
    <?= csrf_field() ?>
    
    <!-- CARD 1: Informasi Dasar -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Dasar</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Luas Wilayah</label>
                    <input type="text" class="form-control" name="luas_wilayah" value="<?= esc($geografi['luas_wilayah'] ?? '') ?>" placeholder="Contoh: 15.4 km²">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Batas Wilayah</label>
                    <textarea class="form-control" name="batas_wilayah" rows="5" placeholder="Jelaskan batas-batas wilayah desa..."><?= esc($geografi['batas_wilayah'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Kondisi Geografis</label>
                    <textarea class="form-control" name="kondisi_geografis" rows="5" placeholder="Jelaskan kondisi geografis desa..."><?= esc($geografi['kondisi_geografis'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD 3: Peta Lokasi -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Peta Lokasi</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Link Google Maps Embed</label>
                    <input type="text" class="form-control" id="inputMaps" name="maps_embed_url"
                           value="<?= esc($geografi['maps_embed_url'] ?? '') ?>"
                           placeholder="https://www.google.com/maps/embed?pb=... atau maps.app.goo.gl/...">
                    <div class="invalid-feedback">Link tidak valid. Gunakan link dari Google Maps.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOMBOL SIMPAN -->
    <div class="mt-4 mb-4">
        <button class="btn btn-success" type="submit">
            Simpan Perubahan
        </button>
    </div>
</form>

<script>
// ── Validasi Google Maps URL (frontend) ──────────────────────────────────────
const MAPS_PATTERNS = [
    /maps\.app\.goo\.gl/i,
    /goo\.gl\/maps/i,
    /google\.com\/maps/i,
    /maps\.google\.com/i,
];
function isValidGoogleMapsUrl(val) {
    if (!val || val.trim() === '') return true; // opsional, boleh kosong
    return MAPS_PATTERNS.some(p => p.test(val));
}

document.addEventListener('DOMContentLoaded', function () {
    const inputMaps = document.getElementById('inputMaps');

    // Real-time feedback saat mengetik
    inputMaps.addEventListener('input', function () {
        const val = this.value.trim();
        if (val === '' || isValidGoogleMapsUrl(val)) {
            this.classList.remove('is-invalid');
            this.classList.add(val !== '' ? 'is-valid' : '');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });

    // Cegah submit jika URL tidak valid
    document.getElementById('geografiForm').addEventListener('submit', function (e) {
        const val = inputMaps.value.trim();
        if (!isValidGoogleMapsUrl(val)) {
            e.preventDefault();
            inputMaps.classList.add('is-invalid');
            showError('Link Google Maps tidak valid. Gunakan link dari Google Maps (maps.app.goo.gl, google.com/maps, dsb.).');
            inputMaps.focus();
        }
    });
});
</script>
<?= $this->endSection() ?>
