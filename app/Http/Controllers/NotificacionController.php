<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function get()
    {
        $unread = Auth::user()->unreadNotifications;
        $fecha = date('Y-m-d');
        foreach ($unread as $item) {
            if ($fecha != $item->created_at->toDateString()){
                $item->maskAsRead();
            }
        }

        return Auth::user()->unreadNotifications;
    }
}
