<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGeografiDesaTable extends Migration
{
    public function up()
    {
        // Create geografi_desa table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'maps_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'maps_embed_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'luas_wilayah' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'batas_wilayah' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kondisi_geografis' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('geografi_desa');

        // Drop columns from desa_profile
        $this->forge->dropColumn('desa_profile', ['maps_url', 'luas_wilayah']);
    }

    public function down()
    {
        // Drop geografi_desa table
        $this->forge->dropTable('geografi_desa');

        // Add columns back to desa_profile
        $fields = [
            'maps_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'luas_wilayah' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('desa_profile', $fields);
    }
}
