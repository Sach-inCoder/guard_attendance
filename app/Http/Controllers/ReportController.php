<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{

    // public function attendance(Request $request)
    // {
    //     $currentYear = now()->year;

    //     $validated = $request->validate([
    //         'company_id' => ['nullable', 'integer', 'exists:companies,id'],
    //         'site_id' => ['nullable', 'integer', 'exists:sites,id'],
    //         'year' => ['nullable', 'integer', 'min:2020', 'max:' . ($currentYear + 1)],
    //         'month' => ['nullable', 'integer', 'between:1,12'],
    //     ]);

    //     $year = (int) ($validated['year'] ?? $currentYear);
    //     $month = $validated['month'] ?? null;

    //     // Date range: selected month or complete year
    //     if ($month) {
    //         $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
    //         $endDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
    //     } else {
    //         $startDate = "$year-01-01";
    //         $endDate = "$year-12-31";
    //     }

    //     $years = range($currentYear, $currentYear - 5);

    //     $companies = Company::where('is_active', true)
    //         ->orderBy('name')
    //         ->get();

    //     $sites = Site::where('is_active', true)
    //         ->when($request->filled('company_id'), function ($query) use ($request) {
    //             $query->where('company_id', $request->company_id);
    //         })
    //         ->orderBy('name')
    //         ->get();

    //     $query = DB::table('attendances as a')
    //         ->join('guards as g', 'g.id', '=', 'a.guard_id')
    //         ->join('sites as s', 's.id', '=', 'a.site_id')
    //         ->join('companies as c', 'c.id', '=', 's.company_id')
    //         ->leftJoin('users as u', 'u.id', '=', 'g.user_id')
    //         ->whereBetween('a.attendance_date', [$startDate, $endDate])
    //         ->select(
    //             'a.*',
    //             'g.employee_id',
    //             'g.full_name',
    //             'u.name as user_name',
    //             's.name as site_name',
    //             'c.name as company_name'
    //         );

    //     if ($request->filled('company_id')) {
    //         $query->where('s.company_id', $request->company_id);
    //     }

    //     if ($request->filled('site_id')) {
    //         $query->where('a.site_id', $request->site_id);
    //     }

    //     $records = $query
    //         ->orderByDesc('a.attendance_date')
    //         ->orderBy('g.employee_id')
    //         ->paginate(20)
    //         ->withQueryString();

    //     $summaryQuery = DB::table('attendances as a')
    //         ->join('sites as s', 's.id', '=', 'a.site_id')
    //         ->whereBetween('a.attendance_date', [$startDate, $endDate]);

    //     if ($request->filled('company_id')) {
    //         $summaryQuery->where('s.company_id', $request->company_id);
    //     }

    //     if ($request->filled('site_id')) {
    //         $summaryQuery->where('a.site_id', $request->site_id);
    //     }

    //     $summary = [
    //         'total' => (clone $summaryQuery)->count(),
    //         'present' => (clone $summaryQuery)->where('a.status', 'present')->count(),
    //         'late' => (clone $summaryQuery)->where('a.status', 'late')->count(),
    //         'half_day' => (clone $summaryQuery)->where('a.status', 'half_day')->count(),
    //         'absent' => (clone $summaryQuery)->where('a.status', 'absent')->count(),
    //         'weekly_off' => (clone $summaryQuery)->where('a.status', 'weekly_off')->count(),
    //         'holiday' => (clone $summaryQuery)->where('a.status', 'holiday')->count(),
    //     ];

    //     return view('admin.reports.attendance', compact(
    //         'companies',
    //         'sites',
    //         'records',
    //         'summary',
    //         'years',
    //         'year',
    //         'month'
    //     ));
    // }

    public function attendanceRegister(Request $request)
    {
        $companies = Company::where('is_active', true)
            ->orderBy('name')
            ->get();

        $sites = Site::where('is_active', true)
            ->orderBy('name')
            ->get();

        $companyId = $request->input('company_id');
        $siteId = $request->input('site_id');
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $register = collect();
        $daysInMonth = 0;

        if ($siteId) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            $daysInMonth = $startDate->daysInMonth;

            $guards = DB::table('guard_site_assignments')
                ->join('guards', 'guards.id', '=', 'guard_site_assignments.guard_id')
                ->leftJoin('users', 'users.id', '=', 'guards.user_id')
                ->where('guard_site_assignments.site_id', $siteId)
                ->whereIn('guard_site_assignments.status', ['active', 'completed'])
                ->whereDate('guard_site_assignments.starts_on', '<=', $endDate)
                ->where(function ($query) use ($startDate) {
                    $query->whereNull('guard_site_assignments.ends_on')
                        ->orWhereDate('guard_site_assignments.ends_on', '>=', $startDate);
                })
                ->select(
                    'guards.id',
                    'guards.employee_id',
                    DB::raw('COALESCE(guards.full_name, users.name) as guard_name')
                )
                ->distinct()
                ->orderBy('guards.employee_id')
                ->get();

            $attendance = DB::table('attendances')
                ->where('site_id', $siteId)
                ->whereBetween('attendance_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString()
                ])
                ->get()
                ->keyBy(fn($item) => $item->guard_id . '_' . $item->attendance_date);

            foreach ($guards as $guard) {
                $days = [];
                $totalDuty = 0;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = $startDate->copy()->day($day)->toDateString();

                    $assigned = DB::table('guard_site_assignments')
                        ->where('guard_id', $guard->id)
                        ->where('site_id', $siteId)
                        ->whereIn('status', ['active', 'completed'])
                        ->whereDate('starts_on', '<=', $date)
                        ->where(function ($query) use ($date) {
                            $query->whereNull('ends_on')
                                ->orWhereDate('ends_on', '>=', $date);
                        })
                        ->exists();

                    if (!$assigned) {
                        $days[$day] = '';
                        continue;
                    }

                    $record = $attendance->get($guard->id . '_' . $date);

                    $status = $record->status ?? 'absent';

                    $code = match ($status) {
                        'present' => 'P',
                        'late' => 'L',
                        'half_day' => 'HD',
                        'weekly_off' => 'WO',
                        'holiday' => 'H',
                        default => 'A',
                    };

                    $days[$day] = $code;

                    if (in_array($code, ['P', 'L'])) {
                        $totalDuty += 1;
                    } elseif ($code === 'HD') {
                        $totalDuty += 0.5;
                    }
                }

                $register->push([
                    'employee_id' => $guard->employee_id,
                    'name' => $guard->guard_name,
                    'days' => $days,
                    'total_duty' => $totalDuty,
                ]);
            }
        }

        $company = $companyId ? Company::find($companyId) : null;
        $site = $siteId ? Site::find($siteId) : null;

        return view('admin.reports.attendance-register', compact(
            'companies',
            'sites',
            'company',
            'site',
            'month',
            'year',
            'daysInMonth',
            'register'
        ));
    }

}
