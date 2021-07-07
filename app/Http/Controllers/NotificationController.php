<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function markAsSeen(Request $request)
    {
        $notification = Notification::where('id', $request->id)->first();
        $notification->seen = true;
        $notification->save();
    }
}
