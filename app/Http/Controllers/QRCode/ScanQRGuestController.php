<?php

namespace App\Http\Controllers\QRCode;

use App\Events\GuestCheckedIn;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventUsers;
use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Support\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScanQRGuestController extends Controller
{
    public function index()
    {
        return view("modules.scan-qr-guest.index");
    }

    public function validateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid',
            ], 422);
        }

        $kodeToken = trim((string) $request->kode_token);

        if ($kodeToken === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid',
            ], 422);
        }

        $guest = Guest::where('kode_token', '=', $kodeToken, 'and')->first(['*']);

        if (!$guest) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid'
            ]);
        }

        $sudahCheckin = GuestCheckin::where('guest_id', '=', $guest->id, 'and')->exists();

        if ($sudahCheckin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode sudah digunakan'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'guest' => [
                'id' => $guest->id,
                'nama_tamu' => $guest->nama_tamu,
                'kode_token' => $guest->kode_token,
                'kategori' => empty($guest->kategori) ? null : $guest->kategori->nama_kategori
            ]
        ]);
    }

    public function poster(string $kode_token)
    {
        try {
            DB::beginTransaction();

            $cek = EventUsers::where("user_id", Auth::user()->id)->first();

            $guest = Guest::where('event_id', $cek->event_id)
                ->where('kode_token', '=', $kode_token, 'and')
                ->first(['*']);

            if (!$guest) {
                return redirect()->to("/modules/error-page");
            }

            $event = Event::where("id", $cek->event_id)->first(['*']);

            $eventName = $event?->nama_event ?: 'WEDDORA';
            $eventDate = $event?->tanggal
                ? Carbon::parse($event->tanggal)->locale('id')->translatedFormat('l, d F Y')
                : null;

            DB::commit();

            return view('qr-poster', [
                'kode_token' => $kode_token,
                'guest' => $guest,
                'event_name' => $eventName,
                'event_date' => $eventDate,
                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($kode_token),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $cek = EventUsers::where("user_id", Auth::user()->id)->first();

        $validator = Validator::make($request->all(), [
            'kode_token' => ['required', 'string'],
            'selfie' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid',
            ], 422);
        }

        $kodeToken = trim((string) $request->kode_token);

        if ($kodeToken === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid',
            ], 422);
        }

        $guest = Guest::where("event_id", $cek->event_id)->where('kode_token', '=', $kodeToken, 'and')->first(['*']);

        if (!$guest) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid'
            ]);
        }

        $sudahCheckin = GuestCheckin::where("event_id", $cek->event_id)->where('guest_id', '=', $guest->id, 'and')->exists();

        if ($sudahCheckin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode sudah digunakan'
            ]);
        }

        if ($request->selfie) {
            $fileName = ImageHelper::uploadBase64ToS3($request->selfie);
        } else {
            $fileName = null;
        }

        ActivityLogger::setContext('Scan QR Guest', 'checkin_qr', [
            'event_id' => $cek->event_id,
            'guest_id' => $guest->id,
            'kode_token' => $guest->kode_token,
            'selfie_used' => (bool) $request->selfie,
        ]);
        GuestCheckin::create([
            'event_id' => $cek->event_id,
            'guest_id' => $guest->id,
            'metode' => 'qr',
            'waktu_checkin' => now(),
            'users_id' => Auth::user()->id,
            "selfie_path" => $fileName,
        ]);

        ActivityLogger::setContext('Tamu Undangan', 'ubah_kehadiran', [
            'guest_id' => $guest->id,
            'metode' => 'qr',
        ]);
        $guest->update([
            "status_kehadiran" => 1
        ]);

        return response()->json([
            'status' => 'success',
            'nama' => $guest->nama_tamu
        ]);
    }
}
