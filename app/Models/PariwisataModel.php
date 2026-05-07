<?php

namespace App\Models;

use CodeIgniter\Model;

class PariwisataModel extends Model
{
    protected $table         = 'pariwisata';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'nama_tempat',
        'deskripsi',
        'alamat',
        'maps_embed_url',
        'thumbnail',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;
}
