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
<?php
$heroImages = [
    base_url('assets/img/Bantaeng (1).jpg'),
    base_url('assets/img/Bantaeng (2).jpg'),
    base_url('assets/img/Bantaeng (3).jpg'),
    base_url('assets/img/Bantaeng (4).jpg'),
    base_url('assets/img/Bantaeng (5).jpg')
];
?>

<section class="hero-section position-relative overflow-hidden d-flex flex-column">
    <div class="hero-media" aria-hidden="true">
        <div id="heroBgCarousel" class="carousel slide carousel-fade h-100 w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="4000">
            <div class="carousel-inner h-100 w-100">
                <?php foreach ($heroImages as $index => $img): ?>
                    <div class="carousel-item h-100 w-100 <?= $index === 0 ? 'active' : '' ?>" 
                         style="background-image: url('<?= esc($img) ?>'); background-size: cover; background-position: center;">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="hero-overlay" aria-hidden="true"></div>
    <div class="hero-texture position-absolute w-100 h-100" aria-hidden="true" style="inset: 0; z-index: -1; pointer-events: none;"></div>
    
    <div class="container position-relative flex-grow-1 d-flex flex-column justify-content-center align-items-center text-center z-2">
        <div class="hero-content">
            <span class="hero-kicker-new">SELAMAT DATANG DI WEBSITE</span>
            <h1 class="hero-title-new">Desa<br>Bonto Marannu<br><span style="font-size: 0.45em; font-weight: 600; opacity: 0.9; position: relative; top: -0.5em;">Kabupaten Bantaeng</span></h1>
            <p class="hero-subtitle-new">Pusat informasi pelayanan publik, transparansi desa, dan dokumentasi kegiatan masyarakat yang cepat dan mudah diakses.</p>
            <a href="#about" class="btn btn-hero-glass">Mulai Jelajah</a>
        </div>
    </div>
    
    <div class="hero-footer-stats w-100 pb-5 text-center z-2 position-relative">
        <span class="stats-number">900+</span>
        <span class="stats-text">orang telah mengunjungi desa ini</span>
    </div>
</section>

