<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4><?= esc($umkm['nama_toko']) ?></h4>
        <div class="text-muted small">
            Detail UMKM •
            <?php if ($umkm['status'] === 'approved'): ?>
                <span class="badge bg-success">Disetujui</span>
            <?php elseif ($umkm['status'] === 'pending'): ?>
                <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
            <?php else: ?>
                <span class="badge bg-danger">Ditolak</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('/staff/umkm/' . $umkm['id'] . '/edit') ?>" class="btn btn-primary btn-sm me-2"><i class="bi bi-pencil"></i> Edit</a>
        <a href="<?= base_url('/staff/umkm') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<?php if ($umkm['status'] === 'pending'): ?>
<div class="alert alert-warning d-flex gap-3 align-items-center">
    <i class="bi bi-clock-history fs-4"></i>
    <div>
        <strong>Menunggu Persetujuan</strong><br>
        UMKM dari <strong><?= esc($umkm['pemilik']) ?></strong> ini masih menunggu. Silakan tinjau dan ambil keputusan.
        <div class="mt-2 d-flex gap-2">
            <button class="btn btn-success btn-sm" onclick="approveUmkm(<?= $umkm['id'] ?>, '<?= esc($umkm['nama_toko']) ?>')"><i class="bi bi-check-circle"></i> Setujui</button>
            <button class="btn btn-danger btn-sm" onclick="rejectUmkm(<?= $umkm['id'] ?>, '<?= esc($umkm['nama_toko']) ?>')"><i class="bi bi-x-circle"></i> Tolak</button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($umkm['status'] === 'rejected' && $umkm['alasan_tolak']): ?>
<div class="alert alert-danger">
    <strong>Alasan Penolakan:</strong> <?= esc($umkm['alasan_tolak']) ?>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">

        <!-- Info Toko -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header fw-semibold"><i class="bi bi-shop me-2"></i>Informasi Toko</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <span class="text-muted small">Pemilik</span>
                        <div class="fw-medium"><?= esc($umkm['pemilik']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Kontak</span>
                        <div><?= esc($umkm['kontak'] ?? '-') ?></div>
                    </div>
                </div>
                <div class="mb-3">
                    <span class="text-muted small">Alamat</span>
                    <div><?= esc($umkm['alamat'] ?? '-') ?></div>
                </div>
                <div class="mb-3">
                    <span class="text-muted small">Deskripsi</span>
                    <div><?= nl2br(esc($umkm['deskripsi'] ?? '-')) ?></div>
                </div>
            </div>
        </div>

        <!-- Produk -->
        <?php if (!empty($produk)): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header fw-semibold"><i class="bi bi-box me-2"></i>Produk (<?= count($produk) ?>)</div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($produk as $p): ?>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <?php if (!empty($p['gambar'])): ?>
                            <div class="d-flex gap-1 mb-2 overflow-auto">
                                <?php foreach ($p['gambar'] as $g): ?>
                                <img src="<?= base_url($g['gambar_path']) ?>" style="height:80px;width:80px;object-fit:cover;border-radius:4px;" alt="">
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <div class="fw-medium"><?= esc($p['nama_produk']) ?></div>
                            <?php if ($p['harga']): ?>
                            <div class="text-success fw-semibold">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                            <?php endif; ?>
                            <?php if ($p['deskripsi']): ?>
                            <div class="small text-muted mt-1"><?= esc($p['deskripsi']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- E-Commerce -->
        <?php if (!empty($ecommerce)): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header fw-semibold"><i class="bi bi-bag me-2"></i>Link E-Commerce</div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($ecommerce as $e): ?>
                    <a href="<?= esc($e['url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-bag"></i> <?= esc($e['platform'] ?: 'E-Commerce') ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header fw-semibold">Info</div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="text-muted small">Didaftarkan</span>
                    <div><?= date('d M Y H:i', strtotime($umkm['created_at'])) ?></div>
                </div>
                <?php if ($umkm['approved_at']): ?>
                <div class="mb-2">
                    <span class="text-muted small">Diproses</span>
                    <div><?= date('d M Y H:i', strtotime($umkm['approved_at'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($umkm['maps_embed_url'])): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header fw-semibold">Lokasi</div>
            <div class="card-body p-0">
                <iframe src="<?= esc($umkm['maps_embed_url']) ?>" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Approve -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Setujui UMKM</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="approveForm" method="POST"><?= csrf_field() ?>
                <div class="modal-body"><p>Setujui toko <strong id="approveName"></strong>? Toko akan langsung tampil di halaman publik.</p></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Tolak UMKM</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="rejectForm" method="POST"><?= csrf_field() ?>
                <div class="modal-body">
                    <p>Tolak toko <strong id="rejectName"></strong>?</p>
                    <textarea name="alasan" class="form-control" rows="3" placeholder="Alasan penolakan..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveUmkm(id, nama) {
    document.getElementById('approveName').textContent = nama;
    document.getElementById('approveForm').action = '<?= base_url('/staff/umkm/') ?>' + id + '/approve';
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}
function rejectUmkm(id, nama) {
    document.getElementById('rejectName').textContent = nama;
    document.getElementById('rejectForm').action = '<?= base_url('/staff/umkm/') ?>' + id + '/reject';
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
<?= $this->endSection() ?>
