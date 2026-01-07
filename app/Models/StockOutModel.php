<?php

namespace App\Models;

use CodeIgniter\Model;

class StockOutModel extends Model
{
    protected $table = 'stock_out';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'company_id',
        'quantity',
        'user_id',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
}