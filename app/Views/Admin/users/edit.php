<?= $this->extend('Admin/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN PENGGUNA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Edit <span style="color: #15803d;">Akun</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Perbarui data pengguna.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/admin/akun') ?>" class="btn btn-outline-success">Kembali</a>
        <a href="<?= base_url('/admin/akun/' . $user['id'] . '/hapus') ?>" class="btn btn-danger btn-delete-account" title="Hapus Akun">Hapus Akun</a>
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
                <input type="file" id="fotoProfilInput" name="foto_profil" accept=".jpg,.jpeg,.png,.webp" style="display: none;" form="formAkun">
                <label for="fotoProfilInput" class="btn btn-outline-success mb-0">
                    Ubah Foto
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
                    <i class="bi bi-person-lines-fill me-2"></i> Informasi Akun
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
            
            <!-- TAB INFO AKUN -->
            <div class="tab-pane fade show active" id="content-info" role="tabpanel">
                <form method="post" enctype="multipart/form-data" action="<?= base_url('/admin/akun/' . $user['id']) ?>" id="formAkun">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="inputUsername" value="<?= old('username', esc($user['username'])) ?>">
                            <div class="invalid-feedback">Username hanya boleh berisi huruf dan angka tanpa spasi.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="inputEmail" value="<?= old('email', esc($user['email'])) ?>">
                            <div class="invalid-feedback">Format email tidak valid (harus mengandung @).</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" id="inputNama" value="<?= old('nama_lengkap', esc($profile['nama_lengkap'] ?? '')) ?>">
                            <div class="invalid-feedback">Nama lengkap hanya boleh berisi huruf dan spasi.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role">
                                <?php foreach (['admin','staf','user'] as $role): ?>
                                    <option value="<?= $role ?>" <?= $user['role'] === $role ? 'selected' : '' ?>><?= ucfirst($role) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="aktif" <?= $user['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="nonaktif" <?= $user['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Kelamin</label>
                            <?php $jk = old('jenis_kelamin', $profile['jenis_kelamin'] ?? ''); ?>
                            <select class="form-select" name="jenis_kelamin">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" <?= $jk === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= $jk === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" id="inputTempatLahir" value="<?= old('tempat_lahir', esc($profile['tempat_lahir'] ?? '')) ?>">
                            <div class="invalid-feedback">Tempat lahir hanya boleh berisi huruf, spasi, dan tanda hubung.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir</label>
                            <?php $tgl = old('tanggal_lahir', $profile['tanggal_lahir'] ?? ''); ?>
                            <input type="date" class="form-control" name="tanggal_lahir" value="<?= $tgl === '0000-00-00' ? '' : esc($tgl) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Agama</label>
                            <input type="text" class="form-control" name="agama" id="inputAgama" value="<?= old('agama', esc($profile['agama'] ?? '')) ?>">
                            <div class="invalid-feedback">Agama hanya boleh berisi huruf dan spasi.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" name="pekerjaan" id="inputPekerjaan" value="<?= old('pekerjaan', esc($profile['pekerjaan'] ?? '')) ?>">
                            <div class="invalid-feedback">Pekerjaan hanya boleh berisi huruf dan spasi.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NIK</label>
                            <input type="text" inputmode="numeric" maxlength="16" class="form-control" name="nik" id="inputNik" value="<?= old('nik', esc($profile['nik'] ?? '')) ?>" placeholder="Masukkan 16 digit angka">
                            <div class="invalid-feedback">NIK harus berupa 16 digit angka.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2"><?= old('alamat', esc($profile['alamat'] ?? '')) ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-success" type="submit" id="btnSimpanAkun">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB PASSWORD -->
            <div class="tab-pane fade" id="content-password" role="tabpanel">
                <form method="post" action="<?= base_url('/admin/akun/' . $user['id'] . '/ubah-password') ?>">
                    <?= csrf_field() ?>
                    <input type="text" name="username" value="<?= esc($user['username'] ?? '') ?>" autocomplete="username" style="display:none;" aria-hidden="true">
                    <div class="row g-3">
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
                            Ganti Password
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
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                errorEl.textContent = 'Format foto hanya boleh JPG, JPEG, PNG atau WEBP.';
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
    const formAkun = document.getElementById('formAkun');
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
        if (!el || !el.value) return true; // Skip if empty
        
        let val = el.value;
        let isValid = true;

        if (key === 'email') {
             if(val && !val.includes('@')) isValid = false;
        } else if (key === 'nik') {
             val = val.replace(/\D/g, '');
             el.value = val;
             if (isSubmit && val.length > 0 && val.length !== 16) isValid = false;
             else if (!isSubmit && val.length > 0 && !/^\d+$/.test(val)) isValid = false;
        } else {
             isValid = regex.test(val);
             if (!isValid && !isSubmit) {
                 if (key === 'username') el.value = val.replace(/[^a-zA-Z0-9]/g, '');
                 if (key === 'nama' || key === 'agama' || key === 'pekerjaan') el.value = val.replace(/[^a-zA-Z\s]/g, '');
                 if (key === 'tempatLahir') el.value = val.replace(/[^a-zA-Z\s\-]/g, '');
                 isValid = regex.test(el.value);
             }
        }

        if (!isValid) el.classList.add('is-invalid');
        else el.classList.remove('is-invalid');
        
        return isValid;
    }

    // Attach real-time listeners
    Object.keys(inputs).forEach(key => {
        const { el, regex } = inputs[key];
        if (el) el.addEventListener('input', () => validateInput(key, el, regex));
    });

    // Form submit listener
    if (formAkun) {
        formAkun.addEventListener('submit', function(e) {
            let formValid = true;
            Object.keys(inputs).forEach(key => {
                const { el, regex } = inputs[key];
                if (el) {
                    if (!validateInput(key, el, regex, true)) formValid = false;
                    if (key === 'email' && el.value && !el.value.includes('@')) {
                         el.classList.add('is-invalid'); formValid = false;
                    }
                    if (key === 'nik' && el.value && el.value.length !== 16) {
                         el.classList.add('is-invalid'); formValid = false;
                    }
                }
            });

            if (!formValid) {
                e.preventDefault(); // Stop submission
                const firstError = formAkun.querySelector('.is-invalid');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    // Delete Account Confirmation
    document.querySelectorAll('.btn-delete-account').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            showConfirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?', 'Hapus Akun', 'Ya, Hapus').then((confirmed) => {
                if (confirmed) {
                    window.location.href = href;
                }
            });
        });
    });
});
</script>
<?= $this->endSection() ?>
