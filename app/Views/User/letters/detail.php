<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<?php
function statusBadgeUser(string $status): string {
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
        <div class="text-muted small">Status: <?= statusBadgeUser($letter['status']) ?></div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('/user/surat/' . $letter['id'] . '/edit') ?>" class="page-header-icon" title="Edit Surat">
            <i class="bi bi-pencil"></i>
        </a>
        <a href="<?= base_url('/user/surat/' . $letter['id'] . '/hapus') ?>" class="page-header-icon page-header-icon-delete" onclick="return confirm('Hapus surat ini?')" title="Hapus Surat">
            <i class="bi bi-trash"></i>
        </a>
        <a href="<?= base_url('/user/surat') ?>" class="page-header-icon">
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

        <?php if ($letter['status'] === 'Ditolak' && !empty($letter['catatan_penolakan'])): ?>
            <div class="mt-3 p-3 border border-danger rounded bg-danger bg-opacity-10">
                <div class="small text-danger fw-semibold mb-1">
                    <i class="bi bi-x-circle-fill me-1"></i>Surat Anda Ditolak
                </div>
                <div class="text-danger">Alasan: <?= nl2br(esc($letter['catatan_penolakan'])) ?></div>
            </div>
        <?php elseif ($letter['status'] === 'Diterima' && !empty($letter['catatan_penolakan'])): ?>
            <div class="mt-3 p-3 border border-success rounded bg-success bg-opacity-10">
                <div class="small text-success fw-semibold mb-1">
                    <i class="bi bi-check-circle-fill me-1"></i>Surat Anda Diterima
                </div>
                <div class="text-success">Catatan: <?= nl2br(esc($letter['catatan_penolakan'])) ?></div>
            </div>
        <?php elseif ($letter['status'] === 'Diterima'): ?>
            <div class="mt-3 p-3 border border-success rounded bg-success bg-opacity-10">
                <div class="small text-success fw-semibold">
                    <i class="bi bi-check-circle-fill me-1"></i>Surat Anda telah diterima oleh pihak desa.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-reply-fill"></i>
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
