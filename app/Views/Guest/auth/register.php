<?= $this->extend('Guest/auth_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/auth/register.css?v=' . time()) ?>">
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
                <h4 class="fw-bold mb-1 text-white">Registrasi</h4>
                <p class="text-white-50 small">Buat akun warga untuk mengajukan surat dan memantau status.</p>
            </div>
            
            <form method="post" action="<?= base_url('/register') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label text-white-50 small mb-1">Username</label>
                    <input type="text" class="form-control glass-input" name="username" value="<?= old('username') ?>" required placeholder="Masukkan username">
                </div>
                <div class="mb-3">
                    <label class="form-label text-white-50 small mb-1">Email</label>
                    <input type="email" class="form-control glass-input" name="email" value="<?= old('email') ?>" required placeholder="Masukkan email">
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small mb-1">Password</label>
                        <input type="password" class="form-control glass-input" name="password" required placeholder="Password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small mb-1">Konfirmasi Password</label>
                        <input type="password" class="form-control glass-input" name="password_confirm" required placeholder="Konfirmasi">
                    </div>
                </div>
                <button class="btn glass-btn w-100 fw-bold" type="submit">Daftar</button>
            </form>
            
            <div class="text-center small mt-4 text-white-50">
                Sudah punya akun? <a href="<?= base_url('/login') ?>" class="text-white fw-semibold text-decoration-none hover-white">Login di sini</a>
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
