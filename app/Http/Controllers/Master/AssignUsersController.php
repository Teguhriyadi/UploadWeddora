<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignUsers\CreateRequest;
use App\Http\Requests\AssignUsers\UpdateRequest;
use App\Models\Event;
use App\Models\EventUsers;
use App\Models\Role;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AssignUsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = EventUsers::with([
                "event:id,nama_event",
                "users:id,nama"
            ])
                // ->filter($request)
                ->orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('event', function ($row) {
                    return $row->event->nama_event;
                })
                ->addColumn('user', function ($row) {
                    return $row->users->nama;
                })
                // ->addColumn('status', function ($row) {

                //     $badgeClass = $row->status_kehadiran == 1
                //         ? 'btn-success'
                //         : 'btn-danger';

                //     $badgeText = $row->status_kehadiran == 1
                //         ? 'Sudah Hadir'
                //         : 'Belum Hadir';

                //     return '
                //         <div class="dropdown dropend">
                //             <button
                //                 class="btn ' . $badgeClass . ' btn-sm dropdown-toggle"
                //                 type="button"
                //                 data-bs-toggle="dropdown"
                //                 aria-expanded="false">
                //                 ' . $badgeText . '
                //             </button>

                //             <ul class="dropdown-menu">
                //                 <li>
                //                     <a class="dropdown-item change-status-kehadiran"
                //                         href="javascript:void(0)"
                //                         data-id="' . $row->id . '"
                //                         data-value="1">
                //                         Sudah Hadir
                //                     </a>
                //                 </li>

                //                 <li>
                //                     <a class="dropdown-item change-status-kehadiran"
                //                         href="javascript:void(0)"
                //                         data-id="' . $row->id . '"
                //                         data-value="0">
                //                         Belum Hadir
                //                     </a>
                //                 </li>
                //             </ul>
                //         </div>
                //     ';
                // })
                ->addColumn('action', function ($row) {
                    return '
                            <a href="/modules/assign-users/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>

                            <form action="/modules/assign-users/' . $row->id . '" method="POST" style="display:inline;" class="delete-form">
                                ' . csrf_field() . '
                                ' . method_field("DELETE") . '
                                <button class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                        ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view("modules.master.assign-users.index");
    }

    public function create()
    {
        try {

            DB::beginTransaction();

            $role = Role::where("nama_role", "Administrator")->first();

            $data["users"] = User::whereNot("role_id", $role["id"])->with(["role:id,nama_role"])->get(["*"]);
            $data["event"] = Event::get(['*']);

            DB::commit();

            return view("modules.master.assign-users.create", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            ActivityLogger::setContext('Master Assign Users', 'create', [
                'event_id' => $request["event_id"],
                'user_id' => $request["user_id"],
            ]);

            EventUsers::create([
                "event_id" => $request["event_id"],
                "user_id" => $request["user_id"],
                "jabatan" => $request["jabatan"]
            ]);

            DB::commit();

            return redirect()->to("/modules/assign-users")->with("success", "Data Berhasil di Tambahkan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {

            DB::beginTransaction();

            $role = Role::where("nama_role", "Administrator")->first();

            $data["users"] = User::whereNot("role_id", $role["id"])->with(["role:id,nama_role"])->get(["*"]);
            $data["event"] = Event::get(['*']);

            $data["edit"] = EventUsers::where("id", "=", $id, "and")->first(['*']);

            DB::commit();

            return view("modules.master.assign-users.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $assignUser = EventUsers::where("id", "=", $id, "and")->first(['*']);
            ActivityLogger::setContext('Master Assign Users', 'update', [
                'event_users_id' => $assignUser?->id,
            ]);
            $assignUser->update([
                "event_id" => $request["event_id"],
                "user_id" => $request["user_id"],
                "jabatan" => $request["jabatan"]
            ]);

            DB::commit();

            return redirect()->to("/modules/assign-users")->with("success", "Data Berhasil di Simpan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $event_users = EventUsers::where("id", "=", $id, "and")->first(['*']);
            if ($event_users) {
                ActivityLogger::setContext('Master Assign Users', 'delete', [
                    'event_users_id' => $event_users->id,
                ]);
                $event_users->delete();
            }

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
