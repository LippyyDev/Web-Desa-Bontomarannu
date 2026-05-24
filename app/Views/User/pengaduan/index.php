<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<style>
#btnRefreshCaptcha {
    height: 50px;
    width: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border-color: #cbd5e1;
    color: #475569;
    background-color: #ffffff;
    transition: all 0.2s ease-in-out;
}
#btnRefreshCaptcha:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
}
#btnRefreshCaptcha i {
    font-size: 1.25rem;
}
</style>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            LAYANAN DESA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Buat <span style="color: #15803d;">Pengaduan</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Sampaikan keluhan atau laporan Anda ke staf desa.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/user/dashboard') ?>" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?= base_url('/user/pengaduan') ?>" method="post" enctype="multipart/form-data" id="pengaduanForm">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" required
                       maxlength="100"
                       oninput="this.value = this.value.replace(/[^a-zA-ZÀ-öø-ÿ\s]/g, '')"
                       value="<?= old('nama_lengkap', $profile['nama_lengkap'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Kontak <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="kontak" id="kontakInput" required
                       maxlength="50"
                       placeholder="Contoh: 0812-3456-7890">
            </div>

            <div class="mb-3">
                <label class="form-label">Perihal <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="perihal" id="perihalInput" required
                       maxlength="200"
                       placeholder="Contoh: Jalan rusak di depan kantor desa">
                <div class="d-flex justify-content-end">
                    <div class="form-text text-muted"><span id="perihalCount">0</span>/200</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Isi Pengaduan <span class="text-danger">*</span></label>
                <textarea class="form-control" name="isi" id="isiInput" rows="6" required
                          maxlength="2000"
                          placeholder="Jelaskan pengaduan Anda secara detail..."></textarea>
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

            <!-- ============================================================
                 CAPTCHA SECTION
                 ============================================================ -->
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Verifikasi CAPTCHA <span class="text-danger">*</span>
                </label>

                <!-- Blok CAPTCHA: form input (default) atau sukses (setelah terverifikasi) -->
                <div id="captchaWidget" style="max-width: 420px;">

                    <!-- ===== Panel input (tampil saat belum diverifikasi) ===== -->
                    <div id="captchaPanel" class="p-3 border rounded" style="background:#f8fdf9;">
                        <!-- Gambar CAPTCHA + tombol refresh -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img id="captchaImg"
                                 src="<?= base_url('/user/pengaduan/captcha') ?>?t=<?= time() ?>"
                                 alt="CAPTCHA"
                                 style="height:50px; border-radius:12px; border:1px solid #cbd5e1; cursor:default; user-select:none;"
                                 draggable="false">
                            <button type="button" id="btnRefreshCaptcha"
                                    class="btn btn-sm btn-outline-secondary"
                                    title="Muat ulang CAPTCHA">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>

                        <!-- Input + tombol cek -->
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

                    <!-- ===== Panel sukses (tersembunyi, tampil setelah terverifikasi) ===== -->
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
            <!-- ============================================================ -->

            <div class="mt-4">
                <?php if (($cooldownSeconds ?? 0) > 0): ?>
                    <!-- Tombol disabled saat rate-limit aktif — countdown dihitung JS -->
                    <button type="button" id="btnKirim"
                            class="btn btn-success"
                            disabled
                            data-cooldown="<?= (int)$cooldownSeconds ?>">
                        <i class="bi bi-send me-1"></i>
                        <span id="btnKirimLabel">Kirim Pengaduan</span>
                    </button>
                    <div class="form-text text-warning mt-1" id="rateLimitNote">
                        <i class="bi bi-clock me-1"></i>
                        Anda telah mencapai batas 3 pengaduan per jam. Tombol akan aktif saat cooldown selesai.
                    </div>
                <?php else: ?>
                    <!-- Tombol disabled sampai CAPTCHA diverifikasi -->
                    <button type="submit" id="btnKirim"
                            class="btn btn-success"
                            disabled>
                        <i class="bi bi-send me-1"></i>
                        <span id="btnKirimLabel">Kirim Pengaduan</span>
                    </button>

                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
