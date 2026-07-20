<?php

namespace App\Http\Controllers\Report;

use App\Exports\HistoryGuest;
use App\Http\Controllers\Controller;
use App\Models\EventUsers;
use App\Models\GuestCheckin;
use App\Models\GuestPublic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class HistoryGuestController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->get(
            'dari',
            Carbon::now()->startOfMonth()->format('Y-m-d')
        );

        $sampai = $request->get(
            'sampai',
            Carbon::now()->endOfMonth()->format('Y-m-d')
        );

        return view(
            "modules.report.history-guest.index",
            compact('dari', 'sampai')
        );
    }

    public function download(Request $request)
    {
        $dari = $request->get(
            'dari',
            Carbon::now()->startOfMonth()->format('Y-m-d')
        );

        $sampai = $request->get(
            'sampai',
            Carbon::now()->endOfMonth()->format('Y-m-d')
        );

        $tab = $request->get(
            'tab',
            'tamu-undangan'
        );

        return Excel::download(
            new HistoryGuest($dari, $sampai, $tab),
            'riwayat-' . $tab . '-' . $dari . '-sd-' . $sampai . '.xlsx'
        );
    }

    public function show($id)
    {
        $cek = EventUsers::where("user_id", Auth::user()->id)->first();

        if (empty($cek)) {
            return redirect()->to("/modules/history-guest")->with("error", "Data Event Anda Tidak Ditemukan. Silahkan Hubungi Admin Kembali");
        }

        $data["show"] = GuestCheckin::where("event_id", $cek->event_id)->where("id", $id)->first();

        return view("modules.report.history-guest.show", $data);
    }

    public function show_guest_public($id)
    {
        $data["show"] = GuestPublic::where("id", $id)->first();

        return view("modules.report.history-guest.show", $data);
    }

    public function dataPublic(Request $request)
    {
        $data = GuestPublic::whereBetween('waktu_checkin', [
            $request->dari . ' 00:00:00',
            $request->sampai . ' 23:59:59'
        ])
            ->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('foto', function ($row) {

                if (empty($row->selfie_path)) {
                    return '
                    <span class="badge badge-danger">
                        Foto Tidak Ada
                    </span>
                ';
                }

                $url = Storage::disk('s3')
                    ->url('selfie/' . $row->selfie_path);

                return '
                    <img
                        src="' . $url . '"
                        width="70"
                        class="rounded"
                        style="cursor:pointer"
                        data-toggle="modal"
                        data-target="#exampleModal"
                        onclick="showImageGuestPublic(\'' . $row->id . '\')"
                    >
                ';
            })
            ->editColumn('waktu_checkin', function ($row) {
                return Carbon::parse($row->waktu_checkin)
                    ->locale('id')
                    ->translatedFormat('d F Y H:i:s');
            })
            ->rawColumns(['foto'])
            ->make(true);
    }

    public function dataInvitation(Request $request)
    {
        $data = GuestCheckin::with([
            "guest.kategori"
        ])
            ->whereBetween('waktu_checkin', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ])
            ->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('foto', function ($row) {

                if (empty($row->selfie_path)) {
                    return '
                    <span class="badge badge-danger">
                        Foto Tidak Ada
                    </span>
                ';
                }

                $url = Storage::disk('s3')
                    ->url('selfie/' . $row->selfie_path);

                return '
                    <img
                        src="' . $url . '"
                        width="70"
                        class="rounded"
                        style="cursor:pointer"
                        data-toggle="modal"
                        data-target="#exampleModal"
                        onclick="showImage(\'' . $row->id . '\')"
                    >
                ';
            })
            ->addColumn('nama_tamu', function ($row) {
                return $row->guest->nama_tamu ?? '-';
            })
            ->addColumn('nama_undangan', function ($row) {
                return $row->guest->nama_undangan ?? '-';
            })
            ->addColumn('relasi', function ($row) {
                return $row->guest->relasi ?? '-';
            })
            ->addColumn('keterangan', function ($row) {
                return $row->guest->keterangan ?? '-';
            })
            ->editColumn("metode", function ($row) {
                if ($row->metode == "manual") {
                    if ($row->guest->inject_at) {
                        return "
                            <span
                                class='badge bg-info text-white fw-bold text-uppercase'
                                data-bs-toggle='tooltip'
                                data-bs-placement='top'
                                title='Status kehadiran diubah secara manual oleh: {$row['guest']['inject']['nama']}'>
                                Inject Manual
                            </span>
                            <br>
                            <small class='text-muted'>
                                " . \Carbon\Carbon::parse($row['guest']['inject_at'])
                            ->locale('id')
                            ->translatedFormat('d F Y H:i:s') . " WIB
                            </small>
                        ";
                    } else {
                        return "<span class='badge bg-primary text-white fw-bold text-uppercase'>Input Manual</span>";
                    }
                } else if ($row->metode == "qr") {
                    return "<span class='badge bg-success text-white fw-bold text-uppercase'>Scan QR</span>";
                }
            })
            ->editColumn('waktu_checkin', function ($row) {
                return Carbon::parse($row->waktu_checkin)
                    ->locale('id')
                    ->translatedFormat('d F Y H:i:s');
            })
            ->rawColumns(['foto', 'metode'])
            ->make(true);
    }
}
