<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login()
    {
        return view("authentication.v_login");
    }

    public function post_login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        $cek = User::where("username", "=", $request->username, "and")->first();

        if (!$cek) {
            return back()->with("error", "Akun Tidak Ditemukan")->withInput();
        }

        if ($cek->is_active != "1") {
            return back()->with("error", "Akun Tidak Aktif")->withInput();
        }

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            ActivityLogger::log('Auth', 'login', Auth::user(), null, [
                'username' => $request->username,
            ]);

            return redirect()
                ->intended('/modules/dashboard')
                ->with('success', 'Anda Berhasil Login');
        }
    }

    public function logout()
    {
        try {

            DB::beginTransaction();

            $user = Auth::user();
            if ($user) {
                ActivityLogger::log('Auth', 'logout', $user);
            }

            Auth::logout();

            DB::commit();

            return redirect()->to("/login")->with("success", "Anda Berhasil Logout");

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
