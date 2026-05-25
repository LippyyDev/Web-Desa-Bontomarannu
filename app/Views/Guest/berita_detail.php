<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/berita.css?v=' . time()) ?>">
<style>
/* Add extra styles for article content if needed */
.article-thumbnail-wrapper {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 2.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}
.article-thumbnail-wrapper img {
    width: 100%;
    height: auto;
    object-fit: cover;
    max-height: 500px;
}
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #334155;
}
.article-content p {
    margin-bottom: 1.5rem;
}

/* ── Media skeleton ── */
.skeleton-media-card {
    border-radius: 16px;
    background: #fff;
    border: 1px solid #edf2f7;
    overflow: hidden;
}
.skeleton-media-img {
    width: 100%;
    height: 180px;
    background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
@keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5 mt-5">
        
        <!-- Main Article Section -->
        <div class="row">
            <div class="col-12 reveal-up">
                <a href="<?= base_url('/berita') ?>" class="btn btn-outline-success btn-sm mb-4 rounded-pill px-3 fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Berita
                </a>
                
                <h1 class="fw-bold mb-3" style="color: #111827; font-size: 2.5rem; line-height: 1.3;"><?= esc($item['judul']) ?></h1>
                
                <div class="d-flex align-items-center gap-3 text-muted mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-event text-success"></i>
                        <span><?= date('d F Y', strtotime($item['tanggal_waktu'])) ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-success"></i>
                        <span>Admin Desa</span>
                    </div>
                </div>

                <div class="article-thumbnail-wrapper">
                    <?php if (!empty($item['thumbnail'])): ?>
                        <a href="<?= esc(base_url($item['thumbnail'])) ?>" target="_blank">
                            <img src="<?= esc(base_url($item['thumbnail'])) ?>" alt="<?= esc($item['judul']) ?>" onerror="this.onerror=null; this.outerHTML='<div class=\'news-placeholder py-5\' style=\'height: 400px;\'><i class=\'bi bi-newspaper\'></i></div>';">
                        </a>
                    <?php else: ?>
                        <div class="news-placeholder" style="height: 400px;">
                            <i class="bi bi-newspaper"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <article class="article-content">
                    <?= $item['isi'] ?>
                </article>

                <!-- ── Gambar Terkait — loaded via AJAX ── -->
                <div id="mediaTerkaitSection" style="display:none;">
                    <h4 class="fw-bold mt-5 mb-4">Gambar Terkait</h4>

                    <!-- Skeleton loader -->
                    <div id="mediaSkeletonLoader" class="row g-3">
                        <?php for ($s = 0; $s < 4; $s++): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="skeleton-media-card">
                                <div class="skeleton-media-img"></div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Actual media container -->
                    <div id="mediaContainer" class="row g-3" style="display:none;"></div>
                </div>
            </div>
        </div>

        <!-- Latest News Section (Bottom) -->
        <div class="row mt-5 pt-5 border-top reveal-up delay-200">
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width: 5px; height: 28px; background-color: #2E7D32; border-radius: 4px;"></div>
                    <h4 class="fw-bold mb-0">Berita Terbaru Lainnya</h4>
                </div>

                <div class="row g-4 justify-content-center">
                    <?php foreach ($other_news as $other): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="news-card">
                                <div class="news-img-wrapper">
                                    <div class="news-date-pill">
                                        <i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($other['tanggal_waktu'])) ?>
                                    </div>
                                    <?php if (!empty($other['thumbnail'])): ?>
                                        <img src="<?= esc(base_url($other['thumbnail'])) ?>" alt="<?= esc($other['judul']) ?>" onerror="this.onerror=null; this.outerHTML='<div class=\'news-placeholder\'><i class=\'bi bi-newspaper\'></i></div>';">
                                    <?php else: ?>
                                        <div class="news-placeholder">
                                            <i class="bi bi-newspaper"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="news-card-body">
                                    <h6 class="news-title" style="font-size: 1.05rem;"><?= esc($other['judul']) ?></h6>
                                    <a href="<?= base_url('/berita/' . $other['id']) ?>" class="news-read-more stretched-link mt-2" style="font-size: 0.85rem;">Baca selengkapnya <span class="arrow">→</span></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($other_news)): ?>
                        <div class="col-12">
                            <div class="text-muted text-center py-4 bg-light rounded-4">
                                <i class="bi bi-journal-x fs-2 mb-2 d-block"></i>
                                Belum ada berita terbaru lainnya
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── CSRF helpers (standard project pattern) ── */
    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    function escapeHtml(str) {
        return (str || '').toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* ── Reveal-up observer ── */
    const revealObserver = new IntersectionObserver(function (entries, obs) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.reveal-up').forEach(el => revealObserver.observe(el));

    /* ── Load media terkait via AJAX POST ── */
    const newsId              = <?= (int)$item['id'] ?>;
    const mediaTerkaitSection = document.getElementById('mediaTerkaitSection');
    const mediaSkeletonLoader = document.getElementById('mediaSkeletonLoader');
    const mediaContainer      = document.getElementById('mediaContainer');

    const formData = new URLSearchParams();
    formData.append(csrfHeaderName, getCsrfHash());

    /* Show the section + skeleton immediately */
    mediaTerkaitSection.style.display = 'block';

    fetch(`<?= base_url('/berita/') ?>${newsId}/media-api`, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(response => {
        mediaSkeletonLoader.style.display = 'none';

        if (!response.success || !response.data || response.data.length === 0) {
            /* No media → hide the whole section cleanly */
            mediaTerkaitSection.style.display = 'none';
            return;
        }

        let html = '';
        response.data.forEach(function (m) {
            if (m.media_type === 'video_link' && m.embed_url) {
                html += `
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="ratio ratio-16x9">
                            <iframe src="${escapeHtml(m.embed_url)}" allowfullscreen loading="lazy"></iframe>
                        </div>
                    </div>
                </div>`;
            } else if (m.media_path) {
                html += `
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <a href="${escapeHtml(m.media_path)}" target="_blank" class="d-block">
                            <img src="${escapeHtml(m.media_path)}"
                                 class="card-img-top w-100 object-fit-cover"
                                 style="height: 180px;"
                                 alt="Media"
                                 loading="lazy"
                                 onerror="this.onerror=null;this.style.display='none';">
                        </a>
                    </div>
                </div>`;
            }
        });

        mediaContainer.innerHTML = html;
        mediaContainer.style.display = '';
    })
    .catch(() => {
        /* On network error, silently hide the section */
        mediaTerkaitSection.style.display = 'none';
    });

});
</script>
<?= $this->endSection() ?>
