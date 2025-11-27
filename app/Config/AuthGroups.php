<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Complete control of the site.',
        ],
        'admin' => [
            'title'       => 'Administrador',
            'description' => 'Acesso total ao sistema.',
            'permissions' => [
                '*'
            ],
        ],
        'developer' => [
            'title'       => 'Developer',
            'description' => 'Site programmers.',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'General users of the site. Often customers.',
        ],
        'beta' => [
            'title'       => 'Beta User',
            'description' => 'Has access to beta-level features.',
        ],

        'gestor' => [
            'title'       => 'Gestor',
            'description' => 'Gerencia stock, vendas e caixa.',
            'permissions' => [
                'stock.*',
                'products.*',
                'categories.*',
                'suppliers.*',
                'customers.*',
                'sales.*',
                'cash.*',
            ],
        ],

        'vendedor' => [
            'title'       => 'Vendedor',
            'description' => 'Registra vendas e clientes.',
            'permissions' => [
                'sales.view',
                'sales.create',
                'customers.view',
                'customers.create',
            ],
        ],

        'caixa' => [
            'title'       => 'Operador de Caixa',
            'description' => 'Movimenta o caixa.',
            'permissions' => [
                'cash.view',
                'cash.deposit',
                'cash.withdraw',
            ],
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'        => 'Can access the sites admin area',
        'admin.settings'      => 'Can access the main site settings',
        'users.manage-admins' => 'Can manage other admins',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'beta.access'         => 'Can access beta-level features',
         
        // Stock
        'stock.view',
        'stock.create',
        'stock.update',
        'stock.delete',

        // Produtos
        'products.view',
        'products.create',
        'products.update',
        'products.delete',

        // Categorias
        'categories.view',
        'categories.create',
        'categories.update',
        'categories.delete',

        // Fornecedores
        'suppliers.view',
        'suppliers.create',
        'suppliers.update',
        'suppliers.delete',

        // Clientes
        'customers.view',
        'customers.create',
        'customers.update',
        'customers.delete',

        // Vendas
        'sales.view',
        'sales.create',
        'sales.refund',

        // Caixa
        'cash.view',
        'cash.deposit',
        'cash.withdraw',

        // Utilizadores
        'users.view',
        'users.create',
        'users.update',
        'users.delete',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.*',
            'users.*',
            'beta.*',
        ],
        'admin' => [
            'admin.access',
            'users.create',
            'users.edit',
            'users.delete',
            'beta.access',
        ],
        'developer' => [
            'admin.access',
            'admin.settings',
            'users.create',
            'users.edit',
            'beta.access',
        ],
        'user' => [],
        'beta' => [
            'beta.access',
        ],
    ];
}
