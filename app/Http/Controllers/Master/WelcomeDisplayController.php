<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventUsers;
use App\Models\GuestCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeDisplayController extends Controller
{
    public function index()
    {
        $cek = EventUsers::where("user_id", Auth::user()->id)->first();
        $event = Event::findOrFail($cek->event_id);

        return view(
            "modules.welcome-display",
            compact("event")
        );

    }

    public function latest(Request $request)
    {
        $cek = EventUsers::where("user_id", Auth::user()->id)->first();
        $lastId = $request->last_id ?? 0;

        $guest = GuestCheckin::with([
            "guest"
        ])
        ->where('event_id', $cek->event_id)
        ->where("id",">",$lastId)
        ->orderBy("id")
        ->first();

        if(!$guest){

            return response()->json([
                "status"=>false
            ]);

        }

        return response()->json([

            "status"=>true,

            "last_id"=>$guest->id,

            "guest"=>[

                "nama"=>$guest->guest->nama,

                "id"=>$guest->id

            ]

        ]);

    }
}
