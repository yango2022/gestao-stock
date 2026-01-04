<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\StockEntryModel;
use App\Models\StockOutModel;
use App\Models\SupplierModel;

class StockController extends BaseController
{
    protected ProductModel $products;
    protected StockEntryModel $entries;
    protected StockOutModel $outs;
    protected SupplierModel $suppliers;

    protected int $companyId;
    protected int $userId;

    public function __construct()
    {
        $this->products  = new ProductModel();
        $this->entries   = new StockEntryModel();
        $this->outs      = new StockOutModel();
        $this->suppliers = new SupplierModel();

        $this->companyId = auth()->user()->company_id;
        $this->userId    = auth()->id();
    }

    /**
     * DASHBOARD DE STOCK
     */
    public function index()
    {
        // ENTRADAS
        $entries = $this->entries
            ->select('
                stock_entries.*,
                products.name   AS product_name,
                suppliers.name  AS supplier_name,
                users.username  AS user_name
            ')
            ->join('products', 'products.id = stock_entries.product_id')
            ->join('suppliers', 'suppliers.id = stock_entries.supplier_id', 'left')
            ->join('users', 'users.id = stock_entries.user_id', 'left')
            ->where('stock_entries.company_id', $this->companyId)
            ->orderBy('stock_entries.id', 'DESC')
            ->findAll();

        // SAÍDAS
        $outs = $this->outs
            ->select('
                stock_out.*,
                products.name AS product_name,
                users.username AS user_name
            ')
            ->join('products', 'products.id = stock_out.product_id')
            ->join('users', 'users.id = stock_out.user_id', 'left')
            ->where('stock_out.company_id', $this->companyId)
            ->orderBy('stock_out.id', 'DESC')
            ->findAll();

        return view('stock/index', [
            'entries'   => $entries,
            'outs'      => $outs,
            'products'  => $this->products->where('company_id', $this->companyId)->findAll(),
            'suppliers' => $this->suppliers->where('company_id', $this->companyId)->findAll(),
            'user'      => auth()->user(),
            'user_id'   => $this->userId,
        ]);
    }

    /**
     * ENTRADA DE STOCK
     */
    public function entrada()
    {
        $data = $this->request->getPost();

        if (! $this->validate([
            'product_id'  => 'required|integer',
            'quantity'    => 'required|numeric|greater_than[0]',
            'unit_cost'   => 'required|numeric|greater_than[0]',
        ])) {
            return redirect()->back()->with('error', 'Dados inválidos.');
        }

        // Produto da mesma empresa
        $product = $this->products
            ->where('id', $data['product_id'])
            ->where('company_id', $this->companyId)
            ->first();

        if (! $product) {
            return redirect()->back()->with('error', 'Produto inválido.');
        }

        $quantity   = (float) $data['quantity'];
        $unitCost   = (float) $data['unit_cost'];
        $totalCost  = $quantity * $unitCost;

        // REGISTAR ENTRADA
        $this->entries->insert([
            'company_id'  => $this->companyId,
            'product_id'  => $product->id,
            'supplier_id' => $data['supplier_id'] ?? null,
            'user_id'     => $this->userId,
            'quantity'    => $quantity,
            'unit_cost'   => $unitCost,
            'total_cost'  => $totalCost,
        ]);

        // ATUALIZAR STOCK DO PRODUTO
        $this->products->update($product->id, [
            'current_stock' => $product->current_stock + $quantity,
            'cost_price'    => $unitCost,
        ]);

        return redirect()->back()->with('success', 'Entrada registada com sucesso!');
    }

    /**
     * SAÍDA DE STOCK
     */
    public function saida()
    {
        $data = $this->request->getPost();

        if (! $this->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|numeric|greater_than[0]',
        ])) {
            return redirect()->back()->with('error', 'Dados inválidos.');
        }

        $product = $this->products
            ->where('id', $data['product_id'])
            ->where('company_id', $this->companyId)
            ->first();

        if (! $product) {
            return redirect()->back()->with('error', 'Produto inválido.');
        }

        if ($product->current_stock < $data['quantity']) {
            return redirect()->back()->with('error', 'Stock insuficiente.');
        }

        // REGISTAR SAÍDA
        $this->outs->insert([
            'company_id' => $this->companyId,
            'product_id' => $product->id,
            'user_id'    => $this->userId,
            'quantity'   => $data['quantity'],
        ]);

        // ATUALIZAR STOCK
        $this->products->update($product->id, [
            'current_stock' => $product->current_stock - $data['quantity'],
        ]);

        return redirect()->back()->with('success', 'Saída registada com sucesso!');
    }
}