<?= $this->extend('Guest/auth_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/auth/forgot.css?v=' . time()) ?>">
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
$isFrozen     = !empty($otpRateLimit['frozen']);
$isCooldown   = !empty($otpRateLimit['cooldown']);
$otpSeconds   = (int) ($otpRateLimit['seconds_left'] ?? 0);
$isRestricted = $isFrozen || $isCooldown;
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
                <h4 class="fw-bold mb-1 text-white">Lupa Password</h4>
                <p class="text-white-50 small">Masukkan email Anda untuk menerima kode OTP reset.</p>
            </div>

            <form method="post" action="<?= base_url('/forgot-password') ?>" id="forgotForm">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="form-label text-white-50 small mb-1">Email</label>
                    <input id="forgotEmail" type="email" class="form-control glass-input" name="email"
                           required placeholder="Masukkan email"
                           <?= $isRestricted ? 'disabled' : '' ?>>
                </div>
                <button id="sendOtpBtn" class="btn glass-btn w-100 fw-bold" type="submit"
                        <?= $isRestricted ? 'disabled' : '' ?>>
                    <?php if ($isFrozen): ?>
                        IP Dibekukan
                    <?php elseif ($isCooldown): ?>
                        Harap Tunggu
                    <?php else: ?>
                        Kirim OTP
                    <?php endif; ?>
                </button>
            </form>

            <div class="mt-4 pt-3 border-top border-secondary border-opacity-50 text-center">
                <a href="<?= base_url('/login') ?>" class="text-white-50 text-decoration-none hover-white small">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    const isFrozen   = <?= $isFrozen   ? 'true' : 'false' ?>;
    const isCooldown = <?= $isCooldown ? 'true' : 'false' ?>;
    let   countdown  = <?= $otpSeconds ?>;

    if (countdown <= 0) return;

    const btn   = document.getElementById('sendOtpBtn');
    const input = document.getElementById('forgotEmail');
    btn.disabled   = true;
    input.disabled = true;

    function formatTime(s) {
        if (s >= 3600) {
            const h = Math.floor(s / 3600);
            const m = Math.floor((s % 3600) / 60);
            return h + 'j ' + m + 'm';
        }
        if (s >= 60) {
            const m   = Math.floor(s / 60);
            const sec = s % 60;
            return m + 'm ' + sec + 'd';
        }
        return s + ' detik';
    }

    function tick() {
        if (countdown <= 0) {
            btn.disabled   = false;
            input.disabled = false;
            btn.innerHTML  = 'Kirim OTP';
            return;
        }
        if (isFrozen) {
            btn.innerHTML = 'Dibekukan (' + formatTime(countdown) + ')';
        } else {
            btn.innerHTML = 'Coba lagi dalam ' + formatTime(countdown);
        }
        countdown--;
        setTimeout(tick, 1000);
    }

    tick();
})();
</script>
<?= $this->endSection() ?>
