<!DOCTYPE html>
<html lang="pt" data-theme="auto">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Gestão de Stock' ?></title>

    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>

<body>

    <?= $this->include('partials/sidebar') ?>

    <main class="content">
        <!-- TOPBAR -->
        <div class="topbar d-flex justify-content-between align-items-center mb-4">
            <h4>Dashboard</h4>

            <div>
                <span class="me-3">Olá, <?= esc($user->username) ?></span>

                <a href="/logout" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </a>
            </div>
        </div>
        <?= $this->renderSection('content') ?>
    </main>

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

    <?= $this->include('layouts/theme-toggle') ?>
    <script src="/assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>