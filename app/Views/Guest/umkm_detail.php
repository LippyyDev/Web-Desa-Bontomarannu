<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container mt-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/umkm') ?>">UMKM</a></li>
            <li class="breadcrumb-item active"><?= esc($umkm['nama_toko']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">

            <!-- Info Toko -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h2 class="fw-bold"><?= esc($umkm['nama_toko']) ?></h2>
                    <?php if ($umkm['alamat']): ?>
                    <p class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= esc($umkm['alamat']) ?></p>
                    <?php endif; ?>
                    <?php if ($umkm['kontak']): ?>
                    <p><i class="bi bi-telephone me-1 text-success"></i>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $umkm['kontak']) ?>" target="_blank" class="text-decoration-none text-success">
                            <?= esc($umkm['kontak']) ?> <i class="bi bi-whatsapp"></i>
                        </a>
                    </p>
                    <?php endif; ?>
                    <?php if ($umkm['deskripsi']): ?>
                    <hr>
                    <p><?= nl2br(esc($umkm['deskripsi'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Link E-Commerce -->
            <?php if (!empty($ecommerce)): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold"><i class="bi bi-bag me-2"></i>Beli Online</div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <?php foreach ($ecommerce as $e): ?>
                    <a href="<?= esc($e['url']) ?>" target="_blank" class="btn btn-outline-success">
                        <i class="bi bi-bag me-1"></i><?= esc($e['platform'] ?: 'E-Commerce') ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Produk -->
            <?php if (!empty($produk)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold"><i class="bi bi-box me-2"></i>Produk (<?= count($produk) ?>)</div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($produk as $p): ?>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <?php if (!empty($p['gambar'])): ?>
                                <!-- Carousel gambar produk -->
                                <div id="carousel-<?= $p['id'] ?>" class="carousel slide mb-3" data-bs-ride="carousel">
                                    <div class="carousel-inner rounded">
                                        <?php foreach ($p['gambar'] as $gi => $g): ?>
                                        <div class="carousel-item <?= $gi === 0 ? 'active' : '' ?>">
                                            <img src="<?= base_url($g['gambar_path']) ?>" class="d-block w-100" style="height:180px;object-fit:cover;" alt="<?= esc($p['nama_produk']) ?>">
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (count($p['gambar']) > 1): ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?= $p['id'] ?>" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?= $p['id'] ?>" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <div class="fw-semibold"><?= esc($p['nama_produk']) ?></div>
                                <?php if ($p['harga']): ?>
                                <div class="text-success fw-bold fs-5">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                                <?php endif; ?>
                                <?php if ($p['deskripsi']): ?>
                                <p class="small text-muted mt-1 mb-0"><?= esc($p['deskripsi']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <?php if (!empty($umkm['maps_embed_url'])): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold"><i class="bi bi-geo-alt me-2"></i>Lokasi Toko</div>
                <div class="card-body p-0">
                    <iframe src="<?= esc($umkm['maps_embed_url']) ?>" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <a href="<?= base_url('/umkm') ?>" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar UMKM
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
