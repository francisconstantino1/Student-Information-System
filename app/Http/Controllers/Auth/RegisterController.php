<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Mark student ID as used
        $studentIdRecord = \App\Models\StudentId::where('student_id', $validated['student_id'])
            ->where('status', 'available')
            ->firstOrFail();

        /** @var \App\Models\User $user */
        $user = User::query()->create([
            'student_id' => $validated['student_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            // 'password' cast on User model hashes via password_hash()
            'password' => $validated['password'],
            'role' => 'student',
        ]);

        // Mark student ID as used
        $studentIdRecord->markAsUsed($user->id);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('enrollment')->with('success', 'Registration successful! Please complete your enrollment form.');
    }
}
