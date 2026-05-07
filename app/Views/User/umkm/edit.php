<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($title) ?></h4>
        <div class="text-muted small">Edit informasi toko UMKM Anda.</div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('/user/umkm/' . $umkm['id']) ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<?php if ($umkm['status'] === 'rejected'): ?>
<div class="alert alert-warning">
    <i class="bi bi-arrow-repeat"></i> <strong>Toko Ditolak.</strong> Perbaiki data di bawah dan simpan untuk <strong>mengirim ulang</strong> ke perangkat desa.
</div>
<?php endif; ?>

<form method="POST" action="<?= base_url('/user/umkm/' . $umkm['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold"><i class="bi bi-shop me-2"></i>Informasi Toko</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Toko <span class="text-danger">*</span></label>
                        <input type="text" name="nama_toko" class="form-control" value="<?= esc($umkm['nama_toko']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4"><?= esc($umkm['deskripsi']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"><?= esc($umkm['alamat']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Kontak</label>
                        <input type="text" name="kontak" class="form-control" value="<?= esc($umkm['kontak']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Link Embedded Google Maps</label>
                        <textarea name="maps_embed_url" class="form-control" rows="2"><?= esc($umkm['maps_embed_url']) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold d-flex justify-content-between">
                    <span><i class="bi bi-bag me-2"></i>Link E-Commerce</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEcommerce()"><i class="bi bi-plus"></i> Tambah</button>
                </div>
                <div class="card-body" id="ecommerceContainer">
                    <?php if (!empty($ecommerce)): ?>
                        <?php foreach ($ecommerce as $e): ?>
                        <div class="ecommerce-row row g-2 mb-2">
                            <div class="col-md-4"><input type="text" name="ecommerce_platform[]" class="form-control" value="<?= esc($e['platform']) ?>"></div>
                            <div class="col-md-7"><input type="url" name="ecommerce_url[]" class="form-control" value="<?= esc($e['url']) ?>"></div>
                            <div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="ecommerce-row row g-2 mb-2">
                            <div class="col-md-4"><input type="text" name="ecommerce_platform[]" class="form-control" placeholder="Shopee / Tokopedia / dll"></div>
                            <div class="col-md-7"><input type="url" name="ecommerce_url[]" class="form-control" placeholder="https://..."></div>
                            <div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Produk existing -->
            <?php if (!empty($produk)): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold">Produk yang Sudah Ada</div>
                <div class="card-body">
                    <?php foreach ($produk as $p): ?>
                    <div class="border rounded p-3 mb-2">
                        <div class="fw-medium mb-1"><?= esc($p['nama_produk']) ?> <?php if ($p['harga']): ?><span class="text-success">— Rp <?= number_format($p['harga'], 0, ',', '.') ?></span><?php endif; ?></div>
                        <?php if (!empty($p['gambar'])): ?>
                        <div class="d-flex gap-1 flex-wrap">
                            <?php foreach ($p['gambar'] as $g): ?>
                            <img src="<?= base_url($g['gambar_path']) ?>" style="height:60px;width:60px;object-fit:cover;border-radius:4px;">
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    <div class="text-muted small mt-2"><i class="bi bi-info-circle"></i> Untuk mengubah/hapus produk yang sudah ada, hubungi perangkat desa atau hapus toko dan buat ulang.</div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Produk baru -->
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold d-flex justify-content-between">
                    <span><i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addProduk()"><i class="bi bi-plus"></i> Tambah</button>
                </div>
                <div class="card-body" id="produkContainer">
                    <p class="text-muted small mb-0">Klik "Tambah" untuk menambah produk baru.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top:80px;">
                <div class="card-body">
                    <?php if ($umkm['status'] === 'rejected'): ?>
                    <button type="submit" class="btn btn-warning w-100 mb-2"><i class="bi bi-arrow-repeat"></i> Simpan & Kirim Ulang</button>
                    <?php else: ?>
                    <button type="submit" class="btn btn-primary w-100 mb-2"><i class="bi bi-save"></i> Simpan Perubahan</button>
                    <?php endif; ?>
                    <a href="<?= base_url('/user/umkm/' . $umkm['id'] . '/hapus') ?>" onclick="return confirm('Hapus toko ini?')" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Hapus Toko</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
let produkCount = 0;
function addEcommerce() {
    const container = document.getElementById('ecommerceContainer');
    const div = document.createElement('div');
    div.className = 'ecommerce-row row g-2 mb-2';
    div.innerHTML = `<div class="col-md-4"><input type="text" name="ecommerce_platform[]" class="form-control" placeholder="Shopee / dll"></div><div class="col-md-7"><input type="url" name="ecommerce_url[]" class="form-control" placeholder="https://..."></div><div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></div>`;
    container.appendChild(div);
}
function removeRow(btn) { btn.closest('.ecommerce-row').remove(); }
function addProduk() {
    const container = document.getElementById('produkContainer');
    const msg = container.querySelector('p');
    if (msg) msg.remove();
    const idx = produkCount++;
    const div = document.createElement('div');
    div.className = 'produk-item border rounded p-3 mb-3';
    div.innerHTML = `<div class="d-flex justify-content-between mb-2"><strong>Produk Baru</strong><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.produk-item').remove()"><i class="bi bi-trash"></i></button></div><div class="row g-2"><div class="col-md-6"><label class="form-label small">Nama Produk</label><input type="text" name="produk_nama[]" class="form-control form-control-sm"></div><div class="col-md-6"><label class="form-label small">Harga (Rp)</label><input type="text" name="produk_harga[]" class="form-control form-control-sm"></div><div class="col-12"><label class="form-label small">Deskripsi</label><textarea name="produk_deskripsi[]" class="form-control form-control-sm" rows="2"></textarea></div><div class="col-12"><label class="form-label small">Foto</label><input type="file" name="produk_gambar_${idx}[]" class="form-control form-control-sm" multiple accept="image/*"></div></div>`;
    container.appendChild(div);
}
</script>
<?= $this->endSection() ?>
