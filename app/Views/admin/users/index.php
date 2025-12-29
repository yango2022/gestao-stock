<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>


    <div class="d-flex justify-content-between mb-3">
        <h3>Gestão de Usuários</h3>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-person-plus"></i> Novo Usuário
        </button>
    </div>

    <!-- TABELA -->
    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped" id="myTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Grupo</th>
                    <th>Último Acesso</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u->id; ?></td>
                        <td><?= $u->username; ?></td>
                        <td><?= $u->email; ?></td>
                        <td>
                            <?php
                                $groups = $u->getGroups();
                                $groupName = $groups[0] ?? 'Sem grupo';
                            ?>
                            <?php if ($groupName === 'admin'): ?>
                                <span class="badge bg-danger">Administrador</span>

                            <?php elseif ($groupName === 'vendedor'): ?>
                                <span class="badge bg-primary">Vendedor</span>

                            <?php elseif ($groupName === 'gestor'): ?>
                                <span class="badge bg-success">Gestor</span>

                            <?php else: ?>
                                <span class="badge bg-secondary"><?= esc($groupName) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $u->last_active ?? '-'?></td>
                        <td>
                            <button class="btn btn-warning btn-sm editBtn" data-id="<?= $u->id; ?>">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button class="btn btn-danger btn-sm deleteBtn"
                                    data-id="<?= $u->id; ?>">
                                    <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

    <!-- MODAL: CRIAR -->
    <div class="modal fade" id="createModal">
        <div class="modal-dialog">
            <form action="usuarios/create" method="post" class="modal-content">
                <div class="modal-header">
                    <h5>Criar Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <label>Nome</label>
                        <input type="text" name="username" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Senha</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Grupo</label>
                        <select name="group" id="group" class="form-select">
                            <option value="admin">Administrador</option>
                            <option value="gestor">Gerente</option>
                            <option value="vendedor">Vendedor</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDITAR -->
    <div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

        <form id="editForm" method="POST">

            <div class="modal-header">
            <h5 class="modal-title">Editar Usuário</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id" name="id">

                <div class="mb-3">
                    <label>Nome de Usuário</label>
                    <input type="text" id="edit_username" name="username" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" id="edit_email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Senha</label>
                    <input type="password" id="edit_password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Grupo</label>
                    <select name="group" id="edit_group" class="form-select">
                        <option value="admin">Administrador</option>
                        <option value="gestor">Gerente</option>
                        <option value="vendedor">Vendedor</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>

        </form>

        </div>
    </div>
    </div>

    <script>

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

        // -----------------------------
        // EDITAR → ABRIR MODAL
        // -----------------------------
        
        $(document).on("click", ".editBtn", function () {

            let id = $(this).data('id');

            $.ajax({
                url: "/admin/users/get/" + id,
                type: "GET",
                dataType: "json",
                success: function (data) {

                    if (!data || data.status === 'error') {
                        Swal.fire("Erro", "Não foi possível carregar os dados do usuário", "error");
                        return;
                    }

                    $("#edit_id").val(data.user.id);
                    $("#edit_username").val(data.user.username);
                    $("#edit_email").val(data.user.email);
                    $("#edit_group").val(data.user.group);
                    $("#edit_password").val(data.user.password);
                   

                    $("#editForm").attr("action", "/admin/users/update/" + data.user.id);

                    let modal = new bootstrap.Modal(document.getElementById("editModal"));
                    modal.show();
                },

                error: function (xhr) {
                    Swal.fire("Erro", "Falha ao buscar dados. Código: " + xhr.status, "error");
                }
            });
        }); 


        // -----------------------------
        // APAGAR COM SWEETALERT
        // -----------------------------
        $(".deleteBtn").click(function () {
            let id = $(this).data('id');

            Swal.fire({
                title: "Tens certeza?",
                text: "Esta ação não pode ser revertida!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim, apagar!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "users/delete/" + id;
                }
            });
        });

    </script>

<?= $this->endSection() ?>