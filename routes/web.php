<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\InputManual\GuestPublicController;
use App\Http\Controllers\InputManual\InputAttendanceController;
use App\Http\Controllers\LandingPage\KategoriController as LandingPageKategoriController;
use App\Http\Controllers\Master\AssignUsersController;
use App\Http\Controllers\Master\CabangController;
use App\Http\Controllers\Master\EventController;
use App\Http\Controllers\Master\GuestController;
use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\SouvenirDepositController;
use App\Http\Controllers\Master\TitipKehadiranController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\WelcomeDisplayController;
use App\Http\Controllers\QRCode\ScanQRGuestController;
use App\Http\Controllers\Report\ActivityLogController;
use App\Http\Controllers\Report\HistoryGuestController;
use Illuminate\Support\Facades\Route;

Route::get("/", function() {
    return redirect()->to("/login");
});

Route::get("/landing-page", [AppController::class, "landing_page"]);

Route::get('/qr/{kode_token}', [ScanQRGuestController::class, 'poster']);

Route::middleware(["web", "guest"])->group(function () {
    Route::prefix("login")->group(function () {
        Route::get("/", [LoginController::class, "login"]);
        Route::post("/", [LoginController::class, "post_login"]);
    });
});

Route::middleware(["web", "autentikasi"])->group(function () {
    Route::prefix('welcome-display')->group(function () {
        Route::get('/', [WelcomeDisplayController::class, 'index'])
            ->name('welcome.display');
        Route::get('/latest', [WelcomeDisplayController::class, 'latest'])
            ->name('welcome.latest');
    });
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

        Route::get('/landing-page/kategori/datatable/{id}', [LandingPageKategoriController::class, 'datatable']);
        Route::post("/landing-page/kategori/toggle-status/{id}", [LandingPageKategoriController::class, "toggleStatus"]);
        Route::prefix("landing-page")->group(function() {
            Route::resource("kategori", LandingPageKategoriController::class);
        });

        Route::post("/guest/update-status-kehadiran", [GuestController::class, "update_status_kehadiran"]);
        Route::get("/guest/generate-card/{token}", [GuestController::class, "show_generate"]);
        Route::get("/guest/download", [GuestController::class, "download"]);
        Route::get("/guest/generate-all", [GuestController::class, "generate_all"]);

        Route::post("/guest/delete-selected", [GuestController::class, "delete_selected"]);
        Route::post("/guest/update-status-undangan", [GuestController::class, "update_status_undangan"]);
        Route::post("/guest/update-kehadiran", [GuestController::class, "update_kehadiran"]);
        Route::post("/guest/upload-file", [GuestController::class, 'upload_file']);
        Route::resource("guest", GuestController::class);

        Route::post("scan-qr-guest/validate", [ScanQRGuestController::class, "validateToken"]);
        Route::resource("scan-qr-guest", ScanQRGuestController::class);

        Route::prefix("history-guest")->group(function() {
            Route::get("/", [HistoryGuestController::class, "index"]);
            Route::get("/download", [HistoryGuestController::class, "download"]);
            Route::get("/{id}", [HistoryGuestController::class, "show"]);
            Route::get("/{id}/guest-public/show-image", [HistoryGuestController::class, "show_guest_public"]);

            Route::prefix("data")->group(function() {
                Route::get("/invitation", [HistoryGuestController::class, "dataInvitation"]);
                Route::get("/public", [HistoryGuestController::class, "dataPublic"]);
            });
        });

        Route::get("/guest/info/{id}", [InputAttendanceController::class, "info_guest"]);

        Route::get("/input-attendance/search/", [InputAttendanceController::class, "search_guest"]);
        Route::resource("input-attendance", InputAttendanceController::class);

        Route::get("/guest-public/download", [GuestPublicController::class, "download"]);
        Route::resource("guest-public", GuestPublicController::class);

        Route::get("/titip-kehadiran/delete-selected", [TitipKehadiranController::class, "delete_selected"]);
        Route::resource("titip-kehadiran", TitipKehadiranController::class);
        Route::resource("titip-kado", SouvenirDepositController::class);

        Route::get("/riwayat-aktifitas", [ActivityLogController::class, "index"]);

        Route::get('/cabang/datatable/{id}', [CabangController::class, 'datatable']);
        Route::post("/cabang/toggle-status/{id}", [CabangController::class, "toggleStatus"]);
        Route::resource('cabang', CabangController::class);

        Route::resource('event', EventController::class);

        Route::resource("assign-users", AssignUsersController::class);

        Route::get("/activity-log", [ActivityLogController::class, "index"]);
        Route::get("/error-page", [AppController::class, "error_page"]);
    });

    Route::get("/logout", [LoginController::class, "logout"]);
});
