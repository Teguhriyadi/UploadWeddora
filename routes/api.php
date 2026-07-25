<?php

use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\TemaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix("v1")->group(function() {
    Route::get("/kategori", [KategoriController::class, "index"]);
    Route::get("/tema/{kategori_id}", [TemaController::class, "index"]);
});
