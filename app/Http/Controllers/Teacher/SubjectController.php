<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
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

        /** @var \App\Models\User $teacher */
        $teacher = Auth::user();
        $teacherCourse = $teacher->course;

        $subjects = Subject::with('instructor')
            ->when($teacherCourse, function ($query) use ($teacherCourse) {
                $query->where('course', $teacherCourse);
            }, function ($query) {
                // If no course assigned to teacher, return none
                $query->whereRaw('1 = 0');
            })
            ->orderBy('course')
            ->orderBy('year_level')
            ->orderBy('subject_name')
            ->paginate(20);

        return view('teacher.subjects.index', [
            'subjects' => $subjects,
            'teacherCourse' => $teacherCourse,
        ]);
    }
}

