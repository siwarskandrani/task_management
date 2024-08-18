<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Récupérer les notifications non lues de l'utilisateur authentifié
        $notifications = Auth::user()->unreadNotifications;

        // Marquer les notifications comme lues
        Auth::user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}
