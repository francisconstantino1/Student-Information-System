<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $user = Auth::user();

        // Check if user has a valid student ID
        if (! $user->student_id) {
            return view('enrollment')->with('error', 'You must have a valid Institutional ID to access the enrollment form. Please contact the administrator.');
        }

        // Check if user already has a pending or approved enrollment
        $latestEnrollment = $user->latestEnrollment;
        if ($latestEnrollment) {
            if ($latestEnrollment->status === 'pending') {
                return redirect()->route('enrollment.waiting');
            }
            if ($latestEnrollment->status === 'approved') {
                return view('enrollment')->with('info', 'You are already enrolled. Your enrollment was approved.');
            }
        }

        return view('enrollment');
    }

    public function waiting(): View
    {
        $user = Auth::user();
        $enrollment = $user->latestEnrollment;

        return view('waiting-for-approval', [
            'enrollment' => $enrollment,
        ]);
    }

    public function store(EnrollmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = Auth::user();

        // Check if user has a valid student ID
        if (! $user->student_id) {
            return redirect()->route('enrollment')
                ->with('error', 'You must have a valid Institutional ID to enroll. Please contact the administrator.');
        }

        // Check if user already has a pending or approved enrollment
        $latestEnrollment = $user->latestEnrollment;
        if ($latestEnrollment) {
            if ($latestEnrollment->status === 'pending') {
                return redirect()->route('enrollment')
                    ->with('error', 'You already have a pending enrollment. Please wait for the approval of registrar.');
            }
            if ($latestEnrollment->status === 'approved') {
                return redirect()->route('enrollment')
                    ->with('error', 'You are already enrolled. Your enrollment was already approved.');
            }
        }

        $enrollment = Enrollment::query()->create([
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'address' => $validated['address'],
            'email' => $validated['email'],
            'birthday' => $validated['birthday'],
            'gender' => $validated['gender'],
            'previous_school' => $validated['previous_school'] ?? null,
            'course_selected' => $validated['course_selected'],
            'year_level' => $validated['year_level'],
            'guardian_name' => $validated['guardian_name'],
            'guardian_contact' => $validated['guardian_contact'],
            'status' => 'pending', // Wait for registrar approval
            'remarks' => 'Enrollment submitted. Waiting for registrar approval.',
        ]);

        // Send enrollment notification
        \App\Models\StudentNotification::create([
            'user_id' => $user->id,
            'type' => 'enrollment',
            'title' => 'Enrollment Submitted ⏳',
            'message' => 'Your enrollment has been submitted successfully. Please wait for the approval of registrar to access all features.',
        ]);

        return redirect()->route('enrollment.waiting')->with('success', 'Your enrollment has been submitted successfully! Please wait for the approval of registrar. You will be notified once your enrollment is approved.');
    }
}
