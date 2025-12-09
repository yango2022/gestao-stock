<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'phone'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'address'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'city'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nif'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('customers');
    }

    public function down()
    {
        $this->forge->dropTable('customers');
    }
}