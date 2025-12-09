<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

    <div class="d-flex justify-content-between mb-3">
        <h3>Clientes</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus"></i> Novo Cliente
        </button>
    </div>

    <!-- SweetAlert -->
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            Swal.fire("Sucesso!", "<?= session('success') ?>", "success");
        </script>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h4>Tabela de Clientes</h4>
        </div>
        <div class="body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>NIF</th>
                        <th>Cidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td><?= $c['name'] ?></td>
                            <td><?= $c['email'] ?></td>
                            <td><?= $c['phone'] ?></td>
                            <td><?= $c['city'] ?></td>
                            <td><?= $c['nif'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning editBtn"
                                        data-id="<?= $c['id'] ?>">
                                    Editar
                                </button>

                                <a href="/clientes/delete/<?= $c['id'] ?>"
                                onclick="return confirmDelete(event)"
                                class="btn btn-sm btn-danger">
                                    Apagar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>


<!-- Modal Criar -->
<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <form action="/clientes/store" method="post" class="modal-content">
            <div class="modal-header">
                <h5>Novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= view('customers/form_fields') ?>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <form id="editForm" method="post" class="modal-content">
            <div class="modal-header">
                <h5>Editar Cliente</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= view('customers/form_fields_edit') ?>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success">Atualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
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

    // Abrir modal de edição
    document.addEventListener("click", function(e){
        if (e.target.classList.contains("editBtn")) {

            let id = e.target.dataset.id;

            fetch(`/clientes/get/${id}`)
                .then(r => r.json())
                .then(res => {
                    let c = res.customer;

                    document.getElementById('edit_name').value = c.name;
                    document.getElementById('edit_email').value = c.email;
                    document.getElementById('edit_phone').value = c.phone;
                    document.getElementById('edit_address').value = c.address;
                    document.getElementById('edit_city').value = c.city;
                    document.getElementById('edit_nif').value = c.nif;

                    document.getElementById('editForm').action = `/clientes/update/${c.id}`;

                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
        }
    });
</script>

<?= $this->endSection() ?>