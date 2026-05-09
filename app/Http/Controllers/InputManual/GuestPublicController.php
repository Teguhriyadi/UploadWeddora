<?php

namespace App\Http\Controllers\InputManual;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuestPublic\CreateRequest;
use App\Http\Requests\GuestPublic\UpdateRequest;
use App\Models\GuestPublic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GuestPublicController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = GuestPublic::orderBy('created_at', 'DESC');

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

                    <form action="/modules/guest-public/' . $row->id . '" method="POST" style="display:inline;">
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

            $fileName = ImageHelper::uploadBase64ToS3($request->selfie);

            GuestPublic::create([
                "nama" => $request['nama'],
                "nomor_handphone" => $request["nomor_handphone"],
                "pekerjaan" => $request["pekerjaan"],
                "alamat" => $request["alamat"],
                "waktu_checkin" => now(),
                "users_id" => Auth::user()->id,
                "jumlah_kedatangan" => $request["jumlah_kedatangan"],
                "selfie_path" => $fileName,
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

            $data["edit"] = GuestPublic::where("id", $id)->first();

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

            $guest = GuestPublic::findOrFail($id);

            $selfiePath = $guest->selfie_path;

            if ($request->selfie) {
                if ($guest->selfie_path) {
                    Storage::disk('s3')->delete('selfie/' . $guest->selfie_path);
                }

                $selfiePath = ImageHelper::uploadBase64ToS3($request->selfie, 'selfie', 800, 70);
            }

            $guest->update([
                'nama' => $request->nama,
                'nomor_handphone' => $request->nomor_handphone,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'selfie_path' => $selfiePath
            ]);

            DB::commit();

            return back()->with("success", "Data berhasil di simpan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $cek = GuestPublic::where("id", $id)->first();

            if ($cek->selfie_path) {
                Storage::disk('s3')->delete('selfie/' . $cek->selfie_path);
            }

            $cek->delete();

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
