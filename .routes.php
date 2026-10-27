
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\GuardSiteAssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AttendanceAuditLogController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Guards
    |--------------------------------------------------------------------------
    */

    Route::get('/guards/import', [GuardController::class, 'importForm'])
        ->middleware('permission:guards.import')
        ->name('guards.import.form');

    Route::post('/guards/import', [GuardController::class, 'import'])
        ->middleware('permission:guards.import')
        ->name('guards.import');

    Route::get('/guards/export', [GuardController::class, 'export'])
        ->middleware('permission:guards.export')
        ->name('guards.export');

    Route::patch('/guards/{guard}/status', [
        GuardController::class, 'updateStatus'
    ])->middleware('permission:guards.edit')
        ->name('guards.status');

    Route::resource('guards', GuardController::class)
        ->middleware([
            'index' => 'permission:guards.view',
            'create' => 'permission:guards.create',
            'store' => 'permission:guards.create',
            'show' => 'permission:guards.view',
            'edit' => 'permission:guards.edit',
            'update' => 'permission:guards.edit',
            'destroy' => 'permission:guards.delete',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Companies
    |--------------------------------------------------------------------------
    */

    Route::resource('companies', CompanyController::class)
        ->middleware([
            'index' => 'permission:companies.view',
            'create' => 'permission:companies.create',
            'store' => 'permission:companies.create',
            'show' => 'permission:companies.view',
            'edit' => 'permission:companies.edit',
            'update' => 'permission:companies.edit',
            'destroy' => 'permission:companies.delete',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Sites
    |--------------------------------------------------------------------------
    */

    Route::get('/companies/{company}/sites', [
        SiteController::class, 'byCompany'
    ])->middleware('permission:sites.view')
        ->name('companies.sites');

    Route::resource('sites', SiteController::class)
        ->middleware([
            'index' => 'permission:sites.view',
            'create' => 'permission:sites.create',
            'store' => 'permission:sites.create',
            'show' => 'permission:sites.view',
            'edit' => 'permission:sites.edit',
            'update' => 'permission:sites.edit',
            'destroy' => 'permission:sites.delete',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Shifts
    |--------------------------------------------------------------------------
    */

    Route::resource('shifts', ShiftController::class)
        ->middleware([
            'index' => 'permission:shifts.view',
            'create' => 'permission:shifts.create',
            'store' => 'permission:shifts.create',
            'show' => 'permission:shifts.view',
            'edit' => 'permission:shifts.edit',
            'update' => 'permission:shifts.edit',
            'destroy' => 'permission:shifts.delete',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Guard Assignments
    |--------------------------------------------------------------------------
    */

    Route::post('/assignments/{assignment}/end', [
        GuardSiteAssignmentController::class, 'endAssignment'
    ])->middleware('permission:assignments.edit')
        ->name('assignments.end');

    Route::resource('assignments', GuardSiteAssignmentController::class)
        ->middleware([
            'index' => 'permission:assignments.view',
            'create' => 'permission:assignments.create',
            'store' => 'permission:assignments.create',
            'show' => 'permission:assignments.view',
            'edit' => 'permission:assignments.edit',
            'update' => 'permission:assignments.edit',
            'destroy' => 'permission:assignments.delete',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Attendance Management
    |--------------------------------------------------------------------------
    */

    Route::get('/attendance/export', [
        AttendanceController::class, 'export'
    ])->middleware('permission:attendance.export')
        ->name('attendance.export');

    Route::post('/attendance/import', [
        AttendanceController::class, 'import'
    ])->middleware('permission:attendance.import')
        ->name('attendance.import');

    Route::resource('attendance', AttendanceController::class)
        ->middleware([
            'index' => 'permission:attendance.view',
            'create' => 'permission:attendance.create',
            'store' => 'permission:attendance.create',
            'show' => 'permission:attendance.view',
            'edit' => 'permission:attendance.edit',
            'update' => 'permission:attendance.edit',
            'destroy' => 'permission:attendance.delete',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Guard Self-Service
    |--------------------------------------------------------------------------
    | These endpoints must additionally restrict records to the
    | authenticated guard's own profile and active assignment.
    */

    Route::prefix('my')->name('my.')->group(function () {
        Route::get('/attendance', [
            AttendanceController::class, 'myAttendance'
        ])->middleware('permission:my_attendance.view')
            ->name('attendance.index');

        Route::get('/attendance/history', [
            AttendanceController::class, 'history'
        ])->middleware('permission:my_attendance.view')
            ->name('attendance.history');

        Route::post('/attendance/check-in', [
            AttendanceController::class, 'checkIn'
        ])->middleware([
            'permission:attendance.check_in',
            'throttle:10,1',
        ])->name('attendance.check-in');

        Route::post('/attendance/check-out', [
            AttendanceController::class, 'checkOut'
        ])->middleware([
            'permission:attendance.check_out',
            'throttle:10,1',
        ])->name('attendance.check-out');
    });

    /*
    |--------------------------------------------------------------------------
    | QR Codes
    |--------------------------------------------------------------------------
    */

    Route::get('/sites/{site}/qr-codes', [
        QrCodeController::class, 'index'
    ])->middleware('permission:qr_codes.view')
        ->name('sites.qr-codes.index');

    Route::post('/sites/{site}/qr-codes', [
        QrCodeController::class, 'store'
    ])->middleware('permission:qr_codes.create')
        ->name('sites.qr-codes.store');

    Route::get('/qr-codes/{qrCode}', [
        QrCodeController::class, 'show'
    ])->middleware('permission:qr_codes.view')
        ->name('qr-codes.show');

    Route::patch('/qr-codes/{qrCode}/status', [
        QrCodeController::class, 'updateStatus'
    ])->middleware('permission:qr_codes.edit')
        ->name('qr-codes.status');

    Route::post('/qr-codes/{qrCode}/regenerate', [
        QrCodeController::class, 'regenerate'
    ])->middleware('permission:qr_codes.edit')
        ->name('qr-codes.regenerate');

    Route::post('/qr-codes/scan', [
        QrCodeController::class, 'scan'
    ])->middleware([
        'permission:attendance.check_in',
        'throttle:10,1',
    ])->name('qr-codes.scan');

    /*
    |--------------------------------------------------------------------------
    | Leaves
    |--------------------------------------------------------------------------
    */

    Route::get('/leaves', [LeaveController::class, 'index'])
        ->middleware('permission:leaves.view')
        ->name('leaves.index');

    Route::get('/leaves/create', [LeaveController::class, 'create'])
        ->middleware('permission:leaves.create')
        ->name('leaves.create');

    Route::post('/leaves', [LeaveController::class, 'store'])
        ->middleware('permission:leaves.create')
        ->name('leaves.store');

    Route::get('/leaves/{leave}', [LeaveController::class, 'show'])
        ->middleware('permission:leaves.view')
        ->name('leaves.show');

    Route::patch('/leaves/{leave}/approve', [
        LeaveController::class, 'approve'
    ])->middleware('permission:leaves.approve')
        ->name('leaves.approve');

    Route::patch('/leaves/{leave}/reject', [
        LeaveController::class, 'reject'
    ])->middleware('permission:leaves.approve')
        ->name('leaves.reject');

    /*
    |--------------------------------------------------------------------------
    | Holidays
    |--------------------------------------------------------------------------
    */

    Route::resource('holidays', HolidayController::class)
        ->middleware([
            'index' => 'permission:holidays.view',
            'create' => 'permission:holidays.create',
            'store' => 'permission:holidays.create',
            'show' => 'permission:holidays.view',
            'edit' => 'permission:holidays.edit',
            'update' => 'permission:holidays.edit',
            'destroy' => 'permission:holidays.delete',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('permission:reports.view')
        ->name('reports.index');

    Route::get('/reports/attendance', [
        ReportController::class, 'attendance'
    ])->middleware('permission:reports.view')
        ->name('reports.attendance');

    Route::get('/reports/monthly', [
        ReportController::class, 'monthly'
    ])->middleware('permission:reports.view')
        ->name('reports.monthly');

    Route::get('/reports/guard/{guard}', [
        ReportController::class, 'guard'
    ])->middleware('permission:reports.view')
        ->name('reports.guard');

    Route::get('/reports/print', [
        ReportController::class, 'print'
    ])->middleware('permission:reports.print')
        ->name('reports.print');

    Route::get('/reports/export', [
        ReportController::class, 'export'
    ])->middleware('permission:reports.export')
        ->name('reports.export');

    /*
    |--------------------------------------------------------------------------
    | Audit Logs
    |--------------------------------------------------------------------------
    */

    Route::get('/attendance-audit-logs', [
        AttendanceAuditLogController::class, 'index'
    ])->middleware('permission:audit_logs.view')
        ->name('attendance-audit-logs.index');

    Route::get('/attendance-audit-logs/{auditLog}', [
        AttendanceAuditLogController::class, 'show'
    ])->middleware('permission:audit_logs.view')
        ->name('attendance-audit-logs.show');
});
