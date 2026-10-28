<?php

namespace App\Http\Controllers;

use App\Models\Guard;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class GuardController extends Controller
{
    public function dashboard()
    {
        return view('guard.dashboard');
    }
    public function index()
    {
        $guards = Guard::paginate(10);
        return view('admin.guards.index', compact('guards'));
    }
    public function edit(Guard $guard)
    {
        return view('admin.guards.edit', compact('guard'));
    }
    public function create()
    {
        return view('admin.guards.create');
    }
    public function update(Request $request, Guard $guard)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'string',
                'max:50',
                Rule::unique('guards', 'employee_id')->ignore($guard->id),
            ],
            'full_name' => 'required|string|max:100',
            'joining_date' => 'required|date',
            'employment_status' => 'nullable|boolean',

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($guard->user_id),
            ],
            'mobile' => [
                'required',
                'digits:10',
                'regex:/^[6-9]\d{9}$/',
                Rule::unique('users', 'mobile')->ignore($guard->user_id),
            ],
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function () use ($validated, $guard) {
            $guard->update([
                'employee_id' => $validated['employee_id'],
                'full_name' => $validated['full_name'],
                'joining_date' => $validated['joining_date'],
                'employment_status' => $validated['employment_status'] ?? 1,
            ]);

            $userData = [
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $guard->user()->update($userData);
        });

        return redirect()
            ->route('admin.guards.index')
            ->with('success', 'Guard updated successfully.');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Guard details
            'employee_id' => [
                'required',
                'string',
                'max:50',
                'unique:guards,employee_id',
            ],
            'full_name' => 'required|string|max:100',
            'joining_date' => 'required|date',
            'employment_status' => 'nullable|boolean',

            // User account details
            'email' => 'required|email|max:255|unique:users,email',
            'mobile' => [
                'required',
                'digits:10',
                'regex:/^[6-9]\d{9}$/',
                'unique:users,mobile',
            ],
            'password' => 'required|string|min:8',
        ]);

        $guardRole = Role::where('name', 'guard')->firstOrFail();

        DB::transaction(function () use ($validated, $guardRole) {
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'password' => Hash::make($validated['password']),
                'role_id' => $guardRole->id,
            ]);

            Guard::create([
                'user_id' => $user->id,
                'employee_id' => $validated['employee_id'],
                'full_name' => $validated['full_name'],
                'joining_date' => $validated['joining_date'],
                'employment_status' => $validated['employment_status'] ?? 1,
            ]);
        });

        return redirect()
            ->route('admin.guards.index')
            ->with('success', 'Guard created successfully.');
    }
}
