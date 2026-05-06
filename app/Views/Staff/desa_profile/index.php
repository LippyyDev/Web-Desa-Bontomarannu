<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Profil Desa</h4>
        <div class="text-muted small">Perbarui informasi profil desa.</div>
    </div>
    <div class="page-header-icon">
        <i class="bi bi-building"></i>
    </div>
</div>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form id="profileForm" method="post" action="<?= base_url('/staff/desa') ?>">
            <?= csrf_field() ?>
            
            <!-- SECTION 1: PROFIL DESA -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Desa</label>
                            <input type="text" class="form-control" name="nama_desa" value="<?= esc($profile['nama_desa'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" class="form-control" name="kecamatan" value="<?= esc($profile['kecamatan'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kabupaten</label>
                            <input type="text" class="form-control" name="kabupaten" value="<?= esc($profile['kabupaten'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Provinsi</label>
                            <input type="text" class="form-control" name="provinsi" value="<?= esc($profile['provinsi'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" class="form-control" name="kode_pos" value="<?= esc($profile['kode_pos'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun Berdiri</label>
                            <input type="number" class="form-control" name="tahun_berdiri" value="<?= esc($profile['tahun_berdiri'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah Penduduk</label>
                            <input type="number" class="form-control" name="jumlah_penduduk" value="<?= esc($profile['jumlah_penduduk'] ?? 0) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah KK</label>
                            <input type="number" class="form-control" name="jumlah_kk" value="<?= esc($profile['jumlah_kk'] ?? 0) ?>">
                        </div>
                        
                        <div class="col-12 mt-4">
                             <label class="form-label fw-bold">Visi</label>
                             <textarea class="form-control" name="visi" rows="3"><?= esc($profile['visi'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                             <label class="form-label fw-bold">Misi</label>
                             <textarea class="form-control" name="misi" rows="3"><?= esc($profile['misi'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <label class="form-label fw-bold">Sejarah Desa</label>
                            <textarea class="form-control" name="sejarah_desa" rows="5"><?= esc($profile['sejarah_desa'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="col-12 mt-4">
                            <label class="form-label fw-bold">Deskripsi Lokasi</label>
                            <input type="text" class="form-control" name="deskripsi_lokasi" value="<?= esc($profile['deskripsi_lokasi'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                <button class="btn btn-primary btn-lg" type="submit">
                    <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
<?= $this->endSection() ?>


