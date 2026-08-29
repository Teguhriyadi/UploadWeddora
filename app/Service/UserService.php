<?php

namespace App\Service;

use Illuminate\Support\Facades\Auth;

class UserService
{
    public static function isNotAdmin(): bool
    {
        return Auth::check() && optional(Auth::user()->role)->nama_role != "Administrator";
    }
}