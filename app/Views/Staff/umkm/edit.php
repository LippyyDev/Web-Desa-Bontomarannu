<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($title) ?></h4>
        <div class="text-muted small">Perbarui informasi UMKM.</div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('/staff/umkm/' . $umkm['id']) ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form method="POST" action="<?= base_url('/staff/umkm/' . $umkm['id']) ?>" enctype="multipart/form-data">
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

            <!-- E-Commerce -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-bag me-2"></i>Link E-Commerce</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEcommerce()"><i class="bi bi-plus"></i> Tambah</button>
                </div>
                <div class="card-body" id="ecommerceContainer">
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
            </div>

            <!-- Produk yang sudah ada -->
            <?php if (!empty($produk)): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold"><i class="bi bi-box me-2"></i>Produk yang Sudah Ada</div>
                <div class="card-body">
                    <?php foreach ($produk as $p): ?>
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong><?= esc($p['nama_produk']) ?></strong>
                            <a href="<?= base_url('/staff/umkm/produk/' . $p['id'] . '/hapus') ?>"
                               onclick="return confirm('Hapus produk ini beserta semua gambarnya?')"
                               class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Hapus Produk
                            </a>
                        </div>
                        <?php if ($p['harga']): ?>
                        <div class="text-success small mb-1">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                        <?php endif; ?>
                        <?php if (!empty($p['gambar'])): ?>
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            <?php foreach ($p['gambar'] as $g): ?>
                            <div class="position-relative">
                                <img src="<?= base_url($g['gambar_path']) ?>" style="height:70px;width:70px;object-fit:cover;border-radius:4px;">
                                <a href="<?= base_url('/staff/umkm/gambar-produk/' . $g['id'] . '/hapus') ?>"
                                   onclick="return confirm('Hapus gambar ini?')"
                                   class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 px-1" style="font-size:10px;">×</a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tambah Produk Baru -->
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addProduk()"><i class="bi bi-plus"></i> Tambah</button>
                </div>
                <div class="card-body" id="produkContainer">
                    <p class="text-muted small mb-0">Klik "Tambah" untuk menambah produk baru.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header fw-semibold">Status</div>
                <div class="card-body">
                    <?php if ($umkm['status'] === 'approved'): ?>
                    <span class="badge bg-success fs-6">Disetujui & Aktif</span>
                    <?php elseif ($umkm['status'] === 'pending'): ?>
                    <span class="badge bg-warning text-dark fs-6">Menunggu Persetujuan</span>
                    <?php else: ?>
                    <span class="badge bg-danger fs-6">Ditolak</span>
                    <?php if ($umkm['alasan_tolak']): ?>
                    <div class="mt-2 small text-muted">Alasan: <?= esc($umkm['alasan_tolak']) ?></div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Simpan Perubahan</button>
                    <a href="<?= base_url('/staff/umkm/' . $umkm['id'] . '/hapus') ?>"
                       onclick="return confirm('Hapus UMKM ini?')"
                       class="btn btn-outline-danger w-100 mt-2"><i class="bi bi-trash"></i> Hapus</a>
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
    const emptyMsg = container.querySelector('p');
    if (emptyMsg) emptyMsg.remove();

    const idx = produkCount++;
    const div = document.createElement('div');
    div.className = 'produk-item border rounded p-3 mb-3';
    div.innerHTML = `
        <div class="d-flex justify-content-between mb-2">
            <strong>Produk Baru</strong>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.produk-item').remove()"><i class="bi bi-trash"></i></button>
        </div>
        <div class="row g-2">
            <div class="col-md-6"><label class="form-label small">Nama Produk</label><input type="text" name="produk_nama[]" class="form-control form-control-sm"></div>
            <div class="col-md-6"><label class="form-label small">Harga (Rp)</label><input type="text" name="produk_harga[]" class="form-control form-control-sm"></div>
            <div class="col-12"><label class="form-label small">Deskripsi</label><textarea name="produk_deskripsi[]" class="form-control form-control-sm" rows="2"></textarea></div>
            <div class="col-12"><label class="form-label small">Foto (bisa lebih dari 1)</label><input type="file" name="produk_gambar_${idx}[]" class="form-control form-control-sm" multiple accept="image/*"></div>
        </div>
    `;
    container.appendChild(div);
}
</script>
<?= $this->endSection() ?>
