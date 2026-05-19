<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<?php
if (!function_exists('getFileIcon')) {
    function getFileIcon(string $filename): string {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, ['pdf'])) return 'bi-file-earmark-pdf text-danger';
        if (in_array($ext, ['doc', 'docx'])) return 'bi-file-earmark-word text-primary';
        if (in_array($ext, ['xls', 'xlsx'])) return 'bi-file-earmark-excel text-success';
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) return 'bi-file-image text-info';
        return 'bi-file-earmark text-secondary';
    }
}
if (!function_exists('truncateFilename')) {
    function truncateFilename(string $filename, int $maxLength = 35): string {
        if (strlen($filename) <= $maxLength) return $filename;
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $keep = $maxLength - strlen($ext) - 4;
        return substr($name, 0, $keep > 0 ? $keep : 15) . '....' . $ext;
    }
}
?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            LAYANAN SURAT
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            <?= isset($letter) ? 'Edit <span style="color: #15803d;">Surat</span>' : 'Buat <span style="color: #15803d;">Surat Baru</span>' ?>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Lengkapi detail surat yang akan dikirim ke staf desa.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/user/surat') ?>" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>



<div class="card">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data" action="<?= isset($letter) ? base_url('/user/surat/' . $letter['id']) : base_url('/user/surat') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Judul / Perihal</label>
                <input type="text" class="form-control" name="judul_perihal" id="judulInput"
                       value="<?= set_value('judul_perihal', $letter['judul_perihal'] ?? '') ?>"
                       maxlength="200" required>
                <div class="d-flex justify-content-end">
                    <div class="form-text text-muted"><span id="judulCount">0</span>/200</div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis / Tipe Surat</label>
                <select class="form-select" name="tipe_surat" required>
                    <option value="">-- Pilih Jenis Surat --</option>
                    <option value="Keterangan Usaha" <?= set_select('tipe_surat', 'Keterangan Usaha', (isset($letter['tipe_surat']) && $letter['tipe_surat'] === 'Keterangan Usaha')) ?>>Keterangan Usaha</option>
                    <option value="Keterangan Tidak Mampu" <?= set_select('tipe_surat', 'Keterangan Tidak Mampu', (isset($letter['tipe_surat']) && $letter['tipe_surat'] === 'Keterangan Tidak Mampu')) ?>>Keterangan Tidak Mampu</option>
                    <option value="Keterangan Belum Menikah" <?= set_select('tipe_surat', 'Keterangan Belum Menikah', (isset($letter['tipe_surat']) && $letter['tipe_surat'] === 'Keterangan Belum Menikah')) ?>>Keterangan Belum Menikah</option>
                    <option value="Keterangan Domisili" <?= set_select('tipe_surat', 'Keterangan Domisili', (isset($letter['tipe_surat']) && $letter['tipe_surat'] === 'Keterangan Domisili')) ?>>Keterangan Domisili</option>
                    <option value="Undangan" <?= set_select('tipe_surat', 'Undangan', (isset($letter['tipe_surat']) && $letter['tipe_surat'] === 'Undangan')) ?>>Undangan</option>
                    <option value="Lain Lain" <?= set_select('tipe_surat', 'Lain Lain', (isset($letter['tipe_surat']) && $letter['tipe_surat'] === 'Lain Lain')) ?>>Lain Lain</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Surat</label>
                <textarea class="form-control" name="isi_surat" id="isiSuratInput" rows="6" required
                          maxlength="3000"><?= set_value('isi_surat', $letter['isi_surat'] ?? '') ?></textarea>
                <div class="d-flex justify-content-end mt-1">
                    <div class="form-text" id="isiSuratCountWrap">
                        <span id="isiSuratCount">0</span><span class="text-muted">/3000</span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Lampiran <span class="text-muted small">(opsional — PDF, Word, JPG/PNG · maks. 1MB per file)</span></label>
                
                <?php if (!empty($attachments)): ?>
                    <div class="mb-3 p-3 bg-light rounded border">
                        <div class="small fw-semibold text-muted mb-2">Lampiran Saat Ini:</div>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($attachments as $att): 
                                $fileName = esc($att['original_name'] ?: basename($att['file_path']));
                            ?>
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-white" style="max-width: 100%;">
                                    <a href="<?= base_url($att['file_path']) ?>" target="_blank" class="d-flex align-items-center gap-2 text-decoration-none text-dark flex-grow-1 overflow-hidden" title="<?= $fileName ?>">
                                        <i class="bi <?= getFileIcon($fileName) ?> fs-5"></i>
                                        <span class="text-truncate"><?= truncateFilename($fileName, 45) ?></span>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-danger ms-2 btn-hapus-lampiran" data-url="<?= base_url('/user/surat/lampiran/' . $att['id'] . '/hapus') ?>" title="Hapus Lampiran">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <input type="file" class="form-control" name="attachments[]" id="letterAttachments" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <div id="attachmentPreviewList" class="mt-2 d-flex flex-column gap-2"></div>
            </div>
            <div class="mt-4">
                <button class="btn btn-success" type="submit">
                    <?= isset($letter) ? '<i class="bi bi-floppy me-1"></i> Update Surat' : '<i class="bi bi-send me-1"></i> Kirim Surat' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Pola karakter berbahaya
