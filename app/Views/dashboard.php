<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<!-- DASHBOARD CARDS -->
<div class="row g-3">

    <!-- TOTAL PRODUTOS -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Total de Produtos</h6>
                <h3><?= $totalProducts ?></h3>
                <small class="text-success">Dados actualizados</small>
            </div>
        </div>
    </div>

    <!-- VENDAS HOJE -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Vendas Hoje</h6>
                <h3><?= $salesToday ?></h3>
                <small class="text-primary">Movimento diário</small>
            </div>
        </div>
    </div>

    <!-- RECEITAS DO DIA -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Receitas do Dia</h6>
                <h3><?= number_format($todayRevenue, 2, ',', '.') ?> Kz</h3>
                <small class="text-success">A decorrer</small>
            </div>
        </div>
    </div>

    <!-- STOCK BAIXO -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Stock Baixo</h6>
                <h3><?= $lowStock ?></h3>
                <small class="text-danger">Repor urgente</small>
            </div>
        </div>
    </div>

</div>

<!-- GRÁFICOS -->
<div class="row mt-4 g-3">
    <!-- GRÁFICO 1: Vendas últimos 7 dias -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Vendas dos Últimos 7 Dias</h6>
                <canvas id="sales7days"></canvas>
            </div>
        </div>
    </div>

    <!-- GRÁFICO 2: Produtos mais vendidos -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Top 5 Produtos Mais Vendidos</h6>
                <canvas id="topProducts"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-3">
    <!-- GRÁFICO 3: Receita mensal -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Receita Mensal (Últimos 12 Meses)</h6>
                <canvas id="monthlyRevenue"></canvas>
            </div>
        </div>
    </div>

    <!-- GRÁFICO 4: Movimentos por forma de pagamento -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Movimentos por Forma de Pagamento</h6>
                <canvas id="paymentMethods"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Custo vs Receita (Últimos 12 Meses)</h6>
                <canvas id="costVsRevenue"></canvas>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ===============================
    // 📊 GRÁFICO 1 — Vendas 7 dias
    // ===============================
    const ctx1 = document.getElementById('sales7days').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?= $chart_days ?>,
            datasets: [{
                label: 'Vendas',
                data: <?= $chart_totals ?>,
                borderWidth: 2
            }]
        }
    });

    // ===============================
    // 📊 GRÁFICO 2 — Produtos mais vendidos
    // ===============================
    const ctx2 = document.getElementById('topProducts').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?= $top_names ?>,
            datasets: [{
                label: 'Quantidade Vendida',
                data: <?= $top_qty ?>,
                borderWidth: 1
            }]
        }
    });
</script>

<script>
    // ================================
    // 📊 GRÁFICO 3 — Receita Mensal
    // ================================
    const ctx3 = document.getElementById('monthlyRevenue').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: <?= $months ?>,
            datasets: [{
                label: 'Receita (Kz)',
                data: <?= $revenues ?>,
                borderWidth: 1
            }]
        }
    });

    // ==========================================
    // 📊 GRÁFICO 4 — Formas de pagamento
    // ==========================================
    const ctx4 = document.getElementById('paymentMethods').getContext('2d');
    new Chart(ctx4, {
        type: 'doughnut',
        data: {
            labels: <?= $pay_labels ?>,
            datasets: [{
                data: <?= $pay_values ?>,
                borderWidth: 1
            }]
        }
    });
</script>

<script>
    const ctxCVR = document.getElementById('costVsRevenue').getContext('2d');

    new Chart(ctxCVR, {
        type: 'bar',
        data: {
            labels: <?= $cv_months ?>,
            datasets: [
                {
                    label: 'Receita (Kz)',
                    data: <?= $cv_revenues ?>,
                    borderWidth: 2,
                    type: 'bar'
                },
                {
                    label: 'Custo (Kz)',
                    data: <?= $cv_costs ?>,
                    borderWidth: 3,
                    type: 'line'
                }
            ]
        }
    });
</script>


<?= $this->endSection() ?>