<?= $this->extend('Staff/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h4>Geografi Desa</h4>
        <div class="text-muted small">Kelola informasi geografis desa.</div>
    </div>
    <div class="page-header-icon">
        <i class="bi bi-map"></i>
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

<form id="geografiForm" method="post" action="<?= base_url('/staff/geografi') ?>">>
    <?= csrf_field() ?>
    
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Luas Wilayah</label>
                    <input type="text" class="form-control" name="luas_wilayah" value="<?= esc($geografi['luas_wilayah'] ?? '') ?>" placeholder="Contoh: 15.4 km²">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link Google Maps Embed</label>
                    <input type="text" class="form-control" name="maps_embed_url" value="<?= esc($geografi['maps_embed_url'] ?? '') ?>" placeholder="https://www.google.com/maps/embed?pb=...">
                </div>
                <div class="col-12">
                    <label class="form-label">Batas Wilayah</label>
                    <textarea class="form-control" name="batas_wilayah" rows="5" placeholder="Jelaskan batas-batas wilayah desa..."><?= esc($geografi['batas_wilayah'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Kondisi Geografis</label>
                    <textarea class="form-control" name="kondisi_geografis" rows="5" placeholder="Jelaskan kondisi geografis desa..."><?= esc($geografi['kondisi_geografis'] ?? '') ?></textarea>
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
