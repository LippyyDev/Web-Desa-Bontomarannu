<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<?php
function getStatusBadgeInv(string $status): string {
    return match(strtolower($status)) {
        'baik'         => '<span class="fw-bold text-success">Baik</span>',
        'rusak ringan' => '<span class="fw-bold text-warning">Rusak Ringan</span>',
        'rusak berat'  => '<span class="fw-bold text-danger">Rusak Berat</span>',
        default        => '<span class="fw-bold text-secondary">' . esc($status) . '</span>',
    };
}
?>

<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN DESA
        </div>
        <h2 class="fw-bold text-dark mb-0" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Detail <span style="color: #15803d;">Inventaris</span>
        </h2>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-danger" id="btnHapus">
            Hapus
        </button>
        <a href="<?= base_url('/staff/inventaris') ?>" class="btn btn-outline-success">
            Kembali
        </a>
    </div>
</div>

<!-- Informasi Barang (read-only) -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4 fw-bold">Informasi Barang</h5>

        <div class="row gy-3 mb-4">
            <div class="col-md-6">
                <div class="small text-muted mb-1">Nama Barang</div>
                <div class="fw-semibold text-dark"><?= esc($item['nama_barang']) ?></div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Jenis</div>
                <div class="fw-semibold text-dark"><?= esc($item['jenis']) ?></div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Total</div>
                <div class="fw-semibold text-dark"><?= esc($item['total']) ?> unit</div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Status</div>
                <div class="fs-6"><?= getStatusBadgeInv($item['status']) ?></div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Ditambahkan</div>
                <div class="fw-semibold text-dark"><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></div>
            </div>
            <?php if (!empty($item['updated_at']) && $item['updated_at'] !== $item['created_at']): ?>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Terakhir Diperbarui</div>
                <div class="fw-semibold text-dark"><?= date('d M Y, H:i', strtotime($item['updated_at'])) ?></div>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($item['foto'])): ?>
        <div class="small text-muted mb-2">Foto Barang</div>
        <img src="<?= base_url($item['foto']) ?>" alt="Foto <?= esc($item['nama_barang']) ?>" class="rounded border" style="max-height: 200px; object-fit: cover;">
        <?php endif; ?>
    </div>
</div>

<!-- Form Edit -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4 fw-bold">Perbarui Data</h5>
        <form action="<?= base_url('/staff/inventaris/' . $item['id']) ?>" method="post" enctype="multipart/form-data" id="editForm">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama_barang" value="<?= old('nama_barang', esc($item['nama_barang'])) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Jenis Barang <span class="text-danger">*</span></label>
                    <select class="form-select" name="jenis" required>
                        <?php
                        $jenisList = ['Tanah','Bangunan / Gedung','Peralatan & Mesin','Kendaraan','Jalan, Irigasi & Jaringan','Lainnya'];
                        $currentJenis = old('jenis', $item['jenis']);
                        foreach ($jenisList as $j): ?>
                        <option value="<?= esc($j) ?>" <?= $currentJenis === $j ? 'selected' : '' ?>><?= esc($j) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Total <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="total" value="<?= old('total', esc($item['total'])) ?>" min="1" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <?php
                        $statusList = ['Baik','Rusak Ringan','Rusak Berat'];
                        $currentStatus = old('status', $item['status']);
                        foreach ($statusList as $s): ?>
                        <option value="<?= esc($s) ?>" <?= $currentStatus === $s ? 'selected' : '' ?>><?= esc($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Ganti Foto (Opsional)</label>
                    <input type="file" class="form-control" id="fotoInput" name="foto" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div class="form-text text-muted">Maks. 1MB · JPG/PNG · Biarkan kosong jika tidak ingin mengganti foto.</div>
                    <div id="fotoPreviewWrap" class="mt-3" style="display:none;">
                        <img id="fotoPreview" src="" alt="Preview" class="rounded border" style="max-height:160px;object-fit:cover;">
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-success" type="submit">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Preview foto
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

    // Hapus via SweetAlert
    document.getElementById('btnHapus').addEventListener('click', function () {
        showConfirm(
            'Hapus barang "<?= esc($item['nama_barang'], 'js') ?>"? Tindakan ini tidak dapat dibatalkan.',
            'Hapus Inventaris', 'Ya, Hapus'
        ).then(confirmed => {
            if (confirmed) {
                window.location.href = '<?= base_url('/staff/inventaris/' . $item['id'] . '/hapus') ?>';
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
