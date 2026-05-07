<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container mt-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/pariwisata') ?>">Pariwisata</a></li>
            <li class="breadcrumb-item active"><?= esc($item['nama_tempat']) ?></li>
        </ol>
    </nav>

    <!-- Thumbnail / Galeri Hero -->
    <?php if (!empty($gambar)): ?>
    <div id="heroCarousel" class="carousel slide mb-4 rounded overflow-hidden shadow" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($gambar as $gi => $g): ?>
            <div class="carousel-item <?= $gi === 0 ? 'active' : '' ?>">
                <img src="<?= base_url($g['gambar_path']) ?>" class="d-block w-100" style="height:400px;object-fit:cover;" alt="<?= esc($item['nama_tempat']) ?>">
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($gambar) > 1): ?>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        <div class="carousel-indicators">
            <?php foreach ($gambar as $gi => $g): ?>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $gi ?>" <?= $gi === 0 ? 'class="active"' : '' ?>></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php elseif ($item['thumbnail']): ?>
    <img src="<?= base_url($item['thumbnail']) ?>" class="img-fluid w-100 rounded mb-4 shadow" style="max-height:400px;object-fit:cover;">
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h2 class="fw-bold"><?= esc($item['nama_tempat']) ?></h2>
                    <?php if ($item['alamat']): ?>
                    <p class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= esc($item['alamat']) ?></p>
                    <?php endif; ?>
                    <?php if ($item['deskripsi']): ?>
                    <hr>
                    <h5>Tentang Destinasi Ini</h5>
                    <p class="text-body"><?= nl2br(esc($item['deskripsi'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Galeri thumbnail kecil -->
            <?php if (count($gambar) > 1): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold"><i class="bi bi-images me-2"></i>Galeri Foto</div>
                <div class="card-body">
                    <div class="row g-2">
                        <?php foreach ($gambar as $g): ?>
                        <div class="col-4 col-md-3">
                            <img src="<?= base_url($g['gambar_path']) ?>" class="img-fluid rounded" style="height:90px;width:100%;object-fit:cover;cursor:pointer;" onclick="viewImage('<?= base_url($g['gambar_path']) ?>')">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <?php if (!empty($item['maps_embed_url'])): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold"><i class="bi bi-geo-alt me-2"></i>Lokasi</div>
                <div class="card-body p-0">
                    <iframe src="<?= esc($item['maps_embed_url']) ?>" width="100%" height="260" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
                <?php if ($item['alamat']): ?>
                <div class="card-footer text-muted small"><?= esc($item['alamat']) ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <a href="<?= base_url('/pariwisata') ?>" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Wisata
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox simple -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
                <img id="lightboxImg" src="" class="img-fluid rounded shadow" style="max-height:80vh;">
            </div>
        </div>
    </div>
</div>

<script>
function viewImage(src) {
    document.getElementById('lightboxImg').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
<?= $this->endSection() ?>
