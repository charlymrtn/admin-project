<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Carbon\Carbon;
use DB;

class NotificacionController extends Controller
{
    public function get()
    {
        $notificaciones = Notification::where('notifiable_id','=',Auth::user()->uuid)
                                        ->get();

        $fecha = date('Y-m-d');
        foreach ($notificaciones as $item) {
            if ($fecha != $item->created_at->toDateString()){
                $item->read_at = Carbon::parse($fecha);
            }
        }

        return response()->json($notificaciones);
    }
}
