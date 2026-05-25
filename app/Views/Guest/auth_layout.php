<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Auth - Website Desa Bonto Marannu' ?></title>
    <meta name="<?= csrf_header() ?>" content="<?= csrf_hash() ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #081d3c; /* fallback color */
        }
        



    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/guest/auth/auth-base.css?v=' . time()) ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body class="min-vh-100 position-relative">
    
    <?= $this->include('Components/AuthNotification') ?>

    <?= $this->renderSection('content') ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
