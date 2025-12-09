<?php

namespace App\Http\Controllers;

use App\Models\ClassSession;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AcademicsController extends Controller
{
    public function index(): View|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        
        // Check if enrollment is approved
        if ($user && $user->role === 'student') {
            $enrollment = $user->latestEnrollment;
            if ($enrollment) {
                if ($enrollment->status === 'pending') {
                    return redirect()->route('enrollment.waiting');
                }
                if ($enrollment->status === 'rejected') {
                    return redirect()->route('enrollment')
                        ->with('error', 'Your enrollment was rejected. Please contact the registrar for more information.');
                }
            } else {
                return redirect()->route('enrollment')
                    ->with('error', 'You must complete your enrollment before accessing this feature.');
            }
        }
        $sessions = ClassSession::query()
            ->where('is_active', true)
            ->orderBy('schedule')
            ->orderBy('course_id')
            ->get();

        return view('academics', [
            'academics' => $sessions,
        ]);
    }
}

