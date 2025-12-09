<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomerController extends BaseController
{
    protected $customers;

    public function __construct()
    {
        $this->customers = new CustomerModel();
    }

    public function index()
    {
        $user = auth()->user();
        $data['customers'] = $this->customers->findAll();
        $data['user'] = $user;

        return view('customers/index', $data);
    }

    public function store()
    {
        $this->customers->save([
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city'    => $this->request->getPost('city'),
            'nif'     => $this->request->getPost('nif'),
        ]);

        return redirect()->back()->with('success', 'Cliente criado com sucesso!');
    }

    public function get($id)
    {
        $customer = $this->customers->find($id);

        return $this->response->setJSON([
            'status' => 'success',
            'customer' => $customer
        ]);
    }

    public function update($id)
    {
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

    public function delete($id)
    {
        $this->customers->delete($id);

        return redirect()->back()->with('success', 'Cliente removido com sucesso!');
    }
}