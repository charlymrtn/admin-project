<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function get()
    {
        return Notification::all();
    }
}
