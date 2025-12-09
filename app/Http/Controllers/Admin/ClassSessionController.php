<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ClassSessionController extends Controller
{
    private function checkAdmin(): void
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->checkAdmin();

        $sessions = ClassSession::orderBy('schedule')->paginate(20);
        $instructors = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();
        $courses = $this->getAvailableCourses();
        $subjects = Subject::orderBy('course')->orderBy('subject_code')->get();

        return view('admin.class-sessions.index', [
            'sessions' => $sessions,
            'instructors' => $instructors,
            'courses' => $courses,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->checkAdmin();

        $instructors = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();
        $courses = $this->getAvailableCourses();
        $subjects = Subject::orderBy('course')->orderBy('subject_code')->get();

        return view('admin.class-sessions.create', [
            'instructors' => $instructors,
            'courses' => $courses,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'course' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'schedule' => ['required', 'string', 'max:255'],
            'time' => ['required', 'string', 'max:255'],
            'instructor' => ['required', 'string', 'max:255'],
            'room' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        ClassSession::create([
            'name' => $validated['code'] . ' - ' . $validated['subject'], // Generate name from code and subject
            'course' => $validated['course'],
            'course_id' => $validated['code'],
            'subject' => $validated['subject'],
            'schedule' => $validated['schedule'],
            'time' => $validated['time'],
            'instructor' => $validated['instructor'],
            'room' => $validated['room'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.class-sessions.index')
            ->with('success', 'Class session created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassSession $classSession): View
    {
        $this->checkAdmin();

        $classSession->load(['students', 'attendanceCodes']);

        return view('admin.class-sessions.show', [
            'session' => $classSession,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassSession $classSession): View
    {
        $this->checkAdmin();

        $instructors = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();
        $courses = $this->getAvailableCourses();
        $subjects = Subject::orderBy('course')->orderBy('subject_code')->get();

        return view('admin.class-sessions.edit', [
            'session' => $classSession,
            'instructors' => $instructors,
            'courses' => $courses,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassSession $classSession): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'course' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'schedule' => ['required', 'string', 'max:255'],
            'time' => ['required', 'string', 'max:255'],
            'instructor' => ['required', 'string', 'max:255'],
            'room' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $classSession->update([
            'name' => $validated['code'] . ' - ' . $validated['subject'], // Update name from code and subject
            'course' => $validated['course'],
            'course_id' => $validated['code'],
            'subject' => $validated['subject'],
            'schedule' => $validated['schedule'],
            'time' => $validated['time'],
            'instructor' => $validated['instructor'],
            'room' => $validated['room'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? $classSession->is_active,
        ]);

        return redirect()->route('admin.class-sessions.index')
            ->with('success', 'Class session updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSession $classSession): RedirectResponse
    {
        $this->checkAdmin();

        // Check if session has attendance codes
        if ($classSession->attendanceCodes()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete session with existing attendance codes.']);
        }

        $classSession->delete();

        return redirect()->route('admin.class-sessions.index')
            ->with('success', 'Class session deleted successfully.');
    }

    private function getAvailableCourses(): Collection
    {
        $fromSubjects = Subject::query()
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->pluck('course');

        $fromUsers = User::query()
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->pluck('course');

        return $fromSubjects
            ->merge($fromUsers)
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }
}
