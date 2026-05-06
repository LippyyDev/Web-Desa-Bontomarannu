<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <p class="text-uppercase text-primary fw-semibold small mb-1">Profil Desa</p>
                    <h2 class="fw-bold mb-3"><?= esc($desaProfile['nama_desa'] ?? 'Desa Bontomarannu') ?></h2>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted" width="140">Kecamatan</td>
                                    <td class="fw-semibold">: <?= esc($desaProfile['kecamatan'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kabupaten</td>
                                    <td class="fw-semibold">: <?= esc($desaProfile['kabupaten'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Provinsi</td>
                                    <td class="fw-semibold">: <?= esc($desaProfile['provinsi'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kode Pos</td>
                                    <td class="fw-semibold">: <?= esc($desaProfile['kode_pos'] ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted" width="140">Luas Wilayah</td>
                                    <td class="fw-semibold">: <?= esc($desaProfile['luas_wilayah'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tahun Berdiri</td>
                                    <td class="fw-semibold">: <?= esc($desaProfile['tahun_berdiri'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jumlah Penduduk</td>
                                    <td class="fw-semibold">: <?= number_format($desaProfile['jumlah_penduduk'] ?? 0, 0, ',', '.') ?> Jiwa</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jumlah KK</td>
                                    <td class="fw-semibold">: <?= number_format($desaProfile['jumlah_kk'] ?? 0, 0, ',', '.') ?> KK</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <?php if (!empty($desaProfile['sejarah_desa'])): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Sejarah Desa</h4>
                    <div class="text-muted" style="text-align: justify;">
                        <?= nl2br(esc($desaProfile['sejarah_desa'])) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>



        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Lokasi Desa</h5>
                    <?php 
                    $mapsUrl = $desaProfile['maps_url'] ?? null;
                    $mapsEmbed = $desaProfile['maps_embed_url'] ?? null;
                    $defaultEmbed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3979.9782469360237!2d120.245!3d-5.435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMjYnMDYuMCJTIDEyMMKwMTQnNDIuMCJF!5e0!3m2!1sen!2sid!4v1704090000';
                    ?>
                    <?php if ($mapsEmbed): ?>
                        <div class="ratio ratio-4x3 rounded-3 overflow-hidden">
                            <iframe src="<?= esc($mapsEmbed) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    <?php elseif ($mapsUrl): ?>
                        <div class="alert alert-info">
                            <p class="mb-2"><strong>Link Lokasi:</strong></p>
                            <a href="<?= esc($mapsUrl) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-geo-alt"></i> Buka di Google Maps
                            </a>
                            <p class="small text-muted mt-2 mb-0">
                                <strong>Catatan:</strong> Untuk menampilkan peta di sini, gunakan link embed dari Google Maps.<br>
                                Cara: Buka Google Maps → Pilih lokasi → Klik "Bagikan" → Pilih "Sematkan peta" → Salin link embed
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="ratio ratio-4x3 rounded-3 overflow-hidden">
                            <iframe src="<?= $defaultEmbed ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($desaProfile['deskripsi_lokasi'])): ?>
                        <div class="mt-3">
                            <p class="small text-muted mb-0"><?= nl2br(esc($desaProfile['deskripsi_lokasi'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


