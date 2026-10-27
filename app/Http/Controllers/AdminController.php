<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guard;
use App\Models\Company;
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
        $guards = Guard::paginate(10);
        return view('admin.guards.index',compact('guards'));
    }
    public function companies()
    {
        $companies = Company::paginate(10);
        return view('admin.companies.index',compact('companies'));
    }
}
