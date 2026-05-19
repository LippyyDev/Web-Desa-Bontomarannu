<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Tambah <span style="color: #15803d;">Berita</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Tulis berita desa beserta media pendukung.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/staff/berita') ?>" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<form method="post" enctype="multipart/form-data" action="<?= base_url('/staff/berita') ?>">
    <?= csrf_field() ?>

    <!-- Informasi Dasar -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Dasar</h5>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-medium">Judul Berita</label>
                    <input type="text" class="form-control" name="judul" required placeholder="Masukkan judul berita...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Tanggal &amp; Waktu</label>
                    <input type="datetime-local" class="form-control" name="tanggal_waktu">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Isi Berita</label>
                    <div class="quill-wrapper">
                        <div id="editor"></div>
                    </div>
                    <textarea name="isi" style="display: none;"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Media -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Media</h5>
            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label fw-medium">Thumbnail Berita <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="file" class="form-control" id="thumbnailInput" name="thumbnail" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div class="form-text">Format JPG/PNG, maksimal 1MB.</div>
                    <div id="thumbnailPreview" class="mt-2"></div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Upload Foto <span class="text-muted fw-normal">(opsional, bisa pilih beberapa)</span></label>
                    <input type="file" class="form-control" id="mediaInput" name="media[]" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div class="form-text">Format JPG/PNG, maksimal 1MB per foto.</div>
                    <div id="mediaPreview" class="row g-2 mt-2"></div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Link Video YouTube <span class="text-muted fw-normal">(opsional)</span></label>
                    <div id="video-list"></div>
                    <button type="button" class="btn btn-link text-success text-decoration-none p-0 mt-2 fw-medium" style="font-size: 0.9rem;" id="add-video">
                        + Tambah Link
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-4">
        <button class="btn btn-success" type="submit">
            <i class="bi bi-save me-1"></i> Simpan Berita
        </button>
    </div>
</form>

<!-- Quill Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<style>
    .quill-wrapper { margin-bottom: 0; }
    .quill-wrapper #editor { min-height: 280px; max-height: 500px; overflow-y: auto; }
    .quill-wrapper .ql-container.ql-snow { border: 1px solid #ced4da; border-bottom-left-radius: 0.375rem; border-bottom-right-radius: 0.375rem; }
    .quill-wrapper .ql-toolbar.ql-snow { border: 1px solid #ced4da; border-bottom: none; border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem; }
</style>
<!-- Quill Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>

<script>
// ── Validasi URL YouTube ──────────────────────────────────────
function isValidYoutubeUrl(url) {
    url = url.trim();
    if (url === '') return true;
    try {
        const parsed = new URL(url);
        const host   = parsed.hostname.toLowerCase();
        const path   = parsed.pathname;
        if (host === 'youtu.be' || host === 'www.youtu.be') {
            const id = path.replace(/^\//, '');
            return id !== '' && /^[a-zA-Z0-9_\-]{1,20}$/.test(id);
        }
        if (host === 'youtube.com' || host === 'www.youtube.com') {
            if (path.startsWith('/watch')) {
                const v = parsed.searchParams.get('v');
                return v !== null && /^[a-zA-Z0-9_\-]{1,20}$/.test(v);
            }
            if (/^\/shorts\/[a-zA-Z0-9_\-]{1,20}/.test(path)) return true;
            if (/^\/embed\/[a-zA-Z0-9_\-]{1,20}/.test(path)) return true;
        }
        return false;
    } catch (e) { return false; }
}

function applyYoutubeValidation(input) {
    const val      = input.value.trim();
    const feedback = input.nextElementSibling?.classList.contains('yt-feedback')
        ? input.nextElementSibling : null;
    if (val === '') {
        input.classList.remove('is-invalid', 'is-valid');
        if (feedback) feedback.textContent = '';
        return true;
    }
    if (isValidYoutubeUrl(val)) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        if (feedback) feedback.textContent = '';
        return true;
    } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        if (feedback) feedback.textContent = 'Link tidak valid. Gunakan link YouTube yang benar (youtube.com/watch, youtu.be, /shorts, /embed).';
        return false;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // ── Quill Editor ──────────────────────────────────────────
    const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }],
                [{ 'align': [] }],
                ['clean'], ['link', 'image', 'video']
            ]
        }
    });

    const form     = document.querySelector('form');
    const textarea = document.querySelector('textarea[name="isi"]');

    form.addEventListener('submit', function (e) {
        if (!quill.getText().trim()) {
            e.preventDefault();
            showError('Isi Berita tidak boleh kosong!');
            return;
        }
        // Validasi YouTube
        let hasYtError = false;
        document.querySelectorAll('input[name="video_links[]"]').forEach(input => {
            if (!applyYoutubeValidation(input)) hasYtError = true;
        });
        if (hasYtError) {
            e.preventDefault();
            showError('Terdapat link video yang tidak valid. Hanya link YouTube yang diperbolehkan.');
            return;
        }
        textarea.value = quill.root.innerHTML;
    });

    // ── Thumbnail Preview ──────────────────────────────────────
    const thumbnailInput   = document.getElementById('thumbnailInput');
    const thumbnailPreview = document.getElementById('thumbnailPreview');

    thumbnailInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) { thumbnailPreview.innerHTML = ''; return; }

        if (!validateImageFile(file)) {
            thumbnailInput.value       = '';
            thumbnailPreview.innerHTML = '';
            showError('Format tidak didukung atau ukuran melebihi 1MB (Hanya JPG/PNG).');
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            thumbnailPreview.innerHTML = `
                <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                    <div class="card border shadow-sm overflow-hidden mb-0">
                        <img src="${e.target.result}" class="w-100 bg-light"
                            style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="thumbnail">
                    </div>
                </div>`;
        };
        reader.readAsDataURL(file);
    });

    // ── Multi-foto Preview — via upload_validator.js ───────────
    initMediaUploader('mediaInput', 'mediaPreview');

    // ── Video Links ────────────────────────────────────────────
    const list   = document.getElementById('video-list');
    const addBtn = document.getElementById('add-video');

    const addField = (value = '') => {
        const group = document.createElement('div');
        group.className = 'd-flex flex-column gap-1 mb-2 video-item';
        group.innerHTML = `
            <div class="d-flex gap-2">
                <input type="text" class="form-control" name="video_links[]" placeholder="https://youtube.com/watch?v=... atau https://youtu.be/..." value="${value}">
                <button type="button" class="btn btn-danger px-3" title="Hapus"><i class="bi bi-trash"></i></button>
            </div>
            <div class="invalid-feedback d-block yt-feedback" style="margin-top:-4px;"></div>
        `;
        const input = group.querySelector('input');
        group.querySelector('button').addEventListener('click', () => group.remove());
        input.addEventListener('blur', () => applyYoutubeValidation(input));
        input.addEventListener('input', () => {
            if (input.classList.contains('is-invalid') || input.classList.contains('is-valid')) {
                applyYoutubeValidation(input);
            }
        });
        list.appendChild(group);
        if (value !== '') applyYoutubeValidation(input);
    };

    addBtn.addEventListener('click', () => addField(''));
    addField('');
});
</script>
<?= $this->endSection() ?>
