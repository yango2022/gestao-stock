<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-4">
    <h3>Fluxo de Caixa</h3>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cashModal">
        <i class="bi bi-plus-circle"></i> Novo Movimento
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h4>Fluxo de Caixa</h4>
    </div>
    <div class="card-body">
        <!-- TABELA -->
        <table class="table table-striped table-bordered" id="myTable">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Categoria</th>
                    <th>Valor</th>
                    <th>Nota</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($i['created_at'])) ?></td>
                    <td><?= ucfirst($i['type']) ?></td>
                    <td><?= ucfirst($i['category']) ?></td>
                    <td><?= number_format($i['amount'], 2, ',', '.') ?> Kz</td>
                    <td><?= $i['note'] ?></td>
                    <td>
                        <button 
                            class="btn btn-sm btn-warning btn-edit" 
                            data-id="<?= $i['id'] ?>"
                            data-type="<?= $i['type'] ?>"
                            data-category="<?= $i['category'] ?>"
                            data-amount="<?= $i['amount'] ?>"
                            data-note="<?= $i['note'] ?>"
                        >
                            <i class="bi bi-pencil"></i>
                            Editar
                        </button>
                        <a href="/fluxo-caixa/delete/<?= $i['id'] ?>"
                            onclick="return confirmDelete(event)"
                            class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                            Apagar
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="cashModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Novo Movimento</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/fluxo-caixa/store" method="post">
                    <div class="mb-3">
                        <label>Tipo</label>
                        <select name="type" class="form-control" required>
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saída</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Categoria</label>
                        <select name="category" class="form-control" required>
                            <option value="venda">Venda</option>
                            <option value="compra">Compra</option>
                            <option value="ajuste">Ajuste</option>
                            <option value="despesas">Despesas</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Valor</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nota</label>
                        <input type="text" name="note" class="form-control">
                    </div>

                    <button class="btn btn-success">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDITAR MOVIMENTO -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="/cashflow/update" method="post" id="editForm">

                <div class="modal-header">
                    <h5 class="modal-title">Editar Movimento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">

                    <div class="mb-3">
                        <label>Tipo</label>
                        <select name="type" id="edit-type" class="form-control" required>
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saída</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Categoria</label>
                        <select name="category" id="edit-category" class="form-control" required>
                            <option value="venda">Venda</option>
                            <option value="compra">Compra</option>
                            <option value="ajuste">Ajuste</option>
                            <option value="despesas">Despesas</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Valor</label>
                        <input type="number" step="0.01" name="amount" id="edit-amount" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nota</label>
                        <input type="text" name="note" id="edit-note" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar Alterações</button>
                </div>

            </form>

        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const editButtons = document.querySelectorAll(".btn-edit");
        const editModal = new bootstrap.Modal(document.getElementById("editModal"));

        editButtons.forEach(btn => {
            btn.addEventListener("click", () => {

                document.getElementById("edit-id").value = btn.dataset.id;
                document.getElementById("edit-type").value = btn.dataset.type;
                document.getElementById("edit-category").value = btn.dataset.category;
                document.getElementById("edit-amount").value = btn.dataset.amount;
                document.getElementById("edit-note").value = btn.dataset.note;

                // Define action do form
                document.getElementById("editForm").action = "/fluxo-caixa/update/" + btn.dataset.id;

                editModal.show();
            });
        });
    });

    function confirmDelete(e) {
        e.preventDefault();
        Swal.fire({
            title: "Tem certeza?",
            text: "Esta ação é irreversível!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, apagar!",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = e.target.href;
            }
        });
    }
</script>



<?= $this->endSection() ?>