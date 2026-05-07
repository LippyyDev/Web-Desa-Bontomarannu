<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($title) ?></h4>
        <div class="text-muted small">Tambahkan data toko UMKM baru (langsung aktif).</div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('/staff/umkm') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form method="POST" action="<?= base_url('/staff/umkm') ?>" enctype="multipart/form-data" id="umkmForm">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Kiri -->
        <div class="col-lg-8">

            <!-- Info Toko -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold"><i class="bi bi-shop me-2"></i>Informasi Toko</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Toko <span class="text-danger">*</span></label>
                        <input type="text" name="nama_toko" class="form-control" placeholder="Nama toko UMKM" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Deskripsi Toko</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Ceritakan tentang toko ini..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap toko"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Nomor Kontak</label>
                            <input type="text" name="kontak" class="form-control" placeholder="08xxxxxxxx">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Link Embedded Google Maps</label>
                        <textarea name="maps_embed_url" class="form-control" rows="2" placeholder="Paste link embed atau kode iframe dari Google Maps..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Link E-Commerce -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-bag me-2"></i>Link E-Commerce</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEcommerce()">
                        <i class="bi bi-plus"></i> Tambah
                    </button>
                </div>
                <div class="card-body" id="ecommerceContainer">
                    <div class="ecommerce-row row g-2 mb-2">
                        <div class="col-md-4">
                            <input type="text" name="ecommerce_platform[]" class="form-control" placeholder="Nama platform (Shopee, dll)">
                        </div>
                        <div class="col-md-7">
                            <input type="url" name="ecommerce_url[]" class="form-control" placeholder="https://shopee.co.id/...">
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk -->
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-box me-2"></i>Daftar Produk</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addProduk()">
                        <i class="bi bi-plus"></i> Tambah Produk
                    </button>
                </div>
                <div class="card-body" id="produkContainer">
                    <!-- Template produk awal -->
                    <div class="produk-item border rounded p-3 mb-3" data-index="0">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Produk #1</strong>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProduk(this)"><i class="bi bi-trash"></i> Hapus</button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="produk_nama[]" class="form-control form-control-sm" placeholder="Nama produk">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Harga (Rp)</label>
                                <input type="text" name="produk_harga[]" class="form-control form-control-sm" placeholder="50000">
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Deskripsi Produk</label>
                                <textarea name="produk_deskripsi[]" class="form-control form-control-sm" rows="2" placeholder="Deskripsi singkat produk"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Foto Produk (bisa lebih dari 1)</label>
                                <input type="file" name="produk_gambar_0[]" class="form-control form-control-sm" multiple accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold">Info Status</div>
                <div class="card-body">
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle"></i> UMKM yang dibuat staff langsung <strong>aktif</strong> dan tampil di halaman publik.
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> Simpan UMKM
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
let produkCount = 1;

function addEcommerce() {
    const container = document.getElementById('ecommerceContainer');
    const div = document.createElement('div');
    div.className = 'ecommerce-row row g-2 mb-2';
    div.innerHTML = `
        <div class="col-md-4">
            <input type="text" name="ecommerce_platform[]" class="form-control" placeholder="Nama platform">
        </div>
        <div class="col-md-7">
            <input type="url" name="ecommerce_url[]" class="form-control" placeholder="https://...">
        </div>
        <div class="col-md-1 d-flex align-items-center">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-trash"></i></button>
        </div>
    `;
    container.appendChild(div);
}

function removeRow(btn) {
    btn.closest('.ecommerce-row').remove();
}

function addProduk() {
    const container = document.getElementById('produkContainer');
    const idx = produkCount;
    produkCount++;
    const div = document.createElement('div');
    div.className = 'produk-item border rounded p-3 mb-3';
    div.dataset.index = idx;
    div.innerHTML = `
        <div class="d-flex justify-content-between mb-2">
            <strong>Produk #${produkCount}</strong>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProduk(this)"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label small">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" name="produk_nama[]" class="form-control form-control-sm" placeholder="Nama produk">
            </div>
            <div class="col-md-6">
                <label class="form-label small">Harga (Rp)</label>
                <input type="text" name="produk_harga[]" class="form-control form-control-sm" placeholder="50000">
            </div>
            <div class="col-12">
                <label class="form-label small">Deskripsi Produk</label>
                <textarea name="produk_deskripsi[]" class="form-control form-control-sm" rows="2"></textarea>
            </div>
            <div class="col-12">
                <label class="form-label small">Foto Produk (bisa lebih dari 1)</label>
                <input type="file" name="produk_gambar_${idx}[]" class="form-control form-control-sm" multiple accept="image/*">
            </div>
        </div>
    `;
    container.appendChild(div);
}

function removeProduk(btn) {
    btn.closest('.produk-item').remove();
}
</script>
<?= $this->endSection() ?>
