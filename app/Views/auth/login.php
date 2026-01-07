<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Entrar | SGSFC</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,.2);
        }

        .login-card h4 {
            font-weight: 600;
        }

        .brand {
            font-weight: 700;
            font-size: 22px;
            color: #0d6efd;
        }
    </style>
</head>
<body>

<div class="card login-card p-4">
    <div class="text-center mb-3">
        <div class="brand">SGSFC</div>
        <small class="text-muted">Sistema de Gestão e Facturação</small>
    </div>

    <!-- ALERTAS -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-info">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>

        <!-- EMAIL -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    required
                    value="<?= old('email') ?>"
                    placeholder="exemplo@email.com"
                >
            </div>
        </div>

        <!-- PASSWORD -->
        <div class="mb-3">
            <label class="form-label">Palavra-passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required
                    placeholder="••••••••"
                >
            </div>
        </div>

        <!-- REMEMBER -->
        <div class="form-check mb-3">
            <input
                class="form-check-input"
                type="checkbox"
                name="remember"
                id="remember"
            >
            <label class="form-check-label" for="remember">
                Lembrar-me
            </label>
        </div>

        <!-- SUBMIT -->
        <button class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right"></i> Entrar
        </button>
    </form>

    <div class="text-center mt-3">
        <small class="text-muted">
            © <?= date('Y') ?> SGSFC
        </small>
    </div>
</div>

</body>
</html>