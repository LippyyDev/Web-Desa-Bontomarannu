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
        <?php endif; ?>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Tindak Lanjut Surat</h5>
        <form id="formTindakLanjut" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Pesan / Catatan</label>
                <textarea class="form-control" name="reply_text" id="reply_text" rows="5" placeholder="Tuliskan balasan atau catatan keputusan di sini..."></textarea>
                <div class="form-text text-muted">Catatan wajib diisi jika Anda menolak surat atau mengirim balasan.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Lampiran (opsional)</label>
                <input type="file" class="form-control" name="reply_attachments[]" multiple>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <?php if (in_array($letter['status'], ['Dibaca', 'Menunggu'], true)): ?>
                    <button class="btn btn-success" type="button" onclick="submitAction('<?= base_url('/staff/surat/' . $letter['id'] . '/terima') ?>', false)">
                        <i class="bi bi-check-circle me-1"></i> Terima Surat
                    </button>
                    <button class="btn btn-danger" type="button" onclick="submitAction('<?= base_url('/staff/surat/' . $letter['id'] . '/tolak') ?>', true, 'Yakin ingin menolak surat ini?')">
                        <i class="bi bi-x-circle me-1"></i> Tolak Surat
                    </button>
                <?php else: ?>
                    <button class="btn btn-primary" type="button" onclick="submitAction('<?= base_url('/staff/surat/' . $letter['id'] . '/balas') ?>', true)">
                        <i class="bi bi-send me-1"></i> Kirim Balasan
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
function submitAction(actionUrl, requireText = false, confirmMsg = null) {
    const text = document.getElementById('reply_text').value.trim();
    if (requireText && !text) {
        alert('Pesan / Catatan wajib diisi untuk tindakan ini.');
        return;
    }
    if (confirmMsg && !confirm(confirmMsg)) {
        return;
    }
    const form = document.getElementById('formTindakLanjut');
    form.action = actionUrl;
    form.submit();
}
</script>

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



<?= $this->endSection() ?>
