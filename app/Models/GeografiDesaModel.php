<?php

namespace App\Models;

use CodeIgniter\Model;

class GeografiDesaModel extends Model
{
    protected $table            = 'geografi_desa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'maps_url',
        'maps_embed_url',
        'luas_wilayah',
        'batas_wilayah',
        'kondisi_geografis',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
