<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<?php
$mapsEmbed = $desaProfile['maps_embed_url'] ?? null;
$defaultEmbed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3979.9782469360237!2d120.245!3d-5.435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMjYnMDYuMCJTIDEyMMKwMTQnNDIuMCJF!5e0!3m2!1sen!2sid!4v1704090000';
$profileDescription = $desaProfile['deskripsi_lokasi'] ?? 'Padang Loang adalah desa yang terus bergerak maju dengan semangat gotong royong, pelayanan yang terbuka, dan pembangunan yang dekat dengan kebutuhan masyarakat.';
$heroImage = !empty($albums[0]['thumbnail'])
    ? base_url($albums[0]['thumbnail'])
    : (!empty($news[0]['thumbnail']) ? base_url($news[0]['thumbnail']) : base_url('assets/img/logolandscape.webp'));
?>

<section class="hero-section position-relative overflow-hidden">
    <div class="hero-media" style="background-image: url('<?= esc($heroImage) ?>');" aria-hidden="true"></div>
    <div class="hero-overlay" aria-hidden="true"></div>
    <div class="container position-relative">
        <div class="row align-items-center g-4 g-xl-5 min-vh-hero">
            <div class="col-lg-7 col-xl-6">
                <div class="hero-copy">
                    <span class="hero-kicker"><i class="bi bi-stars"></i> Website Resmi Pemerintah Desa</span>
                    <h1>Desa Padang Loang</h1>
                    <p class="hero-lead">Pusat informasi pelayanan, pengumuman, berita, dan dokumentasi kegiatan desa yang mudah diakses oleh masyarakat.</p>
                    <div class="hero-actions">
                        <a href="#about" class="btn btn-hero-primary btn-lg">
                            <i class="bi bi-compass me-2"></i>Jelajahi Desa
                        </a>
                        <a href="<?= base_url('/login') ?>" class="btn btn-hero-outline btn-lg">
                            <i class="bi bi-file-earmark-text me-2"></i>Ajukan Surat
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-xl-6">
                <div class="hero-showcase">
                    <div class="showcase-image">
                        <img src="<?= esc($heroImage) ?>" alt="Dokumentasi Desa Padang Loang">
                        <div class="showcase-badge">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Ujung Loe, Bulukumba</span>
                        </div>
                    </div>
                    <div class="hero-service-panel">
                        <div class="panel-header">
                            <div>
                                <span class="panel-label">Akses Cepat</span>
                                <h2>Layanan Desa</h2>
                            </div>
                            <i class="bi bi-grid-1x2-fill"></i>
                        </div>
                        <div class="quick-link-list">
                            <a href="<?= base_url('/pengumuman') ?>" class="quick-link">
                                <span class="quick-icon bg-sky"><i class="bi bi-megaphone"></i></span>
                                <span>
                                    <strong>Pengumuman</strong>
                                    <small>Informasi resmi terbaru</small>
                                </span>
                                <i class="bi bi-arrow-right-short"></i>
                            </a>
                            <a href="<?= base_url('/pengaduan') ?>" class="quick-link">
                                <span class="quick-icon bg-rose"><i class="bi bi-chat-dots"></i></span>
                                <span>
                                    <strong>Pengaduan</strong>
                                    <small>Sampaikan aspirasi warga</small>
                                </span>
                                <i class="bi bi-arrow-right-short"></i>
                            </a>
                            <a href="<?= base_url('/profil') ?>" class="quick-link">
                                <span class="quick-icon bg-emerald"><i class="bi bi-bank"></i></span>
                                <span>
                                    <strong>Profil Desa</strong>
                                    <small>Sejarah dan potensi wilayah</small>
                                </span>
                                <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-stats row g-3">
            <div class="col-md-4">
                <div class="stat-tile">
                    <span class="stat-icon"><i class="bi bi-newspaper"></i></span>
                    <div>
                        <strong><?= count($news ?? []) ?></strong>
                        <span>Berita terbaru</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-tile">
                    <span class="stat-icon"><i class="bi bi-images"></i></span>
                    <div>
                        <strong><?= count($albums ?? []) ?></strong>
                        <span>Album kegiatan</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-tile">
                    <span class="stat-icon"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <strong>24 Jam</strong>
                        <span>Informasi dapat diakses</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about" class="about-section">
    <div class="container">
        <div class="row align-items-center g-4 g-xl-5">
            <div class="col-lg-6">
                <div class="map-frame">
                    <iframe src="<?= esc($mapsEmbed ?: $defaultEmbed) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Desa Padang Loang"></iframe>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="section-kicker">Tentang Desa</span>
                <h2 class="section-title">Kilas Desa Padang Loang</h2>
                <p class="section-text"><?= esc($profileDescription) ?></p>
                <div class="feature-grid">
                    <div class="feature-item">
                        <i class="bi bi-people"></i>
                        <span>Gotong royong warga</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-map"></i>
                        <span>Informasi wilayah</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-file-check"></i>
                        <span>Pelayanan administrasi</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-camera"></i>
                        <span>Dokumentasi kegiatan</span>
                    </div>
                </div>
                <div class="section-actions">
                    <a href="<?= base_url('/profil') ?>" class="btn btn-primary">Profil Lengkap</a>
                    <a href="<?= base_url('/galeri') ?>" class="btn btn-outline-primary">Galeri Desa</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="gallery-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="section-kicker">Galeri</span>
                <h2 class="section-title">Momen Terbaru</h2>
            </div>
            <a href="<?= base_url('/galeri') ?>" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
        </div>
        <div class="row g-4">
            <?php foreach (($albums ?? []) as $album): ?>
                <div class="col-md-4">
                    <article class="card gallery-card h-100 border-0">
                        <img src="<?= $album['thumbnail'] ? base_url($album['thumbnail']) : base_url('assets/img/logolandscape.webp') ?>" class="card-img-top" alt="<?= esc($album['nama_album']) ?>">
                        <div class="card-body">
                            <div class="content-date"><i class="bi bi-calendar-event me-1"></i><?= date('d M Y', strtotime($album['tanggal_waktu'])) ?></div>
                            <h3 class="card-title"><?= esc($album['nama_album']) ?></h3>
                            <p class="card-text"><?= esc($album['deskripsi'] ?? 'Dokumentasi kegiatan Desa Padang Loang.') ?></p>
                            <a href="<?= base_url('/galeri/' . $album['id']) ?>" class="stretched-link">Lihat album</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
            <?php if (empty($albums)): ?>
                <div class="col-12">
                    <div class="empty-state">Belum ada album yang ditampilkan.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="news-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="section-kicker">Berita</span>
                <h2 class="section-title">Kabar Desa</h2>
            </div>
            <a href="<?= base_url('/berita') ?>" class="btn btn-outline-primary btn-sm">Semua Berita</a>
        </div>
        <div class="news-list">
            <?php foreach (($news ?? []) as $item): ?>
                <article class="news-card">
                    <div class="news-date">
                        <span><?= date('d', strtotime($item['tanggal_waktu'])) ?></span>
                        <small><?= date('M Y', strtotime($item['tanggal_waktu'])) ?></small>
                    </div>
                    <div class="news-body">
                        <h3><?= esc($item['judul']) ?></h3>
                        <p><?= word_limiter(strip_tags($item['isi']), 24) ?></p>
                        <a href="<?= base_url('/berita/' . $item['id']) ?>">Baca selengkapnya</a>
                    </div>
                </article>
            <?php endforeach; ?>
            <?php if (empty($news)): ?>
                <div class="empty-state">Belum ada berita yang ditampilkan.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
