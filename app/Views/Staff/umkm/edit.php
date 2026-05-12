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
            KELOLA UMKM
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Edit <span style="color: #15803d;">UMKM</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Ubah informasi toko, produk, atau kelola gambar.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= base_url('/staff/umkm') ?>" class="btn btn-outline-success">Kembali</a>
        <a href="<?= base_url('/staff/umkm/' . $umkm['id'] . '/hapus') ?>" onclick="return confirm('Hapus UMKM ini secara permanen?')" class="btn btn-outline-danger"><i class="bi bi-trash"></i> Hapus UMKM</a>
    </div>
</div>

<!-- Status Banner (Jika Perlu Persetujuan) -->
<?php if ($umkm['status'] === 'pending'): ?>
<div class="card border-warning mb-4 shadow-sm">
    <div class="card-body bg-warning bg-opacity-10 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div>
            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Menunggu Persetujuan</h6>
            <p class="mb-0 text-muted small">Toko ini diajukan oleh warga dan menunggu persetujuan Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <form method="POST" action="<?= base_url('/staff/umkm/' . $umkm['id'] . '/approve') ?>" class="m-0">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-success" onclick="return confirm('Setujui UMKM ini?')"><i class="bi bi-check"></i> Setujui</button>
            </form>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x"></i> Tolak</button>
        </div>
    </div>
</div>
<?php elseif ($umkm['status'] === 'rejected'): ?>
<div class="alert alert-danger shadow-sm border-danger">
    <i class="bi bi-x-circle-fill me-2"></i> UMKM ini telah <strong>ditolak</strong>.
    <?php if (!empty($umkm['alasan_tolak'])): ?>
    <br><small class="ms-4">Alasan: <?= esc($umkm['alasan_tolak']) ?></small>
    <?php endif; ?>
</div>
<?php endif; ?>

