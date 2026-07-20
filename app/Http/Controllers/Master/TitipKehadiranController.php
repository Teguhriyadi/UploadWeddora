<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\TitipKehadiran\CreateRequest;
use App\Http\Requests\TitipKehadiran\UpdateRequest;
use App\Models\EventUsers;
use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Models\GuestPublic;
use App\Models\TitipKehadiran;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TitipKehadiranController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = TitipKehadiran::with([
                "wakil_tamu:id,nama_tamu,nama_undangan",
                "wakil_tamu_luar:id,nama",
                "tamu_berhalangan:id,nama_tamu,nama_undangan",
                "petugas:id,nama"
            ]);

            if (Auth::user()->role->nama_role != "Administrator") {
                $cek = EventUsers::where("user_id", Auth::user()->id)->first();

                $data = $data->where("event_id", $cek->event_id)->orderBy('waktu_kehadiran', 'DESC');
            } else {
                $data = $data->orderBy('waktu_kehadiran', 'DESC');
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('wakil_tamu', function ($row) {
                    if ($row->wakil_tamu) {
                        return $row->wakil_tamu->nama_tamu . " - " . $row->wakil_tamu->nama_undangan;
                    }

                    if ($row->wakil_tamu_luar) {
                        return $row->wakil_tamu_luar->nama . " - Tamu Luar";
                    }

                    return "-";
                })
                ->addColumn("nama_tamu_berhalangan", function ($row) {
                    if ($row->guest_id) {
                        return $row->tamu_berhalangan->nama_tamu . " - " . $row->tamu_berhalangan->nama_undangan;
                    } else if ($row->nama_tamu) {
                        return $row->nama_tamu;
                    }
                })
                ->addColumn("waktu_penitipan", function ($row) {
                    return \Carbon\Carbon::parse($row->waktu_kehadiran)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i:s');
                })
                ->addColumn("petugas", function ($row) {
                    return $row->petugas->nama;
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/titip-kehadiran/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/titip-kehadiran/' . $row->id . '" method="POST" style="display:inline;" class="delete-form">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['status', 'action', 'foto', 'checkbox'])
                ->make(true);
        }

        return view("modules.master.titip-kehadiran.index");
    }

    public function create()
    {
        try {
            DB::beginTransaction();

            $cek = EventUsers::where("user_id", Auth::user()->id)->first();

            if (empty($cek)) {
                return redirect()->to("/modules/titip-kehadiran")->with("error", "Data Event Anda Tidak Ditemukan. Silahkan Hubungi Admin Kembali");
            }

            $data["wakil"] = Guest::where("event_id", $cek->event_id)->where("status_kehadiran", "=", "1", "and")->get(['*']);
            $data["wakil_public"] = GuestPublic::where("event_id", $cek->event_id)->orderBy('waktu_checkin', 'DESC')->get(['*']);
            $data["guest"] = Guest::where("event_id", $cek->event_id)->where("status_kehadiran", "!=", "1", "and")->get(['*']);

            DB::commit();

            return view("modules.master.titip-kehadiran.create", $data);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            $cek = EventUsers::where("user_id", Auth::user()->id)->first();

            if (empty($cek)) {
                return redirect()->to("/modules/titip-kehadiran")->with("error", "Data Event Anda Tidak Ditemukan. Silahkan Hubungi Admin Kembali");
            }

            $waktu_datang = date("Y-m-d H:i:s");
            $users = Auth::user()->id;

            ActivityLogger::setContext('Titip Kehadiran', 'create', [
                'wakil_id' => $request->wakil_id,
                'wakil_guest_public_id' => $request->wakil_guest_public_id,
                'guest_id' => $request->guest_id,
            ]);

            TitipKehadiran::create([
                'event_id' => $cek->event_id,
                'wakil_id' => $request->wakil_id,
                'wakil_guest_public_id' => $request->wakil_guest_public_id,
                'guest_id' => $request->guest_id,
                'nama_tamu' => $request->nama_tamu,
                "alasan_tidak_hadir" => empty($request->alasan_tidak_hadir) ? "Ada Keperluan" : $request->alasan_tidak_hadir,
                "catatan" => empty($request->catatan) ? null : $request->catatan,
                "waktu_kehadiran" => $waktu_datang,
                "petugas_id" => $users
            ]);

            if ($request->guest_id) {
                GuestCheckin::create([
                    "guest_id" => $request->guest_id,
                    "metode" => "manual",
                    "waktu_checkin" => $waktu_datang,
                    "users_id" => $users
                ]);

                $cek = Guest::where("id", "=", $request->guest_id, "and")->first(['*']);
                if ($cek) {
                    ActivityLogger::setContext('Tamu Undangan', 'ubah_kehadiran', [
                        'guest_id' => $cek->id,
                        'sumber' => 'titip_kehadiran',
                    ]);
                    $cek->status_kehadiran = "1";
                    $cek->save();
                }
            }

            DB::commit();

            return redirect()->to("/modules/titip-kehadiran")->with("success", "Data Berhasil di Tambahkan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {

            DB::beginTransaction();

            $cek = EventUsers::where("user_id", Auth::user()->id)->first();

            if (empty($cek)) {
                return redirect()->to("/modules/titip-kehadiran")->with("error", "Data Event Anda Tidak Ditemukan. Silahkan Hubungi Admin Kembali");
            }

            $data["wakil"] = Guest::where("event_id", $cek->event_id)->where("status_kehadiran", "=", "1", "and")->get(['*']);
            $data["wakil_public"] = GuestPublic::where("event_id", $cek->event_id)->orderBy('waktu_checkin', 'DESC')->get(['*']);
            $data["guest"] = Guest::where("event_id", $cek->event_id)->get(['*']);

            $data["edit"] = TitipKehadiran::where("id", "=", $id, "and")->first(['*']);

            DB::commit();

            return view("modules.master.titip-kehadiran.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $titip = TitipKehadiran::where("id", "=", $id, "and")->first(['*']);

            ActivityLogger::setContext('Titip Kehadiran', 'update', [
                'titip_kehadiran_id' => $titip?->id,
            ]);

            if ($titip->guest_id) {
                $cek = Guest::where("id", "=", $titip->guest_id, "and")->first(['*']);

                if ($cek) {
                    ActivityLogger::setContext('Tamu Undangan', 'ubah_kehadiran', [
                        'guest_id' => $cek->id,
                        'sumber' => 'titip_kehadiran',
                    ]);
                    $cek->update([
                        "status_kehadiran" => "0"
                    ]);
                }
            }

            $titip->update([
                'wakil_id' => $request->wakil_id,
                'wakil_guest_public_id' => $request->wakil_guest_public_id,
                'guest_id' => $request->guest_id,
                'nama_tamu' => $request->nama_tamu,
                "alasan_tidak_hadir" => empty($request->alasan_tidak_hadir) ? "Ada Keperluan" : $request->alasan_tidak_hadir,
                "catatan" => empty($request->catatan) ? null : $request->catatan
            ]);

            if ($request->guest_id) {
                $guest = Guest::where("id", "=", $request->guest_id, "and")->first(['*']);
                if ($guest) {
                    ActivityLogger::setContext('Tamu Undangan', 'ubah_kehadiran', [
                        'guest_id' => $guest->id,
                        'sumber' => 'titip_kehadiran',
                    ]);
                    $guest->update([
                        "status_kehadiran" => "1"
                    ]);
                }

                $guestCheckin = GuestCheckin::where("guest_id", "=", $request->guest_id, "and")->first(['*']);
                if ($guestCheckin) {
                    $guestCheckin->update([
                        "guest_id" => $request->guest_id
                    ]);
                }
            }

            DB::commit();

            return redirect()->to("/modules/titip-kehadiran")->with("success", "Data Berhasil di Simpan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $cek = TitipKehadiran::where("id", "=", $id, "and")->first(['*']);

            if ($cek->guest_id) {
                $guest = Guest::where("id", "=", $cek->guest_id, "and")->first(['*']);
                if ($guest) {
                    ActivityLogger::setContext('Tamu Undangan', 'ubah_kehadiran', [
                        'guest_id' => $guest->id,
                        'sumber' => 'titip_kehadiran',
                    ]);
                    $guest->update([
                        "status_kehadiran" => "0"
                    ]);
                }
            }

            ActivityLogger::setContext('Titip Kehadiran', 'delete', [
                'titip_kehadiran_id' => $cek->id,
            ]);
            $cek->delete();

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function delete_selected(Request $request)
    {
        $ids = $request->ids ?: [];
        $before = Guest::whereIn('id', $ids, 'and', false)->get(['id', 'nama_tamu', 'kode_token'])->toArray();
        Guest::whereIn('id', $ids, 'and', false)->delete();

        ActivityLogger::log('Titip Kehadiran', 'bulk_delete', null, $before, null, [
            'ids' => $ids,
            'count' => is_array($ids) ? count($ids) : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
