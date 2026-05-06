<?php

namespace App\Http\Controllers\QRCode;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanQRGuestController extends Controller
{
    public function index()
    {
        return view("modules.scan-qr-guest.index");
    }

    public function store(Request $request)
    {
        $guest = Guest::where('kode_token', $request->kode_token)->first();

        if (!$guest) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid'
            ]);
        }

        $sudahCheckin = GuestCheckin::where('guest_id', $guest->id)->exists();

        if ($sudahCheckin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode sudah digunakan'
            ]);
        }

        $fileName = ImageHelper::uploadBase64ToS3($request->selfie);

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
