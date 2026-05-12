<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<style>
.nav-pills .nav-link { color: #198754; background-color: #fff; border: 1px solid #198754; }
.nav-pills .nav-link.active { background-color: #198754; color: #fff; }
.nav-pills .nav-link:hover:not(.active) { background-color: #e8f5e9; }
.gallery-card { border: 1px solid #edf2f7; border-radius: 16px; overflow: hidden; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; display: flex; flex-direction: column; }
.gallery-card:hover { transform: translateY(-4px); box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04); border-color: #e2e8f0; }
.gallery-img-wrapper { position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f1f5f9; }
.gallery-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
.gallery-card:hover .gallery-img-wrapper img { transform: scale(1.05); }
.gallery-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #cbd5e1; background-color: #f8fafc; }
.gallery-placeholder i { font-size: 3rem; }
.gallery-card-body { padding: 0.875rem; display: flex; flex-direction: column; flex-grow: 1; min-width: 0; }
.gallery-title { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 0.35rem; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; word-break: break-word; }
.gallery-desc { font-size: 0.75rem; color: #64748b; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; margin-bottom: 0.75rem; flex-grow: 1; word-break: break-word; }
</style>

<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            KELOLA UMKM
        </div>
        <h2 class="fw-bold text-dark mb-0" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Detail <span style="color: #15803d;">UMKM</span>
        </h2>
    </div>
    <div class="d-flex gap-2">
        <?php if ($umkm['status'] === 'approved'): ?>
        <a href="<?= base_url('/staff/umkm/' . $umkm['id'] . '/edit') ?>" class="btn btn-success" title="Edit UMKM">Edit</a>
        <?php endif; ?>
        <a href="<?= base_url('/staff/umkm') ?>" class="btn btn-outline-success">Kembali</a>
    </div>
</div>



<ul class="nav nav-pills mb-4 gap-2" id="umkmShowTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill fw-medium px-4" id="info-tab" data-bs-toggle="pill" data-bs-target="#info-pane" type="button" role="tab" aria-controls="info-pane" aria-selected="true"><i class="bi bi-shop me-2"></i>Informasi Toko</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill fw-medium px-4" id="produk-tab" data-bs-toggle="pill" data-bs-target="#produk-pane" type="button" role="tab" aria-controls="produk-pane" aria-selected="false"><i class="bi bi-box-seam me-2"></i>Daftar Produk</button>
    </li>
</ul>

<div class="tab-content" id="umkmShowTabContent">
    <!-- TAB 1: INFORMASI TOKO -->
    <div class="tab-pane fade show active" id="info-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
        
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4 fw-bold">Informasi Umum</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="small text-muted mb-1">Status Toko</div>
                        <div>
                            <?php if ($umkm['status'] === 'approved'): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php elseif ($umkm['status'] === 'pending'): ?>
                                <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($umkm['status'] === 'rejected' && !empty($umkm['alasan_tolak'])): ?>
                        <div class="text-danger small mt-1"><strong>Alasan Penolakan:</strong> <?= esc($umkm['alasan_tolak']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted mb-1">Nama Toko</div>
                        <div class="fw-semibold text-dark"><?= esc($umkm['nama_toko']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted mb-1">Pemilik</div>
                        <div class="fw-semibold text-dark"><?= esc($umkm['pemilik']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted mb-1">Kontak</div>
                        <div class="fw-semibold text-dark"><?= esc($umkm['kontak'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted mb-1">Alamat</div>
                        <div class="fw-semibold text-dark"><?= nl2br(esc($umkm['alamat'] ?? '-')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted mb-1">Didaftarkan Pada</div>
                        <div class="fw-semibold text-dark"><?= date('d M Y H:i', strtotime($umkm['created_at'])) ?></div>
                    </div>
                    <?php if ($umkm['approved_at']): ?>
                    <div class="col-md-6">
                        <div class="small text-muted mb-1">Diproses Pada</div>
                        <div class="fw-semibold text-dark"><?= date('d M Y H:i', strtotime($umkm['approved_at'])) ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="col-12">
                        <div class="small text-muted mb-2">Deskripsi Toko</div>
                        <div class="text-dark p-3 bg-light rounded border border-light"><?= nl2br(esc($umkm['deskripsi'] ?? '-')) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($umkm['foto_toko'])): ?>
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4 fw-bold">Foto Toko</h5>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card border shadow-sm overflow-hidden mb-0">
                            <img src="<?= base_url($umkm['foto_toko']) ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="Foto Toko">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($umkm['maps_embed_url'])): ?>
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4 fw-bold">Lokasi Google Maps</h5>
                <iframe src="<?= esc($umkm['maps_embed_url']) ?>" width="100%" height="300" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($ecommerce)): ?>
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4 fw-bold">Link E-Commerce</h5>
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

        <?php if ($umkm['status'] === 'pending'): ?>
        <!-- ─── Tindak Lanjut ──────────────────────────────────────────────────── -->
        <div class="card mb-4 shadow-sm border-0 mt-4">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-4">Tindak Lanjut</h5>

                <div class="mb-3">
                    <label class="form-label fw-medium">Alasan Penolakan <span class="text-muted fw-normal small">(wajib diisi jika menolak)</span></label>
                    <textarea id="alasanTolak" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan di sini..."></textarea>
                    <div class="form-text text-muted">Catatan wajib diisi jika Anda memilih untuk menolak UMKM ini.</div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="button" id="btnSetujuiTl" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Setujui UMKM
                    </button>
                    <button type="button" id="btnTolakTl" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i>Tolak UMKM
                    </button>
                </div>

                <!-- Hidden forms -->
                <form id="approveFormTl" method="POST" action="<?= base_url('/staff/umkm/' . $umkm['id'] . '/approve') ?>" style="display:none;">
                    <?= csrf_field() ?>
                </form>
                <form id="rejectFormTl" method="POST" action="<?= base_url('/staff/umkm/' . $umkm['id'] . '/reject') ?>" style="display:none;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="alasan" id="alasanHidden">
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- TAB 2: DAFTAR PRODUK -->
    <div class="tab-pane fade" id="produk-pane" role="tabpanel" aria-labelledby="produk-tab" tabindex="0">
        <?php if (!empty($produk)): ?>
        <div class="row g-3 mb-4 mt-1">
            <?php foreach ($produk as $p): ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="gallery-card h-100">
                    <div class="gallery-img-wrapper">
                        <?php if (!empty($p['gambar'])): ?>
                            <img src="<?= base_url($p['gambar'][0]['gambar_path']) ?>" alt="<?= esc($p['nama_produk']) ?>" loading="lazy" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'gallery-placeholder\'><i class=\'bi bi-images\'></i></div>'">
                        <?php else: ?>
                            <div class="gallery-placeholder"><i class="bi bi-images"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="gallery-card-body">
                        <h5 class="gallery-title"><?= esc($p['nama_produk']) ?></h5>
                        <?php if ($p['harga']): ?>
                            <div class="text-success fw-bold mb-2 small">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                        <?php endif; ?>
                        <?php if (!empty($p['deskripsi'])): ?>
                            <p class="gallery-desc"><?= esc($p['deskripsi']) ?></p>
                        <?php else: ?>
                            <p class="gallery-desc text-muted fst-italic">Tidak ada deskripsi</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-box-seam fs-1 d-block mb-3 text-secondary"></i>
                Belum ada produk di toko ini.
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>



<script>
// Tindak Lanjut — Setujui
const btnSetujuiTl = document.getElementById('btnSetujuiTl');
if (btnSetujuiTl) {
    btnSetujuiTl.addEventListener('click', function () {
        showConfirm('Setujui UMKM "<?= esc($umkm['nama_toko']) ?>"? Toko akan langsung tampil di halaman publik.', 'Setujui UMKM', 'Ya, Setujui')
            .then(ok => { if (ok) document.getElementById('approveFormTl').submit(); });
    });
}

// Tindak Lanjut — Tolak
const btnTolakTl = document.getElementById('btnTolakTl');
if (btnTolakTl) {
    btnTolakTl.addEventListener('click', function () {
        const alasan = document.getElementById('alasanTolak').value.trim();
        if (!alasan) {
            showError('Alasan penolakan wajib diisi sebelum menolak UMKM.', 'Alasan Diperlukan');
            return;
        }
        showConfirm('Tolak UMKM "<?= esc($umkm['nama_toko']) ?>"? Pemilik akan mendapatkan notifikasi.', 'Tolak UMKM', 'Ya, Tolak')
            .then(ok => {
                if (ok) {
                    document.getElementById('alasanHidden').value = alasan;
                    document.getElementById('rejectFormTl').submit();
                }
            });
    });
}
</script>
<?= $this->endSection() ?>
