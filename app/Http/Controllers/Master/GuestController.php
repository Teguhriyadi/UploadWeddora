<?php

namespace App\Http\Controllers\Master;

use App\Exports\GuestExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\CreateRequest;
use App\Http\Requests\Guest\UpdateRequest;
use App\Imports\GuestImport;
use App\Models\Event;
use App\Models\EventUsers;
use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Models\Kategori;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $eventId = $this->getActiveEventId();

        if ($request->ajax()) {

            $data = Guest::with([
                "kategori:id,nama_kategori",
                "event:id,nama_event"
            ])
                ->where("event_id", $eventId)
                ->filter($request);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kategori', function ($row) {
                    return $row->kategori?->nama_kategori;
                })
                ->addColumn('event', function ($row) {
                    return $row->event->nama_event;
                })
                ->addColumn('status', function ($row) {

                    $badgeClass = $row->status_kehadiran == 1
                        ? 'btn-success'
                        : 'btn-danger';

                    $badgeText = $row->status_kehadiran == 1
                        ? 'Sudah Hadir'
                        : 'Belum Hadir';

                    return '
                    <div class="dropdown dropend">
                        <button
                            class="btn ' . $badgeClass . ' btn-sm dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            ' . $badgeText . '
                        </button>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item change-status-kehadiran"
                                    href="javascript:void(0)"
                                    data-id="' . $row->id . '"
                                    data-value="1">
                                    Sudah Hadir
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item change-status-kehadiran"
                                    href="javascript:void(0)"
                                    data-id="' . $row->id . '"
                                    data-value="0">
                                    Belum Hadir
                                </a>
                            </li>
                        </ul>
                    </div>
                ';
                })
                ->addColumn('kehadiran', function ($row) {

                    $selectedNull = is_null($row->kehadiran) ? 'selected' : '';
                    $selected0 = $row->kehadiran == '0' ? 'selected' : '';
                    $selected1 = $row->kehadiran == '1' ? 'selected' : '';
                    $selected2 = $row->kehadiran == '2' ? 'selected' : '';

                    return '
                <select class="form-control form-select-sm change-kehadiran" data-id="' . $row->id . '">
                    <option value="" ' . $selectedNull . '>Belum Ditentukan</option>
                    <option value="0" ' . $selected0 . '>Kemungkinan Tidak Hadir</option>
                    <option value="1" ' . $selected1 . '>Pasti Hadir</option>
                    <option value="2" ' . $selected2 . '>Tidak Hadir</option>
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
                    if (Auth::user()->role->nama_role != "Administrator") {
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
                        return '
                        <a href="' . url('/modules/guest/generate-card/' . $row['kode_token']) . '" class="btn btn-success btn-sm">
                            <i class="fa fa-download"></i> Download
                        </a>
                    ';
                    }
                })

                ->rawColumns(['status', 'action', 'kehadiran', 'status_undangan', 'checkbox'])
                ->make(true);
        }

        $data["event"] = Event::get(["*"]);

        return view("modules.master.guest.index", $data);
    }

    public function create()
    {
        try {

            DB::beginTransaction();

            $data["kategori"] = Kategori::get(['*']);
            $data["event"] = Event::get(['*']);

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

            $cek = EventUsers::where("user_id", Auth::user()->id)->first();

            if (empty($cek)) {
                return redirect()->to("/modules/guest")->with("error", "Data Event Anda Tidak Ditemukan. Silahkan Hubungi Admin Kembali");
            }

            $token = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, rand(6, 8));

            ActivityLogger::setContext('Tamu Undangan', 'create', [
                'kode_token' => $token,
                'nama_tamu' => $request["nama_tamu"],
            ]);

            Guest::create([
                "event_id" => $cek["event_id"],
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

            $data["kategori"] = Kategori::get(['*']);
            $data["edit"] = Guest::where("id", "=", $id, "and")->first(['*']);
            $data["event"] = Event::get(['*']);

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

            $guest = Guest::where("id", "=", $id, "and")->first(['*']);
            ActivityLogger::setContext('Tamu Undangan', 'update', [
                'guest_id' => $guest?->id,
            ]);
            $guest->update([
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

            $guest = Guest::where("id", "=", $id, "and")->first(['*']);
            if ($guest) {
                ActivityLogger::setContext('Tamu Undangan', 'delete', [
                    'guest_id' => $guest->id,
                ]);
                $guest->delete();
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

    public function download(Request $request)
    {
        $eventId = $this->getActiveEventId();

        return Excel::download(
            new GuestExport($request, $eventId),
            'Daftar_Guest.xlsx'
        );
    }

    public function upload_file(Request $request)
    {
        try {

            DB::beginTransaction();

            $request->validate([
                'file_upload' => 'required|mimes:xlsx,xls,csv'
            ]);

            $eventId = $this->getActiveEventId();

            Excel::import(new GuestImport($eventId), $request->file('file_upload'));

            $file = $request->file('file_upload');
            ActivityLogger::log('Tamu Undangan', 'upload_excel', null, null, null, [
                'original_name' => $file?->getClientOriginalName(),
                'mime' => $file?->getClientMimeType(),
                'size' => $file?->getSize(),
            ]);

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

            ActivityLogger::setContext('Tamu Undangan', 'ubah_kehadiran', [
                'guest_id' => $guest->id,
            ]);
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

            ActivityLogger::setContext('Tamu Undangan', 'ubah_status_undangan', [
                'guest_id' => $guest->id,
            ]);
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
        $guest = Guest::where('kode_token', '=', $token, 'and')->firstOrFail(['*']);

        $event_name = Event::first(['*']);
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
        try {
            $eventId = $this->getActiveEventId();

            $event = Event::findOrFail($eventId);

            $guests = Guest::where('jenis_undangan', '=', 'Cetak', 'and')
                ->where("event_id", $eventId)
                ->get(['*']);

            return view('qr-generate-all', compact(
                'guests',
                'event'
            ));
        } catch (\Exception $e) {
            return back()->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function delete_selected(Request $request)
    {
        $ids = $request->ids ?: [];
        $before = Guest::whereIn('id', $ids, 'and', false)->get(['id', 'nama_tamu', 'kode_token'])->toArray();
        Guest::whereIn('id', $ids, 'and', false)->delete();

        ActivityLogger::log('Tamu Undangan', 'bulk_delete', null, $before, null, [
            'ids' => $ids,
            'count' => is_array($ids) ? count($ids) : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function update_status_kehadiran(Request $request)
    {
        try {

            $user = Auth::user()->id;
            $now = date("Y-m-d H:i:s");

            Guest::where('id', $request->id)
                ->update([
                    'status_kehadiran' => $request->status_kehadiran,
                    'inject_at' => $now,
                    'inject_by' => $user
                ]);

            GuestCheckin::create([
                "guest_id" => $request->id,
                "metode" => "manual",
                "waktu_checkin" => date("Y-m-d H:i:s"),
                "users_id" => $user
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
