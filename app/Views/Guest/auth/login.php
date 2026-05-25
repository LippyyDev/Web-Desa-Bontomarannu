<?= $this->extend('Guest/auth_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/auth/login.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$heroImages = [
    base_url('assets/img/Bantaeng (1).jpg'),
    base_url('assets/img/Bantaeng (2).jpg'),
    base_url('assets/img/Bantaeng (3).jpg'),
    base_url('assets/img/Bantaeng (4).jpg'),
    base_url('assets/img/Bantaeng (5).jpg')
];
$isBlocked    = !empty($rateLimit['blocked']);
$blockSeconds = (int) ($rateLimit['seconds_left'] ?? 0);
?>

<section class="auth-section position-relative overflow-hidden">
    <!-- Hero Background Start -->
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
    <!-- Hero Background End -->

    <div class="container position-relative z-2 d-flex justify-content-center align-items-center w-100 h-100">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <a href="<?= base_url('/') ?>">
                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Desa" width="60" class="mb-3 drop-shadow">
                </a>
                <h4 class="fw-bold mb-1 text-white">Selamat Datang</h4>
                <p class="text-white-50 small">Masuk dengan email atau username Anda.</p>
            </div>

            <form method="post" action="<?= base_url('/login') ?>" id="loginForm">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label text-white-50 small mb-1">Email atau Username</label>
                    <input id="loginIdentity" type="text" class="form-control glass-input" name="identity"
                           value="<?= old('identity') ?>" required placeholder="Masukkan username atau email"
                           <?= $isBlocked ? 'disabled' : '' ?>>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label text-white-50 small mb-0">Password</label>
                        <a class="small text-white-50 text-decoration-none hover-white" href="<?= base_url('/forgot-password') ?>">Lupa password?</a>
                    </div>
                    <input id="loginPassword" type="password" class="form-control glass-input" name="password"
                           required placeholder="Masukkan password"
                           <?= $isBlocked ? 'disabled' : '' ?>>
                </div>
                <button id="loginSubmitBtn" class="btn glass-btn w-100 fw-bold" type="submit"
                        <?= $isBlocked ? 'disabled' : '' ?>>
                    <?= $isBlocked ? 'Terblokir' : 'Masuk' ?>
                </button>
            </form>

            <div class="text-center small mt-4 text-white-50">
                Belum punya akun? <a href="<?= base_url('/register') ?>" class="text-white fw-semibold text-decoration-none hover-white">Daftar sekarang</a>
            </div>

            <div class="mt-4 pt-3 border-top border-secondary border-opacity-50 text-center">
                <a href="<?= base_url('/') ?>" class="text-white-50 text-decoration-none hover-white small">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    const isBlocked = <?= $isBlocked ? 'true' : 'false' ?>;
    let   countdown = <?= $blockSeconds ?>;

    if (!isBlocked || countdown <= 0) return;

    const btn    = document.getElementById('loginSubmitBtn');
    const inputs = document.querySelectorAll('#loginForm input');

    inputs.forEach(el => { el.disabled = true; });
    btn.disabled = true;

    function tick() {
        if (countdown <= 0) {
            btn.disabled = false;
            btn.innerHTML = 'Masuk';
            inputs.forEach(el => { el.disabled = false; });
            return;
        }
        const m = Math.floor(countdown / 60);
        const s = countdown % 60;
        btn.innerHTML = 'Coba lagi dalam '
                        + m + ':' + String(s).padStart(2, '0');
        countdown--;
        setTimeout(tick, 1000);
    }

    tick();
})();
</script>
<?= $this->endSection() ?>
