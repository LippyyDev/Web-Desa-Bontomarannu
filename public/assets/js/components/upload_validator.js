/**
 * upload_validator.js
 *
 * Reusable frontend helper untuk validasi dan manajemen multi-file upload.
 * Di-load via layout.php sehingga tersedia di semua halaman.
 *
 * Exports (global):
 *   - validateImageFile(file)                          → validasi gambar saja (JPG/PNG)
 *   - initMediaUploader(inputId, previewId)            → uploader gambar dengan preview visual
 *   - validateLetterAttachment(file)                   → validasi lampiran surat, return {ok, error}
 *   - initLetterAttachmentUploader(inputId, previewId) → uploader list file lampiran surat + hapus
 *
 * @see app/Helpers/upload_helper.php  → backend equivalent
 * @see CLAUDE.md §13.1               → dokumentasi pola validasi
 */

// ─── Konstanta Gambar ────────────────────────────────────────────────────────
const UPLOAD_ALLOWED_TYPES  = ['image/jpeg', 'image/png'];
const UPLOAD_ALLOWED_EXTS   = ['jpg', 'jpeg', 'png'];
const UPLOAD_MAX_SIZE_BYTES = 1 * 1024 * 1024; // 1 MB

// ─── Konstanta Lampiran Surat ────────────────────────────────────────────────
const LETTER_ALLOWED_EXTS  = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
const LETTER_ALLOWED_TYPES = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'image/jpeg',
    'image/png',
];
const LETTER_MAX_SIZE_BYTES = 1 * 1024 * 1024; // 1 MB

// ─── Fungsi Validasi Gambar ──────────────────────────────────────────────────

/**
 * Validasi satu file gambar di sisi frontend.
 * - Extension: jpg, jpeg, png | MIME: image/jpeg, image/png | Ukuran: ≤ 1MB
 *
 * @param {File} file
 * @returns {boolean}
 */
function validateImageFile(file) {
    const ext = file.name.split('.').pop().toLowerCase();
    if (!UPLOAD_ALLOWED_EXTS.includes(ext) || !UPLOAD_ALLOWED_TYPES.includes(file.type)) {
        return false;
    }
    if (file.size > UPLOAD_MAX_SIZE_BYTES) {
        return false;
    }
    return true;
}

// ─── Fungsi Validasi Lampiran Surat ─────────────────────────────────────────

/**
 * Validasi satu file lampiran surat di sisi frontend.
 * Sesuai validate_letter_attachment() di backend:
 *   - Extension: pdf, doc, docx, jpg, jpeg, png
 *   - MIME type : sesuai ekstensi
 *   - Ukuran    : ≤ 1MB
 *
 * @param {File} file
 * @returns {{ ok: boolean, error: string|null }}
 */
function validateLetterAttachment(file) {
    const ext = file.name.split('.').pop().toLowerCase();

    if (!LETTER_ALLOWED_EXTS.includes(ext)) {
        return { ok: false, error: `Format "${ext}" tidak didukung. Hanya PDF, Word (.doc/.docx), JPG, JPEG, atau PNG.` };
    }
    if (file.type && !LETTER_ALLOWED_TYPES.includes(file.type)) {
        return { ok: false, error: `Tipe MIME tidak valid untuk "${file.name}".` };
    }
    if (file.size > LETTER_MAX_SIZE_BYTES) {
        const sizeMb = (file.size / 1048576).toFixed(1);
        return { ok: false, error: `"${file.name}" terlalu besar (${sizeMb}MB). Maksimal 1MB per file.` };
    }
    return { ok: true, error: null };
}

// ─── Helper Internal ─────────────────────────────────────────────────────────

function _getAttachmentIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    if (ext === 'pdf') return 'bi-file-earmark-pdf text-danger';
    if (['doc', 'docx'].includes(ext)) return 'bi-file-earmark-word text-primary';
    if (['jpg', 'jpeg', 'png'].includes(ext)) return 'bi-file-image text-info';
    return 'bi-file-earmark text-secondary';
}

function _truncateFilename(name, max = 35) {
    if (name.length <= max) return name;
    const ext  = name.split('.').pop();
    const base = name.substring(0, name.lastIndexOf('.'));
    return base.substring(0, max - ext.length - 4) + '....' + ext;
}

// ─── Uploader Lampiran Surat ─────────────────────────────────────────────────

/**
 * Inisialisasi uploader lampiran surat dengan validasi frontend.
 * Menampilkan daftar file (list item) + tombol hapus per file.
 *
 * @param {string} inputId    - ID elemen <input type="file">
 * @param {string} previewId  - ID elemen container preview list
 */
