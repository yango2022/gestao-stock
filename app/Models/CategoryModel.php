<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table      = 'categories';  // nome da tabela
    protected $primaryKey = 'id';          // chave primária
    protected $returnType = 'array';       // retorna os registros como array
    protected $useSoftDeletes = true;      // opcional: se quiser soft delete
    protected $allowedFields = [
        'company_id',
        'name',
        'description',
        'created_at',
    ];

    // Timestamp automático
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';

    // Regras de validação
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[120]',
        'description' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'O nome da categoria é obrigatório.',
            'min_length' => 'O nome da categoria deve ter pelo menos 2 caracteres.',
            'max_length' => 'O nome da categoria não pode exceder 120 caracteres.'
        ]
    ];
}