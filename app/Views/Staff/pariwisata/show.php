<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($item['nama_tempat']) ?></h4>
        <div class="text-muted small">Detail destinasi wisata</div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('/staff/pariwisata/' . $item['id'] . '/edit') ?>" class="btn btn-primary btn-sm me-2">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?= base_url('/staff/pariwisata') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <?php if ($item['thumbnail']): ?>
        <img src="<?= base_url($item['thumbnail']) ?>" class="img-fluid rounded mb-4 w-100" style="max-height:350px;object-fit:cover;">
        <?php endif; ?>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5>Deskripsi</h5>
                <p><?= nl2br(esc($item['deskripsi'] ?? 'Tidak ada deskripsi.')) ?></p>
            </div>
        </div>

        <?php if (!empty($gambar)): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header fw-semibold">Galeri Foto</div>
            <div class="card-body">
                <div class="row g-2">
                    <?php foreach ($gambar as $g): ?>
                    <div class="col-6 col-md-4">
                        <img src="<?= base_url($g['gambar_path']) ?>" class="img-fluid rounded" style="height:150px;width:100%;object-fit:cover;">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header fw-semibold">Informasi</div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="text-muted small">Alamat</span>
                    <div><?= esc($item['alamat'] ?? '-') ?></div>
                </div>
                <div class="mb-2">
                    <span class="text-muted small">Ditambahkan</span>
                    <div><?= date('d M Y', strtotime($item['created_at'])) ?></div>
                </div>
            </div>
        </div>

        <?php if (!empty($item['maps_embed_url'])): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header fw-semibold">Lokasi di Peta</div>
            <div class="card-body p-0">
                <iframe src="<?= esc($item['maps_embed_url']) ?>" width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
