<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockEntriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
            'supplier_id'=> ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'quantity'   => ['type' => 'INT'],
            'unit_cost'  => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'total_cost' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'user_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('stock_entries');
    }

    public function down()
    {
        $this->forge->dropTable('stock_entries');
    }
}