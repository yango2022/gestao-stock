<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;

class CustomerController extends BaseController
{
    protected CustomerModel $customers;
    protected int $companyId;

    public function __construct()
    {
        $this->customers = new CustomerModel();
        $this->companyId = auth()->user()->company_id;
    }

    /**
     * LISTAR CLIENTES
     */
    public function index()
    {
        return view('customers/index', [
            'customers' => $this->customers
                ->where('company_id', $this->companyId)
                ->orderBy('id', 'DESC')
                ->findAll(),
            'user' => auth()->user(),
        ]);
    }

    /**
     * CRIAR CLIENTE
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

        $this->customers->insert([
            'company_id' => $this->companyId,
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'address'    => $this->request->getPost('address'),
            'city'       => $this->request->getPost('city'),
            'nif'        => $this->request->getPost('nif'),
        ]);

        return redirect()->back()->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * OBTER CLIENTE (AJAX)
     */
    public function get($id)
    {
        $customer = $this->customers
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $customer) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Cliente não encontrado.'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'customer' => $customer
        ]);
    }

    /**
     * ATUALIZAR CLIENTE
     */
    public function update($id)
    {
        $customer = $this->customers
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $customer) {
            return redirect()->back()->with('error', 'Cliente inválido.');
        }

        $this->customers->update($id, [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city'    => $this->request->getPost('city'),
            'nif'     => $this->request->getPost('nif'),
        ]);

        return redirect()->back()->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * REMOVER CLIENTE
     */
    public function delete($id)
    {
        $customer = $this->customers
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $customer) {
            return redirect()->back()->with('error', 'Cliente inválido.');
        }

        $this->customers->delete($id);

        return redirect()->back()->with('success', 'Cliente removido com sucesso!');
    }
}