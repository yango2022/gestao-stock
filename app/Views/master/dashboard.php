<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <!-- ================= KPI CARDS ================= -->
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-secondary">Total de Empresas</small>
                        <h3 class="fw-bold mb-0"><?= $totalCompanies ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-secondary">Total de Usuários</small>
                        <h3 class="fw-bold mb-0"><?= $totalUsers ?></h3>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-secondary">Total de Produtos</small>
                        <h3 class="fw-bold mb-0"><?= $totalProducts ?></h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-secondary">Total de Vendas</small>
                        <h3 class="fw-bold mb-0"><?= $totalSales ?></h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3">
                        <i class="bi bi-cart-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================= GRÁFICOS ================= -->

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Vendas – Últimos 7 Dias (Todas Empresas)</h6>
                    <canvas id="sales7days" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Top 5 Produtos (Todas Empresas)</h6>
                    <canvas id="topProducts" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Receita Mensal (12 meses)</h6>
                    <canvas id="monthlyRevenue" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Fluxo de Caixa (Todas Empresas)</h6>
                    <canvas id="cashFlowChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const baseOptions = {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    };

    // Vendas últimos 7 dias
    new Chart(sales7days, {
        type: 'line',
        data: {
            labels: <?= $chart_days ?>,
            datasets: [{
                label: 'Vendas',
                data: <?= $chart_totals ?>,
                borderWidth: 2,
                tension: .3
            }]
        },
        options: baseOptions
    });

    // Top produtos
    new Chart(topProducts, {
        type: 'bar',
        data: {
            labels: <?= $top_names ?>,
            datasets: [{
                label: 'Quantidade',
                data: <?= $top_qty ?>
            }]
        },
        options: baseOptions
    });

    // Receita mensal
    new Chart(monthlyRevenue, {
        type: 'bar',
        data: {
            labels: <?= $months ?>,
            datasets: [{
                label: 'Receita (Kz)',
                data: <?= $revenues ?>
            }]
        },
        options: baseOptions
    });

    // Fluxo de caixa
    new Chart(cashFlowChart, {
        type: 'line',
        data: {
            labels: <?= $dias ?>,
            datasets: [
                { label: 'Entradas', data: <?= $entradas ?> },
                { label: 'Saídas', data: <?= $saidas ?> },
                { label: 'Saldo', data: <?= $saldo ?> }
            ]
        },
        options: baseOptions
    });
</script>

<?= $this->endSection() ?>