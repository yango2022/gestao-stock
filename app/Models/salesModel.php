<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table            = 'sales';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'user_id',
        'customer_name',
        'subtotal',
        'discount',
        'total',
        'payment_method',
        'created_at'
    ];

    protected $useTimestamps    = false; // já tens created_at manual
}