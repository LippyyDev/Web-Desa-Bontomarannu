<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Website Desa Bonto Marannu' ?></title>
    <meta name="<?= csrf_header() ?>" content="<?= csrf_hash() ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
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
<body class="d-flex flex-column min-vh-100">
<?= $this->include('Components/GuestNavbar') ?>

<main class="page-wrapper flex-grow-1 min-vh-100">
    <div class="pt-5"></div>


    <?= $this->renderSection('content') ?>
</main>

<?= $this->include('Guest/footer') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/components/sweetalert.js') ?>"></script>
<?= $this->include('Components/FlashMessage') ?>
<script src="<?= base_url('assets/js/components/guest-navbar.js') ?>"></script>
<script src="<?= base_url('assets/js/guest/main.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>

