<?php

namespace App\Controllers\Admin;

use App\Controllers\ProtectedController;
use App\Models\NotificationModel;

class DashboardController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        $db = \Config\Database::connect();

        // Optimasi: Gunakan Conditional Aggregation untuk Statistik & Distribusi role
        $stats = $db->query("
            SELECT 
                COUNT(*) as totalAkun,
                SUM(CASE WHEN status = 'aktif' THEN 1 ELSE 0 END) as aktifAkun,
                SUM(CASE WHEN status = 'nonaktif' THEN 1 ELSE 0 END) as nonaktifAkun,
                SUM(CASE WHEN last_seen_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 1 ELSE 0 END) as onlineAkun,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as adminCount,
                SUM(CASE WHEN role = 'staf' THEN 1 ELSE 0 END) as stafCount,
                SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END) as userCount
            FROM users
        ")->getRowArray();

        $totalAkun    = (int)($stats['totalAkun'] ?? 0);
        $aktifAkun    = (int)($stats['aktifAkun'] ?? 0);
        $nonaktifAkun = (int)($stats['nonaktifAkun'] ?? 0);
        $onlineAkun   = (int)($stats['onlineAkun'] ?? 0);
        $adminCount   = (int)($stats['adminCount'] ?? 0);
        $stafCount    = (int)($stats['stafCount'] ?? 0);
        $userCount    = (int)($stats['userCount'] ?? 0);

        // Akun terbaru (5 terakhir) dengan profil
        $recentAccounts = $db->query("
            SELECT u.id, u.username, u.email, u.role, u.status, u.created_at, u.last_seen_at, up.foto_profil
            FROM users u
            LEFT JOIN user_profiles up ON up.user_id = u.id
            ORDER BY u.created_at DESC
            LIMIT 5
        ")->getResultArray();

        // Chart registrasi akun per bulan (6 bulan terakhir)
        $chartRows = $db->query("
            SELECT MIN(DATE_FORMAT(created_at, '%b %Y')) as bulan, COUNT(*) as total
            FROM users
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY MIN(created_at) ASC
        ")->getResultArray();
        $chartLabels = array_column($chartRows, 'bulan');
        $chartData   = array_map('intval', array_column($chartRows, 'total'));

        // Notifikasi admin (unread)
        $notifModel  = new NotificationModel();
        $unreadNotif = $notifModel->where('user_id', $this->currentUser['id'])
                                  ->where('is_read', 0)->countAllResults();
        $notifications = $notifModel->where('user_id', $this->currentUser['id'])
                                    ->orderBy('created_at', 'DESC')
                                    ->limit(4)->findAll();

        return view('Admin/dashboard/index', [
            'title'          => 'Dashboard Admin | Website Desa Bonto Marannu',
            'totalAkun'      => $totalAkun,
            'aktifAkun'      => $aktifAkun,
            'nonaktifAkun'   => $nonaktifAkun,
            'onlineAkun'     => $onlineAkun,
            'adminCount'     => $adminCount,
            'stafCount'      => $stafCount,
            'userCount'      => $userCount,
            'recentAccounts' => $recentAccounts,
            'chartLabels'    => $chartLabels,
            'chartData'      => $chartData,
            'unreadNotif'    => $unreadNotif,
            'notifications'  => $notifications,
        ]);
    }
}
