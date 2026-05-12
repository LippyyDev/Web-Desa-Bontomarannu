<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<style>
.nav-pills .nav-link {
    color: #198754;
    background-color: #fff;
    border: 1px solid #198754;
}
.nav-pills .nav-link.active {
    background-color: #198754;
    color: #fff;
}
.nav-pills .nav-link:hover:not(.active) {
    background-color: #e8f5e9;
}
</style>

<!-- Page Header Dashboard Style -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            UMKM & PARIWISATA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Tambah <span style="color: #15803d;">UMKM</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Tambahkan data toko UMKM baru dan produknya.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/staff/umkm') ?>" class="btn btn-outline-success">Kembali</a>
    </div>
</div>

<form method="POST" action="<?= base_url('/staff/umkm') ?>" enctype="multipart/form-data" id="umkmForm">
    <?= csrf_field() ?>

    <!-- Nav Tabs Selector -->
    <ul class="nav nav-pills mb-4 gap-2" id="umkmCreateTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-medium px-4" id="info-tab" data-bs-toggle="pill" data-bs-target="#info-pane" type="button" role="tab" aria-controls="info-pane" aria-selected="true"><i class="bi bi-shop me-2"></i>Informasi Toko</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-medium px-4" id="tambah-produk-tab" data-bs-toggle="pill" data-bs-target="#tambah-produk-pane" type="button" role="tab" aria-controls="tambah-produk-pane" aria-selected="false"><i class="bi bi-plus-circle me-2"></i>Tambah Produk</button>
        </li>
    </ul>

    <div class="tab-content" id="umkmCreateTabContent">
        <!-- TAB 1: INFORMASI TOKO -->
        <div class="tab-pane fade show active" id="info-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Foto Toko</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <input type="file" name="foto_toko" id="fotoTokoInput" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="previewFotoToko(this)" required>
                            <div class="form-text text-muted mt-1">Wajib · Maks. 1MB · JPG/PNG · Rasio 16:9 disarankan</div>
                            <div id="fotoTokoPreviewContainer" class="mt-2" style="display:none;">
                                <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                    <div class="card border shadow-sm overflow-hidden mb-0">
                                        <img id="fotoTokoPreview" src="" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="Preview Foto Toko">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Informasi Umum</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Nama Toko <span class="text-danger">*</span></label>
                            <input type="text" name="nama_toko" id="inputNamaToko" class="form-control" placeholder="Nama toko UMKM" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Kontak <span class="text-danger">*</span></label>
                            <input type="text" name="kontak" id="inputKontak" class="form-control" placeholder="08xxxxxxxx" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" id="inputDeskripsi" class="form-control" rows="4" placeholder="Ceritakan tentang toko ini..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" id="inputAlamat" class="form-control" rows="2" placeholder="Alamat lengkap toko" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Link Google Maps</label>
                            <input type="text" name="maps_embed_url" id="inputMaps" class="form-control" placeholder="https://maps.app.goo.gl/...">
                            <div class="form-text text-muted">Opsional · Tempel link dari Google Maps. Buka Google Maps → klik lokasi → Bagikan → Salin link.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- E-Commerce -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Link E-Commerce</h5>
                    <div id="ecommerceContainer">
                        <div class="ecommerce-row d-flex gap-2 mb-2">
                            <input type="text" name="ecommerce_platform[]" class="form-control w-25" placeholder="Shopee / dll">
                            <input type="url" name="ecommerce_url[]" class="form-control w-100" placeholder="https://...">
                            <button type="button" class="btn btn-danger px-3" onclick="removeRow(this)" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-link text-success text-decoration-none p-0 mt-2 fw-medium" style="font-size: 0.9rem;" onclick="addEcommerce()">+ Tambah Link</button>
                </div>
            </div>

            <div class="mt-4 mb-4">
                <button type="button" class="btn btn-success" onclick="lanjutKeProduk()">
                    Lanjut ke Tambah Produk
                </button>
            </div>
        </div>

        <!-- TAB 2: TAMBAH PRODUK -->
        <div class="tab-pane fade" id="tambah-produk-pane" role="tabpanel" aria-labelledby="tambah-produk-tab" tabindex="0">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Daftar Produk</h5>
                    <div id="produkContainer">
                        <!-- Template produk awal -->
                        <div class="produk-item card shadow-sm border-0 mb-4 bg-light" data-index="0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <h6 class="fw-bold text-success mb-0">Produk 1</h6>
                                    <button type="button" class="btn btn-danger px-3" onclick="removeProduk(this)" title="Hapus Produk"><i class="bi bi-trash"></i></button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium small">Nama Produk</label>
                                        <input type="text" name="produk_nama[]" class="form-control" placeholder="Nama produk">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium small">Harga (Rp)</label>
                                        <input type="text" name="produk_harga[]" class="form-control" placeholder="Contoh: 50000">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-medium small">Deskripsi Produk</label>
                                        <textarea name="produk_deskripsi[]" class="form-control" rows="3" placeholder="Jelaskan keunggulan produk ini..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-medium small">Foto Produk (bisa pilih banyak)</label>
                                        <input type="file" id="produk_gambar_0" name="produk_gambar_0[]" class="form-control" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                                        <div id="preview_gambar_0" class="row g-2 mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm mt-1" onclick="addProduk()">
                        <i class="bi bi-plus"></i> Tambah Item Produk
                    </button>
                </div>
            </div>

            <div class="mt-4 mb-4">
                <button type="submit" class="btn btn-success">Simpan UMKM</button>
            </div>
        </div>
    </div>
