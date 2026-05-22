<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/profildesa.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-4">
    <div class="container">
        <?php
        $visiLines = !empty($desaProfile['visi']) ? explode("\n", trim($desaProfile['visi'])) : [];
        $misiLines = !empty($desaProfile['misi']) ? explode("\n", trim($desaProfile['misi'])) : [];
        ?>

        <?php if (!empty($visiLines) || !empty($misiLines)): ?>
        <div class="profil-desa-header mb-4">
            <span class="profil-desa-subtitle">Informasi Desa</span>
            <h2 class="profil-desa-title">Visi & <span>Misi</span></h2>
        </div>
        <div class="row g-4 mb-5">
            <?php if (!empty($visiLines)): ?>
            <div class="<?= !empty($misiLines) ? 'col-lg-6' : 'col-12' ?>">
                <div class="bento-card h-100">
                    <h4 class="section-title"><i class="bi bi-eye"></i> Visi</h4>
                    <ul class="bento-list mt-2">
                        <?php foreach ($visiLines as $line): ?>
                            <?php if (trim($line) !== ''): ?>
                            <li class="align-items-start border-0 py-2">
                                <span class="bento-list-label d-inline-flex me-3"><i class="bi bi-check-circle-fill" style="color: #2E7D32;"></i></span>
                                <span class="bento-list-val text-start fw-normal" style="flex: 1; line-height: 1.6; color: #4b5563; font-size: 0.95rem;"><?= esc(trim($line)) ?></span>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($misiLines)): ?>
            <div class="<?= !empty($visiLines) ? 'col-lg-6' : 'col-12' ?>">
                <div class="bento-card h-100">
                    <h4 class="section-title"><i class="bi bi-bullseye"></i> Misi</h4>
                    <ul class="bento-list mt-2">
                        <?php foreach ($misiLines as $line): ?>
                            <?php if (trim($line) !== ''): ?>
                            <li class="align-items-start border-0 py-2">
                                <span class="bento-list-label d-inline-flex me-3"><i class="bi bi-check-circle-fill" style="color: #2E7D32;"></i></span>
                                <span class="bento-list-val text-start fw-normal" style="flex: 1; line-height: 1.6; color: #4b5563; font-size: 0.95rem;"><?= esc(trim($line)) ?></span>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Header Section -->
        <div class="profil-desa-header mt-5">
            <span class="profil-desa-subtitle">Informasi Desa</span>
            <h2 class="profil-desa-title">Profil <span><?= preg_replace('/\d+$/', '', esc($desaProfile['nama_desa'] ?? 'Desa Bontomarannu')) ?></span></h2>
        </div>

        <!-- Top Map Section -->
        <div class="bento-grid">
            <div class="bento-card bento-main p-0 border-0 rounded-4 shadow-sm overflow-hidden" style="height: 450px;">
                <?php 
                $mapsUrl = $desaProfile['maps_url'] ?? null;
                $mapsEmbed = $desaProfile['maps_embed_url'] ?? null;
                $defaultEmbed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7943.595994392008!2d119.91249047356614!3d-5.447609309467859!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbeb0e2a1e219d5%3A0x964855ec2fd33e44!2sBonto%20Marannu%2C%20Kec.%20Uluere%2C%20Kabupaten%20Bantaeng%2C%20Sulawesi%20Selatan!5e0!3m2!1sid!2sid!4v1768373212743!5m2!1sid!2sid';
                ?>
                
                <?php if ($mapsEmbed): ?>
                    <iframe src="<?= esc($mapsEmbed) ?>" style="border:0; width:100%; height:100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php elseif ($mapsUrl): ?>
                    <div class="alert alert-info d-flex align-items-center justify-content-center h-100 flex-column m-0">
                        <i class="bi bi-map fs-1 mb-2"></i>
                        <a href="<?= esc($mapsUrl) ?>" target="_blank" class="btn btn-outline-primary mt-2">
                            Buka di Google Maps
                        </a>
                    </div>
                <?php else: ?>
                    <iframe src="<?= $defaultEmbed ?>" style="border:0; width:100%; height:100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($desaProfile['deskripsi_lokasi'])): ?>
                <div class="bento-card bento-main mt-2">
                    <p class="history-text mb-0"><?= nl2br(esc($desaProfile['deskripsi_lokasi'])) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Bento Grid -->
        <div class="bento-grid">
            
            <!-- Main Info Card -->
            <div class="bento-card bento-main">
                <h4 class="section-title"><i class="bi bi-info-circle-fill"></i> Data Profil Desa</h4>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <ul class="bento-list">
                            <li>
                                <span class="bento-list-label"><i class="bi bi-geo-alt"></i> Kecamatan</span>
                                <span class="bento-list-val"><?= esc($desaProfile['kecamatan'] ?? '-') ?></span>
                            </li>
                            <li>
                                <span class="bento-list-label"><i class="bi bi-map"></i> Kabupaten</span>
                                <span class="bento-list-val"><?= esc($desaProfile['kabupaten'] ?? '-') ?></span>
                            </li>
                            <li>
                                <span class="bento-list-label"><i class="bi bi-building"></i> Provinsi</span>
                                <span class="bento-list-val"><?= esc($desaProfile['provinsi'] ?? '-') ?></span>
                            </li>
                            <li>
                                <span class="bento-list-label"><i class="bi bi-envelope-paper"></i> Kode Pos</span>
                                <span class="bento-list-val"><?= esc($desaProfile['kode_pos'] ?? '-') ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="bento-list">
                            <li>
                                <span class="bento-list-label"><i class="bi bi-aspect-ratio"></i> Luas Wilayah</span>
                                <span class="bento-list-val"><?= esc($desaProfile['luas_wilayah'] ?? '-') ?></span>
                            </li>
                            <li>
                                <span class="bento-list-label"><i class="bi bi-calendar-event"></i> Tahun Berdiri</span>
                                <span class="bento-list-val"><?= esc($desaProfile['tahun_berdiri'] ?? '-') ?></span>
                            </li>
                            <li class="d-md-none">
                                <span class="bento-list-label"><i class="bi bi-people"></i> Jumlah Penduduk</span>
                                <span class="bento-list-val"><?= number_format($desaProfile['jumlah_penduduk'] ?? 0, 0, ',', '.') ?> Jiwa</span>
                            </li>
                            <li class="d-md-none">
                                <span class="bento-list-label"><i class="bi bi-house-door"></i> Jumlah KK</span>
                                <span class="bento-list-val"><?= number_format($desaProfile['jumlah_kk'] ?? 0, 0, ',', '.') ?> KK</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Stats 1: Penduduk -->
            <div class="bento-card bento-stats">
                <div class="bento-card-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="bento-label">Jumlah Penduduk</div>
                <div class="bento-value">
                    <?= number_format($desaProfile['jumlah_penduduk'] ?? 0, 0, ',', '.') ?>
                    <span class="unit">Jiwa</span>
                </div>
            </div>

            <!-- Stats 2: KK -->
            <div class="bento-card bento-stats">
                <div class="bento-card-icon">
                    <i class="bi bi-house-door-fill"></i>
                </div>
                <div class="bento-label">Jumlah KK</div>
                <div class="bento-value">
                    <?= number_format($desaProfile['jumlah_kk'] ?? 0, 0, ',', '.') ?>
                    <span class="unit">KK</span>
                </div>
            </div>
            
        </div>
        
        <?php if (!empty($desaProfile['sejarah_desa'])): ?>
        <div class="bento-grid">
            <!-- Sejarah Desa -->
            <div class="bento-card bento-main">
                <h4 class="section-title"><i class="bi bi-clock-history"></i> Sejarah Desa</h4>
                <div class="history-text">
                    <?= nl2br(esc($desaProfile['sejarah_desa'])) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>