<section id="about" class="about-hero-section">
    <div class="container-fluid px-xl-5">
        <div class="about-grid">
            <!-- Left Column -->
            <div class="about-left">
                <div class="about-kicker">&mdash; TENTANG DESA</div>
                <h2 class="about-title">Jelajahi <strong class="fw-bold" style="color: #166534;">Kehidupan Otentik</strong> dan <strong class="fw-bold" style="color: #166534;">Pesona Desa</strong> Bonto Marannu</h2>
                <div class="about-checks mt-4 mb-4">
                    <span class="me-3"><i class="bi bi-check2 text-primary"></i> Alam Asri</span>
                    <span class="me-3"><i class="bi bi-check2 text-primary"></i> Budaya Lokal</span>
                    <span><i class="bi bi-check2 text-primary"></i> Ramah</span>
                </div>
                <p class="about-desc text-muted mb-5" style="line-height: 1.6;">
                    Desa Bonto Marannu adalah sebuah desa wisata yang terletak di dataran tinggi Kecamatan Uluere, Kabupaten Bantaeng. Menawarkan kesejukan pegunungan dengan panorama perbukitan hijau, desa ini kaya akan hasil bumi seperti kopi dan sayur-sayuran, serta menjadi daya tarik ekowisata peternakan sapi perah yang otentik.
                </p>
                <a href="<?= base_url('/profil') ?>" class="about-cta-link fw-bold text-dark text-decoration-none d-inline-flex align-items-center hover-arrow">
                    Profil Desa <span class="icon-circle ms-3"><i class="bi bi-arrow-right"></i></span>
                </a>
            </div>

            <!-- Center Column (Carousel) -->
            <div class="about-center d-flex justify-content-center">
                <div class="about-image-card">
                    
                    <div id="aboutCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel">
                        <div class="carousel-inner h-100">
                            <div class="carousel-item active h-100">
                                <img src="<?= base_url('assets/img/bantaeng-card (1).jpg') ?>" class="d-block w-100 h-100 object-fit-cover" alt="Bantaeng 1">
                            </div>
                            <div class="carousel-item h-100">
                                <img src="<?= base_url('assets/img/bantaeng-card (2).jpg') ?>" class="d-block w-100 h-100 object-fit-cover" alt="Bantaeng 2">
                            </div>
                            <div class="carousel-item h-100">
                                <img src="<?= base_url('assets/img/bantaeng-card (3).jpg') ?>" class="d-block w-100 h-100 object-fit-cover" alt="Bantaeng 3">
                            </div>
                        </div>
                        <div class="about-carousel-controls">
                            <button class="btn btn-sm btn-light rounded-circle shadow-sm" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="btn btn-sm btn-light rounded-circle shadow-sm ms-2" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="about-card-overlay">
                        <p class="about-card-subtitle mb-0 d-flex align-items-center fs-5 fw-bold text-white">
                            <i class="bi bi-geo-alt-fill me-2"></i> Bantaeng
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="about-right">
                <div class="about-info-list">
                    <div class="info-item d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="info-label text-muted">Profil Desa</span>
                        <a href="<?= base_url('/profil') ?>" class="info-val fw-bold text-dark text-decoration-none d-inline-flex align-items-center hover-arrow">Lihat Profil <i class="bi bi-arrow-right ms-2 fs-5"></i></a>
                    </div>
                    <div class="info-item d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="info-label text-muted">Geografi Desa</span>
                        <a href="<?= base_url('/geografis') ?>" class="info-val fw-bold text-dark text-decoration-none d-inline-flex align-items-center hover-arrow">Lihat Peta <i class="bi bi-arrow-right ms-2 fs-5"></i></a>
                    </div>
                    <div class="info-item d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="info-label text-muted">Pemerintahan</span>
                        <a href="<?= base_url('/perangkat') ?>" class="info-val fw-bold text-dark text-decoration-none d-inline-flex align-items-center hover-arrow">Perangkat Desa <i class="bi bi-arrow-right ms-2 fs-5"></i></a>
                    </div>
                </div>

                <div class="about-map-card p-0 bg-white rounded-4 shadow-sm border border-light w-100 position-relative overflow-hidden">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7943.595994392008!2d119.91249047356614!3d-5.447609309467859!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbeb0e2a1e219d5%3A0x964855ec2fd33e44!2sBonto%20Marannu%2C%20Kec.%20Uluere%2C%20Kabupaten%20Bantaeng%2C%20Sulawesi%20Selatan!5e0!3m2!1sid!2sid!4v1768373212743!5m2!1sid!2sid" style="border:0; position:absolute; top:0; left:0; width:100%; height:100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="home-pengumuman-section py-5">
    <div style="max-width: 1400px; margin: 0 auto; width: 100%; padding: 0 1rem;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
            <div class="mb-4 mb-md-0">
                <span class="section-kicker text-start d-block" style="color: #a7f3d0;">Informasi Publik</span>
                <h2 class="section-title text-start mb-0 text-white">Pengumuman <span style="color: #4ade80;">Terbaru</span></h2>
            </div>
            <div>
                <a href="<?= base_url('/pengumuman') ?>" class="btn btn-light rounded-pill px-4 fw-bold" style="color: #166534;">Lihat Semua</a>
            </div>
        </div>
        
        <?php if (!empty($pengumuman)): ?>
            <div class="row g-4">
                <?php foreach ($pengumuman as $item): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="pengumuman-card h-100">
                            <div class="pengumuman-img-wrapper">
                                <div class="pengumuman-date-pill">
                                    <i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($item['created_at'])) ?>
                                </div>
                                <?php if (!empty($item['thumbnail'])): ?>
                                    <img src="<?= base_url($item['thumbnail']) ?>" alt="<?= esc($item['judul']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="pengumuman-placeholder"><i class="bi bi-megaphone"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="pengumuman-card-body">
                                <h5 class="pengumuman-title"><?= esc($item['judul']) ?></h5>
                                <p class="pengumuman-excerpt"><?= esc(word_limiter(strip_tags($item['isi']), 12)) ?></p>
                                <div class="pengumuman-footer">
                                    <a href="<?= base_url('/pengumuman') ?>" class="pengumuman-action text-success stretched-link text-decoration-none px-0">
                                        Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="text-center text-white-50 py-4">
                <p>Belum ada pengumuman terbaru.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="home-berita-section py-5">
    <div style="max-width: 1400px; margin: 0 auto; width: 100%; padding: 0 1rem;">
        <div class="text-center mb-5">
            <span class="section-kicker d-block mx-auto text-dark">Warta Desa</span>
            <h2 class="section-title mb-3 text-dark">Informasi <span style="color: #166534;">Terkini</span></h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Dapatkan berbagai kabar, perkembangan, dan pemberitahuan terbaru seputar kegiatan kemasyarakatan dan pemerintahan desa kami.</p>
        </div>

        <?php if (!empty($news) && count($news) > 0): ?>
            <div class="berita-magazine-grid">
                <!-- Large Card (Left) -->
                <?php $mainNews = $news[0]; ?>
                <div class="berita-float-wrapper" style="animation-delay: 0s;">
                    <a href="<?= base_url('/berita/' . $mainNews['id']) ?>" class="berita-card berita-main-card" data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-glare data-tilt-max-glare="0.5" data-tilt-scale="1.03">
                        <div class="berita-main-img-wrapper">
                            <?php if (!empty($mainNews['thumbnail'])): ?>
                                <img src="<?= base_url($mainNews['thumbnail']) ?>" alt="<?= esc($mainNews['judul']) ?>" loading="lazy" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'berita-placeholder\'><i class=\'bi bi-newspaper\'></i></div>'">
                            <?php else: ?>
                                <div class="berita-placeholder"><i class="bi bi-newspaper"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="berita-main-body">
                            <div class="berita-meta">
                                <span class="category">BERITA DESA</span>
                                <span class="date"><i class="bi bi-dot"></i> <?= date('d M Y', strtotime($mainNews['tanggal_waktu'])) ?></span>
                            </div>
                            <h3 class="berita-main-title"><?= esc($mainNews['judul']) ?></h3>
                            <p class="berita-main-excerpt"><?= esc(character_limiter(strip_tags($mainNews['isi']), 150, '...')) ?></p>
                        </div>
                    </a>
                </div>

                <!-- Small Cards Stack (Right) -->
                <div class="berita-side-cards">
                    <?php for ($i = 1; $i < min(5, count($news)); $i++): ?>
                        <?php $item = $news[$i]; ?>
                        <div class="berita-float-wrapper" style="animation-delay: <?= $i * 0.4 ?>s;">
                            <a href="<?= base_url('/berita/' . $item['id']) ?>" class="berita-card berita-small-card" data-tilt data-tilt-max="12" data-tilt-speed="400" data-tilt-glare data-tilt-max-glare="0.5" data-tilt-scale="1.04">
                                <div class="berita-small-img-wrapper">
                                    <?php if (!empty($item['thumbnail'])): ?>
                                        <img src="<?= base_url($item['thumbnail']) ?>" alt="<?= esc($item['judul']) ?>" loading="lazy" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'berita-placeholder\'><i class=\'bi bi-newspaper\'></i></div>'">
                                    <?php else: ?>
                                        <div class="berita-placeholder"><i class="bi bi-newspaper"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="berita-small-body">
                                    <div class="berita-meta">
                                        <span class="category">BERITA DESA</span>
                                        <span class="date"><i class="bi bi-dot"></i> <?= date('d M Y', strtotime($item['tanggal_waktu'])) ?></span>
                                    </div>
                                    <h4 class="berita-small-title"><?= esc(character_limiter($item['judul'], 60, '...')) ?></h4>
                                </div>
                            </a>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="<?= base_url('/berita') ?>" class="btn-solid-green px-5 py-2">Lihat Semua</a>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-4">
                <p>Belum ada berita terbaru.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="home-galeri-section py-5 mb-5 position-relative overflow-hidden">
    <!-- Dynamic Background & Dark Overlay -->
    <div class="galeri-dynamic-bg" style="position:absolute; inset:0; z-index:-2; background-size:cover; background-position:center; filter:blur(10px); transform:scale(1.1); transition: background-image 0.8s ease-in-out;"></div>
    <div class="galeri-dark-overlay" style="position:absolute; inset:0; z-index:-1; background:rgba(0,0,0,0.65);"></div>

    <div style="max-width: 1400px; margin: 0 auto; width: 100%; padding: 0 1rem; position:relative; z-index:1;">
        <div class="row align-items-center">
            <!-- Left Column: Text & Controls -->
            <div class="col-lg-4 mb-5 mb-lg-0 pe-lg-4">
                <span class="section-kicker text-start d-block" style="color: #a7f3d0;">Galeri Desa</span>
                <h2 class="section-title text-start mb-3 text-white">Koleksi <span style="color: #4ade80;">Foto</span></h2>
                <p class="text-white-50 mb-4" style="line-height: 1.6;">
                    Temukan berbagai momen dan keindahan desa kami. Jelajahi galeri untuk melihat kegiatan dan pemandangan terbaik dari Desa.
                </p>
                <div class="d-flex align-items-center mb-4">
                    <a href="<?= base_url('/galeri') ?>" class="btn btn-light rounded-pill px-4 fw-bold" style="color: #166534;">Lihat Semua</a>
                </div>
                
                <?php if (!empty($albums) && count($albums) > 0): ?>
                <div class="galeri-controls">
                    <button class="btn-galeri-nav" id="btn-galeri-prev" aria-label="Previous image">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="btn-galeri-nav" id="btn-galeri-next" aria-label="Next image">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Slider -->
            <div class="col-lg-8">
                <?php if (!empty($albums) && count($albums) > 0): ?>
                    <?php $galeriItems = array_slice($albums, 0, 5); ?>
                    <div class="galeri-slider-container" id="galeri-slider-container">
                        <div class="galeri-slider-track" id="galeri-slider-track">
                            <?php foreach ($galeriItems as $album): ?>
                                <a href="<?= base_url('/galeri/' . $album['id']) ?>" class="galeri-slide-card">
                                    <?php if (!empty($album['thumbnail'])): ?>
                                        <img src="<?= base_url($album['thumbnail']) ?>" alt="<?= esc($album['nama_album']) ?>" class="galeri-img" loading="lazy" onerror="this.onerror=null;this.outerHTML='<div class=\'galeri-placeholder\'><i class=\'bi bi-images\'></i></div>'">
                                    <?php else: ?>
                                        <div class="galeri-placeholder"><i class="bi bi-images"></i></div>
                                    <?php endif; ?>
                                    <div class="galeri-overlay">
                                        <div class="galeri-kicker">Album</div>
                                        <h3 class="galeri-title"><?= esc($album['nama_album']) ?></h3>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center text-white-50 py-4 h-100 d-flex align-items-center justify-content-center">
                        <p class="mb-0">Belum ada galeri terbaru.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    // Infinite Slider Logic for Galeri with Dynamic Background
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('galeri-slider-track');
        const btnPrev = document.getElementById('btn-galeri-prev');
        const btnNext = document.getElementById('btn-galeri-next');
        const section = document.querySelector('.home-galeri-section');
        
        if (!track || !btnPrev || !btnNext || !section) return;

        const defaultBg = '<?= base_url('assets/img/section-foto.jpg') ?>';

        function updateBackground(activeCard) {
            let bgUrl = defaultBg;
            if (activeCard) {
                const imgElement = activeCard.querySelector('img.galeri-img');
                // Check if image exists
                if (imgElement && imgElement.src) {
                    bgUrl = imgElement.src;
                }
            }
            
            const bgElement = document.querySelector('.galeri-dynamic-bg');
            if (bgElement && bgUrl) {
                bgElement.style.backgroundImage = `url('${bgUrl}')`;
            }
        }

        // Initialize background with the first card
        const initialCards = track.querySelectorAll('.galeri-slide-card');
        if (initialCards.length > 0) {
            updateBackground(initialCards[0]);
        }

        let isAnimating = false;

        btnNext.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;
            
            const cards = track.querySelectorAll('.galeri-slide-card');
            if (cards.length < 2) {
                isAnimating = false;
                return;
            }
            
            const firstCard = cards[0];
            const nextCard = cards[1]; // This will be the new active card
            const cardWidth = firstCard.offsetWidth;
            const gap = parseFloat(window.getComputedStyle(track).gap) || 24; // 1.5rem = 24px
            
            // Change background immediately as it starts sliding to the next one
            updateBackground(nextCard);
            
            // Animate moving left
            track.style.transition = 'transform 0.5s cubic-bezier(0.25, 1, 0.5, 1)';
            track.style.transform = `translateX(-${cardWidth + gap}px)`;
            
            // After animation, append first item to end and reset transform
            setTimeout(() => {
                track.style.transition = 'none';
                track.appendChild(firstCard);
                track.style.transform = 'translateX(0)';
                isAnimating = false;
            }, 500);
        });

        btnPrev.addEventListener('click', () => {
            if (isAnimating) return;
            isAnimating = true;
            
            const cards = track.querySelectorAll('.galeri-slide-card');
            if (cards.length < 2) {
                isAnimating = false;
                return;
            }
            
            const lastCard = cards[cards.length - 1];
            const cardWidth = lastCard.offsetWidth;
            const gap = parseFloat(window.getComputedStyle(track).gap) || 24;
            
            // Instantly move last item to front and offset track left
            track.style.transition = 'none';
            track.prepend(lastCard);
            track.style.transform = `translateX(-${cardWidth + gap}px)`;
            
            // Force reflow
            track.offsetHeight;
            
            // Update background right before sliding it in
            updateBackground(lastCard);
            
            // Animate moving back to 0
            track.style.transition = 'transform 0.5s cubic-bezier(0.25, 1, 0.5, 1)';
            track.style.transform = 'translateX(0)';
            
            setTimeout(() => {
                isAnimating = false;
            }, 500);
        });
    });
