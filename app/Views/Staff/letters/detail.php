<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<?php
// Helper fungsi badge status
function statusBadgeStaff(string $status): string {
    return match($status) {
        'Menunggu' => '<span class="badge bg-warning text-dark">Menunggu</span>',
        'Dibaca'   => '<span class="badge bg-info text-white">Dibaca</span>',
        'Diterima' => '<span class="badge bg-success">Diterima</span>',
        'Ditolak'  => '<span class="badge bg-danger">Ditolak</span>',
        default    => '<span class="badge bg-secondary">' . esc($status) . '</span>',
    };
}
?>

<div class="page-header">
    <div>
        <h4><?= esc($letter['judul_perihal']) ?></h4>
        <div class="text-muted small">Status: <?= statusBadgeStaff($letter['status']) ?></div>
    </div>
    <div class="page-header-actions">
        <?php if ($letter['tipe_surat'] === 'Keterangan Usaha' || $letter['tipe_surat'] === 'Keterangan Tidak Mampu' || $letter['tipe_surat'] === 'Keterangan Belum Menikah' || $letter['tipe_surat'] === 'Keterangan Domisili' || $letter['tipe_surat'] === 'Undangan'): ?>
            <a href="<?= base_url('/staff/surat/' . $letter['id'] . '/word') ?>" class="btn btn-sm btn-outline-primary" title="Export Word">
                <i class="bi bi-file-earmark-word"></i> Export Word
            </a>
        <?php endif; ?>
        <a href="<?= base_url('/staff/surat') ?>" class="page-header-icon">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card mb-3">
    <div class="card-body">
        <div class="small text-muted mb-2">Tipe Surat</div>
        <div class="fw-semibold mb-3"><?= esc($letter['tipe_surat']) ?></div>
        <div class="small text-muted mb-2">Isi Surat</div>
        <div class="text-muted"><?= nl2br($letter['isi_surat']) ?></div>
        <?php if (!empty($attachments)): ?>
            <div class="mt-3">
                <div class="small text-muted mb-2">Lampiran</div>
                <?php foreach ($attachments as $att): ?>
                    <div class="mb-1"><a href="<?= base_url($att['file_path']) ?>" target="_blank"><?= esc($att['original_name'] ?: basename($att['file_path'])) ?></a></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($letter['catatan_penolakan']) && $letter['status'] === 'Ditolak'): ?>
            <div class="mt-3 p-3 border border-danger rounded bg-danger bg-opacity-10">
                <div class="small text-danger fw-semibold mb-1"><i class="bi bi-x-circle-fill me-1"></i>Catatan Penolakan</div>
                <div class="text-danger"><?= nl2br(esc($letter['catatan_penolakan'])) ?></div>
            </div>
        <?php elseif (!empty($letter['catatan_penolakan']) && $letter['status'] === 'Diterima'): ?>
            <div class="mt-3 p-3 border border-success rounded bg-success bg-opacity-10">
                <div class="small text-success fw-semibold mb-1"><i class="bi bi-check-circle-fill me-1"></i>Catatan Penerimaan</div>
                <div class="text-success"><?= nl2br(esc($letter['catatan_penolakan'])) ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (in_array($letter['status'], ['Dibaca', 'Menunggu'], true)): ?>
<div class="card mb-3 border-0 bg-light">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Keputusan Surat</h6>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTerima">
                <i class="bi bi-check-circle me-1"></i> Terima Surat
            </button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
                <i class="bi bi-x-circle me-1"></i> Tolak Surat
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Kirim Balasan</h5>
        <form method="post" enctype="multipart/form-data" action="<?= base_url('/staff/surat/' . $letter['id'] . '/balas') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Isi Balasan</label>
                <textarea class="form-control" name="reply_text" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Lampiran (opsional)</label>
                <input type="file" class="form-control" name="reply_attachments[]" multiple>
            </div>
            <button class="btn btn-primary w-100" type="submit">Kirim Balasan</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Balasan Sebelumnya
    </div>
    <div class="list-group list-group-flush">
        <?php foreach ($replies as $reply): ?>
            <?php 
            $replyProfile = $replyProfiles[$reply['id']] ?? null;
            $fotoProfil = !empty($replyProfile['foto_profil']) 
                ? base_url($replyProfile['foto_profil']) 
                : base_url('assets/img/guest.webp');
            $namaPengirim = $replyProfile['nama_lengkap'] ?? $replyProfile['username'] ?? 'Staff';
            ?>
            <div class="list-group-item">
                <div class="d-flex align-items-start gap-3">
                    <img src="<?= $fotoProfil ?>" alt="<?= esc($namaPengirim) ?>" 
                         class="rounded-circle" 
                         style="width: 48px; height: 48px; object-fit: cover; flex-shrink: 0;">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="fw-semibold"><?= esc($namaPengirim) ?></div>
                                <div class="small text-muted"><?= date('d M Y H:i', strtotime($reply['created_at'])) ?></div>
                            </div>
                            <?php if ($reply['staff_id'] == $currentStaffId): ?>
                            <div>
                                <a href="<?= base_url('/staff/surat/' . $letter['id'] . '/balasan/' . $reply['id'] . '/hapus') ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Yakin ingin menghapus balasan ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-muted mb-2"><?= nl2br($reply['reply_text']) ?></div>
                        <?php if (!empty($replyAttachments[$reply['id']])): ?>
                            <div class="small mt-2">Lampiran:
                                <?php foreach ($replyAttachments[$reply['id']] as $att): ?>
                                    <a href="<?= base_url($att['file_path']) ?>" target="_blank"><?= esc($att['original_name'] ?: 'lampiran') ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($replies)): ?>
            <div class="list-group-item text-muted small">Belum ada balasan.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Terima Surat -->
<div class="modal fade" id="modalTerima" tabindex="-1" aria-labelledby="modalTerimaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= base_url('/staff/surat/' . $letter['id'] . '/terima') ?>">
                <?= csrf_field() ?>
                <div class="modal-header border-0">
                    <h5 class="modal-title text-success" id="modalTerimaLabel">
                        <i class="bi bi-check-circle-fill me-2"></i>Terima Surat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Anda akan menerima surat: <strong><?= esc($letter['judul_perihal']) ?></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Catatan Penerimaan <span class="text-muted">(opsional)</span></label>
                        <textarea class="form-control" name="catatan_penerimaan" rows="3" 
                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Konfirmasi Terima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tolak Surat -->
<div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= base_url('/staff/surat/' . $letter['id'] . '/tolak') ?>">
                <?= csrf_field() ?>
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger" id="modalTolakLabel">
                        <i class="bi bi-x-circle-fill me-2"></i>Tolak Surat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Anda akan menolak surat: <strong><?= esc($letter['judul_perihal']) ?></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Catatan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="catatan_penolakan" rows="3" required
                                  placeholder="Tuliskan alasan penolakan..."></textarea>
                        <div class="form-text text-danger">Wajib diisi sebelum menolak surat.</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i> Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
