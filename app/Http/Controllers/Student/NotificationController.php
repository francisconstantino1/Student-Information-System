<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Automatically mark expired/inactive code notifications as read
        StudentNotification::where('user_id', $user->id)
            ->where('type', 'attendance_code')
            ->where('is_read', false)
            ->with('attendanceCode')
            ->get()
            ->filter(function ($notification) {
                if (! $notification->attendanceCode) {
                    return true; // Mark as read if code doesn't exist
                }

                return ! $notification->attendanceCode->isValid(); // Mark as read if expired or inactive
            })
            ->each(function ($notification) {
                $notification->markAsRead();
            });

        // Fetch all notifications, but filter expired/inactive ones in the view
        $notifications = StudentNotification::where('user_id', $user->id)
            ->with('attendanceCode')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function fetch(): JsonResponse
    {
        $user = Auth::user();

        // Only fetch notifications for approved students
        $isApproved = $user->latestEnrollment && $user->latestEnrollment->status === 'approved';

        if (! $isApproved) {
            return response()->json([
                'unread_count' => 0,
                'notifications' => [],
            ]);
        }

        // Filter out expired/inactive attendance code notifications
        $allUnreadNotifications = StudentNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->with('attendanceCode')
            ->get();

        // Automatically mark expired/inactive code notifications as read
        $allUnreadNotifications
            ->filter(function ($notification) {
                if ($notification->type !== 'attendance_code' || ! $notification->attendanceCode) {
                    return false; // Don't mark non-attendance-code notifications or ones without codes
                }

                return ! $notification->attendanceCode->isValid(); // Mark as read if expired or inactive
            })
            ->each(function ($notification) {
                $notification->markAsRead();
            });

        // Count only valid unread notifications
        $unreadCount = $allUnreadNotifications
            ->filter(function ($notification) {
                if ($notification->type !== 'attendance_code' || ! $notification->attendanceCode) {
                    return true; // Count non-attendance-code notifications
                }

                return $notification->attendanceCode->isValid(); // Only count valid codes
            })
            ->count();

        // Fetch notifications and filter expired/inactive ones
        $notifications = StudentNotification::where('user_id', $user->id)
            ->with('attendanceCode')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->filter(function ($notification) {
                // Filter out expired/inactive attendance code notifications
                if ($notification->type === 'attendance_code' && $notification->attendanceCode) {
                    return $notification->attendanceCode->isValid();
                }

                return true; // Show all other notifications
            })
            ->take(10)
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'created_at_full' => $notification->created_at->format('M d, Y g:i A'),
                ];
            })
            ->values();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(int $id): JsonResponse
    {
        $notification = StudentNotification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        StudentNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}
