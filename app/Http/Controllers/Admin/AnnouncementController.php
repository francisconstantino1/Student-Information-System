<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\StudentNotification;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    private function checkAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }

    public function index(): View
    {
        $this->checkAdmin();

        $announcements = Announcement::with('user')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get unique courses from students
        $courses = User::where('role', 'student')
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->orderBy('course')
            ->pluck('course')
            ->values();

        return view('admin.announcements.index', [
            'announcements' => $announcements,
            'courses' => $courses,
        ]);
    }

    public function create(): View
    {
        $this->checkAdmin();
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['required', 'string', 'in:general,enrollment,grades,attendance'],
            'target_course' => ['nullable', 'string', 'max:255'],
            'is_pinned' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        // Set default target_audience based on target_course
        if (!empty($validated['target_course'])) {
            $validated['target_audience'] = 'specific_course';
        } else {
            $validated['target_audience'] = 'all';
        }

        $validated['user_id'] = Auth::id();
        $validated['is_pinned'] = $request->has('is_pinned');
        
        // Set published_at: if empty/null, set to now(), otherwise use the provided date
        if (empty($validated['published_at'])) {
            $validated['published_at'] = now();
        } else {
            $validated['published_at'] = \Carbon\Carbon::parse($validated['published_at']);
        }

        $announcement = Announcement::create($validated);

        // Create notifications for students based on target audience
        $this->createNotificationsForAnnouncement($announcement);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement): View
    {
        $this->checkAdmin();
        
        // Get unique courses from students
        $courses = User::where('role', 'student')
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->orderBy('course')
            ->pluck('course')
            ->values();
        
        return view('admin.announcements.edit', [
            'announcement' => $announcement,
            'courses' => $courses,
        ]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['required', 'string', 'in:general,enrollment,grades,attendance'],
            'target_course' => ['nullable', 'string', 'max:255'],
            'is_pinned' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        // Set default target_audience based on target_course
        if (!empty($validated['target_course'])) {
            $validated['target_audience'] = 'specific_course';
        } else {
            $validated['target_audience'] = 'all';
        }

        $validated['is_pinned'] = $request->has('is_pinned');
        
        // Handle published_at: if empty/null, set to now(), otherwise use the provided date
        if (empty($validated['published_at'])) {
            $validated['published_at'] = now();
        } else {
            $validated['published_at'] = \Carbon\Carbon::parse($validated['published_at']);
        }

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $this->checkAdmin();
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Create notifications for students based on announcement target audience
     */
    private function createNotificationsForAnnouncement(Announcement $announcement): void
    {
        $students = $this->getTargetStudents($announcement);

        $notifications = [];
        foreach ($students as $student) {
            $notifications[] = [
                'user_id' => $student->id,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => $announcement->content,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert notifications for better performance
        if (!empty($notifications)) {
            StudentNotification::insert($notifications);
        }
    }

    /**
     * Get target students based on announcement target audience
     */
    private function getTargetStudents(Announcement $announcement): \Illuminate\Database\Eloquent\Collection
    {
        $query = User::where('role', 'student');

        switch ($announcement->target_audience) {
            case 'enrolled':
                // Students with approved enrollment
                $query->whereHas('latestEnrollment', function ($q) {
                    $q->where('status', 'approved');
                });
                break;

            case 'pending':
                // Students with pending enrollment
                $query->whereHas('latestEnrollment', function ($q) {
                    $q->where('status', 'pending');
                });
                break;

            case 'specific_course':
                // Students in a specific course
                if ($announcement->target_course) {
                    $query->where('course', $announcement->target_course);
                }
                break;

            case 'all':
            default:
                // All students - no additional filter needed
                break;
        }

        return $query->get();
    }
}
