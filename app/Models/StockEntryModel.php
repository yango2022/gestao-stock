<?php

namespace App\Models;

use CodeIgniter\Model;

class StockEntryModel extends Model
{
    protected $table = 'stock_entries';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'supplier_id',
        'company_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'user_id',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
}