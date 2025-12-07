<!DOCTYPE html>
<html lang="pt" data-theme="auto">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Gestão de Stock' ?></title>

    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>

<body>

    <?= $this->include('partials/sidebar') ?>

    <main class="content">
        <!-- TOPBAR -->
        <div class="topbar d-flex justify-content-between align-items-center mb-4">
            <h4>Dashboard</h4>

            <div>
                <span class="me-3">Olá, <?= esc($user->username) ?></span>

                <a href="/logout" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </a>
            </div>
        </div>
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('layouts/theme-toggle') ?>
    <script src="/assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?= sweetAlert() ?>
</body>
</html>