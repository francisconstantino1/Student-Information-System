<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Academic;
use App\Models\Grade;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
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
        
        // Get academics/subjects data
        $academics = Academic::query()
            ->where('user_id', $user->id)
            ->orderBy('year_level')
            ->orderBy('subject_code')
            ->get();

        $schedule = $academics->groupBy('schedule');
        $curriculum = $academics->groupBy('year_level');

        // Get grades data
        $grades = Grade::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('subject')
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        // Group grades by academic year and semester
        $groupedGrades = $grades->groupBy(function ($grade) {
            return $grade->academic_year.' - '.$grade->semester;
        });

        // Get messages data
        $admin = User::where('role', 'admin')->first();

        $messages = collect([]);
        if ($admin) {
            $messages = Message::where(function ($query) use ($user, $admin) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $admin->id);
            })
                ->orWhere(function ($query) use ($user, $admin) {
                    $query->where('sender_id', $admin->id)
                        ->where('receiver_id', $user->id);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark messages as read
            Message::where('sender_id', $admin->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
        }

        return view('student.portal', [
            'academics' => $academics,
            'schedule' => $schedule,
            'curriculum' => $curriculum,
            'grades' => $grades,
            'groupedGrades' => $groupedGrades,
            'messages' => $messages,
            'admin' => $admin,
        ]);
    }
}

