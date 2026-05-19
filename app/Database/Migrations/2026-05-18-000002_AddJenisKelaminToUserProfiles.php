<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisKelaminToUserProfiles extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        // Cek apakah kolom sudah ada
        $fields = $db->getFieldNames('user_profiles');
        if (!in_array('jenis_kelamin', $fields)) {
            $this->forge->addColumn('user_profiles', [
                'jenis_kelamin' => [
                    'type'       => 'ENUM',
                    'constraint' => ['Laki-laki', 'Perempuan'],
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'nama_lengkap',
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('user_profiles', 'jenis_kelamin');
    }
}
