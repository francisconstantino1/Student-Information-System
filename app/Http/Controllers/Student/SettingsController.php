<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentPreference;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $preference = $user->preference ?? StudentPreference::create([
            'user_id' => $user->id,
            'theme' => 'light',
            'language' => 'en',
            'sidebar_mode' => 'expanded',
            'notifications' => [
                'grade_updates' => true,
                'enrollment_status' => true,
                'announcements' => true,
                'attendance_alerts' => true,
            ],
        ]);

        return view('student.settings', [
            'user' => $user,
            'preference' => $preference,
        ]);
    }

    public function updateAccount(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'security_question' => ['nullable', 'string', 'max:255'],
            'security_answer' => ['nullable', 'string', 'max:255'],
            '2fa_enabled' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $preference = $user->preference ?? StudentPreference::create(['user_id' => $user->id]);
        $preference->update([
            'security_question' => $validated['security_question'] ?? $preference->security_question,
            'security_answer' => $validated['security_answer'] ?? $preference->security_answer,
            '2fa_enabled' => $validated['2fa_enabled'] ?? false,
        ]);

        return redirect()->route('settings')->with('success', 'Account settings updated successfully.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_number' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = Auth::user();
        $updateData = [
            'contact_number' => $validated['contact_number'] ?? $user->contact_number,
            'address' => $validated['address'] ?? $user->address,
            'guardian_name' => $validated['guardian_name'] ?? $user->guardian_name,
            'guardian_contact' => $validated['guardian_contact'] ?? $user->guardian_contact,
        ];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $updateData['profile_image'] = $request->file('profile_image')->store('profiles/' . $user->id, 'public');
        }

        $user->update($updateData);

        $preference = $user->preference ?? StudentPreference::create(['user_id' => $user->id]);
        
        $preference->bio = $validated['bio'] ?? $preference->bio;
        $preference->save();

        return redirect()->route('settings')->with('success', 'Profile updated successfully.');
    }

    public function updateNotifications(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'grade_updates' => ['nullable', 'boolean'],
            'enrollment_status' => ['nullable', 'boolean'],
            'announcements' => ['nullable', 'boolean'],
            'attendance_alerts' => ['nullable', 'boolean'],
        ]);

        $preference = Auth::user()->preference ?? StudentPreference::create([
            'user_id' => Auth::id(),
            'notifications' => [],
        ]);

        $notifications = $preference->notifications ?? [];
        $notifications = array_merge($notifications, array_filter($validated, fn($v) => !is_null($v)));
        
        $preference->update(['notifications' => $notifications]);

        return redirect()->route('settings')->with('success', 'Notification settings updated successfully.');
    }

    public function updateInterface(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'string', 'in:light,dark'],
            'language' => ['required', 'string', 'in:en,tl'],
            'sidebar_mode' => ['required', 'string', 'in:expanded,compact'],
        ]);

        $preference = Auth::user()->preference ?? StudentPreference::create(['user_id' => Auth::id()]);
        $preference->update($validated);

        return redirect()->route('settings')->with('success', 'Interface settings updated successfully.');
    }
}
