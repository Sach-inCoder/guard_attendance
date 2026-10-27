<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'mobile' => [
                'required',
                'digits:10',
                'regex:/^[6-9]\d{9}$/',
            ],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'mobile' => 'The mobile number or password is incorrect.',
            ]);
        }

        // Regenerate session after successful authentication.
        $request->session()->regenerate();

        $user = Auth::user();

        // Get the role from the roles table through role_id.
        $role = strtolower($user->role?->name ?? '');

        $routes = [
            'admin' => 'admin.dashboard',
            'hr' => 'hr.dashboard',
            'accounts' => 'accounts.dashboard',
            'guard' => 'guard.dashboard',
        ];

        if (isset($routes[$role])) {
            return redirect()->route($routes[$role]);
        }

        // Logout users without a valid role.
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'mobile' => 'Your account does not have a valid role.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out.');
    }
}
