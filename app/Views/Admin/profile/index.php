<?= $this->extend('Admin/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            PENGATURAN
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Profil <span style="color: #15803d;">Admin</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Perbarui identitas admin.</p>
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
                <img id="profilePhotoPreview" src="<?= !empty($profile['foto_profil']) ? base_url($profile['foto_profil']) : base_url('assets/img/guest.webp') ?>" class="rounded-circle object-fit-cover border" style="width: 90px; height: 90px;" alt="Foto Profil">
                <div>
                    <h5 class="fw-bold mb-1"><?= esc($profile['nama_lengkap'] ?? $user['username'] ?? '') ?></h5>
                    <div class="text-muted small mb-1"><i class="bi bi-person me-1"></i> <?= esc($user['username'] ?? '') ?></div>
                    <div class="text-muted small"><i class="bi bi-envelope me-1"></i> <?= esc($user['email'] ?? '') ?></div>
                </div>
            </div>
            <div>
                <input type="file" id="fotoProfilInput" name="foto_profil" accept=".jpg,.jpeg,.png" style="display: none;" form="formProfil">
                <label for="fotoProfilInput" class="btn btn-outline-success mb-0">
                    <i class="bi bi-camera me-1"></i> Ubah Foto
                </label>
            </div>
        </div>
        <div id="fotoError" class="invalid-feedback d-block mt-2 text-center"></div>
    </div>
</div>

<!-- TABS CARD -->
<div class="card">
    <div class="card-header bg-white border-bottom-0 pb-0 pt-3 px-4">
        <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-info" data-bs-toggle="tab" data-bs-target="#content-info" type="button" role="tab" aria-selected="true">
                    <i class="bi bi-person-lines-fill me-2"></i> Informasi Profil
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-password" data-bs-toggle="tab" data-bs-target="#content-password" type="button" role="tab" aria-selected="false">
                    <i class="bi bi-shield-lock me-2"></i> Ganti Password
                </button>
            </li>
        </ul>
    </div>
    
    <div class="card-body pt-4 px-4 pb-4">
        <div class="tab-content" id="myTabContent">
            
            <!-- TAB INFO PROFIL -->
            <div class="tab-pane fade show active" id="content-info" role="tabpanel">
                <form method="post" enctype="multipart/form-data" action="<?= base_url('/admin/profil') ?>" id="formProfil">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="inputUsername" value="<?= old('username', $user['username'] ?? '') ?>" placeholder="Contoh: ahmad123">
                            <div class="invalid-feedback">Username hanya boleh berisi huruf dan angka tanpa spasi.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="inputEmail" value="<?= old('email', $user['email'] ?? '') ?>" placeholder="Contoh: ahmad@gmail.com">
                            <div class="invalid-feedback">Format email tidak valid (harus mengandung @).</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" id="inputNama" value="<?= old('nama_lengkap', $profile['nama_lengkap'] ?? '') ?>" placeholder="Contoh: Ahmad Fauzi">
                            <div class="invalid-feedback">Nama lengkap hanya boleh berisi huruf dan spasi.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" id="inputTempatLahir" value="<?= old('tempat_lahir', $profile['tempat_lahir'] ?? '') ?>" placeholder="Contoh: Bulukumba">
                            <div class="invalid-feedback">Tempat lahir hanya boleh berisi huruf, spasi, dan tanda hubung.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <?php $tgl = old('tanggal_lahir', $profile['tanggal_lahir'] ?? ''); ?>
                            <input type="date" class="form-control" name="tanggal_lahir" value="<?= $tgl === '0000-00-00' ? '' : esc($tgl) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Agama</label>
                            <input type="text" class="form-control" name="agama" id="inputAgama" value="<?= old('agama', $profile['agama'] ?? '') ?>" placeholder="Contoh: Islam">
                            <div class="invalid-feedback">Agama hanya boleh berisi huruf dan spasi.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" name="pekerjaan" id="inputPekerjaan" value="<?= old('pekerjaan', $profile['pekerjaan'] ?? '') ?>" placeholder="Contoh: Petani">
                            <div class="invalid-feedback">Pekerjaan hanya boleh berisi huruf dan spasi.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NIK</label>
                            <input type="text" inputmode="numeric" maxlength="16" class="form-control" name="nik" id="inputNik" value="<?= old('nik', $profile['nik'] ?? '') ?>" placeholder="Masukkan 16 digit angka">
                            <div class="invalid-feedback">NIK harus berupa 16 digit angka.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2" placeholder="Contoh: Jl. Poros Bulukumba No. 1, Desa Padang Loang"><?= old('alamat', $profile['alamat'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-success" type="submit" id="btnSimpanProfil">
                            <i class="bi bi-floppy me-1"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB PASSWORD -->
            <div class="tab-pane fade" id="content-password" role="tabpanel">
                <form method="post" action="<?= base_url('/admin/profil/ubah-password') ?>">
                    <?= csrf_field() ?>
                    <input type="text" name="username" value="<?= esc($user['username'] ?? '') ?>" autocomplete="username" style="display:none;" aria-hidden="true">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Masukkan Password Lama</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control" name="old_password" id="oldPassword" autocomplete="current-password" required>
                                <button class="toggle-password" type="button" onclick="togglePassword('oldPassword', this)" aria-label="Tampilkan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Masukkan Password Baru</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control" name="new_password" id="newPassword" autocomplete="new-password" required>
                                <button class="toggle-password" type="button" onclick="togglePassword('newPassword', this)" aria-label="Tampilkan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control" name="confirm_password" id="confirmPassword" autocomplete="new-password" required>
                                <button class="toggle-password" type="button" onclick="togglePassword('confirmPassword', this)" aria-label="Tampilkan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-shield-check me-1"></i> Ganti Password
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
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

document.addEventListener('DOMContentLoaded', function() {
    // Foto Profil Preview
    const fotoInput = document.getElementById('fotoProfilInput');
    const photoPreview = document.getElementById('profilePhotoPreview');
    
    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const errorEl = document.getElementById('fotoError');
        errorEl.textContent = ''; // Clear previous error

        if (file) {
            // Validasi Ekstensi (Frontend)
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                errorEl.textContent = 'Format foto hanya boleh JPG, JPEG, atau PNG.';
                fotoInput.value = ''; // Reset input
                return;
            }

            // Validasi Ukuran (Max 1 MB)
            if (file.size > 1048576) {
                errorEl.textContent = 'Ukuran foto maksimal 1 MB.';
                fotoInput.value = ''; // Reset input
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Form Validation Logic
    const formProfil = document.getElementById('formProfil');
    const inputs = {
        username: { el: document.getElementById('inputUsername'), regex: /^[a-zA-Z0-9]*$/ },
        email: { el: document.getElementById('inputEmail'), regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ },
        nama: { el: document.getElementById('inputNama'), regex: /^[a-zA-Z\s]*$/ },
        tempatLahir: { el: document.getElementById('inputTempatLahir'), regex: /^[a-zA-Z\s\-]*$/ },
        agama: { el: document.getElementById('inputAgama'), regex: /^[a-zA-Z\s]*$/ },
        pekerjaan: { el: document.getElementById('inputPekerjaan'), regex: /^[a-zA-Z\s]*$/ },
        nik: { el: document.getElementById('inputNik'), regex: /^\d{0,16}$/ }
    };

    // Helper to toggle error state
    function validateInput(key, el, regex, isSubmit = false) {
        if (!el || !el.value) return true;
        
        let val = el.value;
        let isValid = true;

        if (key === 'email') {
             // Email format check
             if(val && !val.includes('@')) {
                 isValid = false;
             }
        } else if (key === 'nik') {
             // Clean non-digits real-time
             val = val.replace(/\D/g, '');
             el.value = val;
             if (isSubmit && val.length > 0 && val.length !== 16) isValid = false;
             else if (!isSubmit && val.length > 0 && !/^\d+$/.test(val)) isValid = false;
        } else {
             // Other inputs using strict regex
             isValid = regex.test(val);
             // If not valid real-time, we try to clean it
             if (!isValid && !isSubmit) {
                 if (key === 'username') el.value = val.replace(/[^a-zA-Z0-9]/g, '');
                 if (key === 'nama' || key === 'agama' || key === 'pekerjaan') el.value = val.replace(/[^a-zA-Z\s]/g, '');
                 if (key === 'tempatLahir') el.value = val.replace(/[^a-zA-Z\s\-]/g, '');
             isValid = regex.test(el.value);
             }
        }

        if (!isValid) {
            el.classList.add('is-invalid');
        } else {
            el.classList.remove('is-invalid');
        }
        return isValid;
    }

    // Attach real-time listeners
    Object.keys(inputs).forEach(key => {
        const { el, regex } = inputs[key];
        if (el) {
            el.addEventListener('input', () => validateInput(key, el, regex));
        }
    });

    // Form submit listener
    if (formProfil) {
        formProfil.addEventListener('submit', function(e) {
            let formValid = true;
            Object.keys(inputs).forEach(key => {
                const { el, regex } = inputs[key];
                if (el) {
                    if (!validateInput(key, el, regex, true)) {
                        formValid = false;
                    }
                    // Email special rule
                    if (key === 'email' && el.value && !el.value.includes('@')) {
                         el.classList.add('is-invalid');
                         formValid = false;
                    }
                    // NIK special rule for submit
                    if (key === 'nik' && el.value && el.value.length !== 16) {
                         el.classList.add('is-invalid');
                         formValid = false;
                    }
                }
            });

            if (!formValid) {
                e.preventDefault(); // Stop submission
                // Scroll to first error
                const firstError = formProfil.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>


