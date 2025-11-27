<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class CreateAdminUser extends Migration
{
    public function up()
    {
        // Criar o usuário admin
        $users = new UserModel();

        $user = new User([
            'username' => 'admin',
            'email'    => 'admin@sistema.com',
            'password' => 'admin123', // será hashed automaticamente pelo Shield
        ]);

        // Inserir usuário
        $users->save($user);


        // Buscar o utilizador salvo
        $user = $users->findById($users->getInsertID());

        // Adicionar ao grupo admin
        $user->addGroup('admin');
    }

    public function down()
    {
        $users = new UserModel();
        $users->where('email', 'admin@sistema.com')->delete();
    }
}