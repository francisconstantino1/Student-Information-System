<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Mail\AttendanceCodeMail;
use App\Models\AttendanceCode;
use App\Models\ClassSession;
use App\Models\SessionEnrollment;
use App\Models\StudentNotification;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AttendanceController extends Controller
{
    private function checkTeacher(): void
    {
        if (! Auth::check() || Auth::user()->role !== 'teacher') {
            abort(403, 'Unauthorized access.');
        }
    }

    public function index(): View
    {
        $this->checkTeacher();

        $attendances = Attendance::with(['user', 'subject', 'attendanceCode', 'attendanceCode.classSession'])
            ->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc')
            ->paginate(20);

        $classSessions = ClassSession::where('is_active', true)->orderBy('start_time')->get();

        $recentCodes = AttendanceCode::with(['classSession', 'creator'])
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $allCodes = AttendanceCode::with(['classSession', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.attendance.index', [
            'attendances' => $attendances,
            'classSessions' => $classSessions,
            'recentCodes' => $recentCodes,
            'allCodes' => $allCodes,
        ]);
    }

    public function generateCode(Request $request): RedirectResponse
    {
        $this->checkTeacher();

        $validated = $request->validate([
            'class_session_id' => ['required', 'exists:class_sessions,id'],
            'date' => ['required', 'date'],
        ]);

        $classSession = ClassSession::findOrFail($validated['class_session_id']);

        $sessionDate = now()->parse($validated['date']);
        $endTime = now()->parse($classSession->end_time);
        $expiresAt = $sessionDate->copy()->setTime($endTime->hour, $endTime->minute, $endTime->second);
        if ($expiresAt->isPast() && $sessionDate->isToday()) {
            $expiresAt = now()->endOfDay();
        }

        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (AttendanceCode::where('code', $code)->where('is_active', true)->exists());

        $attendanceCode = AttendanceCode::create([
            'class_session_id' => $validated['class_session_id'],
            'code' => $code,
            'date' => $validated['date'],
            'expires_at' => $expiresAt,
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        $registeredStudents = User::where('role', 'student')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('enrollments')
                    ->whereColumn('enrollments.user_id', 'users.id')
                    ->where('enrollments.status', 'approved');
            })
            ->get();

        foreach ($registeredStudents as $student) {
            $existing = SessionEnrollment::where('user_id', $student->id)
                ->where('class_session_id', $classSession->id)
                ->where('session_date', $validated['date'])
                ->first();

            if (! $existing) {
                SessionEnrollment::create([
                    'user_id' => $student->id,
                    'class_session_id' => $classSession->id,
                    'attendance_code_id' => $attendanceCode->id,
                    'session_date' => $validated['date'],
                    'enrolled_at' => now(),
                    'is_active' => true,
                ]);
            } else {
                $existing->update([
                    'attendance_code_id' => $attendanceCode->id,
                    'is_active' => true,
                ]);
            }
        }

        foreach ($registeredStudents as $student) {
            StudentNotification::create([
                'user_id' => $student->id,
                'attendance_code_id' => $attendanceCode->id,
                'type' => 'attendance_code',
                'title' => 'New Attendance Code: '.$code,
                'message' => "Attendance code for {$classSession->name} ({$classSession->time_range}) on {$sessionDate->format('M d, Y')}. Code expires at {$expiresAt->format('g:i A')}.",
            ]);

            if ($student->email) {
                try {
                    Mail::to($student->email)->send(new AttendanceCodeMail($attendanceCode));
                } catch (\Exception $e) {
                    Log::error('Failed to send attendance code email to '.$student->email.': '.$e->getMessage());
                }
            }
        }

        return redirect()->route('teacher.attendance.index')
            ->with('success', "Attendance code generated: {$code}.");
    }

    public function deactivateCode(int $id): RedirectResponse
    {
        $this->checkTeacher();

        $code = AttendanceCode::findOrFail($id);
        $code->update(['is_active' => false]);

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Attendance code deactivated successfully.');
    }
}

