<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateRequest;
use App\Http\Requests\Event\UpdateRequest;
use App\Models\Cabang;
use App\Models\Event;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Event::with(["cabang:id,nama"])->orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cabang', function ($row) {
                    return empty($row->cabang) ? '-' : $row->cabang->nama;
                })
                ->addColumn("tanggal", function ($row) {
                    return \Carbon\Carbon::parse($row->tanggal)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i:s');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == "DRAFT") {
                        return '
                            <button class="btn btn-warning btn-sm btn-toggle-status"
                                data-id="' . $row->id . '"
                                data-status="DRAFT">
                                DRAFT
                            </button>
                        ';
                    } else if ($row->status == "AKTIF") {
                        return '
                            <button class="btn btn-success btn-sm btn-toggle-status"
                                data-id="' . $row->id . '"
                                data-status="AKTIF">
                                AKTIF
                            </button>
                        ';
                    } else if ($row->status == "SELESAI") {
                        return '
                            <button class="btn btn-primary btn-sm btn-toggle-status"
                                data-id="' . $row->id . '"
                                data-status="SELESAI">
                                SELESAI
                            </button>
                        ';
                    }
                })

                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/event/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/event/' . $row->id . '" method="POST" style="display:inline;">
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

        return view("modules.master.event.index");
    }

    public function create()
    {
        try {
            DB::beginTransaction();

            $data["cabang"] = Cabang::get();

            DB::commit();

            return view("modules.master.event.create", $data);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            $slug = Str::slug($request->nama_event);

            ActivityLogger::setContext('Master Event', 'create', [
                'nama_event' => $request["nama_event"],
                'slug' => $slug,
                'tanggal' => $request['tanggal']
            ]);

            Event::create([
                "cabang_id" => $request["cabang_id"],
                "nama_cpp" => $request["nama_cpp"],
                "nama_cpw" => $request["nama_cpw"],
                "nama_event" => $request["nama_event"],
                "slug" => $slug,
                "tanggal" => $request["tanggal"],
                "lokasi" => $request["lokasi"]
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

            $data["cabang"] = Cabang::get();
            $data["edit"] = Event::where("id", $id)->first();

            DB::commit();

            return view("modules.master.event.edit", $data);
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

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $event = Event::where("id", "=", $id, "and")->first(['*']);

            $slug = Str::slug($request->nama_event);

            ActivityLogger::setContext('Master Event', 'update', [
                'event_id' => $event?->id,
            ]);

            $event->update([
                "cabang_id" => $request["cabang_id"],
                "nama_cpp" => $request["nama_cpp"],
                "nama_cpw" => $request["nama_cpw"],
                "nama_event" => $request["nama_event"],
                "slug" => $slug,
                "tanggal" => $request["tanggal"],
                "lokasi" => $request["lokasi"]
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

            $event = Event::where("id", "=", $id, "and")->first(['*']);
            if ($event) {
                ActivityLogger::setContext('Master Event', 'delete', [
                    'event_id' => $event->id,
                ]);
                $event->delete();
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
