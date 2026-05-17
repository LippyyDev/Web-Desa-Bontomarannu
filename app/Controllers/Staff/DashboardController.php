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

        // Ambil 5 surat terbaru
        $recentLetters = (new LetterModel())->orderBy('created_at', 'DESC')->findAll(5);
        foreach ($recentLetters as &$letter) {
            $user    = $userModel->find($letter['user_id']);
            $profile = $profileModel->find($letter['user_id']);
            $letter['sender_name'] = ($profile && !empty($profile['nama_lengkap']))
                ? $profile['nama_lengkap']
                : ($user['username'] ?? 'Unknown');
        }

        // Breakdown status surat (untuk donut chart)
        $statusMenunggu  = (new LetterModel())->where('status', 'Menunggu')->countAllResults();
        $statusDibaca    = (new LetterModel())->where('status', 'Dibaca')->countAllResults();
        $statusDiterima  = (new LetterModel())->where('status', 'Diterima')->countAllResults();
        $statusDitolak   = (new LetterModel())->where('status', 'Ditolak')->countAllResults();

        // 5 Pengaduan terbaru
        $recentPengaduan = $pengaduanModel->orderBy('created_at', 'DESC')->findAll(5);
        foreach ($recentPengaduan as &$p) {
            $pUser    = $userModel->find($p['user_id'] ?? 0);
            $pProfile = $profileModel->find($p['user_id'] ?? 0);
            $p['sender_name'] = ($pProfile && !empty($pProfile['nama_lengkap']))
                ? $pProfile['nama_lengkap']
                : ($pUser['username'] ?? 'Anonim');
        }

        // Data grafik 6 bulan terakhir
        $chartLabels   = [];
        $chartIncoming = [];
        $chartReplied  = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthStart = date('Y-m-01', strtotime("-$i months"));
            $monthEnd   = date('Y-m-t 23:59:59', strtotime("-$i months"));
            $chartLabels[] = date('M Y', strtotime("-$i months"));

            $chartIncoming[] = (new LetterModel())
                ->where('created_at >=', $monthStart . ' 00:00:00')
                ->where('created_at <=', $monthEnd)
                ->countAllResults(false);

            $chartReplied[] = (new LetterModel())
                ->whereIn('status', ['Diterima', 'Ditolak'])
                ->where('decided_at >=', $monthStart . ' 00:00:00')
                ->where('decided_at <=', $monthEnd)
                ->countAllResults(false);

            $chartPengaduan[] = (new PengaduanModel())
                ->where('created_at >=', $monthStart . ' 00:00:00')
                ->where('created_at <=', $monthEnd)
                ->countAllResults(false);
        }

        // UMKM Breakdown — status di DB: pending, approved, rejected
        $umkmPending  = $umkmModel->where('status', 'pending')->countAllResults();
        $umkmApproved = $umkmModel->where('status', 'approved')->countAllResults();
        $umkmRejected = $umkmModel->where('status', 'rejected')->countAllResults();

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

        return view('Staff/dashboard/index', $data);
    }
}
