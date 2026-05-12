<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoTokoToUmkm extends Migration
{
    public function up()
    {
        $this->forge->addColumn('umkm', [
            'foto_toko' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'kontak',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('umkm', 'foto_toko');
    }
}
