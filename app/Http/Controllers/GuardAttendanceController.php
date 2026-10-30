<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\GuardSiteAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}