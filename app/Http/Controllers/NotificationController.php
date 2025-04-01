<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = User::find(auth()->user()->id);
        return response()->json($user->notifications, 200);
    }

    public function deleteNotification($id)
    {
        $user = User::find(auth()->user()->id);
        $notification = $user->notifications()->where('id', $id)->first();
        $notification->delete();
    }
}
