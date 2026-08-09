<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\APITemaResource;
use App\Models\LPKategori;
use App\Models\LPTema;
use Illuminate\Support\Facades\DB;

class TemaController extends Controller
{
    public function index($kategori_id)
    {
        try {
            $cek = LPKategori::find($kategori_id);

            if (!$cek) {
                return response()->json([
                    'message' => 'Data Kategori Tidak Ditemukan',
                    'data' => null
                ], 404);
            }

            $tema = LPTema::with("kategori:id,nama_kategori")->where('lp_kategori_id', $kategori_id)->get();

            return response()->json([
                'message' => 'Data Berhasil Ditemukan',
                'data' => [
                    'tema' => APITemaResource::collection($tema)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
