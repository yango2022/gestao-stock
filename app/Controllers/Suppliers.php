<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplierModel;

class Suppliers extends BaseController
{
    protected SupplierModel $supplier;
    protected int $companyId;

    public function __construct()
    {
        $this->supplier  = new SupplierModel();
        $this->companyId = auth()->user()->company_id;
    }

    /**
     * LISTAR FORNECEDORES
     */
    public function index()
    {
        return view('suppliers/index', [
            'suppliers' => $this->supplier
                ->where('company_id', $this->companyId)
                ->orderBy('id', 'DESC')
                ->findAll(),
            'user' => auth()->user(),
        ]);
    }

    /**
     * CRIAR FORNECEDOR
     */
    public function store()
    {
        if (! $this->validate([
            'name'  => 'required|min_length[3]',
            'email' => 'permit_empty|valid_email',
            'phone' => 'permit_empty|min_length[6]',
        ])) {
            return redirect()->back()->with('error', 'Dados inválidos.');
        }

        $this->supplier->insert([
            'company_id' => $this->companyId,
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'address'    => $this->request->getPost('address'),
        ]);

        return redirect()->back()->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    /**
     * BUSCAR FORNECEDOR (AJAX)
     */
    public function get($id)
    {
        $supplier = $this->supplier
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $supplier) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Fornecedor não encontrado.'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'supplier' => $supplier
        ]);
    }

    /**
     * ATUALIZAR FORNECEDOR
     */
    public function update($id)
    {
        $supplier = $this->supplier
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $supplier) {
            return redirect()->back()->with('error', 'Fornecedor inválido.');
        }

        $this->supplier->update($id, [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ]);

        return redirect()->back()->with('success', 'Fornecedor atualizado com sucesso!');
    }

    /**
     * REMOVER FORNECEDOR
     */
    public function delete($id)
    {
        $supplier = $this->supplier
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $supplier) {
            return redirect()->back()->with('error', 'Fornecedor inválido.');
        }

        $this->supplier->delete($id);

        return redirect()->back()->with('success', 'Fornecedor removido!');
    }
}