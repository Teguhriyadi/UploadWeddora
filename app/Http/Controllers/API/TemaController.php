<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LPKategori;
use App\Models\LPTema;
use Illuminate\Support\Facades\DB;

class TemaController extends Controller
{
    public function index($kategori_id)
    {
        try {
            DB::beginTransaction();

            $cek = LPKategori::where("id", $kategori_id)->first();

            if (empty($cek)) {
                return response()->json([
                    "message" => "Data Kategori Tidak Ditemukan",
                    "data" => null
                ]);
            }

            $data["tema"] = LPTema::where("lp_kategori_id", $kategori_id)->first();

            DB::commit();

            return response()->json([
                "message" => "Data Berhasil di Temukan",
                "data" => $data
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                "message" => $e->getMessage(),
                "data" => null
            ]);
        }
    }
}
