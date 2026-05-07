<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUmkmProdukGambarTable extends Migration
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
            'produk_id' => [
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
        $this->forge->addKey('produk_id');
        $this->forge->createTable('umkm_produk_gambar');
    }

    public function down()
    {
        $this->forge->dropTable('umkm_produk_gambar');
    }
}