</script>

<script>
(function() {
    const outer = document.querySelector('.galeri-marquee-outer');
    const track = document.querySelector('.galeri-marquee-track');
    if (!outer || !track) return;

    const DURATION = 25; // must match CSS animation duration in seconds
    let isDragging = false;
    let startX = 0;
    let currentTranslate = 0;

    /* Get current translateX in pixels from computed style */
    function getCurrentTranslateX() {
        const matrix = new DOMMatrix(window.getComputedStyle(track).transform);
        return matrix.m41;
    }

    /* Normalize position into the seamless loop range */
    function normalizeTranslate(px) {
        const halfWidth = track.scrollWidth / 2;
        let normalized = px % halfWidth;
        if (normalized > 0) normalized -= halfWidth;
        return normalized;
    }

    /* Freeze animation at current visual position */
    function freezeAnimation() {
        currentTranslate = getCurrentTranslateX();
        track.style.animation = 'none';
        track.style.transform = `translateX(${currentTranslate}px)`;
    }

    /* Resume animation from current position using negative delay */
    function resumeAnimation() {
        const normalized = normalizeTranslate(currentTranslate);
        const halfWidth = track.scrollWidth / 2;
        const progress = normalized / (-halfWidth);
        const delay = -(progress * DURATION);
        track.style.transform = '';
        track.style.animation = `galeriScroll ${DURATION}s ${delay}s linear infinite`;
    }

    /* ---- Mouse ---- */
    outer.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX;
        freezeAnimation();
        outer.style.cursor = 'grabbing';
        e.preventDefault();
    });
    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        currentTranslate += e.pageX - startX;
        startX = e.pageX;
        track.style.transform = `translateX(${currentTranslate}px)`;
    });
    document.addEventListener('mouseup', () => {
        if (!isDragging) return;
        isDragging = false;
        outer.style.cursor = '';
        resumeAnimation();
    });

    /* ---- Touch ---- */
    outer.addEventListener('touchstart', (e) => {
        isDragging = true;
        startX = e.touches[0].pageX;
        freezeAnimation();
    }, { passive: true });
    outer.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        currentTranslate += e.touches[0].pageX - startX;
        startX = e.touches[0].pageX;
        track.style.transform = `translateX(${currentTranslate}px)`;
    }, { passive: true });
    outer.addEventListener('touchend', () => {
        if (!isDragging) return;
        isDragging = false;
        resumeAnimation();
    });
})();
</script>

