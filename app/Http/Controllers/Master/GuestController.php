<?php

namespace App\Http\Controllers\Master;

use App\Exports\GuestExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\CreateRequest;
use App\Http\Requests\Guest\UpdateRequest;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Guest::with('kategori')
                ->orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('kategori', function ($row) {
                    return $row->kategori?->nama_kategori;
                })

                ->addColumn('status', function ($row) {
                    if ($row->status_kehadiran == 1) {
                        return '<span class="badge bg-success text-white">Sudah Hadir</span>';
                    }
                    return '<span class="badge bg-danger text-white">Belum Hadir</span>';
                })

                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/guest/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/guest/' . $row->id . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button onclick="return confirm(\'Yakin?\')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view("modules.master.guest.index");
    }

    public function create()
    {
        try {

            DB::beginTransaction();

            $data["kategori"] = Kategori::get();

            DB::commit();

            return view("modules.master.guest.create", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            $event = Event::first();

            $token = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, rand(6, 8));

            Guest::create([
                "event_id" => $event["id"],
                "kategori_id" => $request["kategori_id"],
                "kode_token" => $token,
                "nama_tamu" => $request["nama_tamu"],
                "keluarga" => $request["keluarga"],
                "jumlah_undangan" => $request["jumlah_undangan"]
            ]);

            DB::commit();

            return back()->with("success", "Data Berhasil di Tambahkan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {

            DB::beginTransaction();

            $data["kategori"] = Kategori::get();
            $data["edit"] = Guest::where("id", $id)->first();

            DB::commit();

            return view("modules.master.guest.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            Guest::where("id", $id)->update([
                "kategori_id" => $request["kategori_id"],
                "nama_tamu" => $request["nama_tamu"],
                "keluarga" => $request["keluarga"],
                "jumlah_undangan" => $request["jumlah_undangan"]
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

            Guest::where("id", $id)->delete();

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function change_status($id)
    {
        try {

            DB::beginTransaction();

            $kategori = Kategori::where("id", $id)->first();

            if ($kategori['is_active'] == "1") {
                $kategori->update([
                    "is_active" => "0"
                ]);
            } else if ($kategori['is_active'] == "0") {
                $kategori->update([
                    "is_active" => "1"
                ]);
            }

            DB::commit();

            return back()->with("success", "Data Berhasil di Simpan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function download()
    {
        return Excel::download(new GuestExport, 'data-guest.xlsx');
    }

    public function upload_file(Request $request)
    {
        try {

            DB::beginTransaction();

            DB::commit();

            return back()->with("success", "Upload Data Berhasil di Lakukan");

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
