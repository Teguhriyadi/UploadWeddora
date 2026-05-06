<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Models\GuestPublic;

class AppController extends Controller
{
    public function dashboard()
    {
        $totalTamu = Guest::count();

        $tamuHadir = GuestCheckin::count();

        $belumHadir = $totalTamu - $tamuHadir;

        $totalHadir = Guest::where("status_kehadiran", 1)->count();

        $persen = $totalTamu > 0
            ? round(($tamuHadir / $totalTamu) * 100)
            : 0;

        $guest_invitation = GuestCheckin::with('guest.kategori')
            ->latest()
            ->limit(10)
            ->get();

        $guest_public = GuestPublic::latest()
            ->limit(10)
            ->get();

        $kedatangan = GuestCheckin::selectRaw('HOUR(waktu_checkin) as jam, COUNT(*) as total')
            ->groupBy('jam')
            ->orderBy('jam')
            ->get();

        $chartJam = [];
        $chartTotal = [];

        foreach ($kedatangan as $row) {
            $chartJam[] = $row->jam . ":00";
            $chartTotal[] = $row->total;
        }

        return view('modules.dashboard', compact(
            'totalTamu',
            'tamuHadir',
            'belumHadir',
            'totalHadir',
            'persen',
            'guest_invitation',
            'guest_public',
            'chartJam',
            'chartTotal'
        ));
    }
}
