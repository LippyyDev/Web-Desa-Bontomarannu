<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/berita.css?v=' . time()) ?>">
<style>
.form-card {
    border: 1px solid #edf2f7;
    border-radius: 24px;
    background: #ffffff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    padding: 2.5rem;
}
@media (max-width: 768px) {
    .form-card {
        padding: 1.5rem;
    }
}
.form-card .form-label {
    font-weight: 500;
    color: #334155;
    margin-bottom: 0.5rem;
}
.form-card .form-control {
    border-radius: 12px;
    padding: 0.75rem 1rem;
    border: 1px solid #cbd5e1;
    font-size: 0.95rem;
    transition: all 0.2s ease-in-out;
}
.form-card .form-control:focus {
    border-color: #2E7D32;
    box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.15);
}
.form-card textarea.form-control {
    border-radius: 16px;
}
.form-card .btn-success {
    background-color: #2E7D32;
    border-color: #2E7D32;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.2s ease-in-out;
}
.form-card .btn-success:hover, .form-card .btn-success:focus {
    background-color: #1b5e20;
    border-color: #1b5e20;
}
.form-card .btn-success:disabled {
    background-color: #a5d6a7;
    border-color: #a5d6a7;
}
.form-card .btn-outline-secondary {
    border-radius: 10px;
    border-color: #cbd5e1;
    color: #475569;
    padding: 0.375rem 0.75rem;
}
.form-card .btn-outline-secondary:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
}
#captchaPanel {
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 16px !important;
    padding: 1.25rem !important;
}
#captchaSuccess .border-success {
    border-color: #dcfce7 !important;
    border-radius: 16px !important;
}
#captchaInput {
    border-radius: 10px !important;
}
#btnCekCaptcha {
    border-radius: 10px !important;
}
#btnRefreshCaptcha {
    height: 50px !important;
    width: 50px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 12px !important;
    border-color: #cbd5e1 !important;
    color: #475569 !important;
    background-color: #ffffff !important;
    transition: all 0.2s ease-in-out !important;
}
#btnRefreshCaptcha:hover {
    background-color: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #1e293b !important;
}
#btnRefreshCaptcha i {
    font-size: 1.25rem !important;
}
.profil-desa-section .alert {
    border-radius: 16px;
}
.form-info-alert {
    background-color: #f0fdf4;
    border: 1px solid #dcfce7;
    color: #166534;
    border-radius: 16px;
    padding: 1.25rem;
}
.form-info-alert strong {
    color: #14532d;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5">
        
        <div class="profil-desa-header mt-5 mb-5 text-center reveal-up active">
            <span class="profil-desa-subtitle">Layanan Desa</span>
            <h2 class="profil-desa-title">Buat <span>Pengaduan</span></h2>
        </div>


        <div class="form-card mb-4">
            <form action="<?= base_url('/pengaduan') ?>" method="post" enctype="multipart/form-data" id="pengaduanForm">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama" required
                           maxlength="100"
                           oninput="this.value = this.value.replace(/[^a-zA-ZÀ-öø-ÿ\s]/g, '')"
                           value="<?= old('nama') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kontak <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="kontak" id="kontakInput" required
                           maxlength="50"
                           placeholder="Contoh: 0812-3456-7890 / email@example.com" value="<?= old('kontak') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Perihal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="perihal" id="perihalInput" required
                           maxlength="200"
                           placeholder="Contoh: Jalan rusak di depan kantor desa" value="<?= old('perihal') ?>">
                    <div class="d-flex justify-content-end">
                        <div class="form-text text-muted"><span id="perihalCount">0</span>/200</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Pengaduan <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="isi" id="isiInput" rows="6" required
                              maxlength="2000"
                              placeholder="Jelaskan pengaduan Anda secara detail..."><?= old('isi') ?></textarea>
                    <div class="d-flex justify-content-end mt-1">
                        <div class="form-text" id="isiCountWrap">
                            <span id="isiCount">0</span><span class="text-muted">/2000</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto <span class="text-muted small">(opsional — JPG/PNG · maks. 1MB)</span></label>
                    <input type="file" class="form-control" name="foto" id="pengaduanFoto" accept=".jpg,.jpeg,.png">
                    <div id="fotoPreviewList" class="mt-2 d-flex flex-column gap-2"></div>
                </div>

                <!-- CAPTCHA SECTION -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Verifikasi CAPTCHA <span class="text-danger">*</span>
                    </label>
                    <div id="captchaWidget" style="max-width: 420px;">
                        <div id="captchaPanel" class="p-3 border rounded" style="background:#f8fdf9;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img id="captchaImg"
                                     src="<?= base_url('/pengaduan/captcha') ?>?t=<?= time() ?>"
                                     alt="CAPTCHA"
                                     style="height:50px; border-radius:12px; border:1px solid #cbd5e1; cursor:default; user-select:none;"
                                     draggable="false">
                                <button type="button" id="btnRefreshCaptcha"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Muat ulang CAPTCHA">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <input type="text"
                                       id="captchaInput"
                                       class="form-control"
                                       placeholder="Kode..."
                                       maxlength="6"
                                       autocomplete="off"
                                       spellcheck="false"
                                       style="letter-spacing: 3px; font-weight:600; text-transform:uppercase; max-width:180px;">
                                <button type="button" id="btnCekCaptcha" class="btn btn-success">
                                    <i class="bi bi-check-lg me-1"></i> Cek
                                </button>
                            </div>
                            <div id="captchaHelp" class="form-text mt-2 text-muted" style="font-size:0.8rem;">
                                Masukkan 5 karakter yang terlihat pada gambar (tidak peka huruf besar/kecil).
                            </div>
                        </div>
                        <div id="captchaSuccess" style="display:none;">
                            <div class="p-3 border border-success rounded d-flex align-items-center gap-3"
                                 style="background:#f0fdf4;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:40px;height:40px;background:#16a34a;">
                                    <i class="bi bi-check-lg text-white fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-success" style="font-size:0.95rem;">Verifikasi Berhasil</div>
                                    <div class="text-muted" style="font-size:0.8rem;">CAPTCHA telah diverifikasi. Anda dapat mengirim pengaduan.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" id="btnKirim" class="btn btn-success" disabled>
                        <i class="bi bi-send me-1"></i>
                        <span id="btnKirimLabel">Kirim Pengaduan</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="form-info-alert mt-4">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Catatan:</strong> Pengaduan Anda akan ditinjau oleh petugas desa. Pastikan informasi yang Anda berikan akurat dan lengkap.
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const DANGER_PATTERN = /(javascript\s*:|vbscript\s*:|data\s*:|expression\s*\(|on\w+\s*=|<\s*script|\$\{|`[^`]*`|[<>])/i;
document.addEventListener('DOMContentLoaded', function () {

    const perihalInput = document.getElementById('perihalInput');
    const perihalCount = document.getElementById('perihalCount');
    const isiInput     = document.getElementById('isiInput');
    const isiCount     = document.getElementById('isiCount');
    const isiCountWrap = document.getElementById('isiCountWrap');

    function updatePerihalCounter() {
        const len = perihalInput ? perihalInput.value.length : 0;
        if (perihalCount) perihalCount.textContent = len;
    }

    function updateIsiCounter() {
        const len = isiInput ? isiInput.value.length : 0;
        if (isiCount) isiCount.textContent = len;
        if (isiCountWrap) {
            if (len < 10) {
                isiCountWrap.style.color = '#ef4444';
            } else if (len > 1800) {
                isiCountWrap.style.color = '#f59e0b';
            } else {
                isiCountWrap.style.color = '';
            }
        }
    }

    if (perihalInput) {
        perihalInput.addEventListener('input', updatePerihalCounter);
        updatePerihalCounter();
    }
    if (isiInput) {
        isiInput.addEventListener('input', updateIsiCounter);
        updateIsiCounter();
    }

    const pengaduanForm = document.getElementById('pengaduanForm');
    if (pengaduanForm) {
        pengaduanForm.addEventListener('submit', function (e) {
            const perihalVal = perihalInput ? perihalInput.value : '';
            const isiVal     = isiInput     ? isiInput.value     : '';

            if (DANGER_PATTERN.test(perihalVal) || DANGER_PATTERN.test(isiVal)) {
                e.preventDefault();
                showError('Input mengandung karakter atau pola yang tidak diizinkan. Silakan koreksi dan coba lagi.');
                return;
            }

            const isiLen = isiVal.trim().length;
            if (isiLen < 10) {
                e.preventDefault();
                alert('Isi pengaduan terlalu pendek. Minimal 10 karakter.');
                return;
            }
            if (isiLen > 2000) {
                e.preventDefault();
                alert('Isi pengaduan melebihi 2000 karakter.');
                return;
            }
        });
    }

    const fileInput  = document.getElementById('pengaduanFoto');
    const previewDiv = document.getElementById('fotoPreviewList');
    
    function validateImageFile(file) {
        const ALLOWED_EXTS  = ['jpg', 'jpeg', 'png'];
        const ALLOWED_MIMES = ['image/jpeg', 'image/png'];
        const MAX_BYTES     = 1 * 1024 * 1024;
        const ext = file.name.split('.').pop().toLowerCase();
        if (!ALLOWED_EXTS.includes(ext) || !ALLOWED_MIMES.includes(file.type)) return false;
        if (file.size > MAX_BYTES) return false;
        return true;
    }

    function renderPreview(file) {
        previewDiv.innerHTML = '';
        if (!file) return;
        const item = document.createElement('div');
        item.className = 'd-flex align-items-center justify-content-between p-2 border rounded bg-light';
        item.innerHTML = `
            <div class="d-flex align-items-center gap-2 text-truncate" style="max-width:85%;">
                <i class="bi bi-file-image text-info fs-5"></i>
                <span class="text-truncate" title="${file.name}">${file.name}</span>
                <span class="text-muted small ms-1">(${(file.size / 1024).toFixed(1)} KB)</span>
            </div>
            <button type="button"
                class="btn btn-sm btn-danger p-1 d-flex align-items-center justify-content-center"
                style="width:28px;height:28px;border-radius:6px;"
                title="Hapus" id="btnHapusFoto">
                <i class="bi bi-trash m-0" style="font-size:1rem;"></i>
            </button>
        `;
        item.querySelector('#btnHapusFoto').addEventListener('click', function () {
            fileInput.value = '';
            previewDiv.innerHTML = '';
        });
        previewDiv.appendChild(item);
    }

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = fileInput.files[0];
            if (!file) return;
            if (!validateImageFile(file)) {
                showError('Foto tidak valid. Hanya JPG/JPEG/PNG dan maksimal 1MB.');
                fileInput.value = '';
                previewDiv.innerHTML = '';
                return;
            }
            renderPreview(file);
        });
    }

    // CAPTCHA
    const captchaImg    = document.getElementById('captchaImg');
    const captchaInput  = document.getElementById('captchaInput');
    const btnRefresh    = document.getElementById('btnRefreshCaptcha');
    const btnCek        = document.getElementById('btnCekCaptcha');
    const captchaHelp   = document.getElementById('captchaHelp');
    const captchaPanel  = document.getElementById('captchaPanel');
    const captchaSuccess = document.getElementById('captchaSuccess');
    const btnKirim      = document.getElementById('btnKirim');

    let captchaVerified = false;

    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    function showSuccessBlock() {
        captchaPanel.style.display = 'none';
        captchaSuccess.style.display = '';
    }

    function refreshCaptcha() {
        captchaVerified = false;
        captchaInput.value = '';
        captchaImg.src = '<?= base_url('/pengaduan/captcha') ?>?t=' + Date.now();
        captchaInput.classList.remove('is-valid', 'is-invalid');
        captchaHelp.textContent = 'Masukkan 5 karakter yang terlihat pada gambar (tidak peka huruf besar/kecil).';
        captchaHelp.className = 'form-text mt-2 text-muted';
        captchaPanel.style.display = '';
        captchaSuccess.style.display = 'none';
        btnKirim.disabled = true;
    }

    if (btnRefresh) {
        btnRefresh.addEventListener('click', refreshCaptcha);
    }

    if (captchaInput) {
        captchaInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                btnCek.click();
            }
        });
    }

    if (btnCek) {
        btnCek.addEventListener('click', function () {
            const answer = captchaInput.value.trim();
            if (!answer) {
                captchaInput.classList.add('is-invalid');
                captchaHelp.textContent = 'Jawaban tidak boleh kosong.';
                captchaHelp.className = 'form-text mt-2 text-danger';
                return;
            }

            btnCek.disabled = true;
            btnCek.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memeriksa...';

            const fd = new URLSearchParams();
            fd.append('answer', answer);
            fd.append(csrfHeaderName, getCsrfHash());

            fetch('<?= base_url('/pengaduan/captcha/verify') ?>', {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                btnCek.disabled = false;
                btnCek.innerHTML = '<i class="bi bi-check-lg me-1"></i> Cek';

                if (data.success) {
                    captchaVerified = true;
                    showSuccessBlock();
                    btnKirim.disabled = false;
                } else {
                    captchaInput.classList.remove('is-valid');
                    captchaInput.classList.add('is-invalid');
                    captchaHelp.textContent = (data.message || 'Kode salah.') + ' CAPTCHA telah diperbarui.';
                    captchaHelp.className = 'form-text mt-2 text-danger';

                    setTimeout(() => {
                        captchaImg.src = '<?= base_url('/pengaduan/captcha') ?>?t=' + Date.now();
                        captchaInput.value = '';
                        captchaInput.classList.remove('is-invalid');
                        captchaHelp.textContent = 'Masukkan kode baru dari gambar di atas.';
                        captchaHelp.className = 'form-text mt-2 text-muted';
                    }, 1200);
                }
            })
            .catch(() => {
                btnCek.disabled = false;
                btnCek.innerHTML = '<i class="bi bi-check-lg me-1"></i> Cek';
                showError('Terjadi kesalahan saat verifikasi. Silakan coba lagi.');
            });
        });
    }
});
</script>
<?= $this->endSection() ?>

