<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class AttendanceController extends Controller{
    public function myAttendance(Request $request){
        return view('guard.myAttendance');
    }

    public function checkIn(Request $request){
        return $request;
    }
    public function checkOut(Request $request){
        return $request;
    }
}