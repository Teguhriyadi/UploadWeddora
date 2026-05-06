<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\CreateRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Role::orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/role/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/role/' . $row->id . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button onclick="return confirm(\'Yakin?\')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view("modules.master.role.index");
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            Role::create([
                "nama_role" => $request->nama_role
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

            $data["edit"] = Role::where("id", $id)->first();

            DB::commit();

            return view("modules.master.role.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function datatable(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = Role::where("id", "!=", $id)
                ->orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/role/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/role/' . $row->id . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button onclick="return confirm(\'Yakin?\')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            Role::where("id", $id)->update([
                "nama_role" => $request["nama_role"]
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

            Role::where("id", $id)->delete();

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
