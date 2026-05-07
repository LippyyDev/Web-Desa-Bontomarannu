<?php

namespace App\Models;

use CodeIgniter\Model;

class UmkmModel extends Model
{
    protected $table         = 'umkm';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id',
        'nama_toko',
        'deskripsi',
        'alamat',
        'maps_embed_url',
        'kontak',
        'status',
        'alasan_tolak',
        'created_by',
        'approved_by',
        'approved_at',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;
}
