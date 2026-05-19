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

        // Statistik akun
        $totalAkun    = (int) $db->query("SELECT COUNT(*) as c FROM users")->getRow()->c;
        $aktifAkun    = (int) $db->query("SELECT COUNT(*) as c FROM users WHERE status = 'aktif'")->getRow()->c;
        $nonaktifAkun = (int) $db->query("SELECT COUNT(*) as c FROM users WHERE status = 'nonaktif'")->getRow()->c;
        $onlineAkun   = (int) $db->query("SELECT COUNT(*) as c FROM users WHERE last_seen_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)")->getRow()->c;

        // Distribusi role
        $adminCount = (int) $db->query("SELECT COUNT(*) as c FROM users WHERE role = 'admin'")->getRow()->c;
        $stafCount  = (int) $db->query("SELECT COUNT(*) as c FROM users WHERE role = 'staf'")->getRow()->c;
        $userCount  = (int) $db->query("SELECT COUNT(*) as c FROM users WHERE role = 'user'")->getRow()->c;

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
