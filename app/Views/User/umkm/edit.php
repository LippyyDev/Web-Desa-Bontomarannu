<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<style>
.nav-pills .nav-link {
    color: #198754;
    background-color: #fff;
    border: 1px solid #198754;
}
.nav-pills .nav-link.active {
    background-color: #198754;
    color: #fff;
}
.nav-pills .nav-link:hover:not(.active) {
    background-color: #e8f5e9;
}
.gallery-card {
    border: 1px solid #edf2f7;
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
}
.gallery-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    border-color: #e2e8f0;
}
.gallery-img-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background-color: #f1f5f9;
}
.gallery-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
.gallery-card:hover .gallery-img-wrapper img {
    transform: scale(1.05);
}
.gallery-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cbd5e1;
    background-color: #f8fafc;
}
.gallery-placeholder i {
    font-size: 3rem;
}
.gallery-card-body {
    padding: 0.875rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    min-width: 0;
}
.gallery-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.35rem;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    word-break: break-word;
}
.gallery-desc {
    font-size: 0.75rem;
    color: #64748b;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    margin-bottom: 0.75rem;
    flex-grow: 1;
    word-break: break-word;
}
.gallery-footer {
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}
.gallery-action {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
}
.gallery-action-edit {
    color: #10b981;
    background: #ecfdf5;
}
.gallery-action-edit:hover {
    background: #d1fae5;
    color: #059669;
}
.gallery-action-delete {
    color: #ef4444;
    background: #fef2f2;
}
.gallery-action-delete:hover {
    background: #fee2e2;
    color: #dc2626;
}
</style>

<!-- Page Header Dashboard Style -->
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            UMKM SAYA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Edit <span style="color: #15803d;">Toko</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Edit informasi toko UMKM Anda.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/user/umkm/' . $umkm['id'] . '/hapus') ?>" id="btnHapusToko" class="btn btn-danger" title="Hapus Toko">Hapus</a>
        <a href="<?= base_url('/user/umkm') ?>" class="btn btn-outline-success">Kembali</a>
    </div>
</div>




