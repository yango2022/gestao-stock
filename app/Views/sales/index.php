<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-4">
    <h3>Vendas</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#saleModal">
        <i class="bi bi-cart-plus"></i> Nova Venda
    </button>
</div>

<!-- LISTA DE VENDAS -->
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Utilizador</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sales as $s): ?>
        <tr>
            <td><?= $s['id'] ?></td>
            <td><?= $s['customer_name'] ?></td>
            <td><?= number_format($s['total_amount'], 2) ?> Kz</td>
            <td><?= $s['user_name'] ?></td>
            <td><?= $s['created_at'] ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<!-- MODAL NOVA VENDA -->
<div class="modal fade" id="saleModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5>Nova Venda</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="user_id" value="<?= $user_id ?>">

                <div class="mb-2">
                    <label>Cliente</label>
                    <select id="customer_id" class="form-select">
                        <?php foreach($customers as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <hr>

                <h5>Itens da Venda</h5>

                <table class="table" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Qtd</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <button class="btn btn-secondary" id="addItemBtn">Adicionar Item</button>

                <hr>

                <h4>Total: <span id="saleTotal">0</span> Kz</h4>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" id="saveSaleBtn">Finalizar Venda</button>
            </div>

        </div>
    </div>
</div>

<script>
let products = <?= json_encode($products) ?>;
let items = [];

document.getElementById('addItemBtn').addEventListener('click', () => {
    let row = `
        <tr>
            <td>
                <select class="form-select productSelect">
                    ${products.map(p => `<option value="${p.id}" data-price="${p.unit_price}">${p.name}</option>`)}
                </select>
            </td>
            <td><input type="number" class="form-control price" value="0"></td>
            <td><input type="number" class="form-control qty" value="1"></td>
            <td class="itemTotal">0</td>
            <td><button class="btn btn-danger btn-sm removeItem">X</button></td>
        </tr>
    `;
    document.querySelector('#itemsTable tbody').insertAdjacentHTML('beforeend', row);
});

// Remover item
document.addEventListener('click', e => {
    if (e.target.classList.contains('removeItem')) {
        e.target.closest('tr').remove();
        updateTotal();
    }
});

// Atualizar total ao mudar preço ou quantidade
document.addEventListener('input', updateTotal);

function updateTotal() {
    let total = 0;

    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        let price = parseFloat(tr.querySelector('.price').value) || 0;
        let qty   = parseInt(tr.querySelector('.qty').value) || 0;
        let subtotal = price * qty;
        tr.querySelector('.itemTotal').textContent = subtotal.toFixed(2);
        total += subtotal;
    });

    document.getElementById('saleTotal').innerText = total.toFixed(2);
}

document.getElementById('saveSaleBtn').addEventListener('click', () => {
    let saleItems = [];

    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        saleItems.push({
            product_id: tr.querySelector('.productSelect').value,
            price: tr.querySelector('.price').value,
            quantity: tr.querySelector('.qty').value,
            total: tr.querySelector('.itemTotal').innerText
        });
    });

    fetch('/vendas/store', {
        method: 'POST',
        body: JSON.stringify({
            customer_id: document.getElementById('customer_id').value,
            user_id: document.getElementById('user_id').value,
            items: saleItems
        })
    })
    .then(r => r.json())
    .then(res => {
        Swal.fire(res.status, res.message, res.status);
        if (res.status === 'success') location.reload();
    });
});
</script>

<?= $this->endSection() ?>