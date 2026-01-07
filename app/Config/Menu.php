<?php

namespace App\Config;

class Menu
{
    public static function items($user)
    {

        // ===============================
        // MASTER (SUPER ADMIN DO SAAS)
        // ===============================
        if ($user->inGroup('superadmin')) {
            return [
                ['url' => 'master/dashboard', 'icon' => 'bi bi-shield-lock', 'label' => 'Painel Master'],
                ['url' => 'master/empresas', 'icon' => 'bi bi-buildings', 'label' => 'Empresas'],
                ['url' => 'master/usuarios', 'icon' => 'bi bi-people', 'label' => 'Usuários'],
                //['url' => 'master/relatorios', 'icon' => 'bi bi-bar-chart', 'label' => 'Relatórios'],
            ];
        }
        
        // ADMIN ----------------------------------------------------
        if ($user->inGroup('admin')) {
            return [
                ['url' => 'dashboard', 'icon' => 'bi bi-speedometer2', 'label' => 'Dashboard'],
                ['url' => 'usuarios', 'icon' => 'bi bi-person', 'label' => 'Usuários'],

                // PRODUTOS (itens diretos)
                ['url' => 'produtos', 'icon' => 'bi bi-box', 'label' => 'Produtos'],
                ['url' => 'categorias', 'icon' => 'bi bi-tags', 'label' => 'Categorias'],

                ['url' => 'stock', 'icon' => 'bi bi-box-seam', 'label' => 'Stock'],
                ['url' => 'vendas', 'icon' => 'bi bi-cart-check', 'label' => 'Vendas'],
                ['url' => 'clientes', 'icon' => 'bi bi-people', 'label' => 'Clientes'],
                ['url' => 'fornecedores', 'icon' => 'bi bi-truck', 'label' => 'Fornecedores'],
                ['url' => 'fluxo-caixa', 'icon' => 'bi bi-cash-stack', 'label' => 'Fluxo de Caixa'],
                //['url' => 'relatorios', 'icon' => 'bi bi-receipt', 'label' => 'Relatórios'],
               // ['url' => 'config', 'icon' => 'bi bi-gear', 'label' => 'Configurações'],
            ];
        }

        // GESTOR ----------------------------------------------------
        if ($user->inGroup('gestor')) {
            return [
                ['url' => 'dashboard', 'icon' => 'bi bi-speedometer2', 'label' => 'Dashboard'],

                // PRODUTOS (itens diretos)
                ['url' => 'produtos', 'icon' => 'bi bi-box', 'label' => 'Produtos'],
                ['url' => 'categorias', 'icon' => 'bi bi-tags', 'label' => 'Categorias'],

                ['url' => 'stock', 'icon' => 'bi bi-box-seam', 'label' => 'Stock'],
                ['url' => 'vendas', 'icon' => 'bi bi-cart-check', 'label' => 'Vendas'],
                ['url' => 'clientes', 'icon' => 'bi bi-people', 'label' => 'Clientes'],
                ['url' => 'fornecedores', 'icon' => 'bi bi-truck', 'label' => 'Fornecedores'],
                ['url' => 'fluxo-caixa', 'icon' => 'bi bi-cash-stack', 'label' => 'Fluxo de Caixa'],
                //['url' => 'relatorios', 'icon' => 'bi bi-receipt', 'label' => 'Relatórios'],
               // ['url' => 'config', 'icon' => 'bi bi-gear', 'label' => 'Configurações'],
            ];
        }

        // VENDEDOR ----------------------------------------------------
        if ($user->inGroup('vendedor')) {
            return [
                ['url' => 'dashboard', 'icon' => 'bi bi-speedometer2', 'label' => 'Dashboard'],
                ['url' => 'vendas', 'icon' => 'bi bi-cart-check', 'label' => 'Vendas'],
                ['url' => 'clientes', 'icon' => 'bi bi-people', 'label' => 'Clientes'],
                ['url' => 'fluxo-caixa', 'icon' => 'bi bi-cash-stack', 'label' => 'Fluxo de Caixa'],
               // ['url' => 'config', 'icon' => 'bi bi-gear', 'label' => 'Configurações'],
            ];
        }

        return [];
    }
}