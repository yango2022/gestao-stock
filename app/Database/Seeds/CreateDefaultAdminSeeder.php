<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class CreateDefaultAdminSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        // Verifica se o admin já existe
        $existing = $users->where('username', 'admin')->first();
        if ($existing) {
            return;
        }

        // Criar o utilizador Admin
        $user = new User([
            'username' => 'admin',
            'email'    => 'admin@sistema.com',
            'password' => 'Admin123',
        ]);

        $users->save($user);

        // Buscar o utilizador salvo
        $user = $users->findById($users->getInsertID());

        // Adicionar ao grupo admin
        $user->addGroup('admin');
    }
}
