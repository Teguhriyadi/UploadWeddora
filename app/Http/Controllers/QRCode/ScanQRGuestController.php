<?php

namespace App\Http\Controllers\QRCode;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
use App\Models\GuestCheckin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $guest = Guest::where('kode_token', '=', $kodeToken, 'and')->first();

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
            ]
        ]);
    }

    public function poster(string $kode_token)
    {
        $guest = Guest::where('kode_token', '=', $kode_token, 'and')->first();

        if (!$guest) {
            return redirect()->to("/modules/error-page");
        }

        $event = Event::first(['*']);

        $eventName = $event?->nama_event ?: 'WEDDORA';
        $eventDate = $event?->tanggal
            ? Carbon::parse($event->tanggal)->locale('id')->translatedFormat('l, d F Y')
            : null;

        return view('qr-poster', [
            'kode_token' => $kode_token,
            'guest' => $guest,
            'event_name' => $eventName,
            'event_date' => $eventDate,
            'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($kode_token),
        ]);
    }

    public function store(Request $request)
    {
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

        $guest = Guest::where('kode_token', '=', $kodeToken, 'and')->first();

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

        if ($request->selfie) {
            $fileName = ImageHelper::uploadBase64ToS3($request->selfie);
        } else {
            $fileName = null;
        }

        GuestCheckin::create([
            'guest_id' => $guest->id,
            'metode' => 'qr',
            'waktu_checkin' => now(),
            'users_id' => Auth::user()->id,
            "selfie_path" => $fileName,
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
