<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Staff Panel - Website Desa Bonto Marannu' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
    <meta name="<?= csrf_header() ?>" content="<?= csrf_hash() ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/staff/style.css') ?>">
</head>
<body class="bg-light">
<div class="app-wrapper">
    <?= $this->include('Components/Sidebar') ?>
    <div class="main-content">
        <?= $this->include('Components/TopBar') ?>
        <main class="content-area">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/components/sweetalert.js') ?>"></script>
<script src="<?= base_url('assets/js/components/upload_validator.js') ?>"></script>
<?= $this->include('Components/FlashMessage') ?>
<script src="<?= base_url('assets/js/staff/main.js') ?>"></script>
</body>
</html>


