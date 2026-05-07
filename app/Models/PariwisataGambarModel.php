<?php

namespace App\Models;

use CodeIgniter\Model;

class PariwisataGambarModel extends Model
{
    protected $table         = 'pariwisata_gambar';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'pariwisata_id',
        'gambar_path',
        'created_at',
    ];
    protected $useTimestamps = false;
}
