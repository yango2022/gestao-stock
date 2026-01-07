<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\CompanyModel;

class CompanyController extends BaseController
{
    public function index()
    {
        $this->guard();

        return view('master/companies/index', [
            'companies' => (new CompanyModel())->orderBy('created_at', 'DESC')->findAll()
        ]);
    }

    public function show($id)
    {
        $this->guard();

        $company = (new CompanyModel())->find($id);

        if (!$company) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('master/companies/show', compact('company'));
    }

    public function toggle($id)
    {
        $this->guard();

        $model = new CompanyModel();
        $company = $model->find($id);

        $model->update($id, [
            'status' => ! $company['status']
        ]);

        return redirect()->back()->with('success', 'Estado da empresa atualizado.');
    }

    private function guard()
    {
        if (!is_superadmin()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
}