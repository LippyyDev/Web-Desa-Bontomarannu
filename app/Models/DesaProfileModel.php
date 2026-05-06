<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaProfileModel extends Model
{
    protected $table            = 'desa_profile';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'jumlah_penduduk',
        'jumlah_kk',
        'tahun_berdiri',
        'visi',
        'misi',
        'deskripsi_lokasi',
        'updated_by',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps    = false;
}


