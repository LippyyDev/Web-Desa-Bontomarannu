<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($title) ?></h4>
        <div class="text-muted small">Perbarui informasi pariwisata.</div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('/staff/pariwisata') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form method="POST" action="<?= base_url('/staff/pariwisata/' . $item['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold">Informasi Pariwisata</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Tempat Wisata <span class="text-danger">*</span></label>
                        <input type="text" name="nama_tempat" class="form-control" value="<?= esc($item['nama_tempat']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="5"><?= esc($item['deskripsi']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"><?= esc($item['alamat']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Link Embedded Google Maps</label>
                        <textarea name="maps_embed_url" class="form-control" rows="3"><?= esc($item['maps_embed_url']) ?></textarea>
                        <div class="form-text">Bisa paste link biasa maupun kode iframe lengkap dari Google Maps.</div>
                    </div>
                </div>
            </div>

            <!-- Galeri Foto yang sudah ada -->
            <?php if (!empty($gambar)): ?>
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header fw-semibold">Foto yang Sudah Ada</div>
                <div class="card-body">
                    <div class="row g-2">
                        <?php foreach ($gambar as $g): ?>
                        <div class="col-4 col-md-3 position-relative" id="gambar-<?= $g['id'] ?>">
                            <img src="<?= base_url($g['gambar_path']) ?>" class="img-fluid rounded" style="height:100px;width:100%;object-fit:cover;">
                            <a href="<?= base_url('/staff/pariwisata/gambar/' . $g['id'] . '/hapus') ?>"
                               onclick="return confirm('Hapus gambar ini?')"
                               class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-0 px-1">
                                <i class="bi bi-x"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Upload foto baru -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header fw-semibold">Tambah Foto Baru</div>
                <div class="card-body">
                    <input type="file" name="gambar[]" class="form-control" multiple accept="image/*">
                    <div class="form-text">Foto baru akan ditambahkan ke galeri yang sudah ada.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold">Thumbnail Utama</div>
                <div class="card-body">
                    <?php if ($item['thumbnail']): ?>
                    <img src="<?= base_url($item['thumbnail']) ?>" class="img-fluid rounded mb-2">
                    <?php endif; ?>
                    <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept="image/*">
                    <div class="form-text mt-1">Kosongkan jika tidak ingin mengubah.</div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                    <a href="<?= base_url('/staff/pariwisata/' . $item['id'] . '/hapus') ?>"
                       onclick="return confirm('Hapus pariwisata ini beserta semua fotonya?')"
                       class="btn btn-outline-danger w-100 mt-2">
                        <i class="bi bi-trash"></i> Hapus Pariwisata
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
