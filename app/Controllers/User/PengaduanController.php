<?php

namespace App\Controllers\User;

use App\Controllers\ProtectedController;
use App\Models\PengaduanModel;
use App\Models\UserProfileModel;

class PengaduanController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        // Get profile from database like ProfileController does
        $profileModel = new UserProfileModel();
        $profile = $profileModel->find($this->currentUser['id']);

        return view('User/pengaduan/index', [
            'title' => 'Buat Pengaduan',
            'profile' => $profile
        ]);
    }

    public function store()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        $pengaduanModel = new PengaduanModel();
        $userId = session()->get('user_id');
        $userName = $this->request->getPost('nama') ?: (session()->get('nama_lengkap') ?: session()->get('username'));

        $data = [
            'user_id' => $userId,
            'nama'    => $userName,
            'kontak'  => $this->request->getPost('kontak'),
            'perihal' => $this->request->getPost('perihal'),
            'isi'     => $this->request->getPost('isi')
        ];

        // Handle foto upload
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid()) {
            $path = FCPATH . 'uploads/pengaduan';
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            $newName = $foto->getRandomName();
            $foto->move($path, $newName);
            $data['foto'] = 'uploads/pengaduan/' . $newName;
        }

        $pengaduanId = $pengaduanModel->insert($data, true);

        // Buat notifikasi untuk semua staff
        $userModel = new \App\Models\UserModel();
        $staffList = $userModel->where('role', 'staf')->findAll();
        $notifModel = new \App\Models\NotificationModel();
        $emailService = new \App\Libraries\EmailService();
        
        $pengaduanUrl = base_url('/staff/pengaduan/' . $pengaduanId);

        foreach ($staffList as $staff) {
            $notifModel->insert([
                'user_id'              => $staff['id'],
                'type'                 => 'new_pengaduan',
                'title'                => 'Pengaduan Baru Masuk',
                'message'              => 'Pengaduan baru dari ' . $data['nama'] . ' - ' . $data['perihal'],
                'related_pengaduan_id' => $pengaduanId,
                'is_read'              => 0,
                'created_at'           => date('Y-m-d H:i:s'),
            ]);
            
            // Kirim email notifikasi ke staff (masuk ke EmailQueue)
            $emailService->sendNotification(
                $staff['email'],
                $staff['username'],
                'Pengaduan Baru Masuk',
                'Pengaduan baru dari ' . $data['nama'],
                'info',
                $pengaduanUrl,
                $data['perihal'],
                'Pengaduan'
            );
        }

        return redirect()->to('/user/pengaduan')->with('message', 'Pengaduan berhasil dikirim');
    }
}
