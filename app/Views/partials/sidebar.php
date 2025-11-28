<?php
$user = auth()->user();
?>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4 class="text-center mb-4">Painel Admin</h4>

    <?php if ($user->inGroup('admin')): ?>
        <a href="/dashboard" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="#"><i class="bi bi-person"></i> Usuários</a>
        <a href="#"><i class="bi bi-box-seam"></i> Stock</a>
        <a href="#"><i class="bi bi-cart-check"></i> Vendas</a>
        <a href="#"><i class="bi bi-people"></i> Clientes</a>
        <a href="#"><i class="bi bi-truck"></i> Fornecedores</a>
        <a href="#"><i class="bi bi-cash-stack"></i> Fluxo de Caixa</a>
        <a href="#"><i class="bi bi-receipt"></i> Relatórios</a>
        <a href="#"><i class="bi bi-gear"></i> Configurações</a>
    <?php endif; ?>

    <?php if ($user->can('stock.*')): ?>
        <a href="/stock"><i class="bi bi-box-seam"></i> Stock</a>
    <?php endif; ?>

    <?php if ($user->can('sales.*')): ?>
        <a href="/sales"><i class="bi bi-cart-check"></i> Vendas</a>
    <?php endif; ?>

</div>