<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\CompanyModel;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class RegisterController extends BaseController
{
    public function index()
    {
        return view('register');
    }

    public function store()
    {
        $db = db_connect();
        $db->transStart();

        // Validação básica
        if (! $this->validate([
            'company_name' => 'required|min_length[3]',
            'email'        => 'required|valid_email',
            'logo'         => 'permit_empty|is_image[logo]|max_size[logo,2048]'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Dados inválidos.');
        }

         // Upload do logotipo
        $logoName = null;
        $logoFile = $this->request->getFile('logo');

        if ($logoFile && $logoFile->isValid() && ! $logoFile->hasMoved()) {
            $logoName = $logoFile->getRandomName();
            $logoFile->move(FCPATH . 'uploads/companies', $logoName);
        }

        // 1️⃣ Criar empresa
        $db->table('companies')->insert([
            'name'          => $this->request->getPost('company_name'),
            'slug'          => url_title($this->request->getPost('company_name'), '-', true),
            'email'         => $this->request->getPost('email'),
            'nif'           => $this->request->getPost('company_nif'),
            'phone'         => $this->request->getPost('company_phone'),
            'address'       => $this->request->getPost('company_address'),
            'logo'          => $logoName,
            'created_at'    => date('Y-m-d H:i:s'),
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

    public function store1()
    {
        $db = db_connect();
        $db->transStart();

        try {
            $data = $this->request->getPost();

            // 1️⃣ Criar empresa
            $db->table('companies')->insert([
                'name'    => $data['company_name'],
                'slug'    => url_title($data['company_name'], '-', true),
                'nif'     => $data['company_nif'],
                'phone'   => $data['company_phone'],
                'address' => $data['company_address'],
            ]);

            $companyId = $db->insertID();

            // 2️⃣ Criar usuário
            $users = new UserModel();

            $user = new User([
                'username'   => $data['username'],
                'active'     => true,
                'company_id' => $companyId,
            ]);

            $users->save($user);

            // Buscar usuário criado
            $user = $users->where('username', $data['username'])->first();

            if (! $user) {
                throw new \RuntimeException('Falha ao criar usuário.');
            }

            // 3️⃣ Criar identidade (EMAIL + PASSWORD)
            $identities = new UserIdentityModel();

            $identities->insert([
                'user_id' => $user->id,
                'type'    => 'email_password',
                'secret'  => $data['email'],
                'secret2' => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);

            // 4️⃣ Tornar admin
            $user->addGroup('admin');

            // 5️⃣ Login automático
            auth()->login($user);

            $db->transComplete();

            return redirect()->to('/dashboard');

        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function store2()
    {
        
        $data = $this->request->getPost();

        //Criar empresa
        $companyModel = new CompanyModel();
        $companyId = $companyModel->insert([
            'name'  => $data['company_name'],
            'slug'  => url_title($data['company_name'], '-', true),
            'nif'  => $data['company_nif'],
            'phone'  => $data['company_phone'],
            'address'  => $data['company_address'],
        ]);

        $userId    = auth()->id();

        //Criar usuário admin da empresa
        $userModel = new UserModel();

        $user = new User([
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'password'      => $this->request->getPost('password'),
            'company_id'    => $companyId,
            'active'        => true,
            'user_id'       => $userId,
        ]);

        $userModel->save($user);

        //Tornar admin da empresa
        $user = $userModel->findById($userModel->getInsertID());
        $user->addGroup('admin');

        //Login automático
        auth()->login($user);

        return redirect()->to('/dashboard');
    }
}