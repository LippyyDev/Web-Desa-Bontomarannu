<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUmkmEcommerceTable extends Migration
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
            'umkm_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'nama platform bebas, e.g. Shopee, Tokopedia',
            ],
            'url' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('umkm_id');
        $this->forge->createTable('umkm_ecommerce');
    }

    public function down()
    {
        $this->forge->dropTable('umkm_ecommerce');
    }
}
