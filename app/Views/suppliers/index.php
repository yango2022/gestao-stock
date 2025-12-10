<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-4">
    <h3>Fornecedores</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="bi bi-plus-circle"></i> Novo Fornecedor
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h4>Lista de Fornecedores</h4>
    </div>
    <div class="card-body">
        <!-- LISTA -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>Acção</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= $s['name'] ?></td>
                    <td><?= $s['email'] ?></td>
                    <td><?= $s['phone'] ?></td>
                    <td><?= $s['address'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning editBtn" data-id="<?= $s['id'] ?>">
                            <i class="bi bi-pencil"></i>
                            Editar
                        </button>

                        <a href="/fornecedores/delete/<?= $s['id'] ?>"
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

<!-- CREATE MODAL -->
<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/fornecedores/store" method="post">
                <div class="modal-header">
                    <h5>Novo Fornecedor</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Telefone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Endereço</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="post">
                <div class="modal-header">
                    <h5>Editar Fornecedor</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_id">

                    <div class="mb-2">
                        <label>Nome</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Telefone</label>
                        <input type="text" id="edit_phone" name="phone" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Endereço</label>
                        <input type="text" id="edit_address" name="address" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Editar fornecedor
document.addEventListener('click', e => {
    if (e.target.classList.contains('editBtn')) {
        let id = e.target.dataset.id;

        fetch(`/fornecedores/get/${id}`)
            .then(r => r.json())
            .then(res => {
                if (res.status !== 'success') {
                    Swal.fire('Erro', 'Fornecedor não encontrado', 'error');
                    return;
                }

                let f = res.supplier;

                document.getElementById('edit_id').value = f.id;
                document.getElementById('edit_name').value = f.name;
                document.getElementById('edit_email').value = f.email;
                document.getElementById('edit_phone').value = f.phone;
                document.getElementById('edit_address').value = f.address;

                document.getElementById('editForm').action = `/fornecedores/update/${f.id}`;

                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
    }
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