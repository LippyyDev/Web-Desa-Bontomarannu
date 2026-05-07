<?php

namespace App\Models;

use CodeIgniter\Model;

class LetterModel extends Model
{
    protected $table            = 'letters';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'kode_unik',
        'user_id',
        'assigned_staff_id',
        'judul_perihal',
        'tipe_surat',
        'isi_surat',
        'status',
        'catatan_penolakan',
        'sent_at',
        'read_at',
        'replied_at',
        'decided_at',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps    = false;

    // Status constants — matches ENUM('Menunggu','Dibaca','Diterima','Ditolak')
    const STATUS_MENUNGGU = 'Menunggu';
    const STATUS_DIBACA   = 'Dibaca';
    const STATUS_DITERIMA = 'Diterima';
    const STATUS_DITOLAK  = 'Ditolak';
}
