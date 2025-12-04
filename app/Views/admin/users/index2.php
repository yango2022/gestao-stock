<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>


<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Gestão de Usuários</h3>
    <button class="btn btn-primary mb-3" onclick="openCreateModal()">
        <i class="bi bi-person-plus"></i> Novo Usuário
    </button>
</div>

<!-- TABELA -->
<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-hover" id="usersTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Grupo</th>
                    <th>Último Acesso</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody id="usersBody">
                <!-- preenchido por AJAX -->
            </tbody>
        </table>

    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="userModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <form id="userForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Criar Usuário</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">

                    <input type="hidden" id="user_id">

                    <div class="col-md-6">
                        <label>Username</label>
                        <input type="text" name="username" id="username" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Senha</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Grupo</label>
                        <select name="group" id="group" class="form-select">
                            <option value="admin">Administrador</option>
                            <option value="gerente">Gerente</option>
                            <option value="vendedor">Vendedor</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label>Permissões</label>
                        <div class="row">
                            <?php foreach($permissions as $p): ?>
                                <div class="col-md-3">
                                    <label>
                                        <input type="checkbox" class="perm" value="<?= $p->name ?>"> <?= $p->name ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" onclick="saveUser(event)">Salvar</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let userModal = new bootstrap.Modal('#userModal');

    /* =============================
    Carregar tabela
    ============================= */

    function loadUsers() {
        fetch('/admin/users/list', {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.json())
        .then(response => {

            if (!response.data) {
                console.error("Sem dados");
                return;
            }

            let tbody = document.getElementById("usersBody");
            tbody.innerHTML = "";

            response.data.forEach((user, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${user.username ?? '-'}</td>
                        <td>${user.email ?? '-'}</td>
                        <td>${user.last_active ?? '-'}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        })
        .catch(err => console.error("Erro:", err));
    }

    /* =============================
    Abrir modal para criação
    ============================= */
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = "Criar Usuário";

        document.getElementById('user_id').value = "";
        document.getElementById('username').value = "";
        document.getElementById('email').value = "";
        document.getElementById('password').value = "";
        document.getElementById('group').value = "";

        document.querySelectorAll('.perm').forEach(p => p.checked = false);

        userModal.show();
    }

    /* =============================
    Editar usuário
    ============================= */
    function editUser(id) {
        fetch('/admin/users/get/' + id)
            .then(r => r.json())
            .then(res => {
                let u = res.user;

                document.getElementById('modalTitle').innerText = "Editar Usuário";
                document.getElementById('user_id').value = u.id;
                document.getElementById('username').value = u.username;
                document.getElementById('email').value = u.email;
                document.getElementById('password').value = "";

                document.getElementById('group').value = res.groups[0] ?? "";

                document.querySelectorAll('.perm').forEach(p => {
                    p.checked = res.permissions.includes(p.value);
                });

                userModal.show();
            });
    }

    /* =============================
    Salvar (criar ou editar)
    ============================= */
    function saveUser(event) {
        event.preventDefault();

        let id = document.getElementById('user_id').value;

        let data = {
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            group: document.getElementById('group').value,
            permissions: Array.from(document.querySelectorAll('.perm:checked')).map(p => p.value)
        };

        fetch(id ? '/admin/users/update/' + id : '/admin/users/store', {
            method: "POST",
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(res => {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Usuário salvo com sucesso!',
            });

            userModal.hide();
            location.reload();
        });
    }

    /* =============================
    Apagar usuário
    ============================= */
    function deleteUser(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Apagar usuário?',
            showCancelButton: true,
            confirmButtonText: 'Sim, apagar'
        }).then(r => {
            if (r.isConfirmed) {
                fetch('/admin/users/delete/' + id, { method: 'POST' })
                    .then(r => r.json())
                    .then(() => {
                        Swal.fire('Apagado!', '', 'success');
                        location.reload();
                    });
            }
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        loadUsers();
    });
</script>

<?= $this->endSection() ?>