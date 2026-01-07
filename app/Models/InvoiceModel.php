<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'company_id',
        'sale_id',
        'invoice_number',
        'invoice_type',
        'customer_name',
        'customer_nif',
        'customer_phone',
        'customer_email',
        'customer_address',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
        'issued_at',
        'created_at',
        'updated_at',
        'pdf_path',
        'company_name',
        'company_nif',
        'company_address',
        'company_email',
        'company_phone',
        'company_logo',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * 🔹 Retorna faturas apenas da empresa logada
     */
    public function byCompany(int $companyId)
    {
        return $this->where('company_id', $companyId);
    }

    /**
     * 🔹 Buscar fatura completa (com itens)
     */
    public function findWithItems(int $id, int $companyId)
    {
        return $this->select('invoices.*')
            ->where('invoices.id', $id)
            ->where('invoices.company_id', $companyId)
            ->first();
    }
}
