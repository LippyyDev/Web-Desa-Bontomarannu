<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($title) ?></h4>
        <div class="text-muted small">Kelola semua data pariwisata desa.</div>
    </div>
    <div class="page-header-actions">
        <div class="page-header-icon"><i class="bi bi-compass"></i></div>
        <a href="<?= base_url('/staff/pariwisata/tambah') ?>" class="page-header-icon page-header-icon-add" title="Tambah Pariwisata">
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-primary"><?= count($list) ?></div>
                <div class="small text-muted">Total Destinasi</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Cari Pariwisata</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Nama tempat wisata...">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3" id="pariwisataGrid">
    <?php if (empty($list)): ?>
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-compass fs-1 d-block mb-2"></i>
            Belum ada data pariwisata. <a href="<?= base_url('/staff/pariwisata/tambah') ?>">Tambah sekarang</a>
        </div>
    <?php else: ?>
        <?php foreach ($list as $item): ?>
        <div class="col-md-6 col-lg-4 pariwisata-item">
            <div class="card h-100 shadow-sm border-0">
                <?php if ($item['thumbnail']): ?>
                    <img src="<?= base_url($item['thumbnail']) ?>" class="card-img-top" style="height:200px;object-fit:cover;" alt="<?= esc($item['nama_tempat']) ?>">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                        <i class="bi bi-image text-muted fs-1"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= esc($item['nama_tempat']) ?></h5>
                    <p class="text-muted small"><?= esc(mb_strimwidth($item['alamat'] ?? '', 0, 80, '...')) ?></p>
                    <p class="card-text small"><?= esc(mb_strimwidth(strip_tags($item['deskripsi'] ?? ''), 0, 100, '...')) ?></p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('/staff/pariwisata/' . $item['id']) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        <a href="<?= base_url('/staff/pariwisata/' . $item['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="<?= base_url('/staff/pariwisata/' . $item['id'] . '/hapus') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus pariwisata ini?')"><i class="bi bi-trash"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const items = document.querySelectorAll('.pariwisata-item');

    searchInput.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        items.forEach(item => {
            const name = item.querySelector('.card-title').textContent.toLowerCase();
            item.style.display = name.includes(q) ? '' : 'none';
        });
    });
});
</script>
<?= $this->endSection() ?>
