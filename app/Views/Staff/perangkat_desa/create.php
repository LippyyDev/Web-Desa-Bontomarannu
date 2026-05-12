<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN DESA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Tambah <span style="color: #15803d;">Perangkat Desa</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Isi data anggota perangkat desa baru.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/staff/perangkat-desa') ?>" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<form method="post" enctype="multipart/form-data" action="<?= base_url('/staff/perangkat-desa') ?>" id="perangkatForm">
    <?= csrf_field() ?>

    <!-- FOTO CARD -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start gap-3 gap-sm-4">
                    <img id="fotoPreview"
                         src="<?= base_url('assets/img/guest.webp') ?>"
                         class="rounded-circle object-fit-cover border"
                         style="width: 90px; height: 90px;"
                         alt="Foto Perangkat">
                    <div>
                        <h5 class="fw-bold mb-1" id="namaPreview" style="color: #64748b;">Belum diisi</h5>
                        <div class="text-muted small" id="jabatanPreview">—</div>
                    </div>
                </div>
                <div>
                    <input type="file" id="fotoInput" name="foto" accept=".jpg,.jpeg,.png,image/jpeg,image/png" style="display: none;">
                    <label for="fotoInput" class="btn btn-outline-success mb-0">
                        <i class="bi bi-camera me-1"></i> Pilih Foto
                    </label>
                </div>
            </div>
            <div id="fotoError" class="invalid-feedback d-block mt-2"></div>
            <div class="form-text text-muted mt-2">Opsional · Maks. 1MB · JPG/PNG</div>
        </div>
    </div>

    <!-- DATA CARD -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Perangkat</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama" id="inputNama"
                           value="<?= old('nama') ?>" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="jabatan" id="inputJabatan"
                           value="<?= old('jabatan') ?>" placeholder="Contoh: Kepala Desa" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Kontak</label>
                    <input type="text" class="form-control" name="kontak"
                           value="<?= old('kontak') ?>" placeholder="Nomor telepon atau email">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-4">
        <button class="btn btn-success" type="submit" id="btnSimpan">
            <i class="bi bi-check-circle me-1"></i> Simpan Perangkat
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fotoInput    = document.getElementById('fotoInput');
    const fotoPreview  = document.getElementById('fotoPreview');
    const fotoError    = document.getElementById('fotoError');
    const namaPreview  = document.getElementById('namaPreview');
    const jabatanPrev  = document.getElementById('jabatanPreview');
    const inputNama    = document.getElementById('inputNama');
    const inputJabatan = document.getElementById('inputJabatan');

    // Live preview nama & jabatan di card foto
    inputNama.addEventListener('input', function () {
        namaPreview.textContent = this.value.trim() || 'Belum diisi';
    });
    inputJabatan.addEventListener('input', function () {
        jabatanPrev.textContent = this.value.trim() || '—';
    });

    // Preview & validasi foto
    fotoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        fotoError.textContent = '';

        if (!file) {
            fotoPreview.src = '<?= base_url('assets/img/guest.webp') ?>';
            return;
        }

        // Gunakan validateImageFile dari upload_validator.js jika tersedia
        if (typeof validateImageFile === 'function' && !validateImageFile(file)) {
            fotoError.textContent = 'Format tidak didukung atau ukuran melebihi 1MB (Hanya JPG/PNG).';
            fotoInput.value = '';
            fotoPreview.src = '<?= base_url('assets/img/guest.webp') ?>';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            fotoPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
});
</script>
<?= $this->endSection() ?>
