<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePariwisataGambarTable extends Migration
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
            'pariwisata_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'gambar_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('pariwisata_id');
        $this->forge->createTable('pariwisata_gambar');
    }

    public function down()
    {
        $this->forge->dropTable('pariwisata_gambar');
    }
}
