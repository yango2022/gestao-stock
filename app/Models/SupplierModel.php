<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'name',
        'email',
        'phone',
        'address',
        'created_at',
        'updated_at'
    ];
}
