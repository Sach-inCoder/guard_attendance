<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\GuardSiteAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\AttendanceAuditLog;
use App\Models\Guard;
// use App\Models\GuardSiteAssignment;
// use App\Models\QrCode;
use Carbon\Carbon;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GuardAttendanceController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'id' => ['required', 'uuid'],
            'token' => ['required', 'string', 'max:200'],
        ]);

        $qr = QrCode::where('public_id', $request->query('id'))
            ->where('is_active', true)
            ->whereNull('revoked_at')
            ->first();

        if (
            !$qr ||
            !hash_equals(
                (string) $qr->token_hash,
                hash('sha256', $request->query('token'))
            )
        ) {
            abort(403, 'Invalid or expired QR code.');
        }

        $user = Auth::user();

        // Only users with a guard profile can scan attendance QR codes.
        if (strtolower($user->role?->name ?? '') !== 'guard') {
            abort(403, 'Only guards can scan attendance QR codes.');
        }

        $guard = $user->guardProfile;

        if (!$guard) {
            abort(403, 'Guard profile not found.');
        }

        $today = now()->toDateString();

        $assignment = GuardSiteAssignment::with(['site', 'shift'])
            ->where('guard_id', $guard->id)
            ->where('site_id', $qr->site_id)
            ->where('status', 'active')
            ->whereDate('starts_on', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->whereNull('ends_on')
                    ->orWhereDate('ends_on', '>=', $today);
            })
            ->first();

        if (!$assignment) {
            abort(403, 'You are not assigned to this site today.');
        }

        return view('guard.attendance.scan', [
            'qr' => $qr,
            'site' => $assignment->site,
            'shift' => $assignment->shift,
            'token' => $request->query('token'),
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'uuid'],
            'token' => ['required', 'string', 'max:200'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);

        // 1. Verify QR code
        $qr = QrCode::where('public_id', $validated['id'])
            ->where('is_active', true)
            ->whereNull('revoked_at')
            ->first();

        if (
            !$qr ||
            !hash_equals(
                (string) $qr->token_hash,
                hash('sha256', $validated['token'])
            )
        ) {
            abort(403, 'Invalid or expired QR code.');
        }

        // 2. Verify guard account
        $user = Auth::user();

        if (strtolower($user->role?->name ?? '') !== 'guard') {
            abort(403, 'Only guards can mark attendance.');
        }

        $guard = $user->guardProfile;

        if (!$guard) {
            abort(403, 'Guard profile not found.');
        }

        $now = now();
        $today = $now->toDateString();
        $yesterday = $now->copy()->subDay()->toDateString();

        // 3. Find active assignments for this guard and site
        $assignments = GuardSiteAssignment::with(['site', 'shift'])
            ->where('guard_id', $guard->id)
            ->where('site_id', $qr->site_id)
            ->where('status', 'active')
            ->whereDate('starts_on', '<=', $today)
            ->where(function ($query) use ($yesterday) {
                $query->whereNull('ends_on')
                    ->orWhereDate('ends_on', '>=', $yesterday);
            })
            ->get();

        $assignment = null;
        $attendanceDate = null;
        $scheduledStart = null;
        $scheduledEnd = null;

        $openAttendance = Attendance::with('assignment.shift')
            ->where('guard_id', $guard->id)
            ->where('site_id', $qr->site_id)
            ->whereNull('check_out_at')
            ->whereNotNull('check_in_at')
            ->whereIn('attendance_date', [$today, $yesterday])
            ->whereIn('guard_site_assignment_id', $assignments->pluck('id'))
            ->orderByDesc('check_in_at')
            ->first();
        if ($openAttendance) {
            // Existing attendance means this scan is for checkout.
            $assignment = $assignments->firstWhere(
                'id',
                $openAttendance->guard_site_assignment_id
            );

            $attendanceDate = $openAttendance->attendance_date->toDateString();

            $shift = $assignment->shift;

            $startTime = $assignment->scheduled_start_time
                ?? $shift->start_time;

            $endTime = $assignment->scheduled_end_time
                ?? $shift->end_time;

            $scheduledStart = Carbon::parse(
                $attendanceDate . ' ' . $startTime
            );

            $scheduledEnd = Carbon::parse(
                $attendanceDate . ' ' . $endTime
            );

            if ($shift->crosses_midnight) {
                $scheduledEnd->addDay();
            }
        } else {
            // No open attendance: resolve a new check-in.
            foreach ($assignments as $item) {

                $shift = $item->shift;

                if (!$shift || !$item->site) {
                    continue;
                }

                $startTime = $item->scheduled_start_time
                    ?? $shift->start_time;

                $endTime = $item->scheduled_end_time
                    ?? $shift->end_time;

                $crossesMidnight = (bool) $shift->crosses_midnight;

                $businessDate = (
                    $crossesMidnight &&
                    $now->format('H:i:s') <= $endTime
                ) ? $yesterday : $today;

                if (
                    $item->starts_on->toDateString() > $businessDate ||
                    (
                        $item->ends_on &&
                        $item->ends_on->toDateString() < $businessDate
                    )
                ) {
                    continue;
                }

                $shiftStart = Carbon::parse(
                    $businessDate . ' ' . $startTime
                );

                $shiftEnd = Carbon::parse(
                    $businessDate . ' ' . $endTime
                );

                if ($crossesMidnight) {
                    $shiftEnd->addDay();
                }

                $earliestCheckIn = $shiftStart->copy()->subMinutes(30);

                if ($now->lt($earliestCheckIn) || $now->gte($shiftEnd)) {
                    continue;
                }

                $assignment = $item;
                $attendanceDate = $businessDate;
                $scheduledStart = $shiftStart;
                $scheduledEnd = $shiftEnd;

                break;
            }
        }
        if (!$assignment) {
            throw ValidationException::withMessages([
                'id' => 'No active assignment or valid shift found for this site at the current time.',
            ]);
        }

        // 5. Verify site coordinates
        $site = $assignment->site;

        if ($site->latitude === null || $site->longitude === null) {
            throw ValidationException::withMessages([
                'id' => 'Site GPS coordinates have not been configured.',
            ]);
        }

        // 6. Verify GPS accuracy
        if ($validated['accuracy'] > 25) {
            throw ValidationException::withMessages([
                'latitude' => 'GPS accuracy is too low. Please try again in an open area.',
            ]);
        }

        // Calculate distance using Haversine formula.
        $earthRadius = 6371000;

        $lat1 = deg2rad((float) $site->latitude);
        $lat2 = deg2rad((float) $validated['latitude']);

        $latDifference = deg2rad(
            (float) $validated['latitude'] - (float) $site->latitude
        );

        $lonDifference = deg2rad(
            (float) $validated['longitude'] - (float) $site->longitude
        );

        $a = sin($latDifference / 2) ** 2
            + cos($lat1) * cos($lat2)
            * sin($lonDifference / 2) ** 2;

        $distance = 2 * $earthRadius * asin(
            min(1, sqrt($a))
        );

        if ($distance > 25) {
            throw ValidationException::withMessages([
                'latitude' => 'You are outside the allowed 25-metre site radius. Current distance: '
                    . round($distance) . ' metres.',
            ]);
        }

        // 7. Save check-in or check-out
        $result = DB::transaction(function () use ($assignment, $guard, $user, $validated, $attendanceDate, $scheduledStart, $now) {

            // Lock assignment to avoid concurrent duplicate scans.
            $lockedAssignment = GuardSiteAssignment::whereKey($assignment->id)
                ->lockForUpdate()
                ->firstOrFail();

            $attendance = Attendance::where(
                'guard_id',
                $guard->id
            )
                ->where(
                    'guard_site_assignment_id',
                    $lockedAssignment->id
                )
                ->whereDate('attendance_date', $attendanceDate)
                ->lockForUpdate()
                ->first();

            $latitude = $validated['latitude'];
            $longitude = $validated['longitude'];
            $accuracy = $validated['accuracy'];

            // First scan: CHECK IN
            if (!$attendance) {

                $lateAfter = $scheduledStart->copy()->addMinutes(
                    $assignment->shift->grace_period_minutes ?? 0
                );

                $status = $now->gt($lateAfter)
                    ? 'late'
                    : 'present';

                $attendance = Attendance::create([
                    'guard_id' => $guard->id,
                    'guard_site_assignment_id' => $lockedAssignment->id,
                    'site_id' => $lockedAssignment->site_id,
                    'attendance_date' => $attendanceDate,
                    'check_in_at' => $now,
                    'check_in_latitude' => $latitude,
                    'check_in_longitude' => $longitude,
                    'check_in_accuracy_m' => $accuracy,
                    'status' => $status,
                    'check_in_method' => 'qr_gps',
                    'created_by' => $user->id,
                ]);

                $this->writeAttendanceAudit(
                    $attendance,
                    $guard->id,
                    $user->id,
                    'check_in',
                    null,
                    [
                        'check_in_at' => $now->toDateTimeString(),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'accuracy_m' => $accuracy,
                        'status' => $status,
                    ],
                    $request ?? null
                );

                return 'Check-in successful!';
            }

            // Prevent duplicate check-out
            if ($attendance->check_out_at) {
                throw ValidationException::withMessages([
                    'id' => 'You have already checked out for this shift.',
                ]);
            }
            $workedMinutes = Carbon::parse(
                $attendance->check_in_at
            )->diffInMinutes($now);

            $attendance->update([
                'check_out_at' => $now,
                'check_out_latitude' => $latitude,
                'check_out_longitude' => $longitude,
                'check_out_accuracy_m' => $accuracy,
                'worked_minutes' => $workedMinutes,
                'check_out_method' => 'qr_gps',
                'updated_by' => $user->id,
            ]);

            // Prevent checkout before check-in
            if ($now->lte($attendance->check_in_at)) {
                throw ValidationException::withMessages([
                    'id' => 'Invalid checkout time.',
                ]);
            }

            $oldValues = [
                'check_out_at' => $attendance->check_out_at,
                'worked_minutes' => $attendance->worked_minutes,
            ];

            $workedMinutes = Carbon::parse(
                $attendance->check_in_at
            )->diffInMinutes($now);

            $attendance->update([
                'check_out_at' => $now,
                'check_out_latitude' => $latitude,
                'check_out_longitude' => $longitude,
                'check_out_accuracy_m' => $accuracy,
                'worked_minutes' => $workedMinutes,
                'check_out_method' => 'qr_gps',
                'updated_by' => $user->id,
            ]);

            $this->writeAttendanceAudit(
                $attendance,
                $guard->id,
                $user->id,
                'check_out',
                $oldValues,
                [
                    'check_out_at' => $now->toDateTimeString(),
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'accuracy_m' => $accuracy,
                    'worked_minutes' => $workedMinutes,
                ],
                request()
            );

            return 'Check-out successful!';
        });

        return redirect()
            ->route('guard.dashboard')
            ->with('success', $result);
    }

    private function writeAttendanceAudit(
        $attendance,
        $guardId,
        $actorUserId,
        $action,
        $oldValues,
        $newValues,
        Request $request
    ) {
        DB::table('attendance_audit_logs')->insert([
            'attendance_id' => $attendance->id,
            'guard_id' => $guardId,
            'actor_user_id' => $actorUserId,
            'action' => $action,
            'old_values' => $oldValues
                ? json_encode($oldValues)
                : null,
            'new_values' => $newValues
                ? json_encode($newValues)
                : null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
    }

}