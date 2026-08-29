<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventUsers;

class Controller extends BaseController
{
    protected function getActiveEventId()
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        if ($user->role->nama_role == "Administrator") {
            $activeEvent = Event::where('is_active', "1")->first() ?? Event::first();
            return $activeEvent ? $activeEvent->id : null;
        } else {
            $cek = EventUsers::where('user_id', $user->id)->first();
            return $cek ? $cek->event_id : null;
        }
    }
}