const DANGER_PATTERN_SURAT = /(javascript\s*:|vbscript\s*:|data\s*:|expression\s*\(|on\w+\s*=|<\s*script|\$\{|`[^`]*`|[<>])/i;

(function() {
    localStorage.removeItem('draft_surat');

    const judulInput    = document.getElementById('judulInput');
    const tipeSelect    = document.querySelector('select[name="tipe_surat"]');
    const isiTextarea   = document.getElementById('isiSuratInput');
    const judulCount    = document.getElementById('judulCount');
    const isiCount      = document.getElementById('isiSuratCount');
    const isiCountWrap  = document.getElementById('isiSuratCountWrap');
    const form          = document.querySelector('form');

    function updateJudulCounter() {
        if (judulInput && judulCount) judulCount.textContent = judulInput.value.length;
    }
    function updateIsiCounter() {
        if (!isiTextarea) return;
        const len = isiTextarea.value.length;
        if (isiCount) isiCount.textContent = len;
        if (isiCountWrap) {
            if (len < 10) isiCountWrap.style.color = '#ef4444';
            else if (len > 2700) isiCountWrap.style.color = '#f59e0b';
            else isiCountWrap.style.color = '';
        }
    }

    if (judulInput) { judulInput.addEventListener('input', updateJudulCounter); updateJudulCounter(); }
    if (isiTextarea) { isiTextarea.addEventListener('input', updateIsiCounter); updateIsiCounter(); }

    function saveToLocalStorage() {
        localStorage.setItem('draft_surat', JSON.stringify({
            judul_perihal : judulInput ? judulInput.value : '',
            tipe_surat    : tipeSelect ? tipeSelect.value : '',
            isi_surat     : isiTextarea ? isiTextarea.value : ''
        }));
    }

    if (judulInput)  judulInput.addEventListener('input', saveToLocalStorage);
    if (tipeSelect)  tipeSelect.addEventListener('change', saveToLocalStorage);
    if (isiTextarea) isiTextarea.addEventListener('input', saveToLocalStorage);

    if (form) {
        form.addEventListener('submit', function(e) {
            const judulVal = judulInput ? judulInput.value : '';
            const isiVal   = isiTextarea ? isiTextarea.value : '';

            if (DANGER_PATTERN_SURAT.test(judulVal) || DANGER_PATTERN_SURAT.test(isiVal)) {
                e.preventDefault();
                showError('Input mengandung karakter atau pola yang tidak diizinkan (HTML tag, script, dsb). Silakan koreksi dan coba lagi.');
                return;
            }
            const isiLen = isiVal.trim().length;
            if (isiLen < 10) {
                e.preventDefault();
                showError('Isi surat terlalu pendek. Minimal 10 karakter.');
                return;
            }
            if (isiLen > 3000) {
                e.preventDefault();
                showError('Isi surat melebihi 3000 karakter.');
                return;
            }

            localStorage.removeItem('draft_surat');
        });
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    if (typeof initLetterAttachmentUploader === 'function') {
        initLetterAttachmentUploader('letterAttachments', 'attachmentPreviewList');
    }

    document.querySelectorAll('.btn-hapus-lampiran').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            showConfirm('Yakin ingin menghapus lampiran ini? Lampiran akan dihapus secara permanen.', 'Hapus Lampiran', 'Ya, Hapus')
                .then(confirmed => {
                    if (confirmed) window.location.href = url;
                });
        });
    });
});
</script>
<?= $this->endSection() ?>
