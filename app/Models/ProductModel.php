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
        'current_stock', 'min_stock', 'image'
    ];

    public function decreaseStock($productId, $qty)
    {
        return $this->where('id', $productId)
                    ->set('current_stock', "current_stock - $qty", false)
                    ->update();
    }
}