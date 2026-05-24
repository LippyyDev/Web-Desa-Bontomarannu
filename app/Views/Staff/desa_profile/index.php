<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            MANAJEMEN DESA
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Profil <span style="color: #15803d;">Desa</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Perbarui informasi identitas dan profil desa.</p>
    </div>
</div>

<form id="profileForm" method="post" action="<?= base_url('/staff/desa') ?>">
    <?= csrf_field() ?>

    <!-- CARD 1: Informasi Dasar -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Informasi Dasar</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Desa</label>
                    <input type="text" class="form-control" name="nama_desa" value="<?= esc($profile['nama_desa'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Kecamatan</label>
                    <input type="text" class="form-control" name="kecamatan" value="<?= esc($profile['kecamatan'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Kabupaten</label>
                    <input type="text" class="form-control" name="kabupaten" value="<?= esc($profile['kabupaten'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Provinsi</label>
                    <input type="text" class="form-control" name="provinsi" value="<?= esc($profile['provinsi'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Kode Pos</label>
                    <input type="text" class="form-control" name="kode_pos" value="<?= esc($profile['kode_pos'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Tahun Berdiri</label>
                    <input type="number" class="form-control" name="tahun_berdiri" value="<?= esc($profile['tahun_berdiri'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Jumlah Penduduk</label>
                    <input type="number" class="form-control" name="jumlah_penduduk" value="<?= esc($profile['jumlah_penduduk'] ?? 0) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Jumlah KK</label>
                    <input type="number" class="form-control" name="jumlah_kk" value="<?= esc($profile['jumlah_kk'] ?? 0) ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- CARD 2: Kontak Desa -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Kontak Desa</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Alamat Kantor</label>
                    <textarea class="form-control" name="alamat_kantor" rows="2" placeholder="Jl. Contoh No. 1, Desa Bontomarannu..."><?= esc($profile['alamat_kantor'] ?? '') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nomor WhatsApp</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-whatsapp text-success"></i></span>
                        <input type="text" class="form-control" name="kontak_wa" value="<?= esc($profile['kontak_wa'] ?? '') ?>" placeholder="628xxxxxxxxxx">
                    </div>
                    <div class="form-text">Format: 628xxxxxxxxxx (tanpa +, tanpa spasi)</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope text-primary"></i></span>
                        <input type="email" class="form-control" name="kontak_email" value="<?= esc($profile['kontak_email'] ?? '') ?>" placeholder="email@desa.go.id">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Facebook</label>
                    <div class="input-group">
                        <span class="input-group-text" style="color:#1877F2;"><i class="bi bi-facebook"></i></span>
                        <input type="text" class="form-control" name="kontak_facebook" value="<?= esc($profile['kontak_facebook'] ?? '') ?>" placeholder="https://facebook.com/...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Instagram</label>
                    <div class="input-group">
                        <span class="input-group-text" style="color:#E1306C;"><i class="bi bi-instagram"></i></span>
                        <input type="text" class="form-control" name="kontak_instagram" value="<?= esc($profile['kontak_instagram'] ?? '') ?>" placeholder="https://instagram.com/...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">YouTube</label>
                    <div class="input-group">
                        <span class="input-group-text" style="color:#FF0000;"><i class="bi bi-youtube"></i></span>
                        <input type="text" class="form-control" name="kontak_youtube" value="<?= esc($profile['kontak_youtube'] ?? '') ?>" placeholder="https://youtube.com/@...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD 3: Visi & Misi -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Visi & Misi</h5>
            <div class="row g-3">
                <div class="col-12">
                     <label class="form-label fw-medium">Visi</label>
                     <textarea class="form-control" name="visi" rows="3"><?= esc($profile['visi'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                     <label class="form-label fw-medium">Misi</label>
                     <textarea class="form-control" name="misi" rows="3"><?= esc($profile['misi'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD 3: Sejarah & Lokasi -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Sejarah & Lokasi</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Sejarah Desa</label>
                    <textarea class="form-control" name="sejarah_desa" rows="5"><?= esc($profile['sejarah_desa'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Deskripsi Lokasi</label>
                    <input type="text" class="form-control" name="deskripsi_lokasi" value="<?= esc($profile['deskripsi_lokasi'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- TOMBOL SIMPAN -->
    <div class="mt-4 mb-4">
        <button class="btn btn-success" type="submit">
            Simpan Perubahan
        </button>
    </div>
</form>

<?= $this->endSection() ?>
