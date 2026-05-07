<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($umkm['nama_toko']) ?></h4>
        <div class="text-muted small">
            Status:
            <?php if ($umkm['status'] === 'approved'): ?>
                <span class="badge bg-success">Aktif & Tampil di Publik</span>
            <?php elseif ($umkm['status'] === 'pending'): ?>
                <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
            <?php else: ?>
                <span class="badge bg-danger">Ditolak</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('/user/umkm/' . $umkm['id'] . '/edit') ?>" class="btn btn-primary btn-sm me-2"><i class="bi bi-pencil"></i> Edit</a>
        <a href="<?= base_url('/user/umkm') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<?php if ($umkm['status'] === 'pending'): ?>
<div class="alert alert-warning"><i class="bi bi-clock"></i> Toko Anda sedang menunggu peninjauan dari perangkat desa.</div>
<?php elseif ($umkm['status'] === 'rejected'): ?>
<div class="alert alert-danger">
    <i class="bi bi-x-circle"></i> <strong>Toko ditolak.</strong>
    <?php if ($umkm['alasan_tolak']): ?> Alasan: <?= esc($umkm['alasan_tolak']) ?><?php endif; ?>
    <br><a href="<?= base_url('/user/umkm/' . $umkm['id'] . '/edit') ?>" class="btn btn-sm btn-warning mt-2"><i class="bi bi-pencil"></i> Edit & Kirim Ulang</a>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header fw-semibold">Informasi Toko</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6"><span class="text-muted small">Kontak</span><div><?= esc($umkm['kontak'] ?? '-') ?></div></div>
                </div>
                <div class="mb-3"><span class="text-muted small">Alamat</span><div><?= esc($umkm['alamat'] ?? '-') ?></div></div>
                <div><span class="text-muted small">Deskripsi</span><div><?= nl2br(esc($umkm['deskripsi'] ?? '-')) ?></div></div>
            </div>
        </div>

        <?php if (!empty($produk)): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header fw-semibold">Produk</div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($produk as $p): ?>
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <?php if (!empty($p['gambar'])): ?>
                            <div class="d-flex gap-1 mb-2 overflow-auto">
                                <?php foreach ($p['gambar'] as $g): ?>
                                <img src="<?= base_url($g['gambar_path']) ?>" style="height:70px;width:70px;object-fit:cover;border-radius:4px;">
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <div class="fw-medium"><?= esc($p['nama_produk']) ?></div>
                            <?php if ($p['harga']): ?><div class="text-success">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div><?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($ecommerce)): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header fw-semibold">Link E-Commerce</div>
            <div class="card-body d-flex flex-wrap gap-2">
                <?php foreach ($ecommerce as $e): ?>
                <a href="<?= esc($e['url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-bag"></i> <?= esc($e['platform'] ?: 'E-Commerce') ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header fw-semibold">Info</div>
            <div class="card-body">
                <div class="mb-2"><span class="text-muted small">Didaftarkan</span><div><?= date('d M Y', strtotime($umkm['created_at'])) ?></div></div>
                <?php if ($umkm['approved_at']): ?>
                <div><span class="text-muted small">Diproses</span><div><?= date('d M Y', strtotime($umkm['approved_at'])) ?></div></div>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!empty($umkm['maps_embed_url'])): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header fw-semibold">Lokasi</div>
            <div class="card-body p-0">
                <iframe src="<?= esc($umkm['maps_embed_url']) ?>" width="100%" height="200" style="border:0;" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
