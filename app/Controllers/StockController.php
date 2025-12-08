<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockEntryModel;
use App\Models\StockOutModel;
use App\Models\SupplierModel;

class StockController extends BaseController
{
    protected $products;
    protected $entries;
    protected $outs;
    protected $suppliers;

    public function __construct()
    {
        $this->products  = new ProductModel();
        $this->entries   = new StockEntryModel();
        $this->outs      = new StockOutModel();
        $this->suppliers = new SupplierModel();
    }

    public function index()
    {
        // LISTAR ENTRADAS COM O USUÁRIO
        $data['entries'] = $this->entries
            ->select('
                stock_entries.*, 
                products.name AS product_name, 
                suppliers.name AS supplier_name,
                users.username AS user_name
            ')
            ->join('products', 'products.id = stock_entries.product_id')
            ->join('suppliers', 'suppliers.id = stock_entries.supplier_id', 'left')
            ->join('users', 'users.id = stock_entries.user_id', 'left') // 👈 JOIN NO USUÁRIO
            ->orderBy('stock_entries.id', 'DESC')
            ->findAll();

        // LISTAR SAÍDAS COM O USUÁRIO
        $data['outs'] = $this->outs
            ->select('
                stock_out.*, 
                products.name AS product_name,
                users.username AS user_name
            ')
            ->join('products', 'products.id = stock_out.product_id')
            ->join('users', 'users.id = stock_out.user_id', 'left') // 👈 JOIN NO USUÁRIO
            ->orderBy('stock_out.id', 'DESC')
            ->findAll();

        // DADOS PARA FORMULÁRIOS
        $data['products']  = $this->products->findAll();
        $data['suppliers'] = $this->suppliers->findAll();
        $data['user'] = auth()->user();
        $data['user_id'] = auth()->id();

        return view('stock/index', $data);
    }

    public function entrada()
    {
        $data = $this->request->getPost();

        // Validação básica
        if (!$this->validate([
            'product_id'  => 'required',
            'supplier_id' => 'trim',
            'quantity'    => 'required|numeric',
            'unit_cost'   => 'required|numeric'
        ])) {
            return redirect()->back()->with('error', 'Preencha todos os campos!');
        }

        $quantity  = (float) $data['quantity'];
        $unit_cost = (float) $data['unit_cost'];
        $total_cost = $quantity * $unit_cost;

        $entryModel = new \App\Models\StockEntryModel();
        $productModel = new \App\Models\ProductModel();

        // Salvar entrada
        $entryModel->insert([
            'product_id'  => $data['product_id'],
            'supplier_id' => $data['supplier_id'],
            'user_id'  => $data['user_id'],
            'quantity'    => $quantity,
            'unit_cost'   => $unit_cost,
            'total_cost'  => $total_cost,
        ]);

        // Atualizar stock no produto
        $product = $productModel->find($data['product_id']);
        $newStock = $product->current_stock + $quantity;

        $productModel->update($data['product_id'], [
            'current_stock' => $newStock,
            'cost_price'    => $unit_cost, // opcional, dependendo da tua regra
        ]);

        return redirect()->back()->with('success', 'Entrada registada com sucesso!');
    }

    public function saida()
    {
        $post = $this->request->getPost();

        $product = $this->products->find($post['product_id']);

        if ($product->current_stock < $post['quantity']) {
            return redirect()->back()->with('error', 'Stock insuficiente!');
        }

        $this->outs->save($post);

        $this->products->update($product->id, [
            'current_stock' => $product->current_stock - $post['quantity']
        ]);

        return redirect()->back()->with('success', 'Saída registada!');
    }
}