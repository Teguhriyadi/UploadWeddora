<?php

namespace App\Http\Controllers\InputManual;

use App\Exports\GuestPublicExport;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuestPublic\CreateRequest;
use App\Http\Requests\GuestPublic\UpdateRequest;
use App\Models\EventUsers;
use App\Models\GuestPublic;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class GuestPublicController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if (Auth::user()->role->nama_role != "Administrator") {
                $cek = EventUsers::where("user_id", Auth::user()->id)->first();

                $data = GuestPublic::where("event_id", $cek->event_id)->orderBy('created_at', 'DESC');
            } else {
                $data = GuestPublic::orderBy('created_at', 'DESC');
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jumlah_kedatangan', function ($row) {
                    if ($row->jumlah_kedatangan == NULL) {
                        return 0;
                    } else {
                        return $row->jumlah_kedatangan;
                    }
                })
                ->addColumn('waktu_checkin', function ($row) {
                    return \Carbon\Carbon::parse($row->waktu_checkin)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i:s');
                })

                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/guest-public/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/guest-public/' . $row->id . '" method="POST" style="display:inline;" class="delete-form">
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

        return view("modules.master.guest-public.index");
    }

    public function create()
    {
        try {

            DB::beginTransaction();

            DB::commit();

            return view("modules.master.guest-public.create");
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
                return redirect()->to("/modules/guest-public")->with("error", "Data Event Anda Tidak Ditemukan. Silahkan Hubungi Admin Kembali");
            }

            if ($request->selfie) {
                $fileName = ImageHelper::uploadBase64ToS3($request->selfie);
            } else {
                $fileName = NULL;
            }

            ActivityLogger::setContext('Guest Public', 'create', [
                'nama' => $request['nama'],
                'nomor_handphone' => $request["nomor_handphone"],
                'selfie_used' => (bool) $request->selfie,
            ]);
            GuestPublic::create([
                "event_id" => $cek["event_id"],
                "nama" => $request['nama'],
                "nomor_handphone" => $request["nomor_handphone"],
                "pekerjaan" => $request["pekerjaan"],
                "relasi" => $request["relasi"],
                "keterangan" => $request["keterangan"],
                "alamat" => $request["alamat"],
                "waktu_checkin" => now(),
                "users_id" => Auth::user()->id,
                "jumlah_kedatangan" => $request["jumlah_kedatangan"],
                "selfie_path" => $fileName,
            ]);

            DB::commit();

            return redirect()->to("/modules/guest-public")->with("success", "Data Berhasil di Tambahkan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {

            DB::beginTransaction();

            $data["edit"] = GuestPublic::where("id", "=", $id, "and")->first(['*']);

            DB::commit();

            return view("modules.master.guest-public.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $guest = GuestPublic::findOrFail($id);

            $selfiePath = $guest->selfie_path;
            $selfieChanged = false;

            if ($request->selfie) {
                if ($guest->selfie_path) {
                    Storage::disk('s3')->delete('selfie/' . $guest->selfie_path);
                }

                $selfiePath = ImageHelper::uploadBase64ToS3($request->selfie, 'selfie', 800, 70);
                $selfieChanged = true;
            }

            ActivityLogger::setContext('Guest Public', 'update', [
                'guest_public_id' => $guest->id,
                'selfie_changed' => $selfieChanged,
            ]);
            $guest->update([
                'nama' => $request->nama,
                'nomor_handphone' => $request->nomor_handphone,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'selfie_path' => $selfiePath
            ]);

            DB::commit();

            return redirect()->to("/modules/guest-public")->with("success", "Data berhasil di simpan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $cek = GuestPublic::where("id", "=", $id, "and")->first(['*']);

            if ($cek->selfie_path) {
                Storage::disk('s3')->delete('selfie/' . $cek->selfie_path);
            }

            ActivityLogger::setContext('Guest Public', 'delete', [
                'guest_public_id' => $cek->id,
            ]);
            $cek->delete();

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function download()
    {
        return Excel::download(
            new GuestPublicExport(),
            'Daftar_Tamu_Luar.xlsx'
        );
    }
}
