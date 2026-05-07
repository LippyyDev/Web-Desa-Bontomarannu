<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRelatedUmkmIdToNotifications extends Migration
{
    public function up()
    {
        $this->forge->addColumn('notifications', [
            'related_umkm_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'related_reply_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('notifications', 'related_umkm_id');
    }
}
