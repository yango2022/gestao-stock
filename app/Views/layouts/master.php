<!DOCTYPE html>
<html lang="pt" data-theme="auto">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Gestão de Stock' ?></title>

    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        .content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <?= $this->include('partials/header') ?>
    <?= $this->include('partials/sidebar') ?>

    <main class="content">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="/assets/js/theme.js"></script>
</body>
</html>