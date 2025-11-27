<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run()
    {
        $db = db_connect();

        // Verificar se já existe
        $exists = $db->table('auth_groups')->where('name', 'admin')->get()->getRow();

        if (! $exists) {
            $db->table('auth_groups')->insert([
                'name'        => 'admin',
                'description' => 'Administrador do sistema'
            ]);
        }
    }
}