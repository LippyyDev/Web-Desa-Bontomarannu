<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/profildesa.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container">
        <?php
        $sejarahDesa = $desaProfile['sejarah_desa'] ?? '';
        $visiLines = !empty($desaProfile['visi']) ? explode("\n", trim($desaProfile['visi'])) : [];
        $misiLines = !empty($desaProfile['misi']) ? explode("\n", trim($desaProfile['misi'])) : [];
        
        $hasKontak = !empty($desaProfile['alamat_kantor']) || !empty($desaProfile['kontak_wa']) 
                  || !empty($desaProfile['kontak_email'])   || !empty($desaProfile['kontak_facebook'])
                  || !empty($desaProfile['kontak_instagram']) || !empty($desaProfile['kontak_youtube']);
        ?>

        <?php if (!empty($visiLines) || !empty($misiLines)): ?>
        <div class="profil-desa-header mt-5 mb-4 reveal-up">
            <span class="profil-desa-subtitle">Informasi Desa</span>
            <h2 class="profil-desa-title">Visi & <span>Misi</span></h2>
        </div>
        <div class="row g-4 mb-5">
            <?php if (!empty($visiLines)): ?>
            <div class="<?= !empty($misiLines) ? 'col-lg-6' : 'col-12' ?> reveal-up delay-100">
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
            <div class="<?= !empty($visiLines) ? 'col-lg-6' : 'col-12' ?> reveal-up delay-200">
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

        <!-- Header Section Lokasi dan Kontak -->
        <div class="profil-desa-header mt-5 mb-4 reveal-up">
            <span class="profil-desa-subtitle">Informasi Desa</span>
            <h2 class="profil-desa-title">Lokasi dan <span>Kontak</span></h2>
        </div>

        <?php
        $getUsername = function($url) {
            if (empty($url)) return '-';
            if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
                return ltrim($url, '@'); 
            }
            $path = parse_url($url, PHP_URL_PATH);
            $basename = basename(rtrim($path, '/'));
            return $basename ?: $url;
        };

        $fallbackText = '<span class="text-muted">-</span>';
        $alamat = !empty($desaProfile['alamat_kantor']) ? nl2br(esc($desaProfile['alamat_kantor'])) : $fallbackText;
        $wa = !empty($desaProfile['kontak_wa']) ? '+'.esc($desaProfile['kontak_wa']) : $fallbackText;
        $waLink = !empty($desaProfile['kontak_wa']) ? 'https://wa.me/'.esc($desaProfile['kontak_wa']) : '#';
        $email = !empty($desaProfile['kontak_email']) ? esc($desaProfile['kontak_email']) : $fallbackText;
        $emailLink = !empty($desaProfile['kontak_email']) ? 'mailto:'.esc($desaProfile['kontak_email']) : '#';
        $fb = !empty($desaProfile['kontak_facebook']) ? esc($getUsername($desaProfile['kontak_facebook'])) : $fallbackText;
        $fbLink = !empty($desaProfile['kontak_facebook']) ? esc($desaProfile['kontak_facebook']) : '#';
        $ig = !empty($desaProfile['kontak_instagram']) ? esc($getUsername($desaProfile['kontak_instagram'])) : $fallbackText;
        $igLink = !empty($desaProfile['kontak_instagram']) ? esc($desaProfile['kontak_instagram']) : '#';
        $yt = !empty($desaProfile['kontak_youtube']) ? esc($getUsername($desaProfile['kontak_youtube'])) : $fallbackText;
        $ytLink = !empty($desaProfile['kontak_youtube']) ? esc($desaProfile['kontak_youtube']) : '#';
        ?>

        <div class="row g-4 mb-5">
            <!-- Kiri: Map -->
            <div class="col-lg-7 reveal-up delay-100">
                <div class="bento-card p-0 border-0 rounded-4 shadow-sm overflow-hidden h-100" style="min-height: 450px;">
                    <?php 
                    $mapsUrl = $desaProfile['maps_url'] ?? null;
                    $mapsEmbed = $desaProfile['maps_embed_url'] ?? null;
                    $defaultEmbed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d20000!2d119.91249047356614!3d-5.447609309467859!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbeb0e2a1e219d5%3A0x964855ec2fd33e44!2sBonto%20Marannu%2C%20Kec.%20Uluere%2C%20Kabupaten%20Bantaeng%2C%20Sulawesi%20Selatan!5e0!3m2!1sid!2sid!4v1768373212743!5m2!1sid!2sid';
                    ?>
                    
                    <?php if ($mapsEmbed): ?>
                        <iframe class="map-tint-green" src="<?= esc($mapsEmbed) ?>" style="border:0; width:100%; height:100%; min-height: 450px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <?php elseif ($mapsUrl): ?>
                        <div class="alert alert-info d-flex align-items-center justify-content-center h-100 flex-column m-0">
                            <i class="bi bi-map fs-1 mb-2"></i>
                            <a href="<?= esc($mapsUrl) ?>" target="_blank" class="btn btn-outline-primary mt-2">
                                Buka di Google Maps
                            </a>
                        </div>
                    <?php else: ?>
                        <iframe class="map-tint-green" src="<?= $defaultEmbed ?>" style="border:0; width:100%; height:100%; min-height: 450px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($desaProfile['deskripsi_lokasi'])): ?>
                    <div class="bento-card mt-3">
                        <p class="history-text mb-0"><?= nl2br(esc($desaProfile['deskripsi_lokasi'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kanan: Kontak -->
            <div class="col-lg-5">
                <div class="d-flex flex-column gap-3 h-100 justify-content-center">
                    <!-- Alamat -->
                    <div class="d-flex align-items-center p-3 bg-white rounded-4 shadow-sm border border-light transition-hover reveal-up delay-100" style="transition: transform 0.2s;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 48px; height: 48px; background: rgba(46,125,50,0.1); color: #2E7D32;">
                            <i class="bi bi-geo-alt-fill fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="text-muted fw-semibold mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Alamat Kantor</div>
                            <div class="fw-bold text-dark text-truncate" title="<?= esc(strip_tags($alamat)) ?>"><?= $alamat ?></div>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="d-flex align-items-center p-3 bg-white rounded-4 shadow-sm border border-light transition-hover reveal-up delay-200" style="transition: transform 0.2s;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 48px; height: 48px; background: rgba(37,211,102,0.1); color: #25D366;">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="text-muted fw-semibold mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">WhatsApp</div>
                            <a href="<?= $waLink ?>" target="<?= $waLink !== '#' ? '_blank' : '_self' ?>" class="fw-bold text-dark text-decoration-none text-truncate d-block" title="<?= esc(strip_tags($wa)) ?>"><?= $wa ?></a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="d-flex align-items-center p-3 bg-white rounded-4 shadow-sm border border-light transition-hover reveal-up delay-300" style="transition: transform 0.2s;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 48px; height: 48px; background: rgba(66,133,244,0.1); color: #4285F4;">
                            <i class="bi bi-envelope-fill fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="text-muted fw-semibold mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Email</div>
                            <a href="<?= $emailLink ?>" class="fw-bold text-dark text-decoration-none text-truncate d-block" title="<?= esc(strip_tags($email)) ?>"><?= $email ?></a>
                        </div>
                    </div>

                    <!-- Facebook -->
                    <div class="d-flex align-items-center p-3 bg-white rounded-4 shadow-sm border border-light transition-hover reveal-up delay-400" style="transition: transform 0.2s;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 48px; height: 48px; background: rgba(24,119,242,0.1); color: #1877F2;">
                            <i class="bi bi-facebook fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="text-muted fw-semibold mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Facebook</div>
                            <a href="<?= $fbLink ?>" target="<?= $fbLink !== '#' ? '_blank' : '_self' ?>" class="fw-bold text-dark text-decoration-none text-truncate d-block" title="<?= esc(strip_tags($fb)) ?>"><?= $fb ?></a>
                        </div>
                    </div>

                    <!-- Instagram -->
                    <div class="d-flex align-items-center p-3 bg-white rounded-4 shadow-sm border border-light transition-hover" style="transition: transform 0.2s;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 48px; height: 48px; background: rgba(225,48,108,0.1); color: #E1306C;">
                            <i class="bi bi-instagram fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="text-muted fw-semibold mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Instagram</div>
                            <a href="<?= $igLink ?>" target="<?= $igLink !== '#' ? '_blank' : '_self' ?>" class="fw-bold text-dark text-decoration-none text-truncate d-block" title="<?= esc(strip_tags($ig)) ?>"><?= $ig ?></a>
                        </div>
                    </div>

                    <!-- YouTube -->
                    <div class="d-flex align-items-center p-3 bg-white rounded-4 shadow-sm border border-light transition-hover" style="transition: transform 0.2s;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 48px; height: 48px; background: rgba(255,0,0,0.1); color: #FF0000;">
                            <i class="bi bi-youtube fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="text-muted fw-semibold mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">YouTube</div>
                            <a href="<?= $ytLink ?>" target="<?= $ytLink !== '#' ? '_blank' : '_self' ?>" class="fw-bold text-dark text-decoration-none text-truncate d-block" title="<?= esc(strip_tags($yt)) ?>"><?= $yt ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div> <!-- Close current container -->

        <!-- Header Section Profil Desa -->
        <div class="position-relative overflow-hidden py-5 my-5" style="margin: 0 0.75rem; border-radius: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <!-- Blurry Background & Dark Overlay (z-index negative so page grid shows through) -->
            <div style="position:absolute; inset:0; z-index:-2; background-image: url('<?= base_url('assets/img/section-pengumuman.jpg') ?>'); background-size:cover; background-position:center; filter:blur(12px); transform:scale(1.1);"></div>
            <div style="position:absolute; inset:0; z-index:-1; background:rgba(15, 23, 42, 0.75);"></div>

            <div class="container position-relative" style="z-index: 1;">
                <div class="profil-desa-header mb-5 text-center reveal-up">
                    <span class="profil-desa-subtitle" style="color: #a7f3d0; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; font-size: 0.85rem;">Informasi Desa</span>
                    <h2 class="profil-desa-title text-white mt-2 mb-0" style="font-weight: 700;">Statistik & <span style="color: #4ade80;">Demografi</span></h2>
                </div>

                <!-- Modern Admin Grid -->
                <div class="row g-4">
                    <!-- Total Penduduk -->
                    <div class="col-md-6 reveal-up delay-100">
                        <div class="card glass-card rounded-4 p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center h-100">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-people fs-1 text-white me-3" style="opacity: 0.8;"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1 text-white" style="letter-spacing: 0.5px;">Total Penduduk</h5>
                                        <p class="small text-white mb-0" style="opacity: 0.65; line-height: 1.4; max-width: 200px;">
                                            Jumlah seluruh penduduk yang terdaftar.
                                        </p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h1 class="display-5 fw-bold text-white mb-0" style="letter-spacing: -1px;"><?= number_format($desaProfile['jumlah_penduduk'] ?? 0, 0, ',', '.') ?></h1>
                                    <span class="fs-6 fw-normal text-white" style="opacity: 0.7;">Jiwa</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kepala Keluarga -->
                    <div class="col-md-6 reveal-up delay-200">
                        <div class="card glass-card rounded-4 p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center h-100">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-house-heart fs-1 text-white me-3" style="opacity: 0.8;"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1 text-white" style="letter-spacing: 0.5px;">Kepala Keluarga</h5>
                                        <p class="small text-white mb-0" style="opacity: 0.65; line-height: 1.4; max-width: 200px;">
                                            Total KK dalam administrasi desa.
                                        </p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h1 class="display-5 fw-bold text-white mb-0" style="letter-spacing: -1px;"><?= number_format($desaProfile['jumlah_kk'] ?? 0, 0, ',', '.') ?></h1>
                                    <span class="fs-6 fw-normal text-white" style="opacity: 0.7;">KK</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Luas Wilayah -->
                    <div class="col-lg-4 col-md-6 col-sm-6 reveal-up delay-100">
                        <div class="card glass-card rounded-4 p-4 h-100">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-aspect-ratio fs-3 text-white me-3" style="opacity: 0.8;"></i>
                                    <h5 class="fw-bold mb-0 text-white">Luas Wilayah</h5>
                                </div>
                                <div class="mt-auto ps-1 pt-2">
                                    <h3 class="fw-bold text-white mb-0"><?= esc($geografi['luas_wilayah'] ?? '0') ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tahun Berdiri -->
                    <div class="col-lg-4 col-md-6 col-sm-6 reveal-up delay-200">
                        <div class="card glass-card rounded-4 p-4 h-100">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-calendar4-event fs-3 text-white me-3" style="opacity: 0.8;"></i>
                                    <h5 class="fw-bold mb-0 text-white">Tahun Berdiri</h5>
                                </div>
                                <div class="mt-auto ps-1 pt-2">
                                    <h3 class="fw-bold text-white mb-0"><?= esc($desaProfile['tahun_berdiri'] ?? '0') ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kecamatan -->
                    <div class="col-lg-4 col-md-6 col-sm-6 reveal-up delay-300">
                        <div class="card glass-card rounded-4 p-4 h-100">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-geo fs-3 text-white me-3" style="opacity: 0.8;"></i>
                                    <h5 class="fw-bold mb-0 text-white">Kecamatan</h5>
                                </div>
                                <div class="mt-auto ps-1 pt-2">
                                    <h4 class="fw-bold text-white mb-0 text-truncate" title="<?= esc($desaProfile['kecamatan'] ?? '0') ?>"><?= esc($desaProfile['kecamatan'] ?? '0') ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kabupaten -->
                    <div class="col-lg-4 col-md-6 col-sm-6 reveal-up delay-100">
                        <div class="card glass-card rounded-4 p-4 h-100">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-map fs-3 text-white me-3" style="opacity: 0.8;"></i>
                                    <h5 class="fw-bold mb-0 text-white">Kabupaten</h5>
                                </div>
                                <div class="mt-auto ps-1 pt-2">
                                    <h4 class="fw-bold text-white mb-0 text-truncate" title="<?= esc($desaProfile['kabupaten'] ?? '0') ?>"><?= esc($desaProfile['kabupaten'] ?? '0') ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Provinsi -->
                    <div class="col-lg-4 col-md-6 col-sm-6 reveal-up delay-200">
                        <div class="card glass-card rounded-4 p-4 h-100">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-buildings fs-3 text-white me-3" style="opacity: 0.8;"></i>
                                    <h5 class="fw-bold mb-0 text-white">Provinsi</h5>
                                </div>
                                <div class="mt-auto ps-1 pt-2">
                                    <h4 class="fw-bold text-white mb-0 text-truncate" title="<?= esc($desaProfile['provinsi'] ?? '0') ?>"><?= esc($desaProfile['provinsi'] ?? '0') ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kode Pos -->
                    <div class="col-lg-4 col-md-6 col-sm-6 reveal-up delay-300">
                        <div class="card glass-card rounded-4 p-4 h-100">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-envelope-paper fs-3 text-white me-3" style="opacity: 0.8;"></i>
                                    <h5 class="fw-bold mb-0 text-white">Kode Pos</h5>
                                </div>
                                <div class="mt-auto ps-1 pt-2">
                                    <h3 class="fw-bold text-white mb-0"><?= esc($desaProfile['kode_pos'] ?? '0') ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container"> <!-- Re-open container -->
        


        <?php if (!empty($sejarahDesa)): ?>
        <div class="profil-desa-header mt-5 mb-4 text-center reveal-up">
            <span class="profil-desa-subtitle">Informasi Desa</span>
            <h2 class="profil-desa-title">Sejarah <span>Desa</span></h2>
        </div>
        <div class="row mb-0">
            <div class="col-12 reveal-up delay-100">
                <div class="bento-card p-4 p-md-5 shadow-sm" style="line-height: 1.8; color: #4b5563; text-align: justify; font-size: 1.05rem; border-radius: 24px;">
                    <?= nl2br(esc($sejarahDesa)) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const reveals = document.querySelectorAll(".reveal-up");
    const revealOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealOnScroll = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
                observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    reveals.forEach(reveal => {
        revealOnScroll.observe(reveal);
    });
});
</script>
<?= $this->endSection() ?>
