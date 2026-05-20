<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\InputManual\GuestPublicController;
use App\Http\Controllers\InputManual\InputAttendanceController;
use App\Http\Controllers\Master\GuestController;
use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\QRCode\ScanQRGuestController;
use App\Http\Controllers\Report\HistoryGuestController;
use Illuminate\Support\Facades\Route;

Route::get("/", function() {
    return redirect()->to("/login");
});

Route::get('/qr/{kode_token}', [ScanQRGuestController::class, 'poster']);

Route::middleware(["web", "guest"])->group(function () {
    Route::prefix("login")->group(function () {
        Route::get("/", [LoginController::class, "login"]);
        Route::post("/", [LoginController::class, "post_login"]);
    });
});

Route::middleware(["web", "autentikasi"])->group(function () {
    Route::prefix("modules")->group(function () {
        Route::get("/dashboard", [AppController::class, "dashboard"]);

        Route::get('/role/datatable/{id}', [RoleController::class, 'datatable']);
        Route::resource("role", RoleController::class);

        Route::post("/users/toggle-status/{id}", [UserController::class, "toggleStatus"]);
        Route::resource("users", UserController::class);

        Route::get("/kategori/{id}/change-status", [KategoriController::class, "change_status"]);

        Route::get('/kategori/datatable/{id}', [KategoriController::class, 'datatable']);
        Route::post("/kategori/toggle-status/{id}", [KategoriController::class, "toggleStatus"]);
        Route::resource("kategori", KategoriController::class);

        Route::get("/guest/download", [GuestController::class, "download"]);

        Route::post("/guest/upload-file", [GuestController::class, 'upload_file']);
        Route::resource("guest", GuestController::class);

        Route::resource("scan-qr-guest", ScanQRGuestController::class);

        Route::prefix("history-guest")->group(function() {
            Route::get("/", [HistoryGuestController::class, "index"]);
            Route::get("/download", [HistoryGuestController::class, "download"]);
            Route::get("/{id}", [HistoryGuestController::class, "show"]);
            Route::get("/{id}/guest-public", [HistoryGuestController::class, "show_guest_public"]);
        });

        Route::get("/guest/info/{id}", [InputAttendanceController::class, "info_guest"]);

        Route::get("/input-attendance/search/", [InputAttendanceController::class, "search_guest"]);
        Route::resource("input-attendance", InputAttendanceController::class);

        Route::resource("guest-public", GuestPublicController::class);
    });

    Route::get("/logout", [LoginController::class, "logout"]);
});
