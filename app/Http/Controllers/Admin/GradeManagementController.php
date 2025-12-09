<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeManagementController extends Controller
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
        
        // Get unique courses from students
        $courses = User::where('role', 'student')
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->pluck('course')
            ->sort()
            ->values();

        return view('admin.grades.index', [
            'grades' => $grades,
            'students' => $students,
            'subjects' => $subjects,
            'courses' => $courses,
        ]);
    }

    public function create(): View
    {
        $this->checkAdmin();
        $students = User::where('role', 'student')->orderBy('name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('admin.grades.create', [
            'students' => $students,
            'subjects' => $subjects,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester' => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:255'],
            'midterm' => ['nullable', 'string', 'max:255'],
            'final' => ['nullable', 'string', 'max:255'],
        ]);

        // Calculate remarks based on grades
        $midterm = trim($validated['midterm'] ?? '');
        $final = trim($validated['final'] ?? '');
        
        $hasMidterm = !empty($midterm) && strtoupper($midterm) !== 'INC';
        $hasFinal = !empty($final) && strtoupper($final) !== 'INC';
        
        if ($hasMidterm && $hasFinal) {
            // Both grades are available (not INC)
            $validated['remarks'] = 'Complete';
        } else {
            // Only one grade or both are INC/empty
            $validated['remarks'] = 'Incomplete';
        }

        $validated['status'] = 'approved';
        $validated['approved_at'] = now();
        $validated['approved_by'] = Auth::id();

        Grade::create($validated);

        return redirect()->route('admin.grades.index')->with('success', 'Grade created successfully.');
    }

    public function edit(Grade $grade): View
    {
        $this->checkAdmin();
        $students = User::where('role', 'student')->orderBy('name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('admin.grades.edit', [
            'grade' => $grade,
            'students' => $students,
            'subjects' => $subjects,
        ]);
    }

    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester' => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:255'],
            'midterm' => ['nullable', 'string', 'max:255'],
            'final' => ['nullable', 'string', 'max:255'],
        ]);

        // Calculate remarks based on grades
        $midterm = trim($validated['midterm'] ?? '');
        $final = trim($validated['final'] ?? '');
        
        $hasMidterm = !empty($midterm) && strtoupper($midterm) !== 'INC';
        $hasFinal = !empty($final) && strtoupper($final) !== 'INC';
        
        if ($hasMidterm && $hasFinal) {
            // Both grades are available (not INC)
            $validated['remarks'] = 'Complete';
        } else {
            // Only one grade or both are INC/empty
            $validated['remarks'] = 'Incomplete';
        }

        $validated['status'] = 'approved';
        $validated['approved_at'] = now();
        $validated['approved_by'] = Auth::id();

        $grade->update($validated);

        return redirect()->route('admin.grades.index')->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $this->checkAdmin();
        $grade->delete();

        return redirect()->route('admin.grades.index')->with('success', 'Grade deleted successfully.');
    }

    public function approve(Grade $grade): RedirectResponse
    {
        $this->checkAdmin();

        $grade->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('admin.grades.index')->with('success', 'Grade approved and published.');
    }
}
