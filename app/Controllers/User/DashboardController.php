<?php

namespace App\Controllers\User;

use App\Controllers\ProtectedController;
use App\Models\LetterModel;
use App\Models\NotificationModel;
use App\Models\PengaduanModel;
use App\Models\UmkmModel;

class DashboardController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $uid         = $this->currentUser['id'];
        $letterModel = new LetterModel();
        $notifModel  = new NotificationModel();
        $pengModel   = new PengaduanModel();
        $umkmModel   = new UmkmModel();

        // Data grafik 6 bulan terakhir (Optimasi GROUP BY)
        $chartLabels    = [];
        $chartSent      = [];
        $chartDecided   = [];
        $chartPengaduan = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime("-$i months"));
            $chartLabels[] = date('M Y', strtotime("-$i months"));
            $chartSent[$monthKey]      = 0;
            $chartDecided[$monthKey]   = 0;
            $chartPengaduan[$monthKey] = 0;
        }

        $db = \Config\Database::connect();
        $sixMonthsAgo = date('Y-m-01 00:00:00', strtotime("-5 months"));

        // Query 1: Letters Sent (based on created_at)
        $sentData = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total
            FROM letters WHERE user_id = ? AND created_at >= ?
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ", [$uid, $sixMonthsAgo])->getResultArray();
        foreach ($sentData as $row) {
            if (isset($chartSent[$row['bulan']])) $chartSent[$row['bulan']] = (int)$row['total'];
        }

        // Query 2: Letters Decided (based on decided_at)
        $decidedData = $db->query("
            SELECT DATE_FORMAT(decided_at, '%Y-%m') as bulan, COUNT(*) as total
            FROM letters WHERE user_id = ? AND status IN ('Diterima', 'Ditolak') AND decided_at >= ?
            GROUP BY DATE_FORMAT(decided_at, '%Y-%m')
        ", [$uid, $sixMonthsAgo])->getResultArray();
        foreach ($decidedData as $row) {
            if (isset($chartDecided[$row['bulan']])) $chartDecided[$row['bulan']] = (int)$row['total'];
        }

        // Query 3: Pengaduan (semua pengaduan karena user_id NULL di form)
        $pengaduanData = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total
            FROM pengaduan WHERE created_at >= ?
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ", [$sixMonthsAgo])->getResultArray();
        foreach ($pengaduanData as $row) {
            if (isset($chartPengaduan[$row['bulan']])) $chartPengaduan[$row['bulan']] = (int)$row['total'];
        }

        $chartSent      = array_values($chartSent);
        $chartDecided   = array_values($chartDecided);
        $chartPengaduan = array_values($chartPengaduan);

        // Surat terbaru user
        $recentLetters = (new LetterModel())
            ->where('user_id', $uid)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        // Status surat (untuk donut chart & header stat - Optimasi Conditional Aggregation)
        $suratStats = $db->query("
            SELECT 
                COUNT(*) as total_letters,
                SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
                SUM(CASE WHEN status = 'Dibaca' THEN 1 ELSE 0 END) as dibaca,
                SUM(CASE WHEN status = 'Diterima' THEN 1 ELSE 0 END) as diterima,
                SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
            FROM letters
            WHERE user_id = ?
        ", [$uid])->getRowArray();

        $totalLetters   = (int)($suratStats['total_letters'] ?? 0);
        $statusMenunggu = (int)($suratStats['menunggu'] ?? 0);
        $statusDibaca   = (int)($suratStats['dibaca'] ?? 0);
        $statusDiterima = (int)($suratStats['diterima'] ?? 0);
        $statusDitolak  = (int)($suratStats['ditolak'] ?? 0);
        $repliedCount   = $statusDiterima + $statusDitolak;

        // Unread notifications
        $unreadNotif = $notifModel->where('user_id', $uid)->where('is_read', 0)->countAllResults();

        // UMKM user (Optimasi Conditional Aggregation)
        $umkmStats = $db->query("
            SELECT 
                COUNT(*) as total_umkm,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
            FROM umkm
            WHERE user_id = ?
        ", [$uid])->getRowArray();
        $umkmTotal    = (int)($umkmStats['total_umkm'] ?? 0);
        $umkmPending  = (int)($umkmStats['pending'] ?? 0);
        $umkmApproved = (int)($umkmStats['approved'] ?? 0);
        $umkmRejected = (int)($umkmStats['rejected'] ?? 0);

        // Pengaduan user — user_id di tabel pengaduan tersimpan sebagai NULL
        // (pengaduan diinput via form tanpa login), jadi hitung semua pengaduan
        $pengaduanCount = $pengModel->countAllResults();

        $data = [
            'totalLetters'    => $totalLetters,
            'sentCount'       => $statusMenunggu,
            'readCount'       => $statusDibaca,
            'repliedCount'    => $repliedCount,
            'notifications'   => $notifModel->where('user_id', $uid)->orderBy('created_at', 'DESC')->findAll(5),
            'unreadNotif'     => $unreadNotif,
            'recentLetters'   => $recentLetters,
            'pengaduanCount'  => $pengaduanCount,
            'umkmTotal'       => $umkmTotal,
            'umkmPending'     => $umkmPending,
            'umkmApproved'    => $umkmApproved,
            'umkmRejected'    => $umkmRejected,
            'statusMenunggu'  => $statusMenunggu,
            'statusDibaca'    => $statusDibaca,
            'statusDiterima'  => $statusDiterima,
            'statusDitolak'   => $statusDitolak,
            'chartLabels'     => $chartLabels,
            'chartSent'       => $chartSent,
            'chartDecided'    => $chartDecided,
            'chartPengaduan'  => $chartPengaduan,
        ];

        $data['title'] = 'Dashboard Warga | Website Desa Bonto Marannu';
        return view('User/dashboard/index', $data);
    }
}
