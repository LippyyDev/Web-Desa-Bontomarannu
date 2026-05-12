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
    <div class="d-flex gap-2">
        <a href="<?= base_url('/staff/umkm') ?>" class="btn btn-outline-success">Kembali</a>
    </div>
</div>

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
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header fw-semibold bg-white border-bottom-0 pt-3 pb-0"><i class="bi bi-info-circle me-2"></i>Informasi Umum</div>
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
                        <div class="card-header fw-semibold bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-bag me-2"></i>Link E-Commerce</span>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="addEcommerce()"><i class="bi bi-plus"></i> Tambah</button>
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
                </div>

                <div class="col-lg-4">
                    <!-- Foto Toko -->
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header fw-semibold bg-white border-bottom-0 pt-3 pb-0"><i class="bi bi-image me-2"></i>Foto Toko</div>
                        <div class="card-body">
                            <?php if (!empty($umkm['foto_toko'])): ?>
                            <img id="fotoTokoPreview" src="<?= base_url($umkm['foto_toko']) ?>" alt="Foto Toko" class="w-100 rounded mb-3" style="aspect-ratio:16/9; object-fit:cover; border:1px solid #dee2e6;">
                            <?php else: ?>
                            <div id="fotoTokoPlaceholder" class="text-center text-muted p-4 border rounded mb-3" style="aspect-ratio:16/9; display:flex; align-items:center; justify-content:center; flex-direction:column; background:#f8f9fa;">
                                <i class="bi bi-image fs-1 d-block mb-2 text-secondary"></i>
                                <small>Belum ada foto toko</small>
                            </div>
                            <img id="fotoTokoPreview" src="" alt="" style="display:none; width:100%; aspect-ratio:16/9; object-fit:cover; border-radius:6px; margin-bottom:12px;">
                            <?php endif; ?>
                            <input type="file" name="foto_toko" id="fotoTokoInput" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="previewFotoToko(this)">
                            <div class="form-text text-muted mt-1">Ganti foto · Maks. 1MB · JPG/PNG</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header fw-semibold">Status</div>
                        <div class="card-body">
                            <?php if ($umkm['status'] === 'approved'): ?>
                            <span class="badge bg-success fs-6">Disetujui & Aktif</span>
                            <?php elseif ($umkm['status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark fs-6">Menunggu Persetujuan</span>
                            <?php else: ?>
                            <span class="badge bg-danger fs-6">Ditolak</span>
                            <?php if (!empty($umkm['alasan_tolak'])): ?>
                            <div class="text-muted small mt-2">Alasan: <?= esc($umkm['alasan_tolak']) ?></div>
                            <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($umkm['status'] === 'pending'): ?>
                            <hr>
                            <form method="POST" action="<?= base_url('/staff/umkm/' . $umkm['id'] . '/approve') ?>" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-success btn-sm w-100 mb-1" onclick="return confirm('Setujui UMKM ini?')"><i class="bi bi-check"></i> Setujui</button>
                            </form>
                            <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x"></i> Tolak</button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 sticky-top" style="top:80px;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Aksi Informasi Toko</h6>
                            <button type="submit" class="btn btn-success w-100 mb-2"><i class="bi bi-save"></i> Simpan Informasi</button>
                            <a href="<?= base_url('/staff/umkm/' . $umkm['id'] . '/hapus') ?>" onclick="return confirm('Hapus UMKM ini?')" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Hapus UMKM</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: DAFTAR PRODUK -->
        <div class="tab-pane fade" id="produk-pane" role="tabpanel" aria-labelledby="produk-tab" tabindex="0">
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Produk yang sudah ada -->
                    <?php if (!empty($produk)): ?>
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header fw-semibold bg-white border-bottom-0 pt-3 pb-0"><i class="bi bi-box me-2"></i>Produk yang Sudah Ada</div>
                        <div class="card-body">
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
                                            <i class="bi bi-trash"></i>
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
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body text-center py-5 text-muted">
                            Belum ada produk di toko ini. Tambah di tab "Tambah Produk".
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top:80px;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Informasi</h6>
                            <p class="text-muted small">Gunakan tombol <strong>Edit</strong> untuk mengubah nama, harga, deskripsi, atau foto produk. Tombol hapus (merah) untuk menghapus produk.</p>
                            <a href="<?= base_url('/staff/umkm/' . $umkm['id'] . '/hapus') ?>" onclick="return confirm('Hapus UMKM ini?')" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Hapus UMKM</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: TAMBAH PRODUK -->
        <div class="tab-pane fade" id="tambah-produk-pane" role="tabpanel" aria-labelledby="tambah-produk-tab" tabindex="0">
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Tambah Produk Baru -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header fw-semibold bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru</span>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="addProduk()"><i class="bi bi-plus"></i> Tambah Item</button>
                        </div>
                        <div class="card-body" id="produkContainer">
                            <p class="text-muted small mb-0">Klik "Tambah Item" untuk menambah produk baru ke toko ini.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top:80px;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Aksi Tambah Produk</h6>
                            <button type="submit" class="btn btn-success w-100 mb-2"><i class="bi bi-save"></i> Simpan Produk Baru</button>
                            <a href="<?= base_url('/staff/umkm/' . $umkm['id'] . '/hapus') ?>" onclick="return confirm('Hapus UMKM ini?')" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Hapus UMKM</a>
                        </div>
                    </div>
                </div>
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
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('fotoTokoPreview');
            const placeholder = document.getElementById('fotoTokoPlaceholder');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
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
    div.className = 'produk-item border rounded p-3 mb-3';
    div.innerHTML = `
        <div class="d-flex justify-content-between mb-2">
            <strong>Produk Baru</strong>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.produk-item').remove()"><i class="bi bi-trash"></i></button>
        </div>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label small">Nama Produk</label>
                <input type="text" name="produk_nama[]" class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                <label class="form-label small">Harga (Rp)</label>
                <input type="text" name="produk_harga[]" class="form-control form-control-sm">
            </div>
            <div class="col-12">
                <label class="form-label small">Deskripsi</label>
                <textarea name="produk_deskripsi[]" class="form-control form-control-sm" rows="2"></textarea>
            </div>
            <div class="col-12">
                <label class="form-label small">Foto (bisa lebih dari 1)</label>
                <input type="file" name="produk_gambar_${idx}[]" class="form-control form-control-sm" multiple accept="image/*">
            </div>
        </div>
    `;
    container.appendChild(div);
}
</script>
<?= $this->endSection() ?>
