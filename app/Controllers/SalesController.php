<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class SalesController extends BaseController
{
    protected SaleModel $sales;
    protected SaleItemModel $saleItems;
    protected ProductModel $products;
    protected CustomerModel $customers;

    protected int $companyId;
    protected int $userId;

    public function __construct()
    {
        $this->sales     = new SaleModel();
        $this->saleItems = new SaleItemModel();
        $this->products  = new ProductModel();
        $this->customers = new CustomerModel();

        $this->companyId = auth()->user()->company_id;
        $this->userId    = auth()->id();
    }

    /**
     * LISTAR VENDAS
     */
    public function index()
    {
        return view('sales/index', [
            'sales' => $this->sales
                ->select('sales.*, users.username AS user_name')
                ->join('users', 'users.id = sales.user_id', 'left')
                ->where('sales.company_id', $this->companyId)
                ->orderBy('sales.id', 'DESC')
                ->findAll(),

            'products'  => $this->products
                ->where('company_id', $this->companyId)
                ->findAll(),

            'customers' => $this->customers
                ->where('company_id', $this->companyId)
                ->findAll(),

            'user' => auth()->user(),

            'user_id' => $this->userId,
        ]);
    }

    /**
     * REGISTAR VENDA (AJAX)
     */
    public function store()
    {
        $data = $this->request->getJSON(true);

        if (
            empty($data['items']) ||
            empty($data['payment_method'])
        ) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Dados incompletos.'
            ])->setStatusCode(400);
        }

        $db = db_connect();
        $db->transStart();

        try {
            // 🔹 Cálculos
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $subtotal += (float)$item['total'];
            }

            $discount = isset($data['discount']) ? (float)$data['discount'] : 0;
            $total    = max(0, $subtotal - $discount);


            // 1️⃣ Validar cliente
            if (empty($data['customer_id'])) {
                return redirect()->back()->with('error', 'Cliente inválido.');
            }

             $customer = $this->customers->find($data['customer_id']);

            if (! $customer) {
                return redirect()->back()->with('error', 'Cliente não encontrado.');
            }

            // 🔹 Criar venda
            $saleId = $this->sales->insert([
                'company_id'     => $this->companyId,
                'user_id'        => $this->userId,
                'customer_id'    => $customer['id'] ?? null,
                'customer_name'  => $customer['name'] ?? null,
                'customer_nif'    => $customer['nif'] ?? null,
                'customer_phone'  => $customer['phone'] ?? null,
                'customer_email'  => $customer['email'] ?? null,
                'customer_address'  => $customer['address'] ?? null,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'total'          => $total,
                'payment_method' => $data['payment_method'],
                'status'         => 'paid',
                'created_at'     => date('Y-m-d H:i:s'),
            ]);

            // 🔹 Itens + Stock
            foreach ($data['items'] as $item) {
                $product = $this->products
                    ->where('id', $item['product_id'])
                    ->where('company_id', $this->companyId)
                    ->first();

                if (! $product || $product->current_stock < $item['quantity']) {
                    throw new DatabaseException('Stock insuficiente.');
                }

                // Item da venda
                $this->saleItems->insert([
                    'sale_id'    => $saleId,
                    'product_id' => $product->id,
                    'unit_price' => $item['unit_price'],
                    'quantity'   => $item['quantity'],
                    'total'      => $item['total'],
                ]);

                // Atualizar stock
                $this->products->update($product->id, [
                    'current_stock' => $product->current_stock - $item['quantity']
                ]);
            }

            $db->transComplete();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Venda registada com sucesso!',
                'sale_id' => $saleId
            ]);

        } catch (\Throwable $e) {
            $db->transRollback();

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * REMOVER VENDA
     */
    public function delete($id)
    {
        $sale = $this->sales
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $sale) {
            return redirect()->back()->with('error', 'Venda inválida.');
        }

        $this->sales->delete($id);

        return redirect()->back()->with('success', 'Venda removida!');
    }
}