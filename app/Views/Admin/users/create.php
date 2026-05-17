<?= $this->extend('Admin/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN PENGGUNA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Tambah <span style="color: #15803d;">Akun</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Buat akun baru dengan role tertentu.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= base_url('/admin/akun') ?>" class="btn btn-outline-success">
            Kembali
        </a>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #64748b;
    border: none;
    border-bottom: 2px solid transparent;
    padding-bottom: 10px;
}
.nav-tabs .nav-link:hover {
    color: #15803d;
    border-bottom: 2px solid #bbf7d0;
}
.nav-tabs .nav-link.active {
    color: #15803d !important;
    border-bottom: 2px solid #15803d !important;
    background: transparent !important;
    font-weight: 600;
}
.card-header-tabs {
    margin-bottom: 0;
    border-bottom: 1px solid #e2e8f0;
}
.password-wrapper {
    position: relative;
}
.password-wrapper .form-control {
    padding-right: 2.75rem;
}
.password-wrapper .toggle-password {
    position: absolute;
    top: 50%;
    right: 0.75rem;
    transform: translateY(-50%);
    background: none;
    border: none;
    padding: 0;
    color: #94a3b8;
    cursor: pointer;
    line-height: 1;
    z-index: 5;
}
.password-wrapper .toggle-password:hover {
    color: #15803d;
}
</style>

<!-- FOTO PROFIL CARD -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
            <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start gap-3 gap-sm-4">
                <img id="profilePhotoPreview"
                     src="<?= base_url('assets/img/guest.webp') ?>"
                     class="rounded-circle object-fit-cover border"
                     style="width: 90px; height: 90px;"
                     alt="Foto Profil">
                <div>
                    <h5 class="fw-bold mb-1">Akun Baru</h5>
                    <div class="text-muted small mb-1"><i class="bi bi-person me-1"></i> Username akan ditentukan di form</div>
                    <div class="text-muted small"><i class="bi bi-info-circle me-1"></i> Format: JPG/JPEG/PNG · Maks 1 MB</div>
                </div>
            </div>
            <div>
                <input type="file" id="fotoProfilInput" name="foto_profil" accept=".jpg,.jpeg,.png" style="display: none;" form="formBuatAkun">
                <label for="fotoProfilInput" class="btn btn-outline-success mb-0">
                    Pilih Foto
                </label>
            </div>
        </div>
        <div id="fotoError" class="invalid-feedback d-block mt-2 text-center"></div>
    </div>
</div>

<!-- TABS CARD -->
<div class="card">
    <div class="card-header bg-white border-bottom-0 pb-0 pt-3 px-4">
        <ul class="nav nav-tabs card-header-tabs" id="akunTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-info" data-bs-toggle="tab" data-bs-target="#content-info" type="button" role="tab" aria-selected="true">
                    <i class="bi bi-person-lines-fill me-2"></i> Informasi Akun
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-password" data-bs-toggle="tab" data-bs-target="#content-password" type="button" role="tab" aria-selected="false">
                    <i class="bi bi-shield-lock me-2"></i> Password
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body pt-4 px-4 pb-4">
        <form method="post" enctype="multipart/form-data" action="<?= base_url('/admin/akun') ?>" id="formBuatAkun">
            <?= csrf_field() ?>

            <div class="tab-content" id="akunTabContent">

                <!-- TAB INFORMASI AKUN -->
                <div class="tab-pane fade show active" id="content-info" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" id="inputNama"
                                   value="<?= old('nama_lengkap') ?>" placeholder="Contoh: Ahmad Fauzi">
                            <div class="invalid-feedback">Nama lengkap hanya boleh berisi huruf dan spasi.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" id="inputUsername"
                                   value="<?= old('username') ?>" placeholder="Contoh: ahmad123" required>
                            <div class="invalid-feedback">Username hanya boleh berisi huruf dan angka tanpa spasi.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="inputEmail"
                                   value="<?= old('email') ?>" placeholder="Contoh: ahmad@gmail.com" required>
                            <div class="invalid-feedback">Format email tidak valid (harus mengandung @).</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required>
                                <option value="admin"  <?= old('role') === 'admin'  ? 'selected' : '' ?>>Admin</option>
                                <option value="staf"   <?= old('role') === 'staf'   ? 'selected' : '' ?>>Staf</option>
                                <option value="user"   <?= old('role') === 'user'   ? 'selected' : '' ?>>User</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="aktif"    <?= old('status') === 'aktif'    ? 'selected' : '' ?>>Aktif</option>
                                <option value="nonaktif" <?= old('status') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- TAB PASSWORD -->
                <div class="tab-pane fade" id="content-password" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control" name="password" id="inputPassword"
                                       autocomplete="new-password" placeholder="Minimal 6 karakter">
                                <button class="toggle-password" type="button" onclick="togglePassword('inputPassword', this)" aria-label="Tampilkan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="passwordError">Password minimal 6 karakter.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control" name="confirm_password" id="inputConfirmPassword"
                                       autocomplete="new-password" placeholder="Ulangi password">
                                <button class="toggle-password" type="button" onclick="togglePassword('inputConfirmPassword', this)" aria-label="Tampilkan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="confirmPasswordError">Password dan konfirmasi tidak sama.</div>
                        </div>
                    </div>
                </div>

            </div><!-- /.tab-content -->

            <div class="mt-4">
                <button class="btn btn-success" type="submit" id="btnBuatAkun">
                    Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

