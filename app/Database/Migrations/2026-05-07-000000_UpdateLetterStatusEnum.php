<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateLetterStatusEnum extends Migration
{
    public function up()
    {
        // 1. Migrasi data lama sebelum ubah ENUM
        //    Terkirim → Menunggu, Dibalas → Diterima
        $this->db->query("UPDATE letters SET status = 'Menunggu' WHERE status = 'Terkirim'");
        $this->db->query("UPDATE letters SET status = 'Diterima' WHERE status = 'Dibalas'");

        // 2. Ubah kolom status ke ENUM baru
        $this->forge->modifyColumn('letters', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Menunggu', 'Dibaca', 'Diterima', 'Ditolak'],
                'default'    => 'Menunggu',
                'null'       => false,
            ],
        ]);

        // 3. Tambah kolom catatan_penolakan
        $this->forge->addColumn('letters', [
            'catatan_penolakan' => [
                'type'    => 'TEXT',
                'null'    => true,
                'after'   => 'replied_at',
            ],
        ]);

        // 4. Tambah kolom decided_at (waktu keputusan diterima/ditolak)
        $this->forge->addColumn('letters', [
            'decided_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'after'   => 'catatan_penolakan',
            ],
        ]);
    }

    public function down()
    {
        // Rollback: hapus kolom baru dulu
        $this->forge->dropColumn('letters', 'decided_at');
        $this->forge->dropColumn('letters', 'catatan_penolakan');

        // Migrasi data balik (Menunggu → Terkirim, Diterima → Dibalas)
        // Perlu sementara pakai VARCHAR dulu karena ENUM tidak bisa langsung swap
        $this->forge->modifyColumn('letters', [
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'Terkirim',
            ],
        ]);

        $this->db->query("UPDATE letters SET status = 'Terkirim' WHERE status = 'Menunggu'");
        $this->db->query("UPDATE letters SET status = 'Dibalas' WHERE status = 'Diterima'");
        $this->db->query("UPDATE letters SET status = 'Dibalas' WHERE status = 'Ditolak'");

        // Kembalikan ke ENUM lama
        $this->forge->modifyColumn('letters', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Terkirim', 'Dibaca', 'Dibalas'],
                'default'    => 'Terkirim',
                'null'       => false,
            ],
        ]);
    }
}
