<?php

namespace App\Controllers\User;

use App\Controllers\ProtectedController;
use App\Models\NotificationModel;

class NotificationController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['user'])) {
            return $redirect;
        }

        return view('User/notifications/index', ['title' => 'Notifikasi | Website Desa Bonto Marannu']);
    }

    public function data()
    {
        if ($redirect = $this->guard(['user'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $notifModel = new NotificationModel();
        
        $page = (int)$this->request->getPost('page') ?: 1;
        $perPage = (int)$this->request->getPost('per_page') ?: 15;
        
        $offset = ($page - 1) * $perPage;
        
        // Optimasi: Ambil data per_page + 1 untuk mengecek has_more tanpa mengeksekusi query COUNT(*)
        $notifications = $notifModel->where('user_id', $this->currentUser['id'])
                                    ->orderBy('created_at', 'DESC')
                                    ->findAll($perPage + 1, $offset);
        
        $hasMore = count($notifications) > $perPage;
        if ($hasMore) {
            array_pop($notifications);
        }
        
        foreach ($notifications as &$notif) {
            $notif['created_at_formatted'] = date('d M Y H:i', strtotime($notif['created_at']));
            $notif['action_url'] = null;
            $notif['action_label'] = '';
            
            if ($notif['related_letter_id']) {
                $notif['action_url'] = base_url('/user/surat/' . $notif['related_letter_id']);
                $notif['action_label'] = 'Lihat Surat';
            } elseif ($notif['related_umkm_id']) {
                $notif['action_url'] = base_url('/user/umkm/' . $notif['related_umkm_id']);
                $notif['action_label'] = 'Lihat UMKM';
            } elseif (!empty($notif['related_pengaduan_id'])) {
                $notif['action_url'] = base_url('/user/pengaduan');
                $notif['action_label'] = 'Lihat Pengaduan';
            }
        }

        return $this->response->setJSON([
            'success'       => true,
            'notifications' => $notifications,
            'has_more'      => $hasMore
        ]);
    }

    public function markRead($id)
    {
        if ($redirect = $this->guard(['user'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $notifModel = new NotificationModel();
        $notif      = $notifModel->where('user_id', $this->currentUser['id'])->find($id);

        if ($notif) {
            $notifModel->update($id, ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Not found']);
    }

    public function markAllRead()
    {
        if ($redirect = $this->guard(['user'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $notifModel = new NotificationModel();
        $notifModel->where('user_id', $this->currentUser['id'])->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])->update();

        return $this->response->setJSON(['success' => true]);
    }

    public function deleteAll()
    {
        if ($redirect = $this->guard(['user'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $notifModel = new NotificationModel();
        $notifModel->where('user_id', $this->currentUser['id'])->delete();

        return $this->response->setJSON(['success' => true]);
    }
}


