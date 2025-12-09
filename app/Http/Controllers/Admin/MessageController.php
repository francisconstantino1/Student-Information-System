<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
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

        // Get all unique courses from students
        $courses = User::where('role', 'student')
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->orderBy('course')
            ->pluck('course')
            ->map(function ($course) {
                $studentCount = User::where('role', 'student')
                    ->where('course', $course)
                    ->count();

                return [
                    'name' => $course,
                    'student_count' => $studentCount,
                ];
            })
            ->values();

        $selectedCourse = request()->query('course');
        $students = collect();

        if ($selectedCourse) {
            $students = User::where('role', 'student')
                ->where('course', $selectedCourse)
                ->orderBy('name')
                ->get();
        }

        return view('admin.messages.index', [
            'courses' => $courses,
            'selectedCourse' => $selectedCourse,
            'students' => $students,
        ]);
    }

    public function conversation(Request $request): View
    {
        $this->checkAdmin();

        $admin = Auth::user();

        $validated = $request->validate([
            'course' => ['required', 'string'],
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['required', 'integer', 'exists:users,id'],
        ]);

        $course = $validated['course'];
        $selectedStudentIds = $validated['student_ids'];

        // Get selected students
        $students = User::where('role', 'student')
            ->whereIn('id', $selectedStudentIds)
            ->where('course', $course)
            ->get();

        if ($students->isEmpty()) {
            abort(404, 'No students found for the selected course.');
        }

        // Get messages between admin and selected students
        $messages = Message::where(function ($query) use ($admin, $selectedStudentIds) {
            $query->where('sender_id', $admin->id)
                ->whereIn('receiver_id', $selectedStudentIds);
        })
            ->orWhere(function ($query) use ($admin, $selectedStudentIds) {
                $query->whereIn('sender_id', $selectedStudentIds)
                    ->where('receiver_id', $admin->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::whereIn('sender_id', $selectedStudentIds)
            ->where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('admin.messages.conversation', [
            'course' => $course,
            'students' => $students,
            'selectedStudentIds' => $selectedStudentIds,
            'messages' => $messages,
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['required', 'integer', 'exists:users,id'],
        ]);

        $admin = Auth::user();
        $createdMessages = [];

        // Send message to selected students
        foreach ($validated['student_ids'] as $studentId) {
            $message = Message::create([
                'sender_id' => $admin->id,
                'receiver_id' => $studentId,
                'message' => $validated['message'],
            ]);
            $createdMessages[] = $message;
        }

        // Return the first message as reference (they all have the same content)
        $firstMessage = $createdMessages[0];

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $firstMessage->id,
                'message' => $firstMessage->message,
                'created_at' => $firstMessage->created_at->format('M d, Y g:i A'),
                'created_at_human' => $firstMessage->created_at->diffForHumans(),
            ],
            'sent_to_count' => count($createdMessages),
        ]);
    }

    public function fetch(Request $request): JsonResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['required', 'integer', 'exists:users,id'],
        ]);

        $admin = Auth::user();
        $studentIds = $validated['student_ids'];

        $messages = Message::where(function ($query) use ($admin, $studentIds) {
            $query->where('sender_id', $admin->id)
                ->whereIn('receiver_id', $studentIds);
        })
            ->orWhere(function ($query) use ($admin, $studentIds) {
                $query->whereIn('sender_id', $studentIds)
                    ->where('receiver_id', $admin->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->map(function ($message) use ($admin) {
                $sender = User::find($message->sender_id);

                $data = [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $sender ? $sender->name : 'Unknown',
                    'is_admin' => $message->sender_id === $admin->id,
                    'message' => $message->message,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('M d, Y g:i A'),
                    'created_at_human' => $message->created_at->diffForHumans(),
                ];

                if ($message->file_path) {
                    $data['file_name'] = $message->file_name;
                    $data['file_path'] = $message->file_path;
                    $data['file_type'] = $message->file_type;
                    $data['file_size'] = $message->file_size;
                }

                return $data;
            });

        // Mark messages as read
        Message::whereIn('sender_id', $studentIds)
            ->where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'messages' => $messages,
            'unread_count' => Message::where('receiver_id', $admin->id)
                ->where('is_read', false)
                ->count(),
        ]);
    }
}
