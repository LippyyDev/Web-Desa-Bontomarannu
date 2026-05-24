<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKontakToDesaProfile extends Migration
{
    public function up()
    {
        $fields = [
            'alamat_kantor'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'sejarah_desa'],
            'kontak_wa'       => ['type' => 'VARCHAR', 'constraint' => 30,  'null' => true, 'after' => 'alamat_kantor'],
            'kontak_email'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'kontak_wa'],
            'kontak_facebook' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'kontak_email'],
            'kontak_instagram'=> ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'kontak_facebook'],
            'kontak_youtube'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'kontak_instagram'],
        ];
        $this->forge->addColumn('desa_profile', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('desa_profile', [
            'alamat_kantor',
            'kontak_wa',
            'kontak_email',
            'kontak_facebook',
            'kontak_instagram',
            'kontak_youtube',
        ]);
    }
}
