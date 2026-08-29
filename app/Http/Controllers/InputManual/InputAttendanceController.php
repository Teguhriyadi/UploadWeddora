<?php

namespace App\Http\Controllers\InputManual;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\EventUsers;
use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InputAttendanceController extends Controller
{
    public function index()
    {
        return view("modules.input-attendance.index");
    }

    public function store(Request $request)
    {
        try {

            // if (!$request->selfie) {
            //     return back()->with("error", "Harus Foto Selfie Terlebih Dahulu");
            // }

            DB::beginTransaction();

            $cek = EventUsers::where("user_id", Auth::user()->id)->first();

            if (empty($cek)) {
                return back()->with("error", "Data Anda Tidak Ditemukan");
            }

            $guest = Guest::where("event_id", $cek->event_id)
                ->where("id", "=", $request["guest_id"], "and")
                ->first(['*']);

            if (!$guest) {
                DB::rollBack();
                return back()->with("error", "Tamu tidak ditemukan");
            }

            $sudahCheckin = GuestCheckin::where("event_id", $cek->event_id)
                ->where('guest_id', '=', $guest->id, 'and')
                ->exists();

            if ($sudahCheckin) {
                DB::rollBack();
                return back()->with("error", "Nama Tamu " . $guest['nama_tamu'] . ' Sudah Masuk ke Dalam Acara');
            }

            if ($request->selfie) {
                $fileName = ImageHelper::uploadBase64ToS3($request->selfie);
            } else {
                $fileName = NULL;
            }

            ActivityLogger::setContext('Input Attendance', 'checkin_manual', [
                'guest_id' => $guest->id,
                'selfie_used' => (bool) $request->selfie,
            ]);
            GuestCheckin::create([
                "event_id" => $cek->event_id,
                "guest_id" => $guest["id"],
                "metode" => "manual",
                "waktu_checkin" => now(),
                "users_id" => Auth::user()->id,
                "selfie_path" => $fileName,
            ]);

            ActivityLogger::setContext('Tamu Undangan', 'ubah_kehadiran', [
                'guest_id' => $guest->id,
                'metode' => 'manual',
            ]);
            $guest->update([
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
        $eventId = $this->getActiveEventId();

        $guest = Guest::where("event_id", $eventId)->with('kategori')->findOrFail($id);

        return response()->json([
            'nama' => $guest->nama_tamu,
            'nama_undangan' => $guest->nama_undangan,
            'relasi' => $guest->relasi,
            'jenis_undangan' => $guest->jenis_undangan,
            'keterangan' => $guest->keterangan,
            'kategori' => empty($guest->kategori) ? "-" : $guest->kategori->nama_kategori
        ]);
    }

    public function search_guest(Request $request)
    {
        $q = $request->q;

        $cek = EventUsers::where("user_id", Auth::user()->id)->first();

        $data = Guest::where("event_id", $cek->event_id)
            ->where(function ($query) use ($q) {
                $query->where('nama_tamu', 'like', '%' . $q . '%')
                    ->orWhere('nama_undangan', 'like', '%' . $q . '%');
            })
            ->where('status_kehadiran', '0')
            ->get(['*']);

        $result = $data->map(function ($item) {

            return [
                'id' => $item->id,
                'nama_tamu' => $item->nama_tamu,
                'relasi' => $item->relasi,
                'keterangan' => $item->keterangan,
                'nama_undangan' => $item->nama_undangan
            ];
        });

        return response()->json($result);
    }
}
