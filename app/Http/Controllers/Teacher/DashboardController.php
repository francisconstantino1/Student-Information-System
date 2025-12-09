<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\SchoolEvent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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

        $requestMonth = request()->input('month');
        $requestYear = request()->input('year');

        $selectedDate = $requestMonth && $requestYear
            ? Carbon::create((int) $requestYear, (int) $requestMonth, 1)
            : Carbon::now();

        $currentMonth = (int) $selectedDate->month;
        $currentYear = (int) $selectedDate->year;

        $events = SchoolEvent::query()
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->orderBy('date')
            ->get();

        $eventsByDate = $events->groupBy(function (SchoolEvent $event): string {
            $eventDate = $event->date instanceof Carbon ? $event->date : Carbon::parse($event->date);
            return $eventDate->format('Y-m-d');
        });

        $prevMonth = $selectedDate->copy()->subMonth();
        $nextMonth = $selectedDate->copy()->addMonth();

        $announcements = Announcement::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('teacher.dashboard', [
            'eventsByDate' => $eventsByDate,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'selectedDate' => $selectedDate,
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
            'announcements' => $announcements,
        ]);
    }
}

