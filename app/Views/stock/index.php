<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<h3>Gestão de Stock</h3>

<div class="row mt-4">

    <!-- Entrada -->
    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-header bg-success text-white">Entrada de Stock</div>
            <div class="card-body">
                <form action="/stock/entrada" method="post">

                    <div class="mb-3">
                        <label>Produto</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">Selecione</option>
                            <?php foreach($products as $p): ?>
                                <option value="<?= $p->id ?>"><?= $p->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Fornecedor</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">Selecione</option>
                            <?php foreach($suppliers as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Quantidade</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Custo Unitário</label>
                        <input type="number" step="0.01" name="unit_cost" class="form-control" required>
                    </div>

                    <input type="hidden" name="user_id" value="<?= $user_id ?>">

                    <button class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Saída -->
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">Saída de Stock</div>
            <div class="card-body">
                <form action="/stock/saida" method="post">

                    <label>Produto</label>
                    <select name="product_id" class="form-control" required>
                        <?php foreach($products as $p): ?>
                            <option value="<?= $p->id ?>"><?= $p->name ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label class="mt-2">Quantidade</label>
                    <input type="number" name="quantity" class="form-control" required>

                    <input type="hidden" name="user_id" value="<?= $user_id ?>">
                    
                    <button class="btn btn-danger mt-3">Registar</button>
                </form>
            </div>
        </div>
    </div>

</div>

<hr class="my-4">

<h4>Entradas de Stock</h4>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Fornecedor</th>
            <th>Qtd</th>
            <th>Preço</th>
            <th>Usuário</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($entries as $e): ?>
        <tr>
            <td><?= $e['product_name'] ?></td>
            <td><?= $e['supplier_name'] ?? '-' ?></td>
            <td><?= $e['quantity'] ?></td>
            <td><?= $e['unit_cost'] ?></td>
            <td><?= $e['user_name'] ?? '—' ?></td>
            <td><?= $e['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h4 class="mt-5">Saídas de Stock</h4>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Qtd</th>
            <th>Usuário</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($outs as $o): ?>
        <tr>
            <td><?= $o['product_name'] ?></td>
            <td><?= $o['quantity'] ?></td>
            <td><?= $o['user_name'] ?? '—' ?></td>
            <td><?= $o['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>