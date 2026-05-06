<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5" style="max-width: 520px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-3 text-center">Login</h4>
            <p class="text-muted small text-center mb-4">Masuk dengan email atau username Anda.</p>
            <form method="post" action="<?= base_url('/login') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Email atau Username</label>
                    <input type="text" class="form-control" name="identity" value="<?= old('identity') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a class="small" href="<?= base_url('/forgot-password') ?>">Lupa password?</a>
                </div>
                <button class="btn btn-primary w-100" type="submit">Masuk</button>
            </form>
            <div class="text-center small mt-3">
                Belum punya akun? <a href="<?= base_url('/register') ?>">Daftar</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


