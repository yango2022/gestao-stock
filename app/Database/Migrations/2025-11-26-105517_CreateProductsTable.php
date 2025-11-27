<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'category_id'   => ['type' => 'INT', 'unsigned' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 200],
            'sku'           => ['type' => 'VARCHAR', 'constraint' => 120, 'unique' => true],
            'unit_price'    => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'cost_price'    => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'min_stock'     => ['type' => 'INT', 'default' => 0],
            'current_stock' => ['type' => 'INT', 'default' => 0],
            'created_by'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }

}