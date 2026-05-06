<?php

namespace App\Http\Controllers\InputManual;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InputAttendanceController extends Controller
{
    public function index()
    {
        try {

            DB::beginTransaction();

            $data["guest"] = Guest::get();

            DB::commit();

            return view("modules.input-attendance.index", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            if (!$request->selfie) {
                return back()->with("error", "Harus Foto Selfie Terlebih Dahulu");
            }

            DB::beginTransaction();

            $guest = Guest::where("id", $request["guest_id"])->first();

            $sudahCheckin = GuestCheckin::where('guest_id', $guest->id)->exists();

            if ($sudahCheckin) {
                return back()->with("error", "Nama Tamu " . $guest['nama_tamu'] . ' Sudah Masuk ke Dalam Acara');
            }

            $fileName = ImageHelper::uploadBase64ToS3($request->selfie);

            GuestCheckin::create([
                "guest_id" => $guest["id"],
                "metode" => "manual",
                "waktu_checkin" => now(),
                "users_id" => Auth::user()->id,
                "selfie_path" => $fileName,
            ]);

            Guest::where("id", $request["guest_id"])->update([
                "status_kehadiran" => 1
            ]);

            DB::commit();

            return back()->with("success", "Data Berhasil di Tambahkan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withInput()->with("error", $e->getMessage());
        }
    }

    public function info_guest($id)
    {
        $guest = Guest::with('kategori')->findOrFail($id);
        return response()->json([
            'nama' => $guest->nama_tamu,
            'kategori' => $guest->kategori->nama_kategori ?? '-',
            'keluarga' => $guest->keluarga,
            'jumlah' => $guest->jumlah_undangan
        ]);
    }

    public function search_guest(Request $request)
    {
        $q = $request->q;

        $data = Guest::with('kategori')
            ->where('nama_tamu', 'like', '%' . $q . '%')
            ->where("status_kehadiran", "0")
            ->limit(20)
            ->get();

        $result = $data->map(function ($item) {

            return [
                'id' => $item->id,
                'nama_tamu' => $item->nama_tamu,
                'keluarga' => $item->keluarga,
                'kategori' => $item->kategori->nama_kategori ?? ''
            ];
        });

        return response()->json($result);
    }
}
