<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Desa Padang Loang' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/guest/home/home.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components/guest-navbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components/guest-footer.css') ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body>
<?= $this->include('Components/GuestNavbar') ?>

<main class="page-wrapper">
    <div class="pt-5"></div>
    <div class="container mt-4">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger shadow-sm"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info shadow-sm"><?= session()->getFlashdata('info') ?></div>
        <?php endif; ?>
    </div>

    <?= $this->renderSection('content') ?>
</main>

<?= $this->include('Guest/footer') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/components/guest-navbar.js') ?>"></script>
<script src="<?= base_url('assets/js/guest/main.js') ?>"></script>
</body>
</html>

