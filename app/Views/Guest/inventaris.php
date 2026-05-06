<?= $this->extend('Guest/layout') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="text-center mb-5">
                <p class="text-uppercase text-primary fw-semibold small mb-1">Transparansi Desa</p>
                <h2 class="fw-bold mb-3">Inventaris Aset Desa</h2>
                <div class="mx-auto bg-primary" style="height: 3px; width: 60px;"></div>
            </div>

            <?php if (!empty($inventaris)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="15%">Foto</th>
                                <th width="30%">Nama Barang</th>
                                <th width="20%">Jenis</th>
                                <th class="text-center" width="15%">Total</th>
                                <th class="text-center" width="15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventaris as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td>
                                        <?php if (!empty($item['foto'])): ?>
                                            <img src="<?= base_url($item['foto']) ?>" alt="<?= esc($item['nama_barang']) ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-semibold"><?= esc($item['nama_barang']) ?></td>
                                    <td><?= esc($item['jenis']) ?></td>
                                    <td class="text-center"><?= esc($item['total']) ?></td>
                                    <td class="text-center">
                                        <?php
                                            $badgeClass = 'bg-secondary';
                                            $status = strtolower($item['status']);
                                            if ($status === 'baik') $badgeClass = 'bg-success';
                                            elseif ($status === 'rusak ringan') $badgeClass = 'bg-warning text-dark';
                                            elseif ($status === 'rusak berat') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill px-3"><?= esc($item['status']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                    <p class="mb-0">Belum ada data inventaris aset desa yang tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
