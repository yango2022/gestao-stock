<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Usuários do Sistema</h4>
        <span class="badge bg-primary">
            Total: <?= count($users) ?>
        </span>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="myTable">
                    <thead class="table-light">
                        <tr>
                            <th>Usuário</th>
                            <th>Email</th>
                            <th>Empresa</th>
                            <th>Grupos</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Nenhum usuário encontrado
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <strong><?= esc($u->username) ?></strong>
                            </td>

                            <td>
                                <?= esc($u->email) ?>
                            </td>

                            <td>
                                <?= esc($u->company ?? '—') ?>
                            </td>

                            <td>
                                <?php foreach ($u->getGroups() as $group): ?>
                                    <span class="badge bg-secondary me-1">
                                        <?= esc($group) ?>
                                    </span>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>