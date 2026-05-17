<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<?php
function statusTextUser(string $status): string {
    return match($status) {
        'Menunggu' => '<span class="fw-bold text-warning">Menunggu</span>',
        'Dibaca'   => '<span class="fw-bold text-primary">Dibaca</span>',
        'Diterima' => '<span class="fw-bold text-success">Diterima</span>',
        'Ditolak'  => '<span class="fw-bold text-danger">Status: Ditolak</span>',
        default    => '<span class="fw-bold text-secondary">' . esc($status) . '</span>',
    };
}

function getFileIcon(string $filename): string {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (in_array($ext, ['pdf'])) return 'bi-file-earmark-pdf text-danger';
    if (in_array($ext, ['doc', 'docx'])) return 'bi-file-earmark-word text-primary';
    if (in_array($ext, ['xls', 'xlsx'])) return 'bi-file-earmark-excel text-success';
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) return 'bi-file-image text-info';
    return 'bi-file-earmark text-secondary';
}

function truncateFilename(string $filename, int $maxLength = 35): string {
    if (strlen($filename) <= $maxLength) return $filename;
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $keep = $maxLength - strlen($ext) - 4;
    return substr($name, 0, $keep > 0 ? $keep : 15) . '....' . $ext;
}
?>

<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            LAYANAN SURAT
        </div>
        <h2 class="fw-bold text-dark mb-0" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Detail <span style="color: #15803d;">Surat</span>
        </h2>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= base_url('/user/surat/' . $letter['id'] . '/edit') ?>" class="btn btn-primary" title="Edit Surat">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?= base_url('/user/surat/' . $letter['id'] . '/hapus') ?>" class="btn btn-danger" onclick="return confirm('Hapus surat ini?')" title="Hapus Surat">
            <i class="bi bi-trash"></i> Hapus
        </a>
        <a href="<?= base_url('/user/surat') ?>" class="btn btn-outline-success">
            Kembali
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4 fw-bold">Informasi Surat</h5>
        
        <div class="row gy-3 mb-4">
            <div class="col-md-6">
                <div class="small text-muted mb-1">Kode Surat</div>
                <div class="fw-semibold text-dark"><?= esc($letter['kode_unik'] ?? '-') ?></div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Status</div>
                <div class="fs-6"><?= statusTextUser($letter['status']) ?></div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Tipe Surat</div>
                <div class="fw-semibold text-dark"><?= esc($letter['tipe_surat']) ?></div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Perihal Surat</div>
                <div class="fw-semibold text-dark"><?= esc($letter['judul_perihal']) ?></div>
            </div>
        </div>

        <div class="small text-muted mb-2">Isi Surat</div>
        <div class="text-dark p-3 bg-light rounded border border-light"><?= nl2br(esc($letter['isi_surat'])) ?></div>
        <?php if (!empty($attachments)): ?>
            <div class="mt-4">
                <div class="small text-muted mb-2 fw-semibold">Lampiran:</div>
                <div class="d-flex flex-column gap-2">
                <?php foreach ($attachments as $att): 
                    $fileName = esc($att['original_name'] ?: basename($att['file_path']));
                ?>
                    <a href="<?= base_url($att['file_path']) ?>" target="_blank" class="d-flex align-items-center gap-2 p-2 border rounded bg-light text-decoration-none text-dark" style="width: fit-content; max-width: 100%;">
                        <i class="bi <?= getFileIcon($fileName) ?> fs-5"></i>
                        <span class="text-truncate" title="<?= $fileName ?>"><?= truncateFilename($fileName) ?></span>
                    </a>
                <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($letter['status'] === 'Ditolak' && !empty($letter['catatan_penolakan'])): ?>
            <div class="mt-4 text-danger">
                <span class="small fw-semibold mb-1 d-block">Catatan Penolakan:</span>
                <?= nl2br(esc($letter['catatan_penolakan'])) ?>
            </div>
        <?php elseif ($letter['status'] === 'Diterima' && !empty($letter['catatan_penolakan'])): ?>
            <div class="mt-4 text-success">
                <span class="small fw-semibold mb-1 d-block">Catatan Diterima:</span>
                <?= nl2br(esc($letter['catatan_penolakan'])) ?>
            </div>
        <?php elseif ($letter['status'] === 'Diterima'): ?>
            <div class="mt-4 text-success fw-semibold">
                Surat Anda telah diterima oleh pihak desa.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body pb-0">
        <h5 class="card-title mb-4 fw-bold">Balasan Sebelumnya</h5>
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
                            <div class="mt-3">
                                <div class="small text-muted mb-2 fw-semibold">Lampiran:</div>
                                <div class="d-flex flex-column gap-2">
                                <?php foreach ($replyAttachments[$reply['id']] as $att): 
                                    $fileName = esc($att['original_name'] ?: basename($att['file_path']));
                                ?>
                                    <a href="<?= base_url($att['file_path']) ?>" target="_blank" class="d-flex align-items-center gap-2 p-2 border rounded bg-light text-decoration-none text-dark" style="width: fit-content; max-width: 100%;">
                                        <i class="bi <?= getFileIcon($fileName) ?> fs-5"></i>
                                        <span class="text-truncate" title="<?= $fileName ?>"><?= truncateFilename($fileName) ?></span>
                                    </a>
                                <?php endforeach; ?>
                                </div>
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
