<?php

namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Entities\User;

class CompanyRegisterController extends BaseController
{
    public function store2()
    {
        $db = db_connect();
        $db->transStart();

        // 1️⃣ Criar empresa
        $db->table('companies')->insert([
            'name' => $this->request->getPost('company_name'),
            'slug' => url_title($this->request->getPost('company_name')),
            'email' => $this->request->getPost('email'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $companyId = $db->insertID();

        // 2️⃣ Criar usuário admin
        $users = auth()->getProvider();
        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'company_id' => $companyId
        ]);

        $users->save($user);
        $user = $users->findById($users->getInsertID());
        $user->addGroup('admin');

        $db->transComplete();

        return redirect()->to('/login')->with('success', 'Empresa criada com sucesso!');
    }

    public function store3()
    {
        $db = db_connect();
        $db->transStart();

        // 1️⃣ Empresa
        $db->table('companies')->insert([
            'name'       => $this->request->getPost('company_name'),
            'slug'       => url_title($this->request->getPost('company_name'), '-', true),
            'email'      => $this->request->getPost('email'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $companyId = $db->insertID();

        // 2️⃣ Usuário
        $users = new UserModel();

        $user = new User([
            'username'   => $this->request->getPost('username'),
            'company_id' => $companyId,
            'active'     => 1,
        ]);

        $users->save($user);
        $userId = $users->getInsertID();

        // 3️⃣ Identidade
        $identities = new UserIdentityModel();
        $identities->insert([
            'user_id' => $userId,
            'type'    => 'email_password',
            'secret'  => $this->request->getPost('email'),
            'secret2' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
        ]);

        // 4️⃣ Grupo admin
        $user = $users->findById($userId);
        $user->addGroup('admin');

        $db->transComplete();

        return redirect()->to('/login')
            ->with('success', 'Empresa criada com sucesso!');
    }

    public function store()
    {
        $db = db_connect();
        $db->transStart();

        // 1️⃣ Criar empresa
        $db->table('companies')->insert([
            'name'       => $this->request->getPost('company_name'),
            'slug'       => url_title($this->request->getPost('company_name'), '-', true),
            'email'      => $this->request->getPost('email'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $companyId = $db->insertID();

        // 2️⃣ Criar usuário admin
        $users = auth()->getProvider();

        $user = new \CodeIgniter\Shield\Entities\User([
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'company_id' => $companyId,
        ]);

        $users->save($user);

        $user = $users->findById($users->getInsertID());
        $user->addGroup('admin');

        $db->transComplete();

        return redirect()->to('/login')->with('success', 'Empresa criada com sucesso!');
    }


}

