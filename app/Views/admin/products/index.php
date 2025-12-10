<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>



    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Gestão de Produtos</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-circle"></i> Novo Produto
        </button>
    </div>

    <!-- TABELA -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table  class="table table-bordered table-striped" id="myTable">
                <thead>
                <tr class="table-dark">
                    <th>ID</th>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>SKU</th>
                    <th>Preço Compra</th>
                    <th>Preço Venda</th>
                    <th>Stock</th>
                    <th>Acções</th>
                </tr>
                </thead>
                <tbody>
                <!-- AJAX insere aqui -->
                </tbody>
            </table>
        </div>
    </div>



<!-- ================================
     MODAL: CRIAR PRODUTO
================================ -->
<div class="modal fade" id="createModal">
    <div class="modal-dialog modal-lg">
        <form action="/produtos/store" method="post" class="modal-content" enctype="multipart/form-data">

            <div class="modal-header">
                <h5 class="modal-title">Adicionar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">

                <div class="col-md-8">
                    <label>Nome *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>SKU</label>
                    <input type="text" name="sku" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Preço Compra *</label>
                    <input type="number" step="0.01" name="cost_price" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Preço Venda *</label>
                    <input type="number" step="0.01" name="unit_price" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Stock Inicial</label>
                    <input type="number" name="current_stock" class="form-control" value="0">
                </div>

                <div class="col-md-6">
                    <label>Stock Mínimo</label>
                    <input type="number" name="min_stock" class="form-control" value="0">
                </div>

                <div class="col-md-6">
                    <label>Categoria</label>
                    <select name="category_id" class="form-select">
                        <option value="">Selecione uma Categoria</option>
                        <?php if (isset($categories)): ?>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                            <?php endforeach ?>
                        <?php endif ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Imagem</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success">Salvar</button>
            </div>

        </form>
    </div>
</div>



<!-- ================================
     MODAL: EDITAR PRODUTO
================================ -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog modal-lg">
        <form id="editForm" method="post" class="modal-content" enctype="multipart/form-data">

            <div class="modal-header">
                <h5 class="modal-title">Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">

                <input type="hidden" id="edit_id">

                <div class="col-md-8">
                    <label>Nome *</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>SKU</label>
                    <input type="text" id="edit_sku" name="sku" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Preço Compra *</label>
                    <input type="number" step="0.01" id="edit_purchase_price" name="cost_price" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Preço Venda *</label>
                    <input type="number" step="0.01" id="edit_sale_price" name="unit_price" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Stock</label>
                    <input type="number" id="edit_stock" name="current_stock" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Stock Mínimo</label>
                    <input type="number" id="edit_min_stock" name="min_stock" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Categoria</label>
                    <select id="edit_category_id" name="category_id" class="form-select">
                        <option value="">Selecione uma Categoria</option>
                        <?php if (isset($categories)): ?>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                            <?php endforeach ?>
                        <?php endif ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Imagem</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success">Actualizar</button>
            </div>

        </form>
    </div>
</div>



<!-- ================================
     SCRIPT AJAX
================================ -->
<script>
    document.addEventListener("DOMContentLoaded", loadProducts);

    function loadProducts() {
        fetch("/produtos/list")
            .then(r => r.json())
            .then(res => {
                let tbody = document.querySelector("#myTable tbody");
                tbody.innerHTML = "";

                res.data.forEach(p => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${p.id}</td>
                            <td>
                                <img src="/uploads/produtos/${p.image ?? 'default.png'}" width="45" class="rounded">
                            </td>
                            <td>${p.name}</td>
                            <td>${p.sku ?? '-'}</td>
                            <td>${p.cost_price} Kz</td>
                            <td>${p.unit_price} Kz</td>
                            <td>${p.current_stock}</td>

                            <td>
                                <button class="btn btn-sm btn-warning editBtn" data-id="${p.id}">
                                    <i class="bi bi-pencil"></i>
                                    Editar
                                </button>
                                <button class="btn btn-sm btn-danger deleteBtn" data-id="${p.id}">
                                    <i class="bi bi-trash"></i>
                                    Apagar
                                </button>
                            </td>
                        </tr>
                    `;
                });
            });
    }

    // Abrir modal de edição
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('editBtn')){
            let id = e.target.dataset.id;

            fetch(`/produtos/get/${id}`)
                .then(r => r.json())
                .then(res => {

                    let p = res.product;

                    document.getElementById("edit_id").value = p.id;
                    document.getElementById("edit_name").value = p.name;
                    document.getElementById("edit_sku").value = p.sku;
                    document.getElementById("edit_purchase_price").value = p.cost_price;
                    document.getElementById("edit_sale_price").value = p.unit_price;
                    document.getElementById("edit_stock").value = p.current_stock;
                    document.getElementById("edit_min_stock").value = p.min_stock;
                    document.getElementById("edit_category_id").value = p.category_id;

                    // definindo action do form
                    document.getElementById("editForm").action = `/produtos/update/${p.id}`;

                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
        }
    });

    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("deleteBtn")) {

            let id = e.target.dataset.id;
            let url = "/produtos/delete/" + id;

            Swal.fire({
                title: "Tem certeza?",
                text: "Esta ação não pode ser desfeita!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sim, apagar!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    });


</script>

<?= $this->endSection() ?>