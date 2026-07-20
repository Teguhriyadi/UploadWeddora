<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabang\CreateRequest;
use App\Http\Requests\Cabang\UpdateRequest;
use App\Models\Cabang;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Cabang::orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/cabang/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/cabang/' . $row->id . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button onclick="return confirm(\'Yakin? Apakah Anda Ingin Menghapus Data Ini?\')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view("modules.master.cabang.index");
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            ActivityLogger::setContext('Master Cabang', 'create', [
                'nama' => $request->nama,
            ]);
            Cabang::create([
                "nama" => $request->nama,
                "kota" => $request->kota,
                "alamat" => $request->alamat
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

            $data["edit"] = Cabang::where("id", "=", $id, "and")->first(['*']);

            DB::commit();

            return view("modules.master.cabang.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function datatable(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = Cabang::where("id", "!=", $id, "and")
                ->orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    if ($row->is_active == "AKTIF") {
                        return '
                        <button class="btn btn-success btn-sm btn-toggle-status"
                            data-id="' . $row->id . '"
                            data-status="0">
                            <i class="fa fa-check"></i> Aktif
                        </button>
                    ';
                    }

                    return '
                    <button class="btn btn-danger btn-sm btn-toggle-status"
                        data-id="' . $row->id . '"
                        data-status="1">
                        <i class="fa fa-times"></i> Non Aktif
                    </button>
                ';
                })

                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/cabang/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/cabang/' . $row->id . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button onclick="return confirm(\'Yakin? Apakah Anda Ingin Menghapus Data Ini?\')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $cabang = Cabang::where("id", "=", $id, "and")->first(['*']);
            ActivityLogger::setContext('Master Cabang', 'update', [
                'cabang_id' => $cabang?->id,
            ]);
            $cabang->update([
                "nama" => $request["nama"],
                "kota" => $request["kota"],
                "alamat" => $request["alamat"]
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

            $cabang = Cabang::where("id", "=", $id, "and")->first(['*']);
            if ($cabang) {
                ActivityLogger::setContext('Master Cabang', 'delete', [
                    'cabang_id' => $cabang->id,
                ]);
                $cabang->delete();
            }

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
