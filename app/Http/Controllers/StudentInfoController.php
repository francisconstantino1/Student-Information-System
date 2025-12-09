<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentInfoController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        // Students CANNOT edit: student_id, course, year_level, section_id
        $validated = $request->validate([
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
            'contact_number' => ['nullable', 'string', 'max:255'],
            'birthday' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:255'],
        ]);

        /** @var User|null $user */
        $user = Auth::user();

        if ($user instanceof User) {
            $user->update(array_filter($validated, fn ($value) => $value !== null));
        }

        return redirect()->route('settings')->with('success', 'Student information updated successfully.');
    }
}
