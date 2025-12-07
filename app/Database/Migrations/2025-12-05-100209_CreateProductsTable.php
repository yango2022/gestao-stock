<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'auto_increment' => true],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'sku'               => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'category_id'       => ['type' => 'INT', 'null' => true],
            'purchase_price'    => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'sale_price'        => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'stock'             => ['type' => 'INT', 'default' => 0],
            'min_stock'         => ['type' => 'INT', 'default' => 0],
            'image'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}