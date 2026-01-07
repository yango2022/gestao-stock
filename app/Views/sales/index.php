<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-4">
    <h3>Vendas</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#saleModal">
        <i class="bi bi-cart-plus"></i> Nova Venda
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h4>Tabela de Vendas</h4>
    </div>
    <div class="card-body">
        <!-- LISTA DE VENDAS -->
        <table class="table table-striped" id="myTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Metódo de Pagamento</th>
                    <th>Utilizador</th>
                    <th>Factura</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= $s['customer_name'] ?></td>
                    <td><?= number_format($s['total'], 2) ?> Kz</td>
                    <td><?= $s['payment_method'] ?></td>
                    <td><?= $s['user_name'] ?></td>
                    <td>
                        <a href="<?= site_url('invoices/create-from-sale/' . $s['id']) ?>"
                            class="btn btn-sm btn-primary">
                            Gerar Fatura
                        </a>
                        <a href="<?= site_url('invoices/factura/' . $s['id']) ?>"
                            target="_blank"
                            class="btn btn-sm btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i>Baixar PDF
                        </a>
                        <a href="<?= site_url('invoices/thermal/' . $s['id']) ?>"
                            target="_blank"
                            class="btn btn-sm btn-dark">
                            <i class="bi bi-print"></i>Imprimir Térmica
                        </a>
                    </td>
                    <td><?= $s['created_at'] ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

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

                <!-- MÉTODO DE PAGAMENTO -->
                <div class="mb-2">
                    <label>Método de Pagamento</label>
                    <select id="payment_method" class="form-select">
                        <option value="dinheiro">Dinheiro</option>
                        <option value="transferencia">Transferência</option>
                        <option value="multicaixa">Multicaixa</option>
                        <option value="tpapa">TPA</option>
                    </select>
                </div>

                <!-- DESCONTO -->
                <div class="mb-2">
                    <label>Desconto (Kz)</label>
                    <input type="number" id="discount" class="form-control" value="0">
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
                <h5>Com Desconto: <span id="finalTotal">0</span> Kz</h5>

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
    document.getElementById('discount').addEventListener('input', updateTotal);
    document.addEventListener('input', e => {
        if (
            e.target.classList.contains('price') ||
            e.target.classList.contains('qty') ||
            e.target.id === 'discount'
        ) {
            updateTotal();
        }
    });


    function updateTotal() 
    {
        let total = 0;

        document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
            let price = parseFloat(tr.querySelector('.price').value) || 0;
            let qty   = parseInt(tr.querySelector('.qty').value) || 0;
            let subtotal = price * qty;
            tr.querySelector('.itemTotal').textContent = subtotal.toFixed(2);
            total += subtotal;
        });

        document.getElementById('saleTotal').innerText = total.toFixed(2);

        // Aplicar desconto
        let discount = parseFloat(document.getElementById('discount').value) || 0;
        let finalTotal = total - discount;
        if (finalTotal < 0) finalTotal = 0;

        document.getElementById('finalTotal').innerText = finalTotal.toFixed(2);
    }

    document.getElementById('saveSaleBtn').addEventListener('click', () => {
        let saleItems = [];

        document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
            saleItems.push({
                product_id: tr.querySelector('.productSelect').value,
                unit_price: tr.querySelector('.price').value,
                quantity: tr.querySelector('.qty').value,
                total: tr.querySelector('.itemTotal').innerText
            });
        });

        fetch('/vendas/store', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                customer_id: document.getElementById('customer_id').value,
                user_id: document.getElementById('user_id').value,
                payment_method: document.getElementById('payment_method').value,
                discount: document.getElementById('discount').value,
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