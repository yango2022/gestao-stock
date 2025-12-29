<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Criar Empresa</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="mb-4 text-center">Criar Empresa</h4>

                    <?php if (session('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (session('errors') as $error): ?>
                                <div><?= esc($error) ?></div>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <form method="post" action="<?= site_url('registar') ?>">
                        <?= csrf_field() ?>

                        <h6 class="text-muted mb-2">Dados da Empresa</h6>

                        <div class="mb-3">
                            <label class="form-label">Nome da Empresa</label>
                            <input type="text" name="company_name" class="form-control"
                                   value="<?= old('company_name') ?>" required>
                        </div>

                        <hr>

                        <h6 class="text-muted mb-2">Administrador da Empresa</h6>

                        <div class="mb-3">
                            <label class="form-label">Nome de Usuário</label>
                            <input type="text" name="username" class="form-control"
                                   value="<?= old('username') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= old('email') ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Senha</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100">
                            Criar Empresa e Entrar
                        </button>

                        <div class="text-center mt-3">
                            <a href="<?= site_url('/') ?>">← Voltar</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
