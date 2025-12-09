<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SaleModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\SaleItemModel;


class SalesController extends BaseController
{
    protected $sales;
    protected $saleItems;
    protected $user;
    protected $product;
    protected $customers;

    public function __construct()
    {
        $this->sales = new SaleModel();
        $this->saleItems = new SaleItemModel();
        $this->product = new ProductModel();
        $this->customers = new CustomerModel();
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
        $data['user'] = $this->user;
        $data['user_id'] = auth()->id();
        $data['products'] = $this->product->findAll();
        $data['customers'] = $this->customers->findAll();

        return view('sales/index', $data);
    }


    // 🔹 MOSTRAR FORMULÁRIO
    public function create()
    {
        return view('sales/create');
    }


    // 🔹 SALVAR VENDA
    public function store2()
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

        return redirect()->to('/vendas')->with('success', 'Venda registada com sucesso!');
    }

    public function store()
    {
        // Receber JSON
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Dados inválidos.'
            ]);
        }

        // Validação
        if (empty($data['customer_name']) || empty($data['payment_method']) || empty($data['items'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Preencha todos os campos obrigatórios.'
            ]);
        }

        // Cálculos
        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += (float)$item['total'];
        }

        $discount = isset($data['discount']) ? (float)$data['discount'] : 0;
        $total    = $subtotal - $discount;
        if ($total < 0) $total = 0;

        // Salvar venda
        $saleId = $this->sales->insert([
            'user_id'        => $data['user_id'],
            'customer_name'    => $data['customer_name'],
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'total'          => $total,
            'payment_method' => $data['payment_method'],
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        // Salvar itens
        foreach ($data['items'] as $item) {
            $this->saleItems->insert([
                'sale_id'    => $saleId,
                'product_id' => $item['product_id'],
                'unit_price'      => $item['unit_price'],
                'quantity'   => $item['quantity'],
                'total'      => $item['total'],
            ]);
        }

        // 🔹 Atualizar stock após a venda
        foreach ($data['items'] as $item) {
            $product = $this->product->find($item['product_id']);
            if ($product) {
                $newStock = $product->current_stock - intval($item['quantity']);
                if ($newStock < 0) $newStock = 0;

                $this->product->update($item['product_id'], [
                    'current_stock' => $newStock
                ]);
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Venda registada com sucesso!',
            'sale_id' => $saleId
        ]);


        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Venda registada com sucesso!',
            'sale_id' => $saleId
        ]);
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