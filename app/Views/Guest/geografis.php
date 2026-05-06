<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center">
                <h2 class="fw-bold display-5 mb-4">Geografi Desa</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Geografi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0 overflow-hidden rounded-3">
                        <?php 
                        $mapsEmbed = $geografi['maps_embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3979.9782469360237!2d120.245!3d-5.435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMjYnMDYuMCJTIDEyMMKwMTQnNDIuMCJF!5e0!3m2!1sen!2sid!4v1704090000';
                        ?>
                        <div class="ratio ratio-16x9">
                            <iframe src="<?= esc($mapsEmbed) ?>" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Kondisi Geografis</h4>
                        <div class="text-muted" style="text-align: justify;">
                            <?= !empty($geografi['kondisi_geografis']) ? nl2br(esc($geografi['kondisi_geografis'])) : 'Data kondisi geografis belum tersedia.' ?>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Batas Wilayah</h4>
                        <div class="text-muted" style="text-align: justify;">
                            <?= !empty($geografi['batas_wilayah']) ? nl2br(esc($geografi['batas_wilayah'])) : 'Data batas wilayah belum tersedia.' ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Info Wilayah</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                                <i class="bi bi-arrows-fullscreen fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted d-block text-uppercase fw-semibold">Luas Wilayah</small>
                                <span class="fw-bold fs-5"><?= esc($geografi['luas_wilayah'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
