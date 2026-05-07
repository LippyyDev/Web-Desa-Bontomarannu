<?php

namespace App\Models;

use CodeIgniter\Model;

class UmkmEcommerceModel extends Model
{
    protected $table         = 'umkm_ecommerce';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'umkm_id',
        'platform',
        'url',
        'created_at',
    ];
    protected $useTimestamps = false;
}
