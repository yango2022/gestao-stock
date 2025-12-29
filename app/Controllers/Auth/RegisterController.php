<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\CompanyModel;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class RegisterController extends BaseController
{
    public function index()
    {
        return view('register');
    }

    public function store()
    {
        $data = $this->request->getPost();

        // 1️⃣ Criar empresa
        $companyModel = new CompanyModel();
        $companyId = $companyModel->insert([
            'name'  => $data['company_name'],
            'slug'  => url_title($data['company_name'], '-', true),
            'email' => $data['email'],
        ]);

        $userId    = auth()->id();

        // 2️⃣ Criar usuário admin da empresa
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

        // 3️⃣ Tornar admin da empresa
        $user = $userModel->findById($userModel->getInsertID());
        $user->addGroup('admin');

        // 4️⃣ Login automático
        auth()->login($user);

        return redirect()->to('/dashboard');
    }
}