<form method="POST" action="<?= base_url('/user/umkm/' . $umkm['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Nav Tabs Selector -->
    <ul class="nav nav-pills mb-4 gap-2" id="umkmEditTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-medium px-4" id="info-tab" data-bs-toggle="pill" data-bs-target="#info-pane" type="button" role="tab" aria-controls="info-pane" aria-selected="true"><i class="bi bi-shop me-2"></i>Informasi Toko</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-medium px-4" id="produk-tab" data-bs-toggle="pill" data-bs-target="#produk-pane" type="button" role="tab" aria-controls="produk-pane" aria-selected="false"><i class="bi bi-box-seam me-2"></i>Daftar Produk</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-medium px-4" id="tambah-produk-tab" data-bs-toggle="pill" data-bs-target="#tambah-produk-pane" type="button" role="tab" aria-controls="tambah-produk-pane" aria-selected="false"><i class="bi bi-plus-circle me-2"></i>Tambah Produk</button>
        </li>
    </ul>

    <div class="tab-content" id="umkmEditTabContent">
        <!-- TAB 1: INFORMASI TOKO -->
        <div class="tab-pane fade show active" id="info-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
            
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Foto Toko</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <input type="file" name="foto_toko" id="fotoTokoInput" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="previewFotoToko(this)">
                            <div class="form-text text-muted mt-1">Biarkan kosong jika tidak ingin mengubah foto. Maks. 1MB · JPG/PNG</div>
                            <div id="fotoTokoPreviewContainer" class="mt-2" style="<?= empty($umkm['foto_toko']) ? 'display:none;' : '' ?>">
                                <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                    <div class="card border shadow-sm overflow-hidden mb-0">
                                        <img id="fotoTokoPreview" src="<?= !empty($umkm['foto_toko']) ? base_url($umkm['foto_toko']) : '' ?>" class="w-100 bg-light" style="aspect-ratio: 16/9; object-fit: cover; display: block;" alt="Preview Foto Toko">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Informasi Umum</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Nama Toko <span class="text-danger">*</span></label>
                            <input type="text" name="nama_toko" class="form-control" value="<?= esc($umkm['nama_toko']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Kontak <span class="text-danger">*</span></label>
                            <input type="text" name="kontak" class="form-control" value="<?= esc($umkm['kontak']) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control" rows="4" required><?= esc($umkm['deskripsi']) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="2" required><?= esc($umkm['alamat']) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Link Google Maps</label>
                            <input type="text" name="maps_embed_url" id="inputMaps" class="form-control" value="<?= esc($umkm['maps_embed_url']) ?>" placeholder="https://maps.app.goo.gl/...">
                            <div class="form-text text-muted">Opsional · Tempel link dari Google Maps. Buka Google Maps → klik lokasi → Bagikan → Salin link.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Link E-Commerce</h5>
                    <div id="ecommerceContainer">
                        <?php if (!empty($ecommerce)): ?>
                            <?php foreach ($ecommerce as $e): ?>
                            <div class="ecommerce-row d-flex gap-2 mb-2">
                                <input type="text" name="ecommerce_platform[]" class="form-control w-25" value="<?= esc($e['platform']) ?>" placeholder="Shopee / dll">
                                <input type="url" name="ecommerce_url[]" class="form-control w-100" value="<?= esc($e['url']) ?>" placeholder="https://...">
                                <button type="button" class="btn btn-danger px-3" onclick="removeRow(this)" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="ecommerce-row d-flex gap-2 mb-2">
                                <input type="text" name="ecommerce_platform[]" class="form-control w-25" placeholder="Shopee / dll">
                                <input type="url" name="ecommerce_url[]" class="form-control w-100" placeholder="https://...">
                                <button type="button" class="btn btn-danger px-3" onclick="removeRow(this)" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-link text-success text-decoration-none p-0 mt-2 fw-medium" style="font-size: 0.9rem;" onclick="addEcommerce()">+ Tambah Link</button>
                </div>
            </div>

            <div class="mt-4 mb-4">
                <?php if ($umkm['status'] === 'rejected'): ?>
                <button type="submit" class="btn btn-warning"><i class="bi bi-arrow-repeat me-2"></i>Simpan & Kirim Ulang</button>
                <?php else: ?>
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Simpan Perubahan Toko</button>
                <?php endif; ?>
            </div>
        </div>

        <!-- TAB 2: DAFTAR PRODUK (AJAX Infinite Scroll) -->
        <div class="tab-pane fade" id="produk-pane" role="tabpanel" aria-labelledby="produk-tab" tabindex="0">
            <!-- Counter -->
            <div class="d-flex align-items-center justify-content-between mb-3 mt-1">
                <span class="text-muted small" id="produkCountText">Memuat produk...</span>
            </div>

            <!-- Grid Produk -->
            <div class="row g-3" id="produkGrid"></div>

            <!-- Loading Spinner -->
            <div id="produkLoadingIndicator" class="text-center py-4" style="display:none;">
                <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                <span class="text-muted small">Memuat lebih banyak produk...</span>
            </div>

            <!-- Pesan Kosong -->
            <div id="produkEmptyMessage" class="card mb-4 shadow-sm border-0" style="display:none;">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-box-seam fs-1 d-block mb-3 text-secondary"></i>
                    Belum ada produk di toko ini.
                </div>
            </div>

            <!-- Sentinel (pemicu infinite scroll) -->
            <div id="produkScrollSentinel" style="height:1px;"></div>
        </div>

        <!-- TAB 3: TAMBAH PRODUK -->
        <div class="tab-pane fade" id="tambah-produk-pane" role="tabpanel" aria-labelledby="tambah-produk-tab" tabindex="0">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Tambah Produk Baru</h5>
                    <div id="produkContainer">
                        <p class="text-muted small mb-0">Klik "Tambah Item Produk" untuk menambah produk baru ke toko ini saat menyimpan.</p>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm mt-3" onclick="addProduk()">
                        <i class="bi bi-plus"></i> Tambah Item Produk
                    </button>
                </div>
            </div>

            <div class="mt-4 mb-4">
                <?php if ($umkm['status'] === 'rejected'): ?>
                <button type="submit" class="btn btn-warning"><i class="bi bi-arrow-repeat me-2"></i>Simpan & Kirim Ulang</button>
                <?php else: ?>
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<script>
let produkCount = 0;

