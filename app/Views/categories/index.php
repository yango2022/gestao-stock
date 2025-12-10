<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Categorias</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        Nova Categoria
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-striped" id="myTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th width="150">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= $c['name'] ?></td>
                    <td><?= $c['description'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning editBtn" data-id="<?= $c['id'] ?>">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button onclick="deleteCategory(<?= $c['id'] ?>)" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('categories/modals') ?>


<script>
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('editBtn')){
            let id = e.target.dataset.id;

            fetch(`/categorias/get/${id}`)
                .then(r => r.json())
                .then(res => {

                    if(res.status !== 'success'){
                        Swal.fire('Erro', 'Não foi possível carregar a categoria', 'error');
                        return;
                    }

                    let c = res.category;

                    document.getElementById("edit_id").value = c.id;
                    document.getElementById("edit_name").value = c.name;
                    document.getElementById("edit_description").value = c.description;

                    document.getElementById("editForm").action = `/categorias/update/${c.id}`;

                    new bootstrap.Modal(document.getElementById('editModal')).show();
                })
        }
    });

    function deleteCategory(id){
        Swal.fire({
            title: "Tem certeza?",
            text: "Esta ação não pode ser desfeita!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, apagar!",
            cancelButtonText: "Cancelar"
        }).then(res => {
            if(res.isConfirmed){
                window.location.href = `/categorias/delete/${id}`;
            }
        });
    }
</script>

<?php if(session()->has('success')): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Sucesso',
        text: '<?= session('success') ?>'
    });
</script>
<?php endif; ?>


<?= $this->endSection() ?>