</form>

<script>
let produkCount = 1;

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

function previewFotoToko(input) {
    const container = document.getElementById('fotoTokoPreviewContainer');
    const preview   = document.getElementById('fotoTokoPreview');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (typeof validateImageFile === 'function' && !validateImageFile(file)) {
            showError('Foto toko tidak valid. Gunakan JPG/PNG, maks. 1MB.');
            input.value = '';
            container.style.display = 'none';
            preview.src = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        container.style.display = 'none';
        preview.src = '';
    }
}

function lanjutKeProduk() {
    const fotoInput = document.getElementById('fotoTokoInput');
    const namaToko  = document.getElementById('inputNamaToko').value.trim();
    const kontak    = document.getElementById('inputKontak').value.trim();
    const deskripsi = document.getElementById('inputDeskripsi').value.trim();
    const alamat    = document.getElementById('inputAlamat').value.trim();
    const mapsVal   = document.getElementById('inputMaps').value.trim();

    if (!fotoInput.files || !fotoInput.files[0]) { showError('Foto toko wajib diupload.'); return; }
    if (!namaToko)  { showError('Nama toko wajib diisi.'); return; }
    if (!kontak)    { showError('Nomor kontak wajib diisi.'); return; }
    if (!deskripsi) { showError('Deskripsi toko wajib diisi.'); return; }
    if (!alamat)    { showError('Alamat toko wajib diisi.'); return; }
    if (!isValidGoogleMapsUrl(mapsVal)) {
        showError('Link Google Maps tidak valid. Gunakan link dari Google Maps (maps.app.goo.gl, google.com/maps, dsb.).');
        return;
    }
    document.getElementById('tambah-produk-tab').click();
}

function addEcommerce() {
    const container = document.getElementById('ecommerceContainer');
    const div = document.createElement('div');
    div.className = 'ecommerce-row d-flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="ecommerce_platform[]" class="form-control w-25" placeholder="Shopee / dll">
        <input type="url" name="ecommerce_url[]" class="form-control w-100" placeholder="https://...">
        <button type="button" class="btn btn-danger px-3" onclick="removeRow(this)" title="Hapus"><i class="bi bi-trash"></i></button>
    `;
    container.appendChild(div);
}

function removeRow(btn) { btn.closest('.ecommerce-row').remove(); }

function addProduk() {
    const container = document.getElementById('produkContainer');
    const idx = produkCount;
    produkCount++;
    const div = document.createElement('div');
    div.className = 'produk-item card shadow-sm border-0 mb-4 bg-light';
    div.dataset.index = idx;
    div.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                <h6 class="fw-bold text-success mb-0">Produk ${produkCount}</h6>
                <button type="button" class="btn btn-danger px-3" onclick="removeProduk(this)" title="Hapus Produk"><i class="bi bi-trash"></i></button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="produk_nama[]" class="form-control" placeholder="Nama produk">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Harga (Rp)</label>
                    <input type="text" name="produk_harga[]" class="form-control" placeholder="Contoh: 50000">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium small">Deskripsi Produk</label>
                    <textarea name="produk_deskripsi[]" class="form-control" rows="3" placeholder="Jelaskan keunggulan produk ini..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium small">Foto Produk (bisa pilih banyak)</label>
                    <input type="file" id="produk_gambar_${idx}" name="produk_gambar_${idx}[]" class="form-control" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div id="preview_gambar_${idx}" class="row g-2 mt-2"></div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    if (typeof initMediaUploader === 'function') {
        initMediaUploader(`produk_gambar_${idx}`, `preview_gambar_${idx}`);
    }
}

function removeProduk(btn) { btn.closest('.produk-item').remove(); }

// ── Validasi submit form ──────────────────────────────────────────────────────
document.getElementById('umkmForm').addEventListener('submit', function(e) {
    const fotoInput = document.getElementById('fotoTokoInput');
    if (!fotoInput.files || !fotoInput.files[0]) {
        e.preventDefault();
        showError('Foto toko wajib diupload.');
        return;
    }
    const mapsVal = document.getElementById('inputMaps').value.trim();
    if (!isValidGoogleMapsUrl(mapsVal)) {
        e.preventDefault();
        showError('Link Google Maps tidak valid. Gunakan link dari Google Maps (maps.app.goo.gl, google.com/maps, dsb.).');
        return;
    }
    const namaProdukInputs = document.querySelectorAll('input[name="produk_nama[]"]');
    const hasProduk = Array.from(namaProdukInputs).some(inp => inp.value.trim() !== '');
    if (!hasProduk) {
        e.preventDefault();
        showError('Minimal 1 produk harus didaftarkan. Isi nama produk di tab Tambah Produk.');
        return;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    if (typeof initMediaUploader === 'function') {
        initMediaUploader('produk_gambar_0', 'preview_gambar_0');
    }
});
</script>
<?= $this->endSection() ?>
