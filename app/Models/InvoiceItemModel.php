<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceItemModel extends Model
{
    protected $table      = 'invoice_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'total',
        'created_at',
    ];

    protected $useTimestamps = false;

    /**
     * 🔹 Buscar itens de uma fatura
     */
    public function getByInvoice(int $invoiceId)
    {
        return $this->where('invoice_id', $invoiceId)->findAll();
    }
}