function initLetterAttachmentUploader(inputId, previewId) {
    const fileInput  = document.getElementById(inputId);
    const previewDiv = document.getElementById(previewId);
    if (!fileInput || !previewDiv) return;

    let selectedFiles = [];

    function syncInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f.file));
        fileInput.files = dt.files;
    }

    function renderList() {
        previewDiv.innerHTML = '';
        selectedFiles.forEach((fileObj, idx) => {
            const icon        = _getAttachmentIcon(fileObj.file.name);
            const displayName = _truncateFilename(fileObj.file.name);

            const item = document.createElement('div');
            item.className = 'd-flex align-items-center justify-content-between p-2 border rounded bg-light';
            item.innerHTML = `
                <div class="d-flex align-items-center gap-2 text-truncate" style="max-width:85%;">
                    <i class="bi ${icon} fs-5"></i>
                    <span class="text-truncate" title="${fileObj.file.name}">${displayName}</span>
                </div>
                <button type="button"
                    class="btn btn-sm btn-danger p-1 d-flex align-items-center justify-content-center"
                    style="width:28px;height:28px;border-radius:6px;"
                    title="Hapus">
                    <i class="bi bi-trash m-0" style="font-size:1rem;"></i>
                </button>
            `;
            item.querySelector('button').addEventListener('click', () => {
                selectedFiles.splice(idx, 1);
                syncInputFiles();
                renderList();
            });
            previewDiv.appendChild(item);
        });
    }

    fileInput.addEventListener('change', function () {
        const newFiles = Array.from(fileInput.files);
        const rejected = [];

        newFiles.forEach(file => {
            const result = validateLetterAttachment(file);
            if (!result.ok) {
                rejected.push(result.error);
                return;
            }
            // Cek duplikat
            const isDuplicate = selectedFiles.some(f =>
                f.file.name === file.name &&
                f.file.size === file.size &&
                f.file.lastModified === file.lastModified
            );
            if (!isDuplicate) {
                selectedFiles.push({ file });
            }
        });

        if (rejected.length > 0) {
            const msg = 'File dilewati:\n' + rejected.join('\n');
            if (typeof showError === 'function') {
                showError(msg);
            } else {
                alert(msg);
            }
        }

        syncInputFiles();
        renderList();
    });
}

// ─── Uploader Gambar (Gallery) ───────────────────────────────────────────────

/**
 * Inisialisasi media uploader dengan perilaku akumulatif:
 *   - Upload pertama & selanjutnya menambah (tidak menimpa)
 *   - File tidak valid di-skip (bukan tolak semua)
 *   - Tiap preview punya tombol hapus
 *
 * @param {string} inputId    - ID elemen <input type="file">
 * @param {string} previewId  - ID elemen container preview
 */
function initMediaUploader(inputId, previewId) {
    const mediaInput   = document.getElementById(inputId);
    const mediaPreview = document.getElementById(previewId);

    if (!mediaInput || !mediaPreview) return;

    let selectedFiles = [];
    let fileIdCounter = 0;

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f.file));
        mediaInput.files = dt.files;
    }

    function renderPreview() {
        mediaPreview.innerHTML = '';
        selectedFiles.forEach((fileObj) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-6 col-sm-4 col-md-3 col-xl-2';
                col.setAttribute('data-file-id', fileObj.id);
                col.innerHTML = `
                    <div class="card border shadow-sm position-relative overflow-hidden mb-0">
                        <button type="button"
                            class="btn btn-danger position-absolute"
                            style="top: 6px; right: 6px; z-index: 1000; width: 26px; height: 26px; padding: 0; line-height: 1; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                            onclick="__removeFile_${inputId}('${fileObj.id}')"
                            title="Hapus">
                            <i class="bi bi-trash" style="font-size:14px;"></i>
                        </button>
                        <img src="${e.target.result}"
                            class="w-100 bg-light"
                            style="aspect-ratio: 16/9; object-fit: cover; display: block;"
                            alt="preview">
                    </div>
                `;
                mediaPreview.appendChild(col);
            };
            reader.readAsDataURL(fileObj.file);
        });
    }

    // Ekspos fungsi hapus ke global scope dengan namespace unik per instance
    const removeFnName = `__removeFile_${inputId}`;
    window[removeFnName] = function(fileId) {
        selectedFiles = selectedFiles.filter(f => f.id !== fileId);
        updateFileInput();
        renderPreview();
    };

    mediaInput.addEventListener('change', function(e) {
        const newFiles = Array.from(e.target.files);
        const rejected = [];

        newFiles.forEach(file => {
            if (!validateImageFile(file)) {
                rejected.push(file.name);
                return; // skip — jangan tolak semua
            }
            // Cek duplikat
            const isDuplicate = selectedFiles.some(f =>
                f.file.name === file.name &&
                f.file.size === file.size &&
                f.file.lastModified === file.lastModified
            );
            if (!isDuplicate) {
                selectedFiles.push({ id: 'file_' + (fileIdCounter++), file });
            }
        });

        if (rejected.length > 0) {
            showError('File dilewati (maks 1MB, format JPG/PNG): ' + rejected.join(', '));
        }

        updateFileInput();
        renderPreview();
    });
}