document.addEventListener('DOMContentLoaded', function () {

    /* ── Foto Profil Preview & Validasi ── */
    const fotoInput   = document.getElementById('fotoProfilInput');
    const photoPreview = document.getElementById('profilePhotoPreview');
    const fotoError   = document.getElementById('fotoError');

    fotoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        fotoError.textContent = '';

        if (!file) return;

        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            fotoError.textContent = 'Format foto hanya boleh JPG, JPEG, atau PNG.';
            fotoInput.value = '';
            return;
        }

        if (file.size > 1048576) {
            fotoError.textContent = 'Ukuran foto maksimal 1 MB.';
            fotoInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            photoPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    /* ── Form Validation ── */
    const form = document.getElementById('formBuatAkun');

    const fieldRules = {
        username : { el: document.getElementById('inputUsername'),        regex: /^[a-zA-Z0-9]+$/,       tab: 'tab-info' },
        email    : { el: document.getElementById('inputEmail'),           regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, tab: 'tab-info' },
        nama     : { el: document.getElementById('inputNama'),            regex: /^[a-zA-Z\s]*$/,         tab: 'tab-info' },
        password : { el: document.getElementById('inputPassword'),        regex: null,                    tab: 'tab-password' },
        confirm  : { el: document.getElementById('inputConfirmPassword'), regex: null,                    tab: 'tab-password' },
    };

    // Real-time cleaning
    const usernameEl = fieldRules.username.el;
    if (usernameEl) {
        usernameEl.addEventListener('input', function () {
            const cleaned = this.value.replace(/[^a-zA-Z0-9]/g, '');
            if (this.value !== cleaned) this.value = cleaned;
            toggleInvalid(this, /^[a-zA-Z0-9]*$/.test(cleaned));
        });
    }

    const namaEl = fieldRules.nama.el;
    if (namaEl) {
        namaEl.addEventListener('input', function () {
            const cleaned = this.value.replace(/[^a-zA-Z\s]/g, '');
            if (this.value !== cleaned) this.value = cleaned;
            toggleInvalid(this, /^[a-zA-Z\s]*$/.test(cleaned));
        });
    }

    const emailEl = fieldRules.email.el;
    if (emailEl) {
        emailEl.addEventListener('blur', function () {
            const valid = !this.value || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
            toggleInvalid(this, valid);
        });
    }

    function toggleInvalid(el, isValid) {
        if (isValid) {
            el.classList.remove('is-invalid');
        } else {
            el.classList.add('is-invalid');
        }
    }

    // Submit validation
    form.addEventListener('submit', function (e) {
        let valid = true;
        let firstInvalidTab = null;

        // Username
        const uEl = fieldRules.username.el;
        if (uEl && uEl.value && !/^[a-zA-Z0-9]+$/.test(uEl.value)) {
            uEl.classList.add('is-invalid');
            if (!firstInvalidTab) firstInvalidTab = 'tab-info';
            valid = false;
        }

        // Email
        const eEl = fieldRules.email.el;
        if (eEl && eEl.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(eEl.value)) {
            eEl.classList.add('is-invalid');
            if (!firstInvalidTab) firstInvalidTab = 'tab-info';
            valid = false;
        }

        // Password
        const pEl = fieldRules.password.el;
        const cEl = fieldRules.confirm.el;
        const pwdErrorEl   = document.getElementById('passwordError');
        const confErrorEl  = document.getElementById('confirmPasswordError');

        if (pEl) {
            if (pEl.value.length > 0 && pEl.value.length < 6) {
                pEl.classList.add('is-invalid');
                pwdErrorEl.textContent = 'Password minimal 6 karakter.';
                if (!firstInvalidTab) firstInvalidTab = 'tab-password';
                valid = false;
            } else {
                pEl.classList.remove('is-invalid');
            }
        }

        if (cEl && pEl) {
            if (cEl.value && pEl.value !== cEl.value) {
                cEl.classList.add('is-invalid');
                confErrorEl.textContent = 'Password dan konfirmasi tidak sama.';
                if (!firstInvalidTab) firstInvalidTab = 'tab-password';
                valid = false;
            } else {
                cEl.classList.remove('is-invalid');
            }
        }

        // Jika ada error, pindah ke tab yang berisi error pertama
        if (!valid) {
            e.preventDefault();
            if (firstInvalidTab) {
                const tabBtn = document.getElementById(firstInvalidTab);
                if (tabBtn) tabBtn.click();
            }
            setTimeout(function () {
                const firstError = form.querySelector('.is-invalid');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 150);
        }
    });
});
</script>
<?= $this->endSection() ?>
