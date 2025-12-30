<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,

            'company_id',
        ];
    }

    public function findByCompany(int $id, int $companyId)
    {
        return $this->where('id', $id)
                    ->where('company_id', $companyId)
                    ->first();
    }
}
