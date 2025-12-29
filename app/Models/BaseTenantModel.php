<?php


namespace App\Models;

use CodeIgniter\Model;

class BaseTenantModel extends Model
{
    protected function tenant()
    {
        return $this->where('company_id', auth()->user()->company_id);
    }
}
