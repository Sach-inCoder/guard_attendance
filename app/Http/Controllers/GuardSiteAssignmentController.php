<?php

namespace App\Http\Controllers;

use App\Models\Guard;
use App\Models\Site;
use App\Models\Shift;
use App\Models\GuardSiteAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class GuardSiteAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guards = Guard::with('user')
            ->where('employment_status', 1)
            ->orderBy('employee_id')
            ->get();

        $sites = Site::where('is_active', true)
            ->orderBy('name')
            ->get();

        $shifts = Shift::all();

        return view('admin.assignments.create', compact(
            'guards',
            'sites',
            'shifts'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guard_id' => ['required', 'integer', 'exists:guards,id'],
            'site_id' => ['required', 'integer', 'exists:sites,id'],
            'shift_id' => ['required', 'integer', 'exists:shifts,id'],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'scheduled_start_time' => ['nullable', 'date_format:H:i'],
            'scheduled_end_time' => ['nullable', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        // Ensure selected guard is active.
        $guard = Guard::whereKey($validated['guard_id'])
            ->where('employment_status', '1')
            ->first();

        if (!$guard) {
            throw ValidationException::withMessages([
                'guard_id' => 'Please select an active guard.',
            ]);
        }

        // Ensure selected site and shift are active.
        $site = Site::whereKey($validated['site_id'])
            ->where('is_active', 1)
            ->first();

        if (!$site) {
            throw ValidationException::withMessages([
                'site_id' => 'Please select an active site.',
            ]);
        }

        $shift = Shift::whereKey($validated['shift_id'])
            ->where('is_active', 1)
            ->first();

        if (!$shift) {
            throw ValidationException::withMessages([
                'shift_id' => 'Please select an active shift.',
            ]);
        }

        $startDate = $validated['starts_on'];
        $endDate = $validated['ends_on'] ?? null;

        // Reject overlapping active assignments for the same guard.
        $overlapExists = GuardSiteAssignment::where(
            'guard_id',
            $validated['guard_id']
        )
            ->where('status', 'active')
            ->whereDate('starts_on', '<=', $endDate ?? '9999-12-31')
            ->where(function ($query) use ($startDate) {
                $query->whereNull('ends_on')
                    ->orWhereDate('ends_on', '>=', $startDate);
            })
            ->exists();

        if ($overlapExists) {
            throw ValidationException::withMessages([
                'starts_on' => 'This guard already has an active assignment during the selected date range. End or cancel the existing assignment first.',
            ]);
        }

        DB::transaction(function () use ($validated) {
            GuardSiteAssignment::create([
                'guard_id' => $validated['guard_id'],
                'site_id' => $validated['site_id'],
                'shift_id' => $validated['shift_id'],
                'assignment_date' => $validated['starts_on'],
                'starts_on' => $validated['starts_on'],
                'ends_on' => $validated['ends_on'] ?? null,
                'scheduled_start_time' =>
                    $validated['scheduled_start_time'] ?? null,
                'scheduled_end_time' =>
                    $validated['scheduled_end_time'] ?? null,
                'status' => 'active',
                'assigned_by' => auth()->id(),
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.assignments.create')
            ->with('success', 'Guard assigned to site successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
