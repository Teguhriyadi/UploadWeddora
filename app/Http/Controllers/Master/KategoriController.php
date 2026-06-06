<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kategori\CreateRequest;
use App\Http\Requests\Kategori\EditRequest;
use App\Models\Kategori;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Kategori::orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('nama_kategori', function ($row) {
                    return $row->nama_kategori;
                })

                ->addColumn('status', function ($row) {
                    if ($row->is_active == 1) {
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
                    <a href="/modules/kategori/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/kategori/' . $row->id . '" method="POST" style="display:inline;">
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

        return view("modules.master.kategori.index");
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            ActivityLogger::setContext('Master Kategori', 'create', [
                'nama_kategori' => $request->nama_kategori,
            ]);
            Kategori::create([
                "nama_kategori" => $request->nama_kategori
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

            $data["edit"] = Kategori::where("id", "=", $id, "and")->first(['*']);

            DB::commit();

            return view("modules.master.kategori.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function datatable(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = Kategori::where("id", "!=", $id, "and")
                ->orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    if ($row->is_active == 1) {
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
                    <a href="/modules/kategori/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/kategori/' . $row->id . '" method="POST" style="display:inline;">
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

    public function update(EditRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $kategori = Kategori::where("id", "=", $id, "and")->first(['*']);
            ActivityLogger::setContext('Master Kategori', 'update', [
                'kategori_id' => $kategori?->id,
            ]);
            $kategori->update([
                "nama_kategori" => $request["nama_kategori"]
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

            $kategori = Kategori::where("id", "=", $id, "and")->first(['*']);
            if ($kategori) {
                ActivityLogger::setContext('Master Kategori', 'delete', [
                    'kategori_id' => $kategori->id,
                ]);
                $kategori->delete();
            }

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

            $kategori = Kategori::where("id", "=", $id, "and")->first(['*']);
            $newStatus = ((string) $kategori->is_active === "1") ? "0" : "1";
            ActivityLogger::setContext('Master Kategori', 'ubah_status', [
                'kategori_id' => $kategori->id,
                'status' => $newStatus,
            ]);
            $kategori->update([
                "is_active" => $newStatus
            ]);

            DB::commit();

            return back()->with("success", "Data Berhasil di Simpan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $kategori = Kategori::findOrFail($id);

        ActivityLogger::setContext('Master Kategori', 'ubah_status', [
            'kategori_id' => $kategori->id,
            'status' => request('status'),
        ]);
        $kategori->is_active = request('status');
        $kategori->save();

        return response()->json([
            'message' => 'OK'
        ]);
    }
}
