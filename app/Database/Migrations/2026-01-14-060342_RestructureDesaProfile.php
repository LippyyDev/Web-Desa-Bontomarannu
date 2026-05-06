<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RestructureDesaProfile extends Migration
{
    public function up()
    {
        // Drop old columns
        $this->forge->dropColumn('desa_profile', [
            'visi',
            'misi',
            'kontak_wa',
            'kontak_email',
            'alamat_kantor',
        ]);

        // Add new columns
        $fields = [
            'nama_desa'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kecamatan'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kabupaten'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provinsi'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kode_pos'        => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'luas_wilayah'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'jumlah_penduduk' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'jumlah_kk'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'tahun_berdiri'   => ['type' => 'INT', 'constraint' => 4, 'null' => true],
            'sejarah_desa'    => ['type' => 'TEXT', 'null' => true],
        ];
        $this->forge->addColumn('desa_profile', $fields);
    }

    public function down()
    {
        // Drop new columns
        $this->forge->dropColumn('desa_profile', [
            'nama_desa',
            'kecamatan',
            'kabupaten',
            'provinsi',
            'kode_pos',
            'luas_wilayah',
            'jumlah_penduduk',
            'jumlah_kk',
            'tahun_berdiri',
            'sejarah_desa',
        ]);

        // Add back old columns
        $fields = [
            'visi'          => ['type' => 'TEXT', 'null' => true],
            'misi'          => ['type' => 'TEXT', 'null' => true],
            'kontak_wa'     => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'kontak_email'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'alamat_kantor' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ];
        $this->forge->addColumn('desa_profile', $fields);
    }
}
