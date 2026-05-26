<?= $this->extend('Guest/auth_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/auth/verify_reset.css?v=' . time()) ?>">
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
$isFrozen   = !empty($otpRateLimit['frozen']);
$isCooldown = !empty($otpRateLimit['cooldown']);
$otpSeconds = (int) ($otpRateLimit['seconds_left'] ?? 0);
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

<?php
$hasSecurityQuestion = $hasSecurityQuestion ?? false;
$sqOpen              = $sqOpen              ?? false;
$sqLockedSeconds     = (int) ($sqLockedSeconds  ?? 0);
?>

    <div class="container position-relative z-2 d-flex justify-content-center align-items-center w-100 h-100">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <a href="<?= base_url('/') ?>">
                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Desa" width="60" class="mb-3 drop-shadow">
                </a>
                <h4 class="fw-bold mb-1 text-white" id="verifyCardTitle">Verifikasi OTP</h4>
                <p class="text-white-50 small" id="verifyCardSubtitle">Masukkan email dan kode OTP yang kami kirimkan ke email Anda.</p>
            </div>

            <!-- FORM OTP (default) -->
            <div id="formOtpSection">
                <form method="post" action="<?= base_url('/verify-reset') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label text-white-50 small mb-1">Email</label>
                        <input type="email" class="form-control glass-input" name="email"
                               value="<?= $pendingEmail ?? old('email') ?>" required placeholder="Masukkan email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white-50 small mb-1">Kode OTP</label>
                        <input type="text" class="form-control glass-input" name="otp"
                               maxlength="6" required placeholder="XXXXXX" autocomplete="one-time-code">
                    </div>
                    <button class="btn glass-btn w-100 fw-bold mb-2" type="submit">Verifikasi Kode</button>
                </form>

                <!-- Tombol Kirim Ulang OTP -->
                <button id="resendOtpBtn" type="button"
                        class="btn glass-btn-outline w-100 fw-semibold"
                        <?= ($isFrozen || $isCooldown) ? 'disabled' : '' ?>>
                    <?php if ($isFrozen): ?>
                        IP Dibekukan
                    <?php elseif ($isCooldown): ?>
                        Harap Tunggu
                    <?php else: ?>
                        Kirim Ulang Kode OTP
                    <?php endif; ?>
                </button>

                <!-- Opsi Pertanyaan Keamanan (hanya muncul jika ada) -->
                <?php if ($hasSecurityQuestion): ?>
                <div class="mt-3 text-center">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.2);">
                        <span class="text-white-50 small">atau</span>
                        <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.2);">
                    </div>
                    <button type="button" class="btn glass-btn-outline w-100 fw-semibold"
                            onclick="toggleSecurityQuestion()" id="btnUseSQ">
                        <i class="bi bi-shield-lock me-2"></i>Gunakan Pertanyaan Keamanan
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <!-- FORM PERTANYAAN KEAMANAN (tersembunyi default) -->
            <?php if ($hasSecurityQuestion): ?>
            <div id="formSQSection" style="display:none;">
                <form method="post" action="<?= base_url('/verify-security-question') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-4 p-3" style="background: rgba(255,255,255,0.08); border-radius: 10px; border: 1px solid rgba(255,255,255,0.15);">
                        <div class="text-white-50 small mb-1">Pertanyaan Keamanan:</div>
                        <div class="text-white fw-semibold" id="sqDisplay">Memuat...</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white-50 small mb-1">Jawaban Anda</label>
                        <input type="text" class="form-control glass-input" name="security_answer"
                               required placeholder="Masukkan jawaban" autocomplete="off">
                    </div>
                    <button class="btn glass-btn w-100 fw-bold mb-2" type="submit"
                            id="sqSubmitBtn">Verifikasi Jawaban</button>
                </form>
                <button type="button" class="btn glass-btn-outline w-100 fw-semibold mt-2"
                        onclick="toggleSecurityQuestion()">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke OTP
                </button>
            </div>
            <?php endif; ?>

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
/* ── CSRF helper (global scope, dipakai IIFE & toggleSecurityQuestion) ── */
const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

(function () {
    /* ── State dari server ────────────────────────────────────────────────── */
    let isFrozen  = <?= $isFrozen   ? 'true' : 'false' ?>;
    let countdown = <?= $otpSeconds ?>;

    const resendBtn  = document.getElementById('resendOtpBtn');
    let   timerHandle = null;

    /* ── Toast helper (tidak bergantung SweetAlert) ───────────────────────── */
    function showToast(type, message) {
        let container = document.getElementById('authToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id        = 'authToastContainer';
            container.className = 'glass-toast-container';
            document.body.appendChild(container);
        }
        const icons = {
            success: 'bi-check-circle-fill',
            error:   'bi-exclamation-circle-fill',
            info:    'bi-info-circle-fill',
        };
        const toast = document.createElement('div');
        toast.className = 'glass-toast ' + type;
        toast.innerHTML =
            `<div class="glass-toast-icon"><i class="bi ${icons[type] || icons.info}"></i></div>` +
            `<div class="glass-toast-content">${message}</div>`;
        container.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 3500);
    }

    /* ── Format waktu ─────────────────────────────────────────────────────── */
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

    /* ── Mulai countdown pada tombol ──────────────────────────────────────── */
    function startCountdown(seconds, frozen) {
        if (timerHandle) clearInterval(timerHandle);
        countdown = seconds;
        isFrozen  = frozen;
        resendBtn.disabled = true;
        updateLabel();
        timerHandle = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(timerHandle);
                resendBtn.disabled = false;
                resendBtn.innerHTML = 'Kirim Ulang Kode OTP';
                return;
            }
            updateLabel();
        }, 1000);
    }

    function updateLabel() {
        if (isFrozen) {
            resendBtn.innerHTML =
                'Dibekukan (' + formatTime(countdown) + ')';
        } else {
            resendBtn.innerHTML =
                'Kirim ulang dalam ' + formatTime(countdown);
        }
    }

    /* Mulai countdown otomatis jika ada cooldown/frozen dari server */
    if (countdown > 0) {
        startCountdown(countdown, isFrozen);
    }

    /* ── Klik Kirim Ulang ─────────────────────────────────────────────────── */
    resendBtn.addEventListener('click', function () {
        resendBtn.disabled  = true;
        resendBtn.innerHTML = 'Mengirim...';

        const formData = new URLSearchParams();
        formData.append(csrfHeaderName, getCsrfHash());

        fetch('<?= base_url('/resend-otp') ?>', {
            method:  'POST',
            body:    formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message || 'Kode OTP baru telah dikirim ke email Anda.');
                /* Jika setelah kirim IP langsung dibekukan, tampilkan freeze countdown */
                if (data.frozen_after) {
                    startCountdown(data.seconds_left || 3600, true);
                } else {
                    startCountdown(data.seconds_left || 60, false);
                }
            } else if (data.frozen) {
                showToast('error', data.message);
                startCountdown(data.seconds_left || 3600, true);
            } else if (data.cooldown) {
                showToast('info', data.message);
                startCountdown(data.seconds_left || 60, false);
            } else {
                showToast('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                resendBtn.disabled  = false;
                resendBtn.innerHTML = 'Kirim Ulang Kode OTP';
            }
        })
        .catch(() => {
            showToast('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            resendBtn.disabled  = false;
            resendBtn.innerHTML = 'Kirim Ulang Kode OTP';
        });
    });
})();

<?php if ($hasSecurityQuestion ?? false): ?>
/* ── Toggle Pertanyaan Keamanan ──────────────────────────────────── */
let sqLoaded = false;

function toggleSecurityQuestion() {
    const otpSection = document.getElementById('formOtpSection');
    const sqSection  = document.getElementById('formSQSection');
    const title      = document.getElementById('verifyCardTitle');
    const subtitle   = document.getElementById('verifyCardSubtitle');

    if (sqSection.style.display === 'none') {
        // Tampilkan form SQ, sembunyikan OTP
        otpSection.style.display = 'none';
        sqSection.style.display  = '';
        title.textContent    = 'Pertanyaan Keamanan';
        subtitle.textContent = 'Jawab pertanyaan keamanan untuk mereset password Anda.';

        // Fetch teks pertanyaan jika belum dimuat
        if (!sqLoaded) {
            const formData = new URLSearchParams();
            formData.append(csrfHeaderName, getCsrfHash());

            fetch('<?= base_url('/get-security-question') ?>', {
                method:  'POST',
                body:    formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(res => res.json())
            .then(data => {
                const el = document.getElementById('sqDisplay');
                if (data.success && data.question) {
                    el.textContent = data.question;
                } else {
                    el.textContent = '(Pertanyaan tidak tersedia)';
                }
                sqLoaded = true;
            })
            .catch(() => {
                document.getElementById('sqDisplay').textContent = '(Gagal memuat pertanyaan)';
            });
        }
    } else {
        // Kembali ke form OTP
        sqSection.style.display  = 'none';
        otpSection.style.display = '';
        title.textContent    = 'Verifikasi OTP';
        subtitle.textContent = 'Masukkan email dan kode OTP yang kami kirimkan ke email Anda.';
    }
}
<?php endif; ?>

<?php if (($hasSecurityQuestion ?? false) && ($sqOpen ?? false)): ?>
/* ── Auto-buka form SQ saat kembali dari error jawaban salah ─────── */
document.addEventListener('DOMContentLoaded', function () {
    toggleSecurityQuestion();

    /* ── Countdown tombol Verifikasi Jawaban jika sedang dikunci ── */
    const sqSubmitBtn = document.getElementById('sqSubmitBtn');
    let sqCountdown   = <?= (int) $sqLockedSeconds ?>;

    if (sqSubmitBtn && sqCountdown > 0) {
        sqSubmitBtn.disabled = true;

        function formatSqTime(s) {
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

        function updateSqBtn() {
            sqSubmitBtn.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Terkunci (' + formatSqTime(sqCountdown) + ')';
        }

        updateSqBtn();
        const sqTimer = setInterval(function () {
            sqCountdown--;
            if (sqCountdown <= 0) {
                clearInterval(sqTimer);
                sqSubmitBtn.disabled  = false;
                sqSubmitBtn.innerHTML = 'Verifikasi Jawaban';
                return;
            }
            updateSqBtn();
        }, 1000);
    }
});
<?php endif; ?>
</script>
<?= $this->endSection() ?>
