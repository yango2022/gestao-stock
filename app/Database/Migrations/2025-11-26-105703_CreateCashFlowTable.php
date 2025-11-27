<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCashFlowTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'type'       => ['type' => 'ENUM', 'constraint' => ['entrada','saida']],
            'category'   => ['type' => 'ENUM', 'constraint' => ['venda','compra','ajuste','despesa','outro']],
            'amount'     => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'reference_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'note'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('reference_id');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('cash_flow');
    }

    public function down()
    {
        $this->forge->dropTable('cash_flow');
    }
}