<!-- ================================================
     UMKM PRODUK SECTION
     ================================================ -->
<section class="home-umkm-section py-5 mb-5">
    <div style="max-width: 1400px; margin: 0 auto; width: 100%; padding: 0 1rem;">
        <!-- 10 Cards Grid with Left Title -->
        <div class="row g-4 align-items-stretch">
            <!-- Title Section (Takes 2 cards width = col-lg-6) -->
            <div class="col-12 col-lg-6 d-flex flex-column justify-content-center align-items-start pe-lg-5 mb-4 mb-lg-0">
                <span class="section-kicker text-dark d-block">UMKM Desa</span>
                <h2 class="section-title text-dark mb-4" style="font-size: 3.5rem; line-height: 1.1;">Produk <br/><span style="color: #166534;">Unggulan</span></h2>
                <p class="text-muted mb-5" style="font-size: 1.1rem; line-height: 1.6;">Dukung pertumbuhan ekonomi lokal dengan membeli dan menikmati berbagai produk unggulan hasil karya warga Desa Bonto Marannu.</p>
                <a href="<?= base_url('/umkm') ?>" class="btn-solid-green px-5 py-3 rounded-pill fw-bold">
                    Lihat Semua Katalog <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>

            <?php 
            // 6 product slots (2 cards row 1, 4 cards row 2)
            for ($i = 0; $i < 6; $i++): 
            ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <?php if (isset($umkmProducts[$i])): ?>
                        <?php $produk = $umkmProducts[$i]; ?>
                        <a href="<?= base_url('/umkm/' . $produk['umkm_id']) ?>" class="umkm-card">
                            <div class="umkm-img-wrapper">
                                <?php if (!empty($produk['gambar_path'])): ?>
                                    <img src="<?= base_url($produk['gambar_path']) ?>" alt="<?= esc($produk['nama_produk']) ?>" loading="lazy" onerror="this.onerror=null;this.outerHTML='<div class=\'umkm-placeholder\'><i class=\'bi bi-shop\'></i></div>'">
                                <?php else: ?>
                                    <div class="umkm-placeholder"><i class="bi bi-shop"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="umkm-card-body">
                                <h5 class="umkm-title text-truncate d-block w-100" title="<?= esc($produk['nama_produk']) ?>"><?= esc($produk['nama_produk']) ?></h5>
                                <div class="umkm-price-row">
                                    <span class="umkm-price">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></span>
                                    <span class="umkm-action-icon">
                                        <i class="bi bi-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php else: ?>
                        <!-- Placeholder "Segera Hadir" -->
                        <div class="umkm-card d-flex flex-column justify-content-center align-items-center text-center p-4" style="background: rgba(255,255,255,0.5); border: 2px dashed rgba(0,0,0,0.1); box-shadow: none;">
                            <i class="bi bi-box-seam text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h5 class="fw-bold text-muted mb-2">Segera Hadir</h5>
                            <p class="text-muted small mb-0 opacity-75">Produk unggulan baru sedang dipersiapkan oleh warga desa.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>



<!-- ================================================
     PARIWISATA SECTION
     ================================================ -->
<section class="home-pariwisata-section py-5 mb-5 position-relative overflow-hidden">
    <!-- Dynamic Background & Dark Overlay -->
    <div class="pariwisata-dynamic-bg" style="position:absolute; inset:0; z-index:-2; background-size:cover; background-position:center; filter:blur(10px); transform:scale(1.1); transition: background-image 0.8s ease-in-out;"></div>
    <div class="pariwisata-dark-overlay" style="position:absolute; inset:0; z-index:-1; background:rgba(0,0,0,0.65);"></div>

    <div style="max-width: 1400px; margin: 0 auto; width: 100%; padding: 0 1rem; position:relative; z-index:1;">
        <!-- Header -->
        <div class="text-center mb-5 mt-3">
            <span class="section-kicker text-light d-block mb-2" style="opacity: 0.9; letter-spacing: 2px;">Jelajahi Keindahan Alam</span>
            <h2 class="section-title text-white mb-3" style="font-size: 3.5rem;">Destinasi <span style="color: #4ade80;">Wisata</span></h2>
            <p class="text-white-50 mx-auto" style="max-width: 600px; font-size: 1.1rem; line-height: 1.6;">
                Temukan surga tersembunyi di Desa Bonto Marannu. Nikmati pesona alam, keunikan budaya, dan pengalaman liburan yang tak terlupakan.
            </p>
        </div>

        <?php if (!empty($pariwisataList)): ?>
            <!-- Swiper CSS (Inline dependency kept here, only our custom styles moved out) -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
            <div class="swiper pariwisataSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($pariwisataList as $wisata): ?>
                        <div class="swiper-slide" data-bg="<?= !empty($wisata['thumbnail_display']) ? base_url($wisata['thumbnail_display']) : base_url('assets/img/logolandscape.webp') ?>">
                            <a href="<?= base_url('/pariwisata/' . $wisata['id']) ?>" class="d-block w-100 h-100 position-relative text-decoration-none">
                                <?php if (!empty($wisata['thumbnail_display'])): ?>
                                    <img src="<?= base_url($wisata['thumbnail_display']) ?>" alt="<?= esc($wisata['nama_tempat']) ?>" class="w-100 h-100" style="object-fit: cover;" loading="lazy">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted">
                                        <i class="bi bi-image" style="font-size: 4rem;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="pariwisata-overlay">
                                    <div class="pariwisata-kicker">Destinasi Wisata</div>
                                    <h3 class="pariwisata-title"><?= esc($wisata['nama_tempat']) ?></h3>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <div class="text-center mt-5 mb-3">
                <a href="<?= base_url('/pariwisata') ?>" class="btn rounded-pill px-5 py-3 fw-bold text-white" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3); letter-spacing: 1px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'">
                    Jelajahi Semua Wisata
                </a>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-geo-alt d-block mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                <p>Belum ada destinasi wisata yang ditambahkan.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Swiper JS Script -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined' && document.querySelector('.pariwisataSwiper')) {
        new Swiper('.pariwisataSwiper', {
            effect: 'coverflow',
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            loop: true,
            loopedSlides: 5,     // How many slides to clone for seamless infinite feel
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            coverflowEffect: {
                rotate: 0,
                stretch: -10,
                depth: 150,
                modifier: 1.5,
                slideShadows: true,
            },

            on: {
                init: function(swiper) {
                    updatePariwisataBg(swiper);
                },
                slideChange: function(swiper) {
                    updatePariwisataBg(swiper);
                },
                // Ensure enough slides exist for smooth looping
                beforeInit: function(swiper) {
                    const wrapper = swiper.wrapperEl;
                    const origSlides = Array.from(wrapper.children);
                    if (origSlides.length < 6) {
                        // Duplicate slides enough times so loop always has material
                        const needed = Math.ceil(6 / origSlides.length);
                        for (let i = 0; i < needed; i++) {
                            origSlides.forEach(slide => {
                                wrapper.appendChild(slide.cloneNode(true));
                            });
                        }
                    }
                }
            }
        });
        
        function updatePariwisataBg(swiper) {
            const activeSlide = swiper.slides[swiper.activeIndex];
            if (!activeSlide) return;
            const bgUrl = activeSlide.getAttribute('data-bg');
            const bgElement = document.querySelector('.pariwisata-dynamic-bg');
            if (bgElement && bgUrl) {
                bgElement.style.backgroundImage = `url('${bgUrl}')`;
            }
        }
    }
});
</script>