// ── Validasi Google Maps URL (frontend) ───────────────────────────────────────
const MAPS_PATTERNS = [
    /maps\.app\.goo\.gl/i,
    /goo\.gl\/maps/i,
    /google\.com\/maps/i,
    /maps\.google\.com/i,
];
function isValidGoogleMapsUrl(val) {
    if (!val || val.trim() === '') return true; // opsional
    return MAPS_PATTERNS.some(p => p.test(val));
}

function previewFotoToko(input) {
    const container = document.getElementById('fotoTokoPreviewContainer');
    const preview   = document.getElementById('fotoTokoPreview');
    const origSrc   = "<?= !empty($umkm['foto_toko']) ? base_url($umkm['foto_toko']) : '' ?>";

    if (input.files && input.files[0]) {
        const file = input.files[0];
        // Validasi via helper global dari upload_validator.js
        if (typeof validateImageFile === 'function' && !validateImageFile(file)) {
            showError('Foto toko tidak valid. Gunakan JPG/PNG, maks. 1MB.');
            input.value = '';
            if (origSrc) { preview.src = origSrc; container.style.display = 'block'; }
            else { container.style.display = 'none'; preview.src = ''; }
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        if (origSrc) {
            preview.src = origSrc;
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
            preview.src = '';
        }
    }
}

// ── Validasi Maps URL saat submit ────────────────────────────────────────────
document.getElementById('umkmForm').addEventListener('submit', function(e) {
    const mapsVal = document.getElementById('inputMaps')?.value.trim() ?? '';
    if (!isValidGoogleMapsUrl(mapsVal)) {
        e.preventDefault();
        showError('Link Google Maps tidak valid. Gunakan link dari Google Maps (maps.app.goo.gl, google.com/maps, dsb.).');
    }
});

function addEcommerce() {
    const container = document.getElementById('ecommerceContainer');
    const div = document.createElement('div');
    div.className = 'ecommerce-row d-flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="ecommerce_platform[]" class="form-control w-25" placeholder="Shopee / dll">
        <input type="url" name="ecommerce_url[]" class="form-control w-100" placeholder="https://...">
        <button type="button" class="btn btn-danger px-3" onclick="removeRow(this)" title="Hapus"><i class="bi bi-trash"></i></button>
    `;
    container.appendChild(div);
}

function removeRow(btn) { btn.closest('.ecommerce-row').remove(); }

function addProduk() {
    const container = document.getElementById('produkContainer');
    const msg = container.querySelector('p');
    if (msg) msg.remove();
    const idx = produkCount++;
    const div = document.createElement('div');
    div.className = 'produk-item card shadow-sm border-0 mb-4 bg-light';
    div.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                <h6 class="fw-bold text-success mb-0">Produk Baru</h6>
                <button type="button" class="btn btn-danger px-3" onclick="this.closest('.produk-item').remove()" title="Hapus Produk"><i class="bi bi-trash"></i></button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="produk_nama[]" class="form-control" placeholder="Nama produk">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Harga (Rp)</label>
                    <input type="text" name="produk_harga[]" class="form-control" placeholder="Contoh: 50000">
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium small">Deskripsi Produk</label>
                    <textarea name="produk_deskripsi[]" class="form-control" rows="3" placeholder="Jelaskan keunggulan produk ini..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium small">Foto Produk (bisa pilih banyak)</label>
                    <input type="file" id="produk_gambar_${idx}" name="produk_gambar_${idx}[]" class="form-control" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div id="preview_gambar_${idx}" class="row g-2 mt-2"></div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    if (typeof initMediaUploader === 'function') {
        initMediaUploader(`produk_gambar_${idx}`, `preview_gambar_${idx}`);
    }
}

// ─── AJAX Infinite Scroll: Tab Daftar Produk ────────────────────────────────
(function () {
    const siteUrl   = '<?= rtrim(base_url(), '/') ?>';
    const apiUrl    = '<?= base_url('/user/umkm/' . $umkm['id'] . '/produk-api') ?>';
    const editBase  = '<?= base_url('/user/umkm/produk/') ?>';
    const hapusBase = '<?= base_url('/user/umkm/produk/') ?>';

    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    const produkGrid = document.getElementById('produkGrid');
    const loadingEl  = document.getElementById('produkLoadingIndicator');
    const emptyEl    = document.getElementById('produkEmptyMessage');
    const countText  = document.getElementById('produkCountText');
    const sentinel   = document.getElementById('produkScrollSentinel');

    let currentPage = 0;
    let isLoading   = false;
    let hasMore     = true;
    let totalProduk = 0;
    let initialized = false;

    function escapeHtml(str) {
        return (str || '').toString()
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function loadNextPage() {
        if (isLoading || !hasMore) return;
        isLoading = true;
        loadingEl.style.display = 'block';

        const formData = new URLSearchParams();
        formData.append('page', currentPage + 1);
        formData.append(csrfHeaderName, getCsrfHash());

        fetch(apiUrl, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) { showError('Gagal memuat produk.'); return; }

            totalProduk = data.total;
            hasMore     = data.has_more;
            currentPage = data.page;

            if (data.data && data.data.length > 0) {
                emptyEl.style.display = 'none';
                renderProduk(data.data);
            } else if (currentPage === 1) {
                emptyEl.style.display = 'block';
            }

            const shown = produkGrid.querySelectorAll('.col-produk').length;
            countText.textContent = totalProduk > 0
                ? `Menampilkan ${shown} dari ${totalProduk} produk`
                : 'Belum ada produk';
        })
        .catch(() => showError('Terjadi kesalahan saat memuat produk.'))
        .finally(() => {
            isLoading = false;
            loadingEl.style.display = 'none';
        });
    }

    function renderProduk(list) {
        list.forEach(p => {
            const imgHtml = p.gambar_path
                ? `<img src="${siteUrl}/${escapeHtml(p.gambar_path)}" alt="${escapeHtml(p.nama_produk)}" loading="lazy"
                       onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\\'gallery-placeholder\\'><i class=\\'bi bi-images\\'></i></div>'">`
                : `<div class="gallery-placeholder"><i class="bi bi-images"></i></div>`;

            const hargaHtml = p.harga_fmt
                ? `<div class="text-success fw-bold mb-2 small">${escapeHtml(p.harga_fmt)}</div>`
                : '';

            const deskHtml = p.deskripsi
                ? `<p class="gallery-desc">${escapeHtml(p.deskripsi)}</p>`
                : `<p class="gallery-desc text-muted fst-italic">Tidak ada deskripsi</p>`;

            const col = document.createElement('div');
            col.className = 'col-6 col-sm-4 col-md-3 col-lg-2 col-produk';
            col.innerHTML = `
                <div class="gallery-card h-100">
                    <div class="gallery-img-wrapper">${imgHtml}</div>
                    <div class="gallery-card-body">
                        <h5 class="gallery-title">${escapeHtml(p.nama_produk)}</h5>
                        ${hargaHtml}
                        ${deskHtml}
                        <div class="gallery-footer mt-auto">
                            <a href="${editBase}${p.id}/edit" class="gallery-action gallery-action-edit">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <a href="${hapusBase}${p.id}/hapus" class="gallery-action gallery-action-delete btn-hapus-produk"
                               data-nama="${escapeHtml(p.nama_produk)}">
                                <i class="bi bi-trash3"></i> Hapus
                            </a>
                        </div>
                    </div>
                </div>`;
            col.querySelector('.btn-hapus-produk').addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                const nama = this.dataset.nama;
                showConfirm(`Hapus produk "${nama}" beserta semua gambarnya?`, 'Hapus Produk', 'Ya, Hapus')
                    .then(ok => { if (ok) window.location.href = href; });
            });
            produkGrid.appendChild(col);
        });
    }

    // IntersectionObserver untuk infinite scroll
    const observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting && hasMore && !isLoading) {
            loadNextPage();
        }
    }, { rootMargin: '200px' });
    observer.observe(sentinel);

    // Muat batch pertama ketika tab Daftar Produk diklik
    document.getElementById('produk-tab').addEventListener('shown.bs.tab', function () {
        if (!initialized) {
            initialized = true;
            loadNextPage();
        }
    });
})();

// Hapus Toko — SweetAlert confirm
document.getElementById('btnHapusToko').addEventListener('click', function (e) {
    e.preventDefault();
    const href = this.getAttribute('href');
    showConfirm('Hapus toko ini secara permanen? Semua produk dan data terkait akan ikut terhapus.', 'Hapus Toko', 'Ya, Hapus')
        .then(ok => { if (ok) window.location.href = href; });
});
</script>
<?= $this->endSection() ?>
