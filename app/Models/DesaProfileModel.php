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
        'luas_wilayah',
        'sejarah_desa',
        'visi',
        'misi',
        'alamat_kantor',
        'kontak_wa',
        'kontak_email',
        'kontak_facebook',
        'kontak_instagram',
        'kontak_youtube',
        'deskripsi_lokasi',
        'updated_by',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps    = false;
}