<!-- ================================================
     CTA LAPORAN SECTION
     ================================================ -->
<section class="home-cta-section py-5 my-5">
    <div style="max-width: 1400px; margin: 0 auto; width: 100%; padding: 0 1rem;">
        <div class="row align-items-center">
            <!-- Text Content -->
            <div class="col-lg-7 py-5 pe-lg-5 text-start">
                <div class="me-lg-5 my-3">
                    <span class="section-kicker text-dark d-block mb-3">Layanan Masyarakat</span>
                    <h2 class="section-title text-dark mb-4" style="font-size: 3rem; line-height: 1.2;">Ada Pertanyaan atau <br/><span style="color: #166534;">Laporan?</span></h2>
                    <p class="text-muted fs-5 mb-5" style="line-height: 1.6;">
                        Jangan ragu untuk menghubungi kami. Setiap pertanyaan, masukan, maupun laporan Anda sangat berharga untuk kemajuan Desa Bonto Marannu.
                    </p>
                    <a href="<?= base_url('/pengaduan') ?>" class="btn-solid-green d-inline-block text-center" style="padding: 1.1rem 3rem; font-size: 1.1rem; font-weight: 600; border-radius: 50px; box-shadow: 0 8px 20px rgba(22, 101, 52, 0.2);">
                        Buat Laporan Sekarang
                    </a>
                </div>
            </div>
            <!-- Illustration -->
            <div class="col-lg-5 d-none d-lg-flex justify-content-center align-items-center position-relative" style="min-height: 400px;">
                <img src="<?= base_url('assets/img/laporan-illustration.png') ?>" alt="Laporan Masyarakat" class="img-fluid" style="object-fit: contain; max-height: 500px; mix-blend-mode: multiply;">
            </div>
        </div>
    </div>
