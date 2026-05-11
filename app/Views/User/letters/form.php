<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
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
            Kembali
        </a>
    </div>
</div>



<div class="card">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data" action="<?= isset($letter) ? base_url('/user/surat/' . $letter['id']) : base_url('/user/surat') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Judul / Perihal</label>
                <input type="text" class="form-control" name="judul_perihal" value="<?= set_value('judul_perihal', $letter['judul_perihal'] ?? '') ?>" required>
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
                <textarea class="form-control" name="isi_surat" rows="6" required><?= set_value('isi_surat', $letter['isi_surat'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Lampiran <span class="text-muted small">(opsional — PDF, Word, JPG/PNG · maks. 1MB per file)</span></label>
                <input type="file" class="form-control" name="attachments[]" id="letterAttachments" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <div id="attachmentPreviewList" class="mt-2 d-flex flex-column gap-2"></div>
            </div>
            <div class="mt-4">
                <button class="btn btn-success" type="submit">
                    <?= isset($letter) ? 'Update Surat' : 'Kirim Surat' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    localStorage.removeItem('draft_surat');

    const judulInput  = document.querySelector('input[name="judul_perihal"]');
    const tipeSelect  = document.querySelector('select[name="tipe_surat"]');
    const isiTextarea = document.querySelector('textarea[name="isi_surat"]');
    const form        = document.querySelector('form');

    function saveToLocalStorage() {
        localStorage.setItem('draft_surat', JSON.stringify({
            judul_perihal : judulInput.value,
            tipe_surat    : tipeSelect.value,
            isi_surat     : isiTextarea.value
        }));
    }

    judulInput.addEventListener('input', saveToLocalStorage);
    tipeSelect.addEventListener('change', saveToLocalStorage);
    isiTextarea.addEventListener('input', saveToLocalStorage);

    form.addEventListener('submit', function() {
        localStorage.removeItem('draft_surat');
    });
})();

document.addEventListener('DOMContentLoaded', function () {
    if (typeof initLetterAttachmentUploader === 'function') {
        initLetterAttachmentUploader('letterAttachments', 'attachmentPreviewList');
    }
});
</script>
<?= $this->endSection() ?>
