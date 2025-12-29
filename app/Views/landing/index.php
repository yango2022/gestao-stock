<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gestão | SaaS</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- HERO -->
<section class="bg-dark text-white py-5">
    <div class="container text-center">
        <h1 class="display-5 fw-bold">Gestão de Stock, Vendas e Caixa</h1>
        <p class="lead mt-3">
            Uma plataforma simples, segura e profissional para gerir o seu negócio.
        </p>

        <div class="mt-4">
            <a href="<?= site_url('register') ?>" class="btn btn-success btn-lg me-2">
                <i class="bi bi-building"></i> Criar Empresa
            </a>

            <a href="<?= site_url('login') ?>" class="btn btn-outline-light btn-lg">
                <i class="bi bi-box-arrow-in-right"></i> Entrar
            </a>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="py-5">
    <div class="container">
        <div class="row text-center g-4">

            <div class="col-md-4">
                <i class="bi bi-box-seam display-4 text-primary"></i>
                <h5 class="mt-3">Gestão de Stock</h5>
                <p>Entradas, saídas e controlo em tempo real.</p>
            </div>

            <div class="col-md-4">
                <i class="bi bi-cart-check display-4 text-success"></i>
                <h5 class="mt-3">Vendas</h5>
                <p>Registo rápido, clientes e múltiplos métodos de pagamento.</p>
            </div>

            <div class="col-md-4">
                <i class="bi bi-cash-stack display-4 text-warning"></i>
                <h5 class="mt-3">Fluxo de Caixa</h5>
                <p>Entradas, saídas e relatórios financeiros claros.</p>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h3>Pronto para organizar o seu negócio?</h3>
        <p class="mb-4">Crie a sua empresa agora e comece em minutos.</p>

        <a href="<?= site_url('register') ?>" class="btn btn-light btn-lg">
            Criar Empresa Gratuitamente
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3">
    <small>
        © <?= date('Y') ?> - Sistema de Gestão | Desenvolvido pela ARTING
    </small>
</footer>

</body>
</html>