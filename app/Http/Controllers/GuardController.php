<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuardController extends Controller
{
    public function dashboard()
    {
        return view('guard.dashboard');
    }
}
