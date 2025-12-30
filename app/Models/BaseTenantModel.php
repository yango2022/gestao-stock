<?php


namespace App\Models;

use CodeIgniter\Model;

class BaseTenantModel extends Model
{

    protected int $companyId;

    public function __construct()
    {
        parent::__construct();

        $user = auth()->user();

        if ($user && $user->company_id) {
            $this->companyId = $user->company_id;
        }
    }

    protected function applyCompanyScope()
    {
        return $this->where($this->table . '.company_id', $this->companyId);
    }

    public function findAllByCompany($limit = 0, $offset = 0)
    {
        return $this->applyCompanyScope()->findAll($limit, $offset);
    }

    public function findByCompany($id)
    {
        return $this->applyCompanyScope()->where('id', $id)->first();
    }
}
