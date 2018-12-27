<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function get()
    {
        //$notificaciones = Notification::where('notifiable_id',Auth::user()->uuid);
        $notificaciones = Notification::all();

        $fecha = date('Y-m-d');
        foreach ($notificaciones as $item) {
            if ($fecha != $item->created_at->toDateString()){
                $item->maskAsRead();
            }
        }
        //
        //return Auth::user()->unreadNotifications;
        return response()->json($notificaciones);
    }
}
