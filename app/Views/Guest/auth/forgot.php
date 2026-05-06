<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5" style="max-width: 520px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-3 text-center">Lupa Password</h4>
            <p class="text-muted small text-center mb-4">Masukkan email Anda untuk menerima kode OTP reset.</p>
            <form method="post" action="<?= base_url('/forgot-password') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Kirim OTP</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


