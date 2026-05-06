<?php

namespace App\Http\Controllers\Report;

use App\Exports\HistoryGuest;
use App\Http\Controllers\Controller;
use App\Models\GuestCheckin;
use App\Models\GuestPublic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryGuestController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->get('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $guest_invitation = GuestCheckin::whereBetween('waktu_checkin', [
            $dari . ' 00:00:00',
            $sampai . ' 23:59:59'
        ])
            ->orderBy('waktu_checkin', 'DESC')
            ->get();

        $guest_public = GuestPublic::whereBetween('waktu_checkin', [
            $dari . ' 00:00:00',
            $sampai . ' 23:59:59'
        ])
            ->orderBy('waktu_checkin', 'DESC')
            ->get();

        return view("modules.report.history-guest.index", compact(
            'guest_invitation',
            'guest_public',
            'dari',
            'sampai'
        ));
    }

    public function download(Request $request)
    {
        $dari = $request->get('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $tab = $request->get('tab', 'tamu-undangan');

        return Excel::download(
            new HistoryGuest($dari, $sampai, $tab),
            'riwayat-' . $tab . '-' . $dari . '-sd-' . $sampai . '.xlsx'
        );
    }

    public function show($id)
    {
        $data["show"] = GuestCheckin::where("id", $id)->first();

        return view("modules.report.history-guest.show", $data);
    }

    public function show_guest_public($id)
    {
        $data["show"] = GuestPublic::where("id", $id)->first();

        return view("modules.report.history-guest.show", $data);
    }
}
