<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'customer_name'  => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'subtotal'       => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'discount'       => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'total'          => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'payment_method' => ['type' => 'ENUM', 'constraint' => ['dinheiro','tpaga','multicaixa','transferencia']],
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('sales');
    }

    public function down()
    {
        $this->forge->dropTable('sales');
    }
}