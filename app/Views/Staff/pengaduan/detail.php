<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            LAYANAN DESA
        </div>
        <h2 class="fw-bold text-dark mb-0" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Detail <span style="color: #15803d;">Pengaduan</span>
        </h2>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-danger" onclick="hapusPengaduan(<?= $pengaduan['id'] ?>)">
            <i class="bi bi-trash me-1"></i> Hapus
        </button>
        <a href="<?= base_url('/staff/pengaduan') ?>" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4 fw-bold">Informasi Pengaduan</h5>
        
        <div class="row gy-3 mb-4">
            <div class="col-md-6">
                <div class="small text-muted mb-1">Tanggal</div>
                <div class="fw-semibold text-dark"><?= date('d F Y, H:i', strtotime($pengaduan['created_at'])) ?> WIB</div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Nama</div>
                <div class="fw-semibold text-dark"><?= esc($pengaduan['nama']) ?></div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Kontak</div>
                <div class="fw-semibold text-dark"><?= esc($pengaduan['kontak']) ?></div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Perihal</div>
                <div class="fw-semibold text-dark"><?= esc($pengaduan['perihal']) ?></div>
            </div>
        </div>

        <div class="small text-muted mb-2">Isi Pengaduan</div>
        <div class="text-dark p-3 bg-light rounded border border-light"><?= nl2br(esc($pengaduan['isi'])) ?></div>

        <?php if (!empty($pengaduan['foto'])): ?>
            <div class="mt-4">
                <div class="small text-muted mb-2 fw-semibold">Foto Pendukung:</div>
                <a href="<?= base_url($pengaduan['foto']) ?>" target="_blank" title="Klik untuk memperbesar">
                    <img src="<?= base_url($pengaduan['foto']) ?>" alt="Foto Pengaduan" class="img-thumbnail" style="width: 250px; height: 250px; object-fit: cover; cursor: pointer;">
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function hapusPengaduan(id) {
    showConfirm(
        'Hapus pengaduan ini? Tindakan ini tidak dapat dibatalkan.',
        'Hapus Pengaduan', 
        'Ya, Hapus'
    ).then(confirmed => {
        if (confirmed) {
            window.location.href = `<?= base_url('/staff/pengaduan/') ?>${id}/hapus`;
        }
    });
}
</script>
<?= $this->endSection() ?>
