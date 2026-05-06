<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventarisDesaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'total' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Baik',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('inventaris_desa');
    }

    public function down()
    {
        $this->forge->dropTable('inventaris_desa');
    }
}
