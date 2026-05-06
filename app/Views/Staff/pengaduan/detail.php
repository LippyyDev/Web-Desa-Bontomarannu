<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Detail Pengaduan</h4>
        <div class="text-muted small">Detail pengaduan dari masyarakat.</div>
    </div>
    <div class="page-header-actions d-flex gap-2">
        <form action="<?= base_url('/staff/pengaduan/' . $pengaduan['id']) ?>" method="post" onsubmit="return confirm('Hapus pengaduan ini?')" class="d-inline">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="page-header-icon page-header-icon-delete border-0" title="Hapus Pengaduan">
                <i class="bi bi-trash"></i>
            </button>
        </form>
        <a href="<?= base_url('/staff/pengaduan') ?>" class="page-header-icon" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Pengaduan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Tanggal</th>
                        <td><?= date('d F Y, H:i', strtotime($pengaduan['created_at'])) ?> WIB</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?= esc($pengaduan['nama']) ?></td>
                    </tr>
                    <tr>
                        <th>Kontak</th>
                        <td><?= esc($pengaduan['kontak']) ?></td>
                    </tr>
                    <tr>
                        <th>Perihal</th>
                        <td><strong><?= esc($pengaduan['perihal']) ?></strong></td>
                    </tr>
                </table>
                
                <hr>
                
                <div class="mb-3">
                    <h6>Isi Pengaduan:</h6>
                    <p class="text-justify"><?= nl2br(esc($pengaduan['isi'])) ?></p>
                </div>

                <?php if (!empty($pengaduan['foto'])): ?>
                    <div class="mb-3">
                        <h6>Foto Pendukung:</h6>
                        <img src="<?= base_url($pengaduan['foto']) ?>" alt="Foto Pengaduan" class="img-fluid rounded" style="max-height: 400px; width: auto;">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
