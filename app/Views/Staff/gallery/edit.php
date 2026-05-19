<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Edit <span style="color: #15803d;">Album</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Perbarui informasi album dan media.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-danger" id="btnHapusAlbum" title="Hapus Album">
            <i class="bi bi-trash3 me-1"></i> Hapus
        </button>
        <a href="<?= base_url('/staff/galeri') ?>" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<form method="post" enctype="multipart/form-data" action="<?= base_url('/staff/galeri/' . $album['id']) ?>">
    <?= csrf_field() ?>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Dasar</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Album</label>
                    <input type="text" class="form-control" name="nama_album" value="<?= esc($album['nama_album']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Tanggal & Waktu</label>
                    <input type="datetime-local" class="form-control" name="tanggal_waktu" value="<?= date('Y-m-d\TH:i', strtotime($album['tanggal_waktu'])) ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3"><?= esc($album['deskripsi'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Media</h5>
            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label fw-medium">Thumbnail (opsional)</label>
                    <input type="file" class="form-control" id="thumbnailInput" name="thumbnail" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div id="thumbnailPreview" class="mt-2">
                        <?php if (!empty($album['thumbnail'])): ?>
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card border shadow-sm overflow-hidden mb-0">
                                    <img src="<?= base_url($album['thumbnail']) ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="thumbnail">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Tambah Foto</label>
                    <input type="file" class="form-control" id="mediaInput" name="media[]" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div id="mediaPreview" class="row g-2 mt-2"></div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Link Video YouTube</label>
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
            <i class="bi bi-save me-1"></i> Update Album
        </button>
    </div>
</form>

<?php if (!empty($media)): ?>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4 fw-bold">Media Saat Ini</h5>
        <div class="row g-3">
            <?php foreach ($media as $m): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card border shadow-sm position-relative overflow-hidden mb-0">
                        <button type="button" class="btn btn-danger position-absolute btn-hapus-media" data-url="<?= base_url('/staff/galeri/media/' . $m['id'] . '/hapus') ?>" style="top: 6px; right: 6px; z-index: 1000; width: 26px; height: 26px; padding: 0; line-height: 1; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" title="Hapus Media">
                            <i class="bi bi-trash" style="font-size: 14px;"></i>
                        </button>
                        <?php if ($m['media_type'] === 'foto'): ?>
                            <img src="<?= base_url($m['media_path']) ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="media">
                        <?php else: ?>
                            <iframe src="<?= esc($m['embed_url'] ?? $m['media_path']) ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; border: 0; display: block;" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
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
        // Preview Thumbnail
        const thumbnailInput = document.getElementById('thumbnailInput');
        const thumbnailPreview = document.getElementById('thumbnailPreview');
        const originalThumbnailHtml = thumbnailPreview.innerHTML;
        
        thumbnailInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!validateImageFile(file)) {
                    thumbnailInput.value = '';
                    showError('Format tidak didukung atau ukuran melebihi 1MB (Hanya JPG/PNG).');
                    thumbnailPreview.innerHTML = originalThumbnailHtml;
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailPreview.innerHTML = `
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card border shadow-sm overflow-hidden mb-0">
                                <img src="${e.target.result}" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="thumbnail">
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                thumbnailPreview.innerHTML = originalThumbnailHtml;
            }
        });

        // Preview Media Foto — dikelola oleh upload_validator.js
        initMediaUploader('mediaInput', 'mediaPreview');

        // Video Links
        const list = document.getElementById('video-list');
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

        // Validasi submit
        document.querySelector('form').addEventListener('submit', function(e) {
            let hasYtError = false;
            document.querySelectorAll('input[name="video_links[]"]').forEach(input => {
                if (!applyYoutubeValidation(input)) hasYtError = true;
            });
            if (hasYtError) {
                e.preventDefault();
                showError('Terdapat link video yang tidak valid. Hanya link YouTube yang diperbolehkan.');
            }
        });

        // Hapus album via SweetAlert
        const btnHapusAlbum = document.getElementById('btnHapusAlbum');
        if (btnHapusAlbum) {
            btnHapusAlbum.addEventListener('click', function () {
                showConfirm('Album beserta semua media di dalamnya akan dihapus permanen.', 'Hapus Album?', 'Ya, Hapus', 'Batal').then(confirmed => {
                    if (confirmed) window.location.href = '<?= base_url('/staff/galeri/' . $album['id'] . '/hapus') ?>';
                });
            });
        }

        // Hapus media via SweetAlert
        document.querySelectorAll('.btn-hapus-media').forEach(btn => {
            btn.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                showConfirm('Media ini akan dihapus permanen dari album.', 'Hapus Media?', 'Ya, Hapus', 'Batal').then(confirmed => {
                    if (confirmed) window.location.href = url;
                });
            });
        });
    });
</script>
<?= $this->endSection() ?>
