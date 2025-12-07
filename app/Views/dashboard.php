<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

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

<!-- GRÁFICOS -->
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

<?= $this->endSection() ?>