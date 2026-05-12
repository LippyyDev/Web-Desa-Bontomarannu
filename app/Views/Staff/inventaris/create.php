<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN DESA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Tambah <span style="color: #15803d;">Inventaris</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Tambahkan barang inventaris aset desa.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/staff/inventaris') ?>" class="btn btn-outline-success">
            Kembali
        </a>
    </div>
</div>

<form action="<?= base_url('/staff/inventaris') ?>" method="post" enctype="multipart/form-data" id="inventarisForm">
    <?= csrf_field() ?>

    <!-- CARD: Informasi Barang -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Barang</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama_barang" value="<?= old('nama_barang') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Jenis Barang <span class="text-danger">*</span></label>
                    <select class="form-select" name="jenis" required>
                        <option value="">Pilih Jenis</option>
                        <option value="Tanah" <?= old('jenis') === 'Tanah' ? 'selected' : '' ?>>Tanah</option>
                        <option value="Bangunan / Gedung" <?= old('jenis') === 'Bangunan / Gedung' ? 'selected' : '' ?>>Bangunan / Gedung</option>
                        <option value="Peralatan & Mesin" <?= old('jenis') === 'Peralatan & Mesin' ? 'selected' : '' ?>>Peralatan & Mesin</option>
                        <option value="Kendaraan" <?= old('jenis') === 'Kendaraan' ? 'selected' : '' ?>>Kendaraan</option>
                        <option value="Jalan, Irigasi & Jaringan" <?= old('jenis') === 'Jalan, Irigasi & Jaringan' ? 'selected' : '' ?>>Jalan, Irigasi & Jaringan</option>
                        <option value="Lainnya" <?= old('jenis') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Total <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="total" value="<?= old('total') ?>" min="1" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="">Pilih Status</option>
                        <option value="Baik" <?= old('status') === 'Baik' ? 'selected' : '' ?>>Baik</option>
                        <option value="Rusak Ringan" <?= old('status') === 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= old('status') === 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD: Foto -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Foto Barang</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Foto (Opsional)</label>
                    <input type="file" class="form-control" id="fotoInput" name="foto" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div class="form-text text-muted">Maks. 1MB · JPG/PNG</div>
                    <div id="fotoPreviewWrap" class="mt-3" style="display:none;">
                        <img id="fotoPreview" src="" alt="Preview" class="rounded border" style="max-height:160px;object-fit:cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-4">
        <button class="btn btn-success" type="submit">
            Simpan Inventaris
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('fotoInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) { document.getElementById('fotoPreviewWrap').style.display = 'none'; return; }

        if (typeof validateImageFile === 'function' && !validateImageFile(file)) {
            showError('Format tidak didukung atau ukuran melebihi 1MB (Hanya JPG/PNG).');
            this.value = '';
            document.getElementById('fotoPreviewWrap').style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('fotoPreview').src = e.target.result;
            document.getElementById('fotoPreviewWrap').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
});
</script>
<?= $this->endSection() ?>
