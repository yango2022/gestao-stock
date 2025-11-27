<!--DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel do Administrador</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #f5f6fa;
        }
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #212529;
            color: #fff;
            z-index: 100;
            padding-top: 20px;
        }
        .sidebar a {
            color: #adb5bd;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            font-size: 15px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #343a40;
            color: #fff;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .topbar {
            background: #fff;
            border-radius: 6px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h4 class="text-center mb-4">Painel Admin</h4>

        <a href="#" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="#"><i class="bi bi-box-seam"></i> Stock</a>
        <a href="#"><i class="bi bi-cart-check"></i> Vendas</a>
        <a href="#"><i class="bi bi-people"></i> Clientes</a>
        <a href="#"><i class="bi bi-truck"></i> Fornecedores</a>
        <a href="#"><i class="bi bi-cash-stack"></i> Fluxo de Caixa</a>
        <a href="#"><i class="bi bi-receipt"></i> Relatórios</a>
        <a href="#"><i class="bi bi-gear"></i> Configurações</a>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- TOPBAR -->
        <div class="topbar d-flex justify-content-between align-items-center">
            <h4>Dashboard</h4>

            <div>
                <span class="me-3">Olá, Admin</span>
                <button class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </button>
            </div>
        </div>

        <!-- DASHBOARD CARDS -->
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Total de Produtos</h6>
                        <h3>154</h3>
                        <small class="text-success">+12 este mês</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Vendas Hoje</h6>
                        <h3>85</h3>
                        <small class="text-primary">+8 desde ontem</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Receitas do Dia</h6>
                        <h3>45.000 Kz</h3>
                        <small class="text-success">+10%</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Stock Baixo</h6>
                        <h3>12</h3>
                        <small class="text-danger">Repor urgente</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRÁFICOS (PLACEHOLDERS) -->
        <div class="row mt-4 g-3">
            <div class="col-md-8">
                <div class="card shadow-sm p-3">
                    <h6>Fluxo de Caixa (Últimos 30 dias)</h6>
                    <div class="bg-light text-center p-5 rounded">
                        <i>Gráfico aqui...</i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h6>Top Produtos</h6>
                    <div class="bg-light text-center p-5 rounded">
                        <i>Gráfico aqui...</i>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html-->

<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
    <h2>Bem-vindo, <?= esc($user->username) ?>!</h2>

    <div class="card mt-4 p-3" style="background: var(--card-bg);">
        <p>Esta é a página inicial do painel administrativo.</p>
    </div>
<?= $this->endSection() ?>
