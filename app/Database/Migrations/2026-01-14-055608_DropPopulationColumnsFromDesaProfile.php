<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropPopulationColumnsFromDesaProfile extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('desa_profile', [
            'jumlah_penduduk',
            'jumlah_kk',
            'penduduk_sementara',
            'jumlah_laki',
            'jumlah_perempuan',
            'mutasi_penduduk',
        ]);
    }

    public function down()
    {
        $fields = [
            'jumlah_penduduk'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'jumlah_kk'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'penduduk_sementara' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'jumlah_laki'        => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'jumlah_perempuan'   => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'mutasi_penduduk'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
        ];
        $this->forge->addColumn('desa_profile', $fields);
    }
}
