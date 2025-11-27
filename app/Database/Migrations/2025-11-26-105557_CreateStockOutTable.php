<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockOutTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
            'quantity'   => ['type' => 'INT'],
            'type'       => ['type' => 'ENUM', 'constraint' => ['venda', 'perda', 'ajuste'], 'default' => 'venda'],
            'user_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('stock_out');
    }

    public function down()
    {
        $this->forge->dropTable('stock_out');
    }
}