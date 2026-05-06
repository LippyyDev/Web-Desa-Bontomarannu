<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropProjectTables extends Migration
{
    public function up()
    {
        $this->forge->dropTable('project_media', true);
        $this->forge->dropTable('projects', true);
    }

    public function down()
    {
        // Re-create tables if needed (optional for this task as it's a removal)
        // Leaving empty as requested to just remove.
    }
}
