<?php
use App\Config\Menu;

/** @var \CodeIgniter\HTTP\IncomingRequest $request */
$request = service('request');

$user  = auth()->user();
$menu  = Menu::items($user);
$path  = trim($request->getUri()->getPath(), '/');
?>

<aside class="sidebar border border-right col-md-3 col-lg-2 p-0">
    
    <h5 class="text-center p-3 mb-0">
        <?php if (auth()->user() && auth()->user()->inGroup('superadmin')): ?>
            <?= esc(auth()->user()->username) ?>
        <?php else: ?>
            <?= esc($company['name'] ?? 'Painel') ?>
        <?php endif; ?>
    </h5>

    <ul class="nav flex-column">

        <?php foreach ($menu as $item): ?>

            <?php
                // verifica se a rota atual começa com a url do menu
                $isActive = str_starts_with($path, trim($item['url'], '/'));
            ?>

            <li class="nav-item mb-1">
                <a href="<?= base_url($item['url']) ?>"
                   class="nav-link d-flex align-items-center <?= $isActive ? 'active' : '' ?>">

                    <i class="<?= $item['icon'] ?> me-2"></i>
                    <?= esc($item['label']) ?>

                </a>
            </li>

        <?php endforeach ?>

    </ul>

    <hr class="my-3" />

    <!-- RODAPÉ / AÇÕES -->
    <ul class="nav flex-column mb-auto">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2 text-danger"
                href="/logout">
                <i class="bi bi-box-arrow-right"></i>
                Sair
            </a>
        </li>
    </ul>

</aside>

<style>
    .nav-link.active {
        background-color: rgba(40, 76, 235, 0.6);
        border-radius: 6px;
        font-weight: 600;
    }
</style>