// Pola karakter berbahaya yang diblok di frontend
const DANGER_PATTERN = /(javascript\s*:|vbscript\s*:|data\s*:|expression\s*\(|on\w+\s*=|<\s*script|\$\{|`[^`]*`|[<>])/i;
document.addEventListener('DOMContentLoaded', function () {

    // ----------------------------------------------------------------
    // Character counter & real-time sanitasi
    // ----------------------------------------------------------------
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

    // ----------------------------------------------------------------
    // Pre-submit guard: blok pola berbahaya sebelum form dikirim
    // ----------------------------------------------------------------
    const pengaduanForm = document.getElementById('pengaduanForm');
    if (pengaduanForm) {
        pengaduanForm.addEventListener('submit', function (e) {
            const perihalVal = perihalInput ? perihalInput.value : '';
            const isiVal     = isiInput     ? isiInput.value     : '';

            if (DANGER_PATTERN.test(perihalVal) || DANGER_PATTERN.test(isiVal)) {
                e.preventDefault();
                showError('Input mengandung karakter atau pola yang tidak diizinkan (HTML tag, script, dsb). Silakan koreksi dan coba lagi.');
                return;
            }

            const isiLen = isiVal.trim().length;
            if (isiLen < 10) {
                e.preventDefault();
                showError('Isi pengaduan terlalu pendek. Minimal 10 karakter.');
                return;
            }
            if (isiLen > 2000) {
                e.preventDefault();
                showError('Isi pengaduan melebihi 2000 karakter.');
                return;
            }
        });
    }

    // ----------------------------------------------------------------
    // Foto preview
    // ----------------------------------------------------------------
    const fileInput  = document.getElementById('pengaduanFoto');
    const previewDiv = document.getElementById('fotoPreviewList');
    let currentFile  = null;

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
            currentFile = null;
            fileInput.value = '';
            previewDiv.innerHTML = '';
        });
        previewDiv.appendChild(item);
    }

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = fileInput.files[0];
            if (!file) return;
            if (typeof validateImageFile === 'function' && !validateImageFile(file)) {
                showError('Foto tidak valid. Hanya JPG/JPEG/PNG dan maksimal 1MB.');
                fileInput.value = '';
                currentFile = null;
                previewDiv.innerHTML = '';
                return;
            }
            currentFile = file;
            renderPreview(file);
        });
    }

    // ----------------------------------------------------------------
    // CAPTCHA
    // ----------------------------------------------------------------
    const captchaImg    = document.getElementById('captchaImg');
    const captchaInput  = document.getElementById('captchaInput');
    const btnRefresh    = document.getElementById('btnRefreshCaptcha');
    const btnCek        = document.getElementById('btnCekCaptcha');
    const captchaHelp   = document.getElementById('captchaHelp');
    const captchaPanel  = document.getElementById('captchaPanel');
    const captchaSuccess = document.getElementById('captchaSuccess');
    const btnKirim      = document.getElementById('btnKirim');
    const captchaNote   = document.getElementById('captchaNote');

    let captchaVerified = false;

    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    function showSuccessBlock() {
        // Sembunyikan form input, tampilkan blok hijau
        captchaPanel.style.display = 'none';
        captchaSuccess.style.display = '';
    }

    function refreshCaptcha() {
        captchaVerified = false;
        captchaInput.value = '';
        captchaImg.src = '<?= base_url('/user/pengaduan/captcha') ?>?t=' + Date.now();
        captchaInput.classList.remove('is-valid', 'is-invalid');
        captchaHelp.textContent = 'Masukkan 5 karakter yang terlihat pada gambar (tidak peka huruf besar/kecil).';
        captchaHelp.className = 'form-text mt-2 text-muted';
        // Pastikan panel input terlihat (dipakai saat cooldown selesai & captcha belum verify)
        captchaPanel.style.display = '';
        captchaSuccess.style.display = 'none';

        if (!isRateLimited()) {
            btnKirim.disabled = true;
        }
    }

    function isRateLimited() {
        return btnKirim.hasAttribute('data-cooldown') &&
               parseInt(btnKirim.getAttribute('data-cooldown')) > 0;
    }

    if (btnRefresh) {
        btnRefresh.addEventListener('click', refreshCaptcha);
    }

    // Tekan Enter di input = klik tombol Cek
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

            fetch('<?= base_url('/user/pengaduan/captcha/verify') ?>', {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                btnCek.disabled = false;
                btnCek.innerHTML = '<i class="bi bi-check-lg me-1"></i> Cek';

                if (data.success) {
                    // ✅ Benar — ganti seluruh widget jadi blok hijau
                    captchaVerified = true;
                    showSuccessBlock();

                    // Aktifkan tombol kirim (hanya jika rate-limit tidak aktif)
                    if (!isRateLimited()) {
                        btnKirim.disabled = false;
                        if (captchaNote) captchaNote.style.display = 'none';
                    }
                } else {
                    // ❌ Salah — tanda merah di input
                    captchaInput.classList.remove('is-valid');
                    captchaInput.classList.add('is-invalid');
                    captchaHelp.textContent = (data.message || 'Kode salah.') + ' CAPTCHA telah diperbarui.';
                    captchaHelp.className = 'form-text mt-2 text-danger';

                    // Refresh gambar otomatis setelah 1.2 detik
                    setTimeout(() => {
                        captchaImg.src = '<?= base_url('/user/pengaduan/captcha') ?>?t=' + Date.now();
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

    // ----------------------------------------------------------------
    // Rate-limit countdown timer
    // ----------------------------------------------------------------
    const btnEl = document.getElementById('btnKirim');
    if (btnEl && btnEl.hasAttribute('data-cooldown')) {
        let remaining = parseInt(btnEl.getAttribute('data-cooldown'));

        function formatTime(secs) {
            const m = String(Math.floor(secs / 60)).padStart(2, '0');
            const s = String(secs % 60).padStart(2, '0');
            return `${m}:${s}`;
        }

        function updateCountdown() {
            const label = document.getElementById('btnKirimLabel');
            if (remaining > 0) {
                label.textContent = `Kirim Pengaduan (${formatTime(remaining)})`;
                remaining--;
                setTimeout(updateCountdown, 1000);
            } else {
                // Cooldown habis
                btnEl.removeAttribute('data-cooldown');
                label.textContent = 'Kirim Pengaduan';

                const rateLimitNote = document.getElementById('rateLimitNote');
                if (rateLimitNote) rateLimitNote.style.display = 'none';

                // Tampilkan petunjuk captcha kembali
                if (captchaNote) {
                    captchaNote.style.display = '';
                }

                // Aktifkan hanya jika CAPTCHA sudah diverifikasi
                if (captchaVerified) {
                    btnEl.disabled = false;
                }
                // Jika belum, reset CAPTCHA supaya user isi ulang
                else {
                    refreshCaptcha();
                }
            }
        }

        updateCountdown();
    }
});
</script>
<?= $this->endSection() ?>
