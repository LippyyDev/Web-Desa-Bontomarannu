<?php

namespace App\Models;

use CodeIgniter\Model;

class UmkmProdukGambarModel extends Model
{
    protected $table         = 'umkm_produk_gambar';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'produk_id',
        'gambar_path',
        'created_at',
    ];
    protected $useTimestamps = false;
}
