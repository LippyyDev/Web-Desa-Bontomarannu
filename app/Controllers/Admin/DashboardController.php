<?php

namespace App\Controllers\Admin;

use App\Controllers\ProtectedController;
use App\Models\GalleryAlbumModel;
use App\Models\LetterModel;
use App\Models\NewsModel;

use App\Models\UserModel;

class DashboardController extends ProtectedController
{
    public function index()
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }

        $userModel    = new UserModel();
        $letterModel  = new LetterModel();
        $newsModel    = new NewsModel();

        $albumModel   = new GalleryAlbumModel();

        return view('Admin/dashboard/index', [
            'userCount'    => $userModel->countAllResults(),
            'letterCount'  => $letterModel->countAllResults(),
            'newsCount'    => $newsModel->countAllResults(),

            'albumCount'   => $albumModel->countAllResults(),
        ]);
    }
}


