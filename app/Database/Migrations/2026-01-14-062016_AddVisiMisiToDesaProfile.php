<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVisiMisiToDesaProfile extends Migration
{
    public function up()
    {
        $fields = [
            'visi' => ['type' => 'TEXT', 'null' => true],
            'misi' => ['type' => 'TEXT', 'null' => true],
        ];
        $this->forge->addColumn('desa_profile', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('desa_profile', ['visi', 'misi']);
    }
}
