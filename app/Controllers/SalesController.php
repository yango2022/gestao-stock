<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SaleModel;

class SalesController extends BaseController
{
    protected $sales;
    protected $user;

    public function __construct()
    {
        $this->sales = new SaleModel();
        $this->user  = auth()->user();
    }

    // 🔹 LISTAR TODAS AS VENDAS
    public function index()
    {
        $data['sales'] = $this->sales
            ->select('sales.*, users.username AS user_name')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->orderBy('sales.id', 'DESC')
            ->findAll();

        return view('sales/index', $data);
    }


    // 🔹 MOSTRAR FORMULÁRIO
    public function create()
    {
        return view('sales/create');
    }


    // 🔹 SALVAR VENDA
    public function store()
    {
        // validação básica
        $rules = [
            'customer_name'  => 'required',
            'subtotal'       => 'required|numeric',
            'discount'       => 'permit_empty|numeric',
            'payment_method' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Preencha todos os campos obrigatórios!');
        }

        $subtotal = (float) $this->request->getPost('subtotal');
        $discount = (float) $this->request->getPost('discount');
        $total    = $subtotal - $discount;

        $this->sales->save([
            'user_id'        => auth()->id(),
            'customer_name'  => $this->request->getPost('customer_name'),
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'total'          => $total,
            'payment_method' => $this->request->getPost('payment_method'),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/sales')->with('success', 'Venda registada com sucesso!');
    }


    // 🔹 APAGAR VENDA
    public function delete($id)
    {
        if (! $this->sales->find($id)) {
            return redirect()->back()->with('error', 'Venda não encontrada!');
        }

        $this->sales->delete($id);

        return redirect()->back()->with('success', 'Venda eliminada!');
    }
}