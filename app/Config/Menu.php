<?php

namespace App\Config;

class Menu
{
    public static function items($user)
    {
        // ADMIN ----------------------------------------------------
        if ($user->inGroup('admin')) {

            return [
                ['url' => 'dashboard', 'icon' => 'bi bi-speedometer2', 'label' => 'Dashboard'],
                ['url' => 'admin/users', 'icon' => 'bi bi-person', 'label' => 'Usuários'],
                // ===============================
                // SUBMENU PRODUTOS
                // ===============================
                [
                    'icon' => 'bi bi-box',
                    'label' => 'Produtos',
                    'submenu' => [
                        ['url' => 'produtos', 'label' => 'Listar Produtos'],
                        ['url' => 'categorias', 'label' => 'Categorias'],
                    ]
                ],

                ['url' => 'stock', 'icon' => 'bi bi-box-seam', 'label' => 'Stock'],
                ['url' => 'vendas', 'icon' => 'bi bi-cart-check', 'label' => 'Vendas'],
                ['url' => 'clientes', 'icon' => 'bi bi-people', 'label' => 'Clientes'],
                ['url' => 'fornecedores', 'icon' => 'bi bi-truck', 'label' => 'Fornecedores'],
                ['url' => 'fluxo-caixa', 'icon' => 'bi bi-cash-stack', 'label' => 'Fluxo de Caixa'],
                ['url' => 'relatorios', 'icon' => 'bi bi-receipt', 'label' => 'Relatórios'],
                ['url' => 'config', 'icon' => 'bi bi-gear', 'label' => 'Configurações'],
            ];
        }

        // GESTOR ----------------------------------------------------
        if ($user->inGroup('gestor')) {

            return [
                ['url' => 'dashboard', 'icon' => 'bi bi-speedometer2', 'label' => 'Dashboard'],

                // SUBMENU PRODUTOS
                [
                    'icon' => 'bi bi-box',
                    'label' => 'Produtos',
                    'submenu' => [
                        ['url' => 'produtos', 'label' => 'Listar Produtos'],
                        ['url' => 'categorias', 'label' => 'Categorias'],
                    ]
                ],

                ['url' => 'stock', 'icon' => 'bi bi-box-seam', 'label' => 'Stock'],
                ['url' => 'vendas', 'icon' => 'bi bi-cart-check', 'label' => 'Vendas'],
                ['url' => 'clientes', 'icon' => 'bi bi-people', 'label' => 'Clientes'],
                ['url' => 'fornecedores', 'icon' => 'bi bi-truck', 'label' => 'Fornecedores'],
                ['url' => 'fluxo-caixa', 'icon' => 'bi bi-cash-stack', 'label' => 'Fluxo de Caixa'],
                ['url' => 'relatorios', 'icon' => 'bi bi-receipt', 'label' => 'Relatórios'],
                ['url' => 'config', 'icon' => 'bi bi-gear', 'label' => 'Configurações'],
            ];
        }

        // VENDEDOR ----------------------------------------------------
        if ($user->inGroup('vendedor')) {

            return [
                ['url' => 'dashboard', 'icon' => 'bi bi-speedometer2', 'label' => 'Dashboard'],
                ['url' => 'vendas', 'icon' => 'bi bi-cart-check', 'label' => 'Vendas'],
                ['url' => 'clientes', 'icon' => 'bi bi-people', 'label' => 'Clientes'],
                ['url' => 'fluxo-caixa', 'icon' => 'bi bi-cash-stack', 'label' => 'Fluxo de Caixa'],
                ['url' => 'config', 'icon' => 'bi bi-gear', 'label' => 'Configurações'],
            ];
        }

        return [];
    }
}