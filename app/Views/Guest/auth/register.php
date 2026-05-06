<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5" style="max-width: 560px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-3 text-center">Registrasi</h4>
            <p class="text-muted small text-center mb-4">Buat akun warga untuk mengajukan surat dan memantau status.</p>
            <form method="post" action="<?= base_url('/register') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="<?= old('username') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= old('email') ?>" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirm" required>
                    </div>
                </div>
                <button class="btn btn-primary w-100 mt-4" type="submit">Daftar</button>
            </form>
            <div class="text-center small mt-3">
                Sudah punya akun? <a href="<?= base_url('/login') ?>">Login</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