<form method="POST" action="<?= base_url('/staff/umkm/' . $umkm['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Nav Tabs Selector -->
    <ul class="nav nav-pills mb-4 gap-2" id="umkmEditTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-medium px-4" id="info-tab" data-bs-toggle="pill" data-bs-target="#info-pane" type="button" role="tab" aria-controls="info-pane" aria-selected="true"><i class="bi bi-shop me-2"></i>Informasi Toko</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-medium px-4" id="produk-tab" data-bs-toggle="pill" data-bs-target="#produk-pane" type="button" role="tab" aria-controls="produk-pane" aria-selected="false"><i class="bi bi-box-seam me-2"></i>Daftar Produk</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-medium px-4" id="tambah-produk-tab" data-bs-toggle="pill" data-bs-target="#tambah-produk-pane" type="button" role="tab" aria-controls="tambah-produk-pane" aria-selected="false"><i class="bi bi-plus-circle me-2"></i>Tambah Produk</button>
        </li>
    </ul>

    <div class="tab-content" id="umkmEditTabContent">
        <!-- TAB 1: INFORMASI TOKO -->
        <div class="tab-pane fade show active" id="info-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
            
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Foto Toko</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <input type="file" name="foto_toko" id="fotoTokoInput" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="previewFotoToko(this)">
                            <div class="form-text text-muted mt-1">Biarkan kosong jika tidak ingin mengubah foto. Maks. 1MB · JPG/PNG</div>
                            <div id="fotoTokoPreviewContainer" class="mt-2" style="<?= empty($umkm['foto_toko']) ? 'display:none;' : '' ?>">
                                <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                    <div class="card border shadow-sm overflow-hidden mb-0">
                                        <img id="fotoTokoPreview" src="<?= !empty($umkm['foto_toko']) ? base_url($umkm['foto_toko']) : '' ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="Preview Foto Toko">
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
                            <input type="text" name="nama_toko" class="form-control" value="<?= esc($umkm['nama_toko']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Kontak</label>
                            <input type="text" name="kontak" class="form-control" value="<?= esc($umkm['kontak']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4"><?= esc($umkm['deskripsi']) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"><?= esc($umkm['alamat']) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Link Embedded Google Maps</label>
                            <textarea name="maps_embed_url" class="form-control" rows="2"><?= esc($umkm['maps_embed_url']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Link E-Commerce</h5>
                    <div id="ecommerceContainer">
                        <?php if (!empty($ecommerce)): ?>
                            <?php foreach ($ecommerce as $e): ?>
                            <div class="ecommerce-row row g-2 mb-2">
                                <div class="col-md-4">
                                    <input type="text" name="ecommerce_platform[]" class="form-control" value="<?= esc($e['platform']) ?>" placeholder="Nama platform">
                                </div>
                                <div class="col-md-7">
                                    <input type="url" name="ecommerce_url[]" class="form-control" value="<?= esc($e['url']) ?>" placeholder="https://...">
                                </div>
                                <div class="col-md-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="ecommerce-row row g-2 mb-2">
                                <div class="col-md-4"><input type="text" name="ecommerce_platform[]" class="form-control" placeholder="Nama platform"></div>
                                <div class="col-md-7"><input type="url" name="ecommerce_url[]" class="form-control" placeholder="https://..."></div>
                                <div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm mt-1" onclick="addEcommerce()"><i class="bi bi-plus"></i> Tambah Link</button>
                </div>
            </div>

            <div class="mt-4 mb-4">
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Simpan Perubahan Toko</button>
            </div>
        </div>

        <!-- TAB 2: DAFTAR PRODUK -->
        <div class="tab-pane fade" id="produk-pane" role="tabpanel" aria-labelledby="produk-tab" tabindex="0">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Produk yang Sudah Ada</h5>
                    <?php if (!empty($produk)): ?>
                        <?php foreach ($produk as $p): ?>
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong class="fs-6"><?= esc($p['nama_produk']) ?></strong>
                                    <?php if ($p['harga']): ?>
                                    <div class="text-success small fw-semibold">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($p['deskripsi'])): ?>
                                    <div class="text-muted small mt-1"><?= esc(mb_substr($p['deskripsi'], 0, 100)) ?><?= mb_strlen($p['deskripsi']) > 100 ? '...' : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-1 flex-shrink-0">
                                    <a href="<?= base_url('/staff/umkm/produk/' . $p['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="<?= base_url('/staff/umkm/produk/' . $p['id'] . '/hapus') ?>"
                                       onclick="return confirm('Hapus produk ini beserta semua gambarnya?')"
                                       class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                            <?php if (!empty($p['gambar'])): ?>
                            <div class="d-flex gap-2 flex-wrap mt-2">
                                <?php foreach ($p['gambar'] as $g): ?>
                                <div class="position-relative">
                                    <img src="<?= base_url($g['gambar_path']) ?>" style="height:80px;width:80px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                                    <a href="<?= base_url('/staff/umkm/gambar-produk/' . $g['id'] . '/hapus') ?>"
                                       onclick="return confirm('Hapus gambar ini?')"
                                       class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 px-1"
                                       style="font-size:10px; border-radius:0 6px 0 6px; line-height:1.6;">×</a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            Belum ada produk di toko ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- TAB 3: TAMBAH PRODUK -->
        <div class="tab-pane fade" id="tambah-produk-pane" role="tabpanel" aria-labelledby="tambah-produk-tab" tabindex="0">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Tambah Produk Baru</h5>
                    <div id="produkContainer">
                        <p class="text-muted small mb-0">Klik "Tambah Item Produk" untuk menambah produk baru ke toko ini saat menyimpan.</p>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm mt-3" onclick="addProduk()">
                        <i class="bi bi-plus"></i> Tambah Item Produk
                    </button>
                </div>
            </div>

            <div class="mt-4 mb-4">
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<!-- Modal Tolak UMKM -->
<?php if ($umkm['status'] === 'pending'): ?>
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak UMKM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= base_url('/staff/umkm/' . $umkm['id'] . '/reject') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <label class="form-label fw-medium">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="alasan" class="form-control" rows="4" placeholder="Jelaskan alasan penolakan..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak UMKM</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
let produkCount = 0;

function previewFotoToko(input) {
    const container = document.getElementById('fotoTokoPreviewContainer');
    const preview = document.getElementById('fotoTokoPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        // if clearing the input, restore the original photo if it exists
        const origSrc = "<?= !empty($umkm['foto_toko']) ? base_url($umkm['foto_toko']) : '' ?>";
        if (origSrc) {
            preview.src = origSrc;
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
            preview.src = '';
        }
    }
}

function addEcommerce() {
    const container = document.getElementById('ecommerceContainer');
    const div = document.createElement('div');
    div.className = 'ecommerce-row row g-2 mb-2';
    div.innerHTML = `
        <div class="col-md-4"><input type="text" name="ecommerce_platform[]" class="form-control" placeholder="Nama platform"></div>
        <div class="col-md-7"><input type="url" name="ecommerce_url[]" class="form-control" placeholder="https://..."></div>
        <div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></div>
    `;
    container.appendChild(div);
}

function removeRow(btn) { btn.closest('.ecommerce-row').remove(); }

function addProduk() {
    const container = document.getElementById('produkContainer');
    const msg = container.querySelector('p');
    if (msg) msg.remove();
    const idx = produkCount++;
    const div = document.createElement('div');
    div.className = 'produk-item card shadow-sm border-0 mb-4 bg-light';
    div.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                <h6 class="fw-bold text-success mb-0">Produk Baru</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.produk-item').remove()"><i class="bi bi-trash"></i></button>
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
</script>
<?= $this->endSection() ?>
