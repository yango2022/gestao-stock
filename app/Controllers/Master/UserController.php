<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        if (!is_superadmin()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('master/users/index', [
            'users' => (new UserModel())
                ->select('users.*, companies.name as company')
                ->join('companies', 'companies.id = users.company_id', 'left')
                ->orderBy('users.created_at', 'DESC')
                ->findAll()
        ]);
    }
}