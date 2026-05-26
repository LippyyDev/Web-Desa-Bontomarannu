<?php

namespace App\Controllers\Staff;

use App\Controllers\ProtectedController;
use App\Models\LetterModel;
use App\Models\NewsModel;
use App\Models\PerangkatDesaModel;
use App\Models\GalleryAlbumModel;
use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\PengaduanModel;
use App\Models\PengumumanModel;
use App\Models\UmkmModel;
use App\Models\PariwisataModel;
use App\Models\InventarisDesaModel;

class DashboardController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $uid             = $this->currentUser['id'];
        $letterModel     = new LetterModel();
        $newsModel       = new NewsModel();
        $perangkatModel  = new PerangkatDesaModel();
        $galleryModel    = new GalleryAlbumModel();
        $userModel       = new UserModel();
        $profileModel    = new UserProfileModel();
        $pengaduanModel  = new PengaduanModel();
        $pengumumanModel = new PengumumanModel();
        $umkmModel       = new UmkmModel();
        $pariwisataModel = new PariwisataModel();
        $inventarisModel = new InventarisDesaModel();

        // Hitung total surat masuk
        $incoming = $letterModel->countAllResults();

        // Ambil 5 surat terbaru (Optimasi JOIN)
        $recentLetters = (new LetterModel())
            ->select('letters.*, users.username, user_profiles.nama_lengkap')
            ->join('users', 'users.id = letters.user_id', 'left')
            ->join('user_profiles', 'user_profiles.user_id = letters.user_id', 'left')
            ->orderBy('letters.created_at', 'DESC')
            ->findAll(5);
        foreach ($recentLetters as &$letter) {
            $letter['sender_name'] = !empty($letter['nama_lengkap']) 
                ? $letter['nama_lengkap'] 
                : ($letter['username'] ?? 'Unknown');
        }

        // Breakdown status surat (untuk donut chart - Optimasi)
        $db = \Config\Database::connect();
        $suratStats = $db->query("
            SELECT 
                SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
                SUM(CASE WHEN status = 'Dibaca' THEN 1 ELSE 0 END) as dibaca,
                SUM(CASE WHEN status = 'Diterima' THEN 1 ELSE 0 END) as diterima,
                SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
            FROM letters
        ")->getRowArray();
        $statusMenunggu  = (int)($suratStats['menunggu'] ?? 0);
        $statusDibaca    = (int)($suratStats['dibaca'] ?? 0);
        $statusDiterima  = (int)($suratStats['diterima'] ?? 0);
        $statusDitolak   = (int)($suratStats['ditolak'] ?? 0);

        // 5 Pengaduan terbaru (Optimasi JOIN)
        $recentPengaduan = $pengaduanModel
            ->select('pengaduan.*, users.username, user_profiles.nama_lengkap')
            ->join('users', 'users.id = pengaduan.user_id', 'left')
            ->join('user_profiles', 'user_profiles.user_id = pengaduan.user_id', 'left')
            ->orderBy('pengaduan.created_at', 'DESC')
            ->findAll(5);
        foreach ($recentPengaduan as &$p) {
            $p['sender_name'] = !empty($p['nama_lengkap']) 
                ? $p['nama_lengkap'] 
                : (!empty($p['username']) ? $p['username'] : 'Anonim');
        }

        // Data grafik 6 bulan terakhir (Optimasi GROUP BY)
        $chartLabels    = [];
        $chartIncoming  = [];
        $chartReplied   = [];
        $chartPengaduan = [];

        // Inisialisasi default 0 untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime("-$i months"));
            $chartLabels[] = date('M Y', strtotime("-$i months"));
            $chartIncoming[$monthKey]  = 0;
            $chartReplied[$monthKey]   = 0;
            $chartPengaduan[$monthKey] = 0;
        }
        
        $sixMonthsAgo = date('Y-m-01 00:00:00', strtotime("-5 months"));

        // Query 1: Letters (Masuk - based on created_at)
        $incomingData = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total
            FROM letters WHERE created_at >= ?
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ", [$sixMonthsAgo])->getResultArray();
        foreach ($incomingData as $row) {
            if (isset($chartIncoming[$row['bulan']])) $chartIncoming[$row['bulan']] = (int)$row['total'];
        }

        // Query 2: Letters (Dibalas - based on decided_at)
        $repliedData = $db->query("
            SELECT DATE_FORMAT(decided_at, '%Y-%m') as bulan, COUNT(*) as total
            FROM letters WHERE status IN ('Diterima', 'Ditolak') AND decided_at >= ?
            GROUP BY DATE_FORMAT(decided_at, '%Y-%m')
        ", [$sixMonthsAgo])->getResultArray();
        foreach ($repliedData as $row) {
            if (isset($chartReplied[$row['bulan']])) $chartReplied[$row['bulan']] = (int)$row['total'];
        }

        // Query 3: Pengaduan
        $pengaduanChartData = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total
            FROM pengaduan WHERE created_at >= ?
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ", [$sixMonthsAgo])->getResultArray();
        foreach ($pengaduanChartData as $row) {
            if (isset($chartPengaduan[$row['bulan']])) $chartPengaduan[$row['bulan']] = (int)$row['total'];
        }

        // Re-index arrays for charts
        $chartIncoming  = array_values($chartIncoming);
        $chartReplied   = array_values($chartReplied);
        $chartPengaduan = array_values($chartPengaduan);

        // UMKM Breakdown (Optimasi Conditional Aggregation)
        $umkmStats = $db->query("
            SELECT 
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
            FROM umkm
        ")->getRowArray();
        $umkmPending  = (int)($umkmStats['pending'] ?? 0);
        $umkmApproved = (int)($umkmStats['approved'] ?? 0);
        $umkmRejected = (int)($umkmStats['rejected'] ?? 0);

        $data = [
            'incoming'        => $incoming,
            'galleryTotal'    => $galleryModel->countAllResults(),
            'newsTotal'       => $newsModel->countAllResults(),
            'perangkatTotal'  => $perangkatModel->countAllResults(),
            'pengaduanTotal'  => $pengaduanModel->countAllResults(),
            'pengumumanTotal' => $pengumumanTotal ?? $pengumumanModel->countAllResults(),
            'umkmTotal'       => $umkmModel->countAllResults(),
            'umkmPending'     => $umkmPending,
            'umkmApproved'    => $umkmApproved,
            'umkmRejected'    => $umkmRejected,
            'pariwisataTotal' => $pariwisataModel->countAllResults(),
            'inventarisTotal' => $inventarisModel->countAllResults(),
            'recentLetters'   => $recentLetters,
            'recentPengaduan' => $recentPengaduan,
            'statusMenunggu'  => $statusMenunggu,
            'statusDibaca'    => $statusDibaca,
            'statusDiterima'  => $statusDiterima,
            'statusDitolak'   => $statusDitolak,
            'chartLabels'     => $chartLabels,
            'chartIncoming'   => $chartIncoming,
            'chartReplied'    => $chartReplied,
            'chartPengaduan'  => $chartPengaduan,
        ];

        $data['title'] = 'Dashboard Staf | Website Desa Bonto Marannu';
        return view('Staff/dashboard/index', $data);
    }
}
