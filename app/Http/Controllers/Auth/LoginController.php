<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /** @var \App\Models\User|null $user */
        $user = User::query()
            ->where('email', $validated['email'])
            ->orWhere('student_id', $validated['email'])
            ->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->withInput($request->except('password'));
        }

        // Laravel's 'hashed' cast uses password_hash/password_verify under the hood.
        $remember = (bool) ($validated['remember'] ?? false);

        if (! Auth::attempt(['email' => $user->email, 'password' => $validated['password']], $remember)) {
            return back()
                ->withErrors(['password' => 'The provided password is incorrect.'])
                ->withInput($request->except('password'));
        }

        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out.');
    }
}
