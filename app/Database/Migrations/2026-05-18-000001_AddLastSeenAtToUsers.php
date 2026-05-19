<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLastSeenAtToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'last_seen_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
                'after'      => 'is_verified',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', 'last_seen_at');
    }
}
