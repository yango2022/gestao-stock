<?php
$user = auth()->user();
?>

<div class="sidebar position-fixed h-100 p-3 shadow">
    <h5 class="mb-4">Menu</h5>

    <ul class="nav flex-column">

        <li class="nav-item mb-2">
            <a href="/dashboard" class="nav-link">📊 Dashboard</a>
        </li>

        <?php if ($user->inGroup('admin')): ?>
            <li class="nav-item mb-2">
                <a href="/users" class="nav-link">👤 Gestão de Usuários</a>
            </li>

            <li class="nav-item mb-2">
                <a href="/stock" class="nav-link">📦 Stock</a>
            </li>

            <li class="nav-item mb-2">
                <a href="/sales" class="nav-link">💵 Vendas</a>
            </li>

            <li class="nav-item mb-2">
                <a href="/settings" class="nav-link">⚙ Configurações</a>
            </li>
        <?php endif; ?>

        <?php if ($user->can('stock.*')): ?>
            <li class="nav-item mb-2">
                <a href="/stock" class="nav-link">📦 Stock</a>
            </li>
        <?php endif; ?>

        <?php if ($user->can('sales.*')): ?>
            <li class="nav-item mb-2">
                <a href="/sales" class="nav-link">💵 Vendas</a>
            </li>
        <?php endif; ?>
    </ul>
</div>