<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleItemModel extends Model
{
    protected $table            = 'sale_items';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'total'
    ];

    protected $useTimestamps = false;
}