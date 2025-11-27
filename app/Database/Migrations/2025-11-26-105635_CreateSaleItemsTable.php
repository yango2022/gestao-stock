<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSaleItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'sale_id'    => ['type' => 'INT', 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
            'quantity'   => ['type' => 'INT'],
            'unit_price' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'total'      => ['type' => 'DECIMAL', 'constraint' => '12,2']
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('sale_id', 'sales', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('sale_items');
    }

    public function down()
    {
        $this->forge->dropTable('sale_items');
    }
}