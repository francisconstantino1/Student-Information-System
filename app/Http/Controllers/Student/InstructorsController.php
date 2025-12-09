<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class InstructorsController extends Controller
{
    public function index(): View
    {
        $student = Auth::user();
        $course = $student->course;
        $yearLevel = $student->year_level;

        $instructors = User::where('role', 'teacher')
            ->when($course, fn ($q) => $q->where('course', $course))
            ->when($yearLevel, function ($q) use ($yearLevel) {
                $q->where(function ($qq) use ($yearLevel) {
                    $qq->whereNull('year_level')
                        ->orWhere('year_level', $yearLevel);
                });
            })
            ->orderBy('name')
            ->get();

        // Load subjects for each instructor
        $instructorIds = $instructors->pluck('id');
        $subjectsByInstructor = Subject::whereIn('instructor_id', $instructorIds)
            ->get()
            ->groupBy('instructor_id');

        return view('student.instructors', [
            'instructors' => $instructors,
            'subjectsByInstructor' => $subjectsByInstructor,
            'course' => $course,
            'yearLevel' => $yearLevel,
        ]);
    }
}
