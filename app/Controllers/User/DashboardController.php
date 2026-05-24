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

        // Data grafik 6 bulan terakhir (khusus surat milik user ini)
        $chartLabels  = [];
        $chartSent    = [];
        $chartDecided = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthStart = date('Y-m-01', strtotime("-$i months"));
            $monthEnd   = date('Y-m-t 23:59:59', strtotime("-$i months"));
            $chartLabels[] = date('M Y', strtotime("-$i months"));

            $chartSent[] = (new LetterModel())
                ->where('user_id', $uid)
                ->where('created_at >=', $monthStart . ' 00:00:00')
                ->where('created_at <=', $monthEnd)
                ->countAllResults(false);

            $chartDecided[] = (new LetterModel())
                ->where('user_id', $uid)
                ->whereIn('status', ['Diterima', 'Ditolak'])
                ->where('decided_at >=', $monthStart . ' 00:00:00')
                ->where('decided_at <=', $monthEnd)
                ->countAllResults(false);
                
            $chartPengaduan[] = (new PengaduanModel())
                ->where('created_at >=', $monthStart . ' 00:00:00')
                ->where('created_at <=', $monthEnd)
                ->countAllResults(false);
        }

        // Surat terbaru user
        $recentLetters = (new LetterModel())
            ->where('user_id', $uid)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        // Status surat (untuk donut chart)
        $statusMenunggu = (new LetterModel())->where('user_id', $uid)->where('status', 'Menunggu')->countAllResults();
        $statusDibaca   = (new LetterModel())->where('user_id', $uid)->where('status', 'Dibaca')->countAllResults();
        $statusDiterima = (new LetterModel())->where('user_id', $uid)->where('status', 'Diterima')->countAllResults();
        $statusDitolak  = (new LetterModel())->where('user_id', $uid)->where('status', 'Ditolak')->countAllResults();

        // Unread notifications
        $unreadNotif = $notifModel->where('user_id', $uid)->where('is_read', 0)->countAllResults();

        // UMKM user — status di DB: pending, approved, rejected
        $umkmPending  = $umkmModel->where('user_id', $uid)->where('status', 'pending')->countAllResults();
        $umkmApproved = $umkmModel->where('user_id', $uid)->where('status', 'approved')->countAllResults();
        $umkmRejected = $umkmModel->where('user_id', $uid)->where('status', 'rejected')->countAllResults();
        $umkmTotal    = $umkmModel->where('user_id', $uid)->countAllResults();

        // Pengaduan user — user_id di tabel pengaduan tersimpan sebagai NULL
        // (pengaduan diinput via form tanpa login), jadi hitung semua pengaduan
        $pengaduanCount = $pengModel->countAllResults();
        // Khusus chart per bulan: karena user_id NULL, filter hanya by created_at
        // (chartPengaduan sudah dihitung di loop atas)
        $chartPengaduan = $chartPengaduan ?? array_fill(0, 6, 0);

        $data = [
            'totalLetters'    => $letterModel->where('user_id', $uid)->countAllResults(),
            'sentCount'       => $letterModel->where('user_id', $uid)->where('status', 'Menunggu')->countAllResults(),
            'readCount'       => $letterModel->where('user_id', $uid)->where('status', 'Dibaca')->countAllResults(),
            'repliedCount'    => $letterModel->where('user_id', $uid)->whereIn('status', ['Diterima', 'Ditolak'])->countAllResults(),
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
