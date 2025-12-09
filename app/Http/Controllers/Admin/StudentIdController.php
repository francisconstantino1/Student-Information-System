<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentId;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class StudentIdController extends Controller
{
    private function checkAdmin(): void
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }

    public function index(): View
    {
        $this->checkAdmin();

        $studentIds = StudentId::with(['assignedUser', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.student-ids.index', [
            'studentIds' => $studentIds,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'student_id' => ['required', 'string', 'max:255', 'unique:student_ids,student_id'],
            'count' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $count = $validated['count'] ?? 1;

        for ($i = 0; $i < $count; $i++) {
            $studentId = $validated['student_id'];
            if ($count > 1) {
                $studentId = $validated['student_id'].'-'.str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            }

            StudentId::create([
                'student_id' => $studentId,
                'status' => 'available',
                'created_by' => Auth::id(),
            ]);
        }

        $message = $count > 1 
            ? "Successfully created {$count} institutional IDs."
            : "Successfully created institutional ID: {$validated['student_id']}.";

        return redirect()->route('admin.student-ids.index')->with('success', $message);
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAdmin();

        $studentId = StudentId::findOrFail($id);

        if ($studentId->status === 'used') {
            return redirect()->route('admin.student-ids.index')
                ->with('error', 'Cannot delete an institutional ID that has been used.');
        }

        $studentId->delete();

        return redirect()->route('admin.student-ids.index')
            ->with('success', 'Institutional ID deleted successfully.');
    }
}
