<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolEvent;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        if ($user && $user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Check enrollment status from latest enrollment record
        $enrollmentStatus = 'pending';
        if ($user && $user->role === 'student') {
            $enrollment = $user->latestEnrollment;
            if ($enrollment) {
                $enrollmentStatus = $enrollment->status;
            }
            
            // Redirect students with pending enrollment to waiting page
            if ($enrollmentStatus === 'pending') {
                return redirect()->route('enrollment.waiting');
            }
            
            // Redirect students with rejected enrollment to enrollment page
            if ($enrollmentStatus === 'rejected') {
                return redirect()->route('enrollment')
                    ->with('error', 'Your enrollment was rejected. Please contact the registrar for more information.');
            }
        }

        $totalStudents = Student::query()->count();
        $enrolledCount = Student::query()
            ->where('enrollment_status', 'enrolled')
            ->count();

        $notEnrolledCount = max($totalStudents - $enrolledCount, 0);

        if ($totalStudents > 0) {
            $enrolledPercentage = round(($enrolledCount / $totalStudents) * 100, 1);
            $notEnrolledPercentage = round(($notEnrolledCount / $totalStudents) * 100, 1);
        } else {
            $enrolledPercentage = 0.0;
            $notEnrolledPercentage = 0.0;
        }

        $enrollmentChartData = [
            'labels' => ['Enrolled', 'Not Enrolled'],
            'data' => [
                $enrolledPercentage,
                $notEnrolledPercentage,
            ],
        ];

        // Handle month navigation
        $requestMonth = request()->input('month');
        $requestYear = request()->input('year');
        
        if ($requestMonth && $requestYear) {
            $selectedDate = Carbon::create((int) $requestYear, (int) $requestMonth, 1);
        } else {
            $selectedDate = Carbon::now();
        }

        $currentMonth = (int) $selectedDate->month;
        $currentYear = (int) $selectedDate->year;

        $events = SchoolEvent::query()
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->orderBy('date')
            ->get();

        $eventsByDate = $events->groupBy(function (SchoolEvent $event): string {
            return $event->date->format('Y-m-d');
        });

        // Calculate previous and next month
        $prevMonth = $selectedDate->copy()->subMonth();
        $nextMonth = $selectedDate->copy()->addMonth();

        $monthlyTuition = $user?->monthly_tuition ?? 0.00;

        // Fetch announcements: show latest published (no audience filtering to ensure visibility)
        $announcements = Announcement::query()
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now())
                    ->orWhere('published_at', '');
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'enrollmentChartData' => $enrollmentChartData,
            'eventsByDate' => $eventsByDate,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'selectedDate' => $selectedDate,
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
            'monthlyTuition' => $monthlyTuition,
            'user' => $user,
            'enrollmentStatus' => $enrollmentStatus,
            'announcements' => $announcements,
        ]);
    }
}


