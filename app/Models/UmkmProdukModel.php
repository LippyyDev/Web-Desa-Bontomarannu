<?php

namespace App\Models;

use CodeIgniter\Model;

class UmkmProdukModel extends Model
{
    protected $table         = 'umkm_produk';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'umkm_id',
        'nama_produk',
        'harga',
        'deskripsi',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;
}
