<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Empresas Registadas</h4>
        <span class="badge bg-primary">
            Total: <?= count($companies) ?>
        </span>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="myTable">
                    <thead class="table-light">
                        <tr>
                            <th>Empresa</th>
                            <th>NIF</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if (empty($companies)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Nenhuma empresa registada
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($companies as $c): ?>
                        <tr>
                            <td>
                                <strong><?= esc($c['name']) ?></strong>
                            </td>

                            <td>
                                <?= esc($c['nif']) ?>
                            </td>

                            <td>
                                <?php if ($c['status']): ?>
                                    <span class="badge bg-success">Ativa</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Suspensa</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-end">
                                <a href="<?= site_url('master/empresas/' . $c['id']) ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Ver
                                </a>

                                <form action="<?= site_url('master/empresas/'.$c['id'].'/toggle') ?>"
                                      method="post"
                                      class="d-inline"
                                      onsubmit="return confirm('Tem certeza?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm <?= $c['status'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                        <i class="bi <?= $c['status'] ? 'bi-lock' : 'bi-unlock' ?>"></i>
                                        <?= $c['status'] ? 'Suspender' : 'Ativar' ?>
                                    </button>
                                </form>
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