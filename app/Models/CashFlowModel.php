<?php

namespace App\Models;

use CodeIgniter\Model;

class CashFlowModel extends Model
{
    protected $table = 'cash_flow';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'type',
        'category',
        'amount',
        'reference_id',
        'note',
        'created_at'
    ];

    protected $useTimestamps = true;
}