<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    private function resolvePartner(User $student, ?string $target = null, ?int $instructorId = null): ?User
    {
        // If specific instructor ID is provided, use that
        if ($instructorId) {
            $instructor = User::where('role', 'teacher')->find($instructorId);
            if ($instructor) {
                return $instructor;
            }
        }

        if ($target === 'admin') {
            return User::where('role', 'admin')->first();
        }

        // Target instructor (default) - find assigned instructor
        $partner = User::where('role', 'teacher')
            ->where('course', $student->course)
            ->when($student->year_level, fn ($q) => $q->where('year_level', $student->year_level))
            ->first();

        // Fallback: any teacher for the course
        if (! $partner && $student->course) {
            $partner = User::where('role', 'teacher')
                ->where('course', $student->course)
                ->first();
        }

        // Fallback: admin
        return $partner ?: User::where('role', 'admin')->first();
    }

    public function index(): View
    {
        $user = Auth::user();
        $target = request()->query('target', 'instructor');
        $instructorId = request()->query('instructor_id');
        $partner = $this->resolvePartner($user, $target, $instructorId ? (int) $instructorId : null);

        if (! $partner) {
            abort(404, 'No available instructor or admin to message.');
        }

        $messages = Message::where(function ($query) use ($user, $partner) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $partner->id);
        })
            ->orWhere(function ($query) use ($user, $partner) {
                $query->where('sender_id', $partner->id)
                    ->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $partner->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Get all instructors for dropdown
        $instructors = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();

        return view('student.messages.index', [
            'partner' => $partner,
            'target' => $target,
            'instructorId' => $instructorId,
            'instructors' => $instructors,
            'hasInstructor' => $instructors->isNotEmpty(),
            'messages' => $messages,
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required_without:file', 'string', 'max:5000'],
            'file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png,gif,txt,xls,xlsx,ppt,pptx'],
        ]);

        // Ensure at least message or file is provided
        if (empty($validated['message']) && !$request->hasFile('file')) {
            return response()->json(['success' => false, 'error' => 'Either a message or a file must be provided.'], 422);
        }

        $user = Auth::user();
        $target = $request->input('target', 'instructor');
        $instructorId = $request->input('instructor_id');
        $partner = $this->resolvePartner($user, $target, $instructorId ? (int) $instructorId : null);

        if (! $partner) {
            return response()->json(['success' => false, 'error' => 'No available instructor or admin found.'], 404);
        }

        $messageData = [
            'sender_id' => $user->id,
            'receiver_id' => $partner->id,
            'message' => $validated['message'],
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('messages/' . $user->id, $fileName, 'public');

            $messageData['file_path'] = $filePath;
            $messageData['file_name'] = $file->getClientOriginalName();
            $messageData['file_type'] = $file->getMimeType();
            $messageData['file_size'] = $file->getSize();
        }

        $message = Message::create($messageData);

        $responseData = [
            'id' => $message->id,
            'message' => $message->message,
            'created_at' => $message->created_at->format('M d, Y g:i A'),
            'created_at_human' => $message->created_at->diffForHumans(),
        ];

        if ($message->file_path) {
            $responseData['file_name'] = $message->file_name;
            $responseData['file_path'] = $message->file_path;
            $responseData['file_type'] = $message->file_type;
        }

        return response()->json([
            'success' => true,
            'message' => $responseData,
        ]);
    }

    public function fetch(Request $request): JsonResponse
    {
        $user = Auth::user();
        $target = $request->query('target', 'instructor');
        $instructorId = $request->query('instructor_id');
        $partner = $this->resolvePartner($user, $target, $instructorId ? (int) $instructorId : null);

        if (! $partner) {
            return response()->json(['success' => false, 'error' => 'No available instructor or admin found.'], 404);
        }

        $messages = Message::where(function ($query) use ($user, $partner) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $partner->id);
        })
            ->orWhere(function ($query) use ($user, $partner) {
                $query->where('sender_id', $partner->id)
                    ->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->map(function ($message) use ($user) {
                $data = [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'is_student' => $message->sender_id === $user->id,
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
        Message::where('sender_id', $partner->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'messages' => $messages,
            'unread_count' => Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count(),
        ]);
    }

    public function download(Message $message)
    {
        $user = Auth::user();

        // Check if user is sender or receiver
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        if (! $message->file_path) {
            abort(404, 'File not found.');
        }

        if (! Storage::disk('public')->exists($message->file_path)) {
            abort(404, 'File not found.');
        }

        $fullPath = Storage::disk('public')->path($message->file_path);
        return response()->download($fullPath, $message->file_name);
    }
}
