<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends BaseTenantModel
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $returnType       = 'App\Entities\Product';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'company_id', 'name', 'sku', 'category_id', 'cost_price', 'unit_price',
        'current_stock', 'min_stock', 'image', 'iva_rate', 'iva_type'
    ];

    protected $validationRules = [
        'name' => 'required|min_length[2]',
        'cost_price' => 'required|decimal',
        'unit_price' => 'required|decimal',
        'iva_type' => 'required|in_list[normal,reduzido,isento]',
        'iva_rate' => 'required|decimal'
    ];

    public function decreaseStock($productId, $qty)
    {
        return $this->where('id', $productId)
                    ->set('current_stock', "current_stock - $qty", false)
                    ->update();
    }
}