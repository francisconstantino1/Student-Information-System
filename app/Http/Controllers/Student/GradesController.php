<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class GradesController extends Controller
{
    public function index(): View|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        
        // Check if enrollment is approved
        if ($user && $user->role === 'student') {
            $enrollment = $user->latestEnrollment;
            if ($enrollment) {
                if ($enrollment->status === 'pending') {
                    return redirect()->route('enrollment.waiting');
                }
                if ($enrollment->status === 'rejected') {
                    return redirect()->route('enrollment')
                        ->with('error', 'Your enrollment was rejected. Please contact the registrar for more information.');
                }
            } else {
                return redirect()->route('enrollment')
                    ->with('error', 'You must complete your enrollment before accessing this feature.');
            }
        }

        // Fetch all grades for the logged-in student with subject information
        $grades = Grade::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('subject')
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        // Group grades by academic year and semester
        $groupedGrades = $grades->groupBy(function ($grade) {
            return $grade->academic_year.' - '.$grade->semester;
        });

        return view('student.grades', [
            'grades' => $grades,
            'groupedGrades' => $groupedGrades,
        ]);
    }

    public function generatePdf()
    {
        $user = Auth::user();
        
        // Check if enrollment is approved
        if ($user && $user->role === 'student') {
            $enrollment = $user->latestEnrollment;
            if ($enrollment) {
                if ($enrollment->status === 'pending') {
                    return redirect()->route('enrollment.waiting');
                }
                if ($enrollment->status === 'rejected') {
                    return redirect()->route('enrollment')
                        ->with('error', 'Your enrollment was rejected. Please contact the registrar for more information.');
                }
            } else {
                return redirect()->route('enrollment')
                    ->with('error', 'You must complete your enrollment before accessing this feature.');
            }
        }

        // Fetch all grades for the logged-in student with subject information
        $grades = Grade::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('subject')
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        // Group grades by academic year and semester
        $groupedGrades = $grades->groupBy(function ($grade) {
            return $grade->academic_year.' - '.$grade->semester;
        });

        // Generate PDF
        $pdf = Pdf::loadView('student.grades-pdf', [
            'user' => $user,
            'grades' => $grades,
            'groupedGrades' => $groupedGrades,
        ]);

        $filename = 'Grades_' . ($user->student_id ?? $user->id) . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
