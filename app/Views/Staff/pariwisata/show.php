<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            UMKM & PARIWISATA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Detail <span style="color: #15803d;">Pariwisata</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Informasi lengkap destinasi wisata.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/staff/pariwisata/' . $item['id'] . '/edit') ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?= base_url('/staff/pariwisata') ?>" class="btn btn-outline-success">
            Kembali
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4 fw-bold">Informasi Dasar</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-medium text-muted small mb-1">Nama Tempat Wisata</label>
                <div class="fs-5 fw-semibold"><?= esc($item['nama_tempat']) ?></div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium text-muted small mb-1">Ditambahkan Pada</label>
                <div><?= date('d M Y', strtotime($item['created_at'])) ?></div>
            </div>
            <div class="col-12 mt-3">
                <label class="form-label fw-medium text-muted small mb-1">Deskripsi</label>
                <p class="mb-0"><?= nl2br(esc($item['deskripsi'] ?? 'Tidak ada deskripsi.')) ?></p>
            </div>
            <div class="col-12 mt-3">
                <label class="form-label fw-medium text-muted small mb-1">Alamat</label>
                <div class="mb-3"><?= esc($item['alamat'] ?? '-') ?></div>
                
                <?php if (!empty($item['maps_embed_url'])): ?>
                    <div class="rounded overflow-hidden border">
                        <iframe src="<?= esc($item['maps_embed_url']) ?>" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4 fw-bold">Media</h5>
        <div class="row g-4">
            <?php if ($item['thumbnail']): ?>
            <div class="col-12">
                <label class="form-label fw-medium text-muted small mb-2">Thumbnail Utama</label>
                <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                    <div class="card border shadow-sm overflow-hidden mb-0">
                        <img src="<?= base_url($item['thumbnail']) ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="thumbnail">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($gambar)): ?>
            <div class="col-12">
                <label class="form-label fw-medium text-muted small mb-2">Galeri Foto</label>
                <div class="row g-3">
                    <?php foreach ($gambar as $g): ?>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <div class="card border shadow-sm overflow-hidden mb-0">
                                <img src="<?= base_url($g['gambar_path']) ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="galeri">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="col-12">
                <p class="text-muted small">Belum ada media foto untuk destinasi wisata ini.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
