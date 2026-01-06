<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $table = 'companies';
    protected $allowedFields = [
        'name', 'slug', 'email', 'phone', 'nif', 'address', 'status'
    ];
}
