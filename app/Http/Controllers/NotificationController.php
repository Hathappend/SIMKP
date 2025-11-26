<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function open($id): RedirectResponse
    {
        // Cari notifikasi milik user yang sedang login
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();

            if ($notification->type === 'App\Notifications\LetterRequestNotification') {
                return redirect($notification->data['link']);
            }

            // Redirect user ke link yang ada di dalam data notifikasi
            return redirect($notification->data['link']);
        }

        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }
}
