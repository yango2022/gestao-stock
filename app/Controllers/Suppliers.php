<?php

namespace App\Controllers;

use App\Models\SupplierModel;

class Suppliers extends BaseController
{
    protected $supplier;

    public function __construct()
    {
        $this->supplier = new SupplierModel();
    }

    //LISTAR FORNECEDORES
    public function index()
    {
        $data['suppliers'] = $this->supplier->findAll();
        $data['user'] = auth()->user();

        return view('suppliers/index', $data);
    }

    //SALVAR NOVO FORNECEDOR
    public function store()
    {
        $this->supplier->save([
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ]);

        return redirect()->back()->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    //BUSCAR PARA EDIÇÃO (AJAX)
    public function get($id)
    {
        $supplier = $this->supplier->find($id);

        if (!$supplier) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Fornecedor não encontrado']);
        }

        return $this->response->setJSON(['status' => 'success', 'supplier' => $supplier]);
    }

    //ATUALIZAR
    public function update($id)
    {
        $this->supplier->update($id, [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ]);

        return redirect()->back()->with('success', 'Fornecedor atualizado!');
    }

    //APAGAR
    public function delete($id)
    {
        $this->supplier->delete($id);
        return redirect()->back()->with('success', 'Fornecedor removido!');
    }
}