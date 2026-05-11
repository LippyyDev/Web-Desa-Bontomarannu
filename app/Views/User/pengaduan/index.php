<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
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
            Kembali
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?= base_url('/user/pengaduan') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" required 
                       oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"
                       value="<?= old('nama_lengkap', $profile['nama_lengkap'] ?? '') ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kontak <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="kontak" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Perihal <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="perihal" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Isi Pengaduan <span class="text-danger">*</span></label>
                <textarea class="form-control" name="isi" rows="6" required></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Foto <span class="text-muted small">(opsional — JPG/PNG · maks. 1MB)</span></label>
                <input type="file" class="form-control" name="foto" id="pengaduanFoto" accept=".jpg,.jpeg,.png">
                <div id="fotoPreviewList" class="mt-2 d-flex flex-column gap-2"></div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    Kirim Pengaduan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput  = document.getElementById('pengaduanFoto');
    const previewDiv = document.getElementById('fotoPreviewList');
    if (!fileInput || !previewDiv) return;

    let currentFile = null;

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

    fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];
        if (!file) return;

        if (typeof validateImageFile !== 'function') {
            currentFile = file;
            renderPreview(file);
            return;
        }

        if (!validateImageFile(file)) {
            showError('Foto tidak valid. Hanya JPG/JPEG/PNG dan maksimal 1MB.');
            fileInput.value = '';
            currentFile = null;
            previewDiv.innerHTML = '';
            return;
        }

        currentFile = file;
        renderPreview(file);
    });
});
</script>
<?= $this->endSection() ?>