</section>



<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Text Typing Animation (Only for Title)
    const title = document.querySelector('.hero-title-new');
    
    if(title) {
        const tHTML = title.innerHTML;
        title.innerHTML = '';
        
        function typeHTML(element, htmlString, speed) {
            let i = 0;
            element.style.visibility = 'visible';
            function type() {
                if (i <= htmlString.length) {
                    if (htmlString.charAt(i) === '<') {
                        while (htmlString.charAt(i) !== '>' && i < htmlString.length) i++;
                        i++;
                    } else {
                        i++;
                    }
                    element.innerHTML = htmlString.substring(0, i);
                    if (i < htmlString.length) {
                        setTimeout(type, speed);
                    }
                }
            }
            type();
        }
        
        typeHTML(title, tHTML, 50);
    }

    // 2. Number Counter Animation
    const statsNumber = document.querySelector('.stats-number');
    if (statsNumber) {
        // Find the number in the text
        const textStr = statsNumber.innerText;
        const numMatch = textStr.match(/\d+/);
        if (numMatch) {
            const target = parseInt(numMatch[0], 10);
            const suffix = textStr.replace(numMatch[0], '');
            let count = 0;
            const duration = 2000;
            const interval = 20;
            const increment = target / (duration / interval);
            
            const timer = setInterval(() => {
                count += increment;
                if (count >= target) {
                    statsNumber.innerText = target + suffix;
                    clearInterval(timer);
                } else {
                    statsNumber.innerText = Math.floor(count) + suffix;
                }
            }, interval);
        }
    }
});
</script>

<!-- Vanilla Tilt JS for 3D Parallax Effect -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js" integrity="sha512-wC/g5368cx2Owt1xO16tH0jE4ZkhR5J5XGlYn1Xv+H3F/xR/7x55B0xQ5C14N17g5nEw/wT+sC6aT+uQYw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?= $this->endSection() ?>
