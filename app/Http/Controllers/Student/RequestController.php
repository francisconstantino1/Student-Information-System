<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $requests = $user->requests()->latest()->get();
        
        // Get available courses for shift request
        $courses = User::where('role', 'student')
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->orderBy('course')
            ->pluck('course')
            ->values();
        
        return view('student.requests', [
            'requests' => $requests,
            'currentCourse' => $user->course,
            'courses' => $courses,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'request_type' => ['required', 'string', 'in:shift'],
            'target_course' => ['required', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        
        // Check if student already has a pending shift request
        $existingRequest = StudentRequest::where('user_id', $user->id)
            ->where('request_type', 'shift')
            ->where('status', 'pending')
            ->first();
            
        if ($existingRequest) {
            return redirect()->route('requests')->with('error', 'You already have a pending shift request. Please wait for admin approval.');
        }

        // Check if target course is different from current course
        if ($validated['target_course'] === $user->course) {
            return redirect()->route('requests')->with('error', 'You are already enrolled in this course.');
        }

        StudentRequest::create([
            'user_id' => $user->id,
            'request_type' => $validated['request_type'],
            'target_course' => $validated['target_course'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('requests')->with('success', 'Shift request submitted successfully. Admin will review it.');
    }
}
