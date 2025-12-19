<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyIdToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'company_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'id'
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
