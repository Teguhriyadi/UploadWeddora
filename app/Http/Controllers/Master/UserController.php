<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = User::with('role')
                ->orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    return $row->role?->nama_role;
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
                ->rawColumns(['status', 'action'])
                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/users/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/users/' . $row->id . '" method="POST" style="display:inline;">
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

        return view("modules.master.users.index");
    }

    public function create()
    {
        try {

            DB::beginTransaction();

            $data["role"] = Role::get();

            DB::commit();

            return view("modules.master.users.create", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            User::create([
                "nama" => $request['nama'],
                "username" => $request["username"],
                "email" => $request["email"],
                "password" => bcrypt("password"),
                "role_id" => $request['role_id']
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

            $data["role"] = Role::get();
            $data["edit"] = User::where("id", $id)->first();

            DB::commit();

            return view("modules.master.users.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            User::where("id", $id)->update([
                "nama" => $request['nama'],
                "username" => $request["username"],
                "email" => $request["email"],
                "role_id" => $request['role_id']
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

            User::where("id", $id)->delete();

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->is_active = request('status');
        $user->save();

        return response()->json([
            'message' => 'OK'
        ]);
    }
}
