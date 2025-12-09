<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
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

        $grades = Grade::with(['user', 'subject'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester')
            ->paginate(20);

        $students = User::where('role', 'student')
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->orderBy('course')
            ->orderBy('name')
            ->get();

        $subjects = Subject::orderBy('subject_name')->get();

        // Unique courses for filter
        $courses = User::where('role', 'student')
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->pluck('course')
            ->sort()
            ->values();

        return view('teacher.grades.index', [
            'grades' => $grades,
            'students' => $students,
            'subjects' => $subjects,
            'courses' => $courses,
        ]);
    }

    public function create(): View
    {
        $this->checkTeacher();
        $students = User::where('role', 'student')->orderBy('name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('teacher.grades.create', [
            'students' => $students,
            'subjects' => $subjects,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkTeacher();

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester' => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:255'],
            'midterm' => ['nullable', 'string', 'max:255'],
            'final' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['remarks'] = $this->calculateRemarks($validated['midterm'] ?? '', $validated['final'] ?? '');
        $validated['status'] = 'pending';
        $validated['approved_at'] = null;
        $validated['approved_by'] = null;

        Grade::create($validated);

        return redirect()->route('teacher.grades.index')->with('success', 'Grade saved successfully.');
    }

    public function edit(Grade $grade): View
    {
        $this->checkTeacher();
        $students = User::where('role', 'student')->orderBy('name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('teacher.grades.edit', [
            'grade' => $grade,
            'students' => $students,
            'subjects' => $subjects,
        ]);
    }

    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $this->checkTeacher();

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester' => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:255'],
            'midterm' => ['nullable', 'string', 'max:255'],
            'final' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['remarks'] = $this->calculateRemarks($validated['midterm'] ?? '', $validated['final'] ?? '');
        $validated['status'] = 'pending';
        $validated['approved_at'] = null;
        $validated['approved_by'] = null;

        $grade->update($validated);

        return redirect()->route('teacher.grades.index')->with('success', 'Grade updated successfully.');
    }

    private function calculateRemarks(string $midterm, string $final): string
    {
        $midterm = trim($midterm);
        $final = trim($final);

        $hasMidterm = $midterm !== '' && strtoupper($midterm) !== 'INC';
        $hasFinal = $final !== '' && strtoupper($final) !== 'INC';

        if ($hasMidterm && $hasFinal) {
            return 'Complete';
        }

        return 'Incomplete';
    }
}

