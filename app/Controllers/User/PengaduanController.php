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

        $pengaduanModel->save($data);

        return redirect()->to('/user/pengaduan')->with('message', 'Pengaduan berhasil dikirim');
    }
}
