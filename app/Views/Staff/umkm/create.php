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

<div class="alert alert-success mb-4 border-0 shadow-sm">
    <i class="bi bi-check-circle me-2"></i> UMKM yang dibuat staff langsung <strong>aktif</strong> dan tampil di halaman publik.
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
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4 fw-bold">Informasi Umum</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Nama Toko <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_toko" class="form-control" placeholder="Nama toko UMKM" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Kontak</label>
                                    <input type="text" name="kontak" class="form-control" placeholder="08xxxxxxxx">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Ceritakan tentang toko ini..."></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap toko"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Link Embedded Google Maps</label>
                                    <textarea name="maps_embed_url" class="form-control" rows="2" placeholder="Paste link embed atau kode iframe dari Google Maps..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- E-Commerce -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4 fw-bold">Link E-Commerce (opsional)</h5>
                            <div id="ecommerceContainer">
                                <div class="ecommerce-row row g-2 mb-2">
                                    <div class="col-md-4"><input type="text" name="ecommerce_platform[]" class="form-control" placeholder="Nama platform"></div>
                                    <div class="col-md-7"><input type="url" name="ecommerce_url[]" class="form-control" placeholder="https://..."></div>
                                    <div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-danger btn-sm px-3" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm text-success fw-semibold border-0 mt-1 px-0" onclick="addEcommerce()"><i class="bi bi-plus"></i> Tambah Link</button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar: Foto Toko -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header fw-semibold bg-white border-bottom-0 pt-3 pb-0"><i class="bi bi-image me-2"></i>Foto Toko</div>
                        <div class="card-body">
                            <div class="text-center text-muted p-3 border rounded mb-3" id="fotoTokoPlaceholder" style="aspect-ratio:16/9; display:flex; align-items:center; justify-content:center; flex-direction:column; background:#f8f9fa;">
                                <i class="bi bi-image fs-1 d-block mb-2 text-secondary"></i>
                                <small>Preview foto toko</small>
                            </div>
                            <img id="fotoTokoPreview" src="" alt="" style="display:none; width:100%; aspect-ratio:16/9; object-fit:cover; border-radius:6px; margin-bottom:12px;">
                            <input type="file" name="foto_toko" id="fotoTokoInput" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="previewFotoToko(this)">
                            <div class="form-text text-muted mt-1">Maks. 1MB · JPG/PNG · Rasio 16:9 disarankan</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 mb-4">
                <button type="button" class="btn btn-success" onclick="document.getElementById('tambah-produk-tab').click()">
                    Konfirmasi & Lanjut ke Produk
                </button>
            </div>
        </div>

        <!-- TAB 2: TAMBAH PRODUK -->
        <div class="tab-pane fade" id="tambah-produk-pane" role="tabpanel" aria-labelledby="tambah-produk-tab" tabindex="0">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Daftar Produk</h5>

                    <div id="produkContainer">
                        <!-- Template produk awal -->
                        <div class="produk-item card shadow-sm border-0 mb-4 bg-light" data-index="0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <h6 class="fw-bold text-success mb-0">Produk 1</h6>
                                    <button type="button" class="btn btn-sm btn-danger px-3" onclick="removeProduk(this)"><i class="bi bi-trash"></i></button>
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
                                        <input type="file" name="produk_gambar_0[]" class="form-control" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm text-success fw-semibold border-0 mt-1 px-0" onclick="addProduk()">
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

function previewFotoToko(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('fotoTokoPreview').src = e.target.result;
            document.getElementById('fotoTokoPreview').style.display = 'block';
            document.getElementById('fotoTokoPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function addEcommerce() {
    const container = document.getElementById('ecommerceContainer');
    const div = document.createElement('div');
    div.className = 'ecommerce-row row g-2 mb-2';
    div.innerHTML = `
        <div class="col-md-4"><input type="text" name="ecommerce_platform[]" class="form-control" placeholder="Nama platform"></div>
        <div class="col-md-7"><input type="url" name="ecommerce_url[]" class="form-control" placeholder="https://..."></div>
        <div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-danger btn-sm px-3" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></div>
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
                <button type="button" class="btn btn-sm btn-danger px-3" onclick="removeProduk(this)"><i class="bi bi-trash"></i></button>
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
                    <input type="file" name="produk_gambar_${idx}[]" class="form-control" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function removeProduk(btn) { btn.closest('.produk-item').remove(); }
</script>
<?= $this->endSection() ?>
