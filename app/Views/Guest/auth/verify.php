<?= $this->extend('Guest/auth_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/auth/verify.css?v=' . time()) ?>">
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
                <h4 class="fw-bold mb-1 text-white">Verifikasi Akun</h4>
                <p class="text-white-50 small">Masukkan email dan kode OTP yang dikirim. Untuk pengujian, OTP juga ditampilkan di bawah.</p>
            </div>
            
            <?php if (!empty($previewOtp)): ?>
                <div class="alert alert-info text-center small py-2 mb-3" style="background: rgba(13, 202, 240, 0.2); border-color: rgba(13, 202, 240, 0.3); color: #fff;">
                    OTP: <strong><?= esc($previewOtp) ?></strong>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?= base_url('/verify') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label text-white-50 small mb-1">Email</label>
                    <input type="email" class="form-control glass-input" name="email" value="<?= $pendingEmail ?? old('email') ?>" required placeholder="Masukkan email">
                </div>
                <div class="mb-4">
                    <label class="form-label text-white-50 small mb-1">Kode OTP</label>
                    <input type="text" class="form-control glass-input" name="otp" maxlength="6" required placeholder="XXXXXX">
                </div>
                <button class="btn glass-btn w-100 fw-bold" type="submit">Verifikasi</button>
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
