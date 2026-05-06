<?php

namespace App\Controllers\Staff;

use App\Controllers\ProtectedController;
use App\Models\NotificationModel;

class NotificationController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $notifModel = new NotificationModel();

        return view('Staff/notifications/index', [
            'notifications' => $notifModel->where('user_id', $this->currentUser['id'])
                ->orderBy('created_at', 'DESC')
                ->findAll(),
        ]);
    }

    public function markRead($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $notifModel = new NotificationModel();
        $notif      = $notifModel->where('user_id', $this->currentUser['id'])->find($id);
        if ($notif) {
            $notifModel->update($id, ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);
        }

        return redirect()->back();
    }
}


