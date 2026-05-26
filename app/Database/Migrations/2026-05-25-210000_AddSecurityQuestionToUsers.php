<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSecurityQuestionToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'security_question' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'default'    => null,
                'after'      => 'password_hash',
            ],
            'security_answer_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'security_question',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', 'security_question');
        $this->forge->dropColumn('users', 'security_answer_hash');
    }
}
