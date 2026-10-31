<?php

use App\Http\Controllers\GuardSiteAssignmentController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\GuardAttendanceController;
use App\Http\Controllers\GuardController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout',[LoginController::class,'logout'])->name('logout');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('/admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class,'dashboard',])->name('dashboard');
        Route::resource('/guards', GuardController::class)->except(['show']);
        Route::resource('/companies', CompanyController::class)->except(['show']);
        Route::resource('/sites', SiteController::class)->except(['show']);
        Route::get('/reports/attendance',[ReportController::class,'attendance'])->name('reports.attendance');
        Route::get('/reports/attendance-register',[ReportController::class, 'attendanceRegister'])->name('reports.attendance-register');
        Route::resource('/qrcodes', QrCodeController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::get('/qrcodes/{qrcode}/image', [
            QrCodeController::class,
            'image',
        ])->name('qrcodes.image');

        Route::get('/assignments/create', [
            GuardSiteAssignmentController::class,
            'create',
        ])->name('assignments.create');
        Route::post('/assignments', [
            GuardSiteAssignmentController::class,
            'store',
        ])->name('assignments.store');
    });
});
// Route::middleware(['auth', 'role:accounts'])->group(function () {
//     Route::get('/accounts/dashboard', [AccountsController::class,'dashboard',])->name('accounts.dashboard');
// });
Route::middleware(['auth', 'role:guard'])->group(function () {
    Route::get('/guard/dashboard', [GuardController::class,'dashboard',])->name('guard.dashboard');
    Route::get('/qr/scan', [GuardAttendanceController::class, 'scan'])
        ->name('guard.qr.scan');
    Route::post('/qr/attendance', [GuardAttendanceController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('guard.qr.attendance');
    Route::prefix('/guard/my')
    ->name('my.')
    ->group(function (){
        Route::get('/attendance',[AttendanceController::class,'myAttendance'])->name('attendance');
        Route::post('/attendance/check-in',[AttendanceController::class,'checkIn'])
        ->middleware('throttle:10,1')
        ->name('attendance.check-in');
        Route::post('/attendance/check-out',[AttendanceController::class,'checkOut'])
        ->middleware('throttle:10,1')
        ->name('attendance.check-out');
    });
});
