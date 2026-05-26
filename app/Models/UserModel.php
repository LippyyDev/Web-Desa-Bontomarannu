<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'username',
        'email',
        'password_hash',
        'security_question',
        'security_answer_hash',
        'role',
        'status',
        'is_verified',
        'last_seen_at',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps    = false;
}


