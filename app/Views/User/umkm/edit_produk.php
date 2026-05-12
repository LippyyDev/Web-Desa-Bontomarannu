<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            UMKM SAYA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Edit <span style="color: #15803d;">Produk</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Ubah nama, harga, deskripsi, atau foto produk Anda.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/user/umkm/' . $umkm['id'] . '/edit') ?>" class="btn btn-outline-success">Kembali</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="<?= base_url('/user/umkm/produk/' . $produk['id']) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold bg-white border-bottom-0 pt-3 pb-0"><i class="bi bi-pencil me-2"></i>Informasi Produk</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama_produk" class="form-control" value="<?= esc($produk['nama_produk']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Harga (Rp)</label>
                        <input type="text" name="harga" class="form-control" value="<?= $produk['harga'] ? number_format((float)$produk['harga'], 0, ',', '') : '' ?>" placeholder="Contoh: 50000">
                        <div class="form-text text-muted">Kosongkan jika harga tidak ditentukan.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan keunggulan produk ini..."><?= esc($produk['deskripsi']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Tambah Foto Baru (opsional)</label>
                        <input type="file" name="gambar_baru[]" class="form-control" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                        <div class="form-text text-muted">Foto baru akan ditambahkan ke foto yang sudah ada. Maks. 1MB per foto · JPG/PNG.</div>
                    </div>
                </div>
            </div>

            <!-- Foto yang sudah ada -->
            <?php if (!empty($produk['gambar'])): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold bg-white border-bottom-0 pt-3 pb-0"><i class="bi bi-images me-2"></i>Foto Produk Saat Ini</div>
                <div class="card-body">
                    <div class="d-flex gap-3 flex-wrap">
                        <?php foreach ($produk['gambar'] as $g): ?>
                        <div class="position-relative">
                            <img src="<?= base_url($g['gambar_path']) ?>" alt="Foto Produk"
                                 style="height: 120px; width: 120px; object-fit:cover; border-radius:8px; border:1px solid #dee2e6;">
                            <a href="<?= base_url('/user/umkm/gambar-produk/' . $g['id'] . '/hapus') ?>"
                               onclick="return confirm('Hapus foto ini?')"
                               class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 px-1"
                               style="font-size:12px; border-radius:0 8px 0 8px; line-height:1.6;">×</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                <a href="<?= base_url('/user/umkm/' . $umkm['id'] . '/edit') ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 80px;">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Informasi</h6>
                <p class="text-muted small">Perubahan pada produk akan <strong>langsung tersimpan</strong>. Tidak perlu menunggu persetujuan staff lagi.</p>
                <hr>
                <a href="<?= base_url('/user/umkm/produk/' . $produk['id'] . '/hapus') ?>"
                   onclick="return confirm('Hapus produk ini beserta semua fotonya?')"
                   class="btn btn-outline-danger w-100">
                    <i class="bi bi-trash me-1"></i> Hapus Produk Ini
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
