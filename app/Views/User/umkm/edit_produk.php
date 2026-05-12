<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>

<!-- Page Header Dashboard Style -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            UMKM SAYA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Edit <span style="color: #15803d;">Produk</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Ubah nama, harga, deskripsi, atau kelola foto produk Anda.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/user/umkm/produk/' . $produk['id'] . '/hapus') ?>"
           id="btnHapusProduk"
           data-href="<?= base_url('/user/umkm/produk/' . $produk['id'] . '/hapus') ?>"
           class="btn btn-danger" title="Hapus Produk">Hapus</a>
        <a href="<?= base_url('/user/umkm/' . $umkm['id'] . '/edit') ?>" class="btn btn-outline-success">Kembali</a>
    </div>
</div>

<form method="POST" action="<?= base_url('/user/umkm/produk/' . $produk['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Produk</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk" class="form-control" value="<?= esc($produk['nama_produk']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Harga (Rp)</label>
                    <input type="text" name="harga" class="form-control" value="<?= $produk['harga'] ? number_format((float)$produk['harga'], 0, ',', '') : '' ?>" placeholder="Contoh: 50000">
                    <div class="form-text text-muted">Kosongkan jika harga tidak ditentukan.</div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Deskripsi Produk</label>
                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan keunggulan produk ini..."><?= esc($produk['deskripsi']) ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Tambah Foto Baru (opsional)</label>
                    <input type="file" id="gambarBaruInput" name="gambar_baru[]" class="form-control" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div class="form-text text-muted">Foto baru akan ditambahkan ke foto yang sudah ada. Maks. 1MB per foto · JPG/PNG.</div>
                    <div id="gambarBaruPreview" class="row g-2 mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Foto yang sudah ada -->
    <?php if (!empty($produk['gambar'])): ?>
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Foto Produk Saat Ini</h5>
            <div class="d-flex gap-3 flex-wrap">
                <?php foreach ($produk['gambar'] as $g): ?>
                <div class="position-relative">
                    <img src="<?= base_url($g['gambar_path']) ?>" alt="Foto Produk"
                         style="height: 120px; width: 120px; object-fit:cover; border-radius:8px; border:1px solid #dee2e6;">
                    <a href="<?= base_url('/user/umkm/gambar-produk/' . $g['id'] . '/hapus') ?>"
                       class="btn btn-danger btn-hapus-foto position-absolute"
                       data-href="<?= base_url('/user/umkm/gambar-produk/' . $g['id'] . '/hapus') ?>"
                       style="top: 6px; right: 6px; z-index: 1000; width: 26px; height: 26px; padding: 0; line-height: 1; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" title="Hapus Gambar"><i class="bi bi-trash" style="font-size: 14px;"></i></a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="mt-4 mb-4">
        <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Simpan Perubahan Produk</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof initMediaUploader === 'function') {
        initMediaUploader('gambarBaruInput', 'gambarBaruPreview');
    }

    // Hapus Produk — SweetAlert confirm
    const btnHapusProduk = document.getElementById('btnHapusProduk');
    if (btnHapusProduk) {
        btnHapusProduk.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.dataset.href;
            showConfirm('Hapus produk ini beserta semua fotonya?', 'Hapus Produk', 'Ya, Hapus')
                .then(ok => { if (ok) window.location.href = href; });
        });
    }

    // Hapus Foto — SweetAlert confirm
    document.querySelectorAll('.btn-hapus-foto').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.dataset.href;
            showConfirm('Hapus foto ini secara permanen?', 'Hapus Foto', 'Ya, Hapus')
                .then(ok => { if (ok) window.location.href = href; });
        });
    });
});
</script>

<?= $this->endSection() ?>
