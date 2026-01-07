<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceSequenceModel extends Model
{
    protected $table      = 'invoice_sequences';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'year',
        'last_number',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;

    /**
     * 🔹 Gerar próximo número de fatura
     * Ex: FT-2025-00001
     */
    public function nextNumber(int $companyId): string
    {
        $year = date('Y');

        $sequence = $this->where([
            'company_id' => $companyId,
            'year'       => $year,
        ])->first();

        if (!$sequence) {
            $this->insert([
                'company_id'  => $companyId,
                'year'        => $year,
                'last_number' => 1,
            ]);
            $number = 1;
        } else {
            $number = $sequence['last_number'] + 1;

            $this->update($sequence['id'], [
                'last_number' => $number,
            ]);
        }

        return sprintf('FT/%s/%05d', $year, $number);
    }
}