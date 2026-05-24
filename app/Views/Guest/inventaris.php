<?= $this->extend('Guest/layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guest/inventaris.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="profil-desa-section pt-0" style="margin-top: -2.5rem;">
    <div class="container pb-5">
        
        <div class="profil-desa-header mt-5 mb-5 text-center reveal-up">
            <span class="profil-desa-subtitle">Transparansi Desa</span>
            <h2 class="profil-desa-title">Inventaris <span>Aset Desa</span></h2>
        </div>

        <div class="row g-4">
            <?php 
            $delay = 100;
            if (!empty($inventaris)): 
                foreach ($inventaris as $index => $item): 
                    if ($index % 3 == 0 && $index != 0) $delay = 100;
                    
                    $statusClass = 'status-baik';
                    $status = strtolower($item['status']);
                    if ($status === 'rusak ringan') $statusClass = 'status-rusak-ringan';
                    elseif ($status === 'rusak berat') $statusClass = 'status-rusak-berat';
            ?>
                <div class="col-md-6 col-lg-4 reveal-up delay-<?= $delay ?>">
                    <div class="inv-card">
                        <div class="inv-img-wrapper">
                            <div class="inv-status-pill <?= $statusClass ?>">
                                <?= esc($item['status']) ?>
                            </div>
                            <?php if (!empty($item['foto'])): ?>
                                <img src="<?= esc(base_url($item['foto'])) ?>" 
                                     alt="<?= esc($item['nama_barang']) ?>" 
                                     onerror="this.onerror=null; this.outerHTML='<div class=\'inv-placeholder\'><i class=\'bi bi-image\'></i></div>';">
                            <?php else: ?>
                                <div class="inv-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="inv-card-body">
                            <h5 class="inv-title"><?= esc($item['nama_barang']) ?></h5>
                            <div class="inv-type"><i class="bi bi-tags"></i> <?= esc($item['jenis']) ?></div>
                            <div class="inv-footer">
                                <span class="text-muted small">Total Kuantitas</span>
                                <span class="inv-total"><?= esc($item['total']) ?> Unit</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                $delay += 100;
                endforeach; 
            else: ?>
                <div class="col-12 text-center text-muted py-5 reveal-up">
                    <i class="bi bi-box-seam fs-1 d-block mb-3" style="color: #cbd5e1;"></i>
                    <p class="mb-0 fs-5">Belum ada data inventaris aset desa yang tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const reveals = document.querySelectorAll(".reveal-up");
    const revealOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealOnScroll = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
                observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    reveals.forEach(reveal => {
        revealOnScroll.observe(reveal);
    });
});
</script>
<?= $this->endSection() ?>
