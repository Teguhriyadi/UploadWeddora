<?php

namespace App\Http\Controllers\Master;

use App\Exports\GuestExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\CreateRequest;
use App\Http\Requests\Guest\UpdateRequest;
use App\Imports\GuestImport;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                ->addColumn('kehadiran', function ($row) {

                    $selected0 = $row->kehadiran == '0' ? 'selected' : '';
                    $selected1 = $row->kehadiran == '1' ? 'selected' : '';

                    return '
                        <select class="form-control form-select-sm change-kehadiran" data-id="' . $row->id . '">
                            <option value="0" ' . $selected0 . '>Kemungkinan Tidak Hadir</option>
                            <option value="1" ' . $selected1 . '>Pasti Hadir</option>
                        </select>
                    ';
                })
                ->addColumn('status_undangan', function ($row) {

                    $selected0 = $row->status_undangan == '0' ? 'selected' : '';
                    $selected1 = $row->status_undangan == '1' ? 'selected' : '';

                    return '
                        <select class="form-control form-select-sm change-status-undangan" data-id="' . $row->id . '">
                            <option value="0" ' . $selected0 . '>Belum Terkirim</option>
                            <option value="1" ' . $selected1 . '>Terkirim</option>
                        </select>
                    ';
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    if (Auth::user()->role->nama_role == "Administrator") {
                        return '
                            <a href="/modules/guest/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>

                            <form action="/modules/guest/' . $row->id . '" method="POST" style="display:inline;" class="delete-form">
                                ' . csrf_field() . '
                                ' . method_field("DELETE") . '
                                <button class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>

                            <a href="' . env('APP_URL') . '/qr/' . $row['kode_token'] . '" class="btn btn-info btn-sm" target="_blank">
                                <i class="fa fa-search"></i>
                            </a>

                            <a href="' . url('/modules/guest/generate-card/' . $row['kode_token']) . '" class="btn btn-success btn-sm">
                                <i class="fa fa-download"></i>
                            </a>
                        ';
                    } else {
                        return "-";
                    }
                })

                ->rawColumns(['status', 'action', 'kehadiran', 'status_undangan', 'checkbox'])
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
                "nama_undangan" => $request["nama_undangan"],
                "relasi" => $request["relasi"],
                "jenis_undangan" => $request["jenis_undangan"],
                "keterangan" => $request["keterangan"]
            ]);

            DB::commit();

            return redirect()->to("/modules/guest")->with("success", "Data Berhasil di Tambahkan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage())->withInput();
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
                "nama_undangan" => $request["nama_undangan"],
                "relasi" => $request["relasi"],
                "jenis_undangan" => $request["jenis_undangan"],
                "keterangan" => $request["keterangan"]
            ]);

            DB::commit();

            return redirect()->to("/modules/guest")->with("success", "Data Berhasil di Simpan");
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

            $request->validate([
                'file_upload' => 'required|mimes:xlsx,xls,csv'
            ]);

            Excel::import(new GuestImport, $request->file('file_upload'));

            DB::commit();

            return back()->with("success", "Upload Data Berhasil di Lakukan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function update_kehadiran(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required',
                'kehadiran' => 'required|in:0,1'
            ]);

            $guest = Guest::findOrFail($request->id);

            $guest->update([
                'kehadiran' => $request->kehadiran
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status kehadiran berhasil diupdate'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update_status_undangan(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required',
                'status_undangan' => 'required|in:0,1'
            ]);

            $guest = Guest::findOrFail($request->id);

            $guest->update([
                'status_undangan' => $request->status_undangan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status kehadiran berhasil diupdate'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show_generate($token)
    {
        $guest = Guest::where('kode_token', $token)->firstOrFail();

        $event_name = Event::first();
        $event_date = "13 Juni 2026";

        $html = view('qr-poster-generate', compact(
            'guest',
            'event_name',
            'event_date'
        ))->render();

        return response($html)
            ->header('Content-Type', 'text/html');
    }

    public function generate_all()
    {
        $guests = Guest::where('jenis_undangan', 'Cetak')->get();

        $event_name = Event::first();
        $event_date = "13 Juni 2026";

        return view('qr-generate-all', compact(
            'guests',
            'event_name',
            'event_date'
        ));
    }

    public function delete_selected(Request $request)
    {
        Guest::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
