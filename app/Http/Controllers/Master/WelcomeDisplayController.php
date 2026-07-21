<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

class WelcomeDisplayController extends Controller
{
    public function welcome()
    {
        return view("modules.welcome-display");
    }
}
