<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guard;
class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            "guards"=> Guard::where("employment_status","active")->count(),
        ];
        return view('admin.dashboard',compact('stats'));
    }
    public function guards()
    {
        $guards = Guard::paginate(5);
        return view('admin.guards.index',compact('guards'));
    }
}
