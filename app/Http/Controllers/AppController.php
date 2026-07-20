<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventUsers;
use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Models\GuestPublic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function dashboard(Request $request)
    {
        if (Auth::user()->role->nama_role == "Administrator") {

            $events = Event::orderBy('nama_event')->get();

            // Kalau belum memilih event
            if (!$request->event_id) {
                return view('modules.dashboard', [
                    'events' => $events,
                    'selectedEvent' => null
                ]);
            }

            $eventId = $request->event_id;
        } else {

            // Admin Event
            $cek = EventUsers::where('user_id', Auth::id())->first();

            if (!$cek) {
                abort(403);
            }

            $eventId = $cek->event_id;

            $events = collect();
        }

        $selectedEvent = Event::find($eventId);

        $totalTamu = Guest::where("event_id", $eventId)->count();

        $tamuHadir = GuestCheckin::where("event_id", $eventId)->count();

        $belumHadir = $totalTamu - $tamuHadir;

        $totalHadir = Guest::where("event_id", $eventId)
            ->where("status_kehadiran", 1)
            ->count();

        $totalTamuLuarHadir = GuestPublic::where("event_id", $eventId)->count();

        $persen = $totalTamu > 0
            ? round(($tamuHadir / $totalTamu) * 100)
            : 0;

        $guest_invitation = GuestCheckin::with('guest.kategori')
            ->where("event_id", $eventId)
            ->latest()
            ->limit(10)
            ->get();

        $guest_public = GuestPublic::where("event_id", $eventId)
            ->latest()
            ->limit(10)
            ->get();

        $kedatangan = GuestCheckin::where("event_id", $eventId)
            ->selectRaw('HOUR(waktu_checkin) as jam, COUNT(*) as total')
            ->groupBy('jam')
            ->orderBy('jam')
            ->get();

        $chartJam = [];
        $chartTotal = [];

        foreach ($kedatangan as $row) {
            $chartJam[] = sprintf('%02d:00', $row->jam);
            $chartTotal[] = $row->total;
        }

        return view('modules.dashboard', compact(
            'events',
            'selectedEvent',
            'totalTamu',
            'tamuHadir',
            'belumHadir',
            'totalHadir',
            'persen',
            'guest_invitation',
            'guest_public',
            'chartJam',
            'chartTotal',
            'totalTamuLuarHadir'
        ));
    }

    public function error_page()
    {
        return view("modules.error-page");
    }
}
