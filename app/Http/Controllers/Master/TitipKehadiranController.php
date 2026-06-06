<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\TitipKehadiran\CreateRequest;
use App\Http\Requests\TitipKehadiran\UpdateRequest;
use App\Models\Guest;
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

            $data = TitipKehadiran::with(["wakil_tamu:id,nama_tamu,nama_undangan", "tamu_berhalangan:id,nama_undangan", "petugas:id,nama"]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('wakil_tamu', function ($row) {
                    return $row->wakil_tamu->nama_undangan;
                })
                ->addColumn("nama_tamu_berhalangan", function ($row) {
                    if ($row->guest_id) {
                        return $row->tamu_berhalangan->nama_undangan;
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
        $data["wakil"] = Guest::where("status_kehadiran", "=", "1", "and")->get(['*']);
        $data["guest"] = Guest::get(['*']);

        return view("modules.master.titip-kehadiran.create", $data);
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            ActivityLogger::setContext('Titip Kehadiran', 'create', [
                'wakil_id' => $request->wakil_id,
                'guest_id' => $request->guest_id,
            ]);
            TitipKehadiran::create([
                'wakil_id' => $request->wakil_id,
                'guest_id' => $request->guest_id,
                'nama_tamu' => $request->nama_tamu,
                "alasan_tidak_hadir" => empty($request->alasan_tidak_hadir) ? "Ada Keperluan" : $request->alasan_tidak_hadir,
                "catatan" => empty($request->catatan) ? null : $request->catatan,
                "waktu_kehadiran" => date("Y-m-d H:i:s"),
                "petugas_id" => Auth::user()->id
            ]);

            if ($request->guest_id) {
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

            $data["wakil"] = Guest::where("status_kehadiran", "=", "1", "and")->get(['*']);
            $data["guest"] = Guest::get(['*']);
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
            $titip->update([
                'wakil_id' => $request->wakil_id,
                'guest_id' => $request->guest_id,
                'nama_tamu' => $request->nama_tamu,
                "alasan_tidak_hadir" => empty($request->alasan_tidak_hadir) ? "Ada Keperluan" : $request->alasan_tidak_hadir,
                "catatan" => empty($request->catatan) ? null : $request->catatan
            ]);

            DB::commit();

            return back()->with("success", "Data Berhasil di Simpan");
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
}
