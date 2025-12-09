<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\User;
use App\Services\SectionAssignmentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentManagementController extends Controller
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

        // Sync approved enrollments with user profiles (fix existing data)
        $this->syncApprovedEnrollments();

        // Query students with fresh data, ensuring section relationship is loaded
        $students = User::where('role', 'student')
            ->with(['section', 'documents'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $sections = Section::orderBy('name')->get();

        return view('admin.students.index', [
            'students' => $students,
            'sections' => $sections,
        ]);
    }

    /**
     * Sync approved enrollments with user profiles
     * This ensures that students with approved enrollments have their course, year_level, and section updated
     */
    private function syncApprovedEnrollments(): void
    {
        $approvedEnrollments = Enrollment::where('status', 'approved')
            ->with('user')
            ->get();

        $sectionService = new SectionAssignmentService;

        foreach ($approvedEnrollments as $enrollment) {
            if (! $enrollment->user) {
                continue;
            }

            // Get fresh user data
            $user = $enrollment->user->fresh();

            $updateData = [];

            // Always update course and year_level from enrollment if they exist
            if (! empty($enrollment->course_selected)) {
                $updateData['course'] = $enrollment->course_selected;
            }
            if (! empty($enrollment->year_level)) {
                $updateData['year_level'] = $enrollment->year_level;
            }

            // Update user course and year_level first
            if (! empty($updateData)) {
                $user->update($updateData);
                $user->refresh();
            }

            // Assign section if user doesn't have one (check for null or 0) and enrollment has course/year_level
            $needsSection = ($user->section_id === null || $user->section_id === 0);
            if ($needsSection && ! empty($enrollment->course_selected) && ! empty($enrollment->year_level)) {
                try {
                    $section = $sectionService->assignSection(
                        $user,
                        $enrollment->course_selected,
                        $enrollment->year_level,
                        $enrollment->semester ?? null,
                        $enrollment->academic_year ?? null
                    );
                    
                    // Refresh user to ensure section_id is loaded
                    if ($section) {
                        $user->refresh();
                        // Reload the section relationship
                        $user->load('section');
                    }
                } catch (\Exception $e) {
                    // Log error but continue with other enrollments
                    Log::error('Failed to assign section for user ' . $user->id . ': ' . $e->getMessage());
                }
            }
        }
    }

    public function create(): View
    {
        $this->checkAdmin();
        $sections = Section::orderBy('name')->get();

        return view('admin.students.create', [
            'sections' => $sections,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'student_id' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'course' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'max:255'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'address' => ['nullable', 'string'],
            'birthday' => ['nullable', 'date'],
            'contact_number' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'student';

        User::create($validated);

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function show(User $student): View
    {
        $this->checkAdmin();
        $student->load('section', 'documents', 'grades', 'attendances');

        return view('admin.students.show', [
            'student' => $student,
        ]);
    }

    public function edit(User $student): View
    {
        $this->checkAdmin();
        
        // Load section relationship
        $student->load('section');
        
        $sections = Section::orderBy('name')->get();

        return view('admin.students.edit', [
            'student' => $student,
            'sections' => $sections,
        ]);
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'student_id' => ['required', 'string', 'max:255', 'unique:users,student_id,' . $student->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $student->id],
            'password' => ['nullable', 'string', 'min:8'],
            'course' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'max:255'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'address' => ['nullable', 'string'],
            'birthday' => ['nullable', 'date'],
            'contact_number' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(User $student): RedirectResponse
    {
        $this->checkAdmin();

        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }

    public function archive(User $student): RedirectResponse
    {
        $this->checkAdmin();

        $student->delete(); // Soft delete

        return redirect()->route('admin.students.index')->with('success', 'Student archived successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->checkAdmin();

        $student = User::onlyTrashed()->findOrFail($id);
        $student->restore();

        return redirect()->route('admin.archive.index')->with('success', 'Student restored successfully.');
    }
}
