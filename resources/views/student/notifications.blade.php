@extends('layouts.app')

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #FFFFFF;
            color: #1F2937;
        }

        .notifications-root {
            min-height: 100vh;
            padding: 16px;
            padding-top: 80px;
            background: #FFFFFF;
            margin-left: 0;
            position: relative;
            overflow-x: hidden;
        }

        @media (max-width: 768px) {
            .notifications-root {
                padding-top: 70px;
            }
        }

        .notifications-container {
            width: 100%;
            min-height: calc(100vh - 32px);
            border-radius: 24px;
            overflow: hidden;
            background: #FFFFFF;
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.1);
        }

        .notifications-content {
            padding: 24px;
            position: relative;
            overflow: visible;
        }

        .flash-messages-container {
            position: relative;
            width: 100%;
            margin-bottom: 0;
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-top: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #E5E7EB;
        }

        .notifications-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
        }

        .mark-all-read-btn {
            background: #3B82F6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .mark-all-read-btn:hover {
            background: #2563EB;
        }

        .notification-item {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
        }

        .notification-item:hover {
            background: #F3F4F6;
            transform: translateX(4px);
        }

        .notification-item.unread {
            background: #EFF6FF;
            border-left: 4px solid #3B82F6;
        }

        .notification-item.unread::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background: #3B82F6;
            border-radius: 50%;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 8px;
        }

        .notification-title {
            font-weight: 600;
            color: #1F2937;
            font-size: 1rem;
            margin: 0;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #6B7280;
        }

        .notification-message {
            color: #4B5563;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }

        .notification-type {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .notification-type.enrollment {
            background: #D1FAE5;
            color: #065F46;
        }

        .notification-type.attendance_code {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6B7280;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .flash-message {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 500;
            animation: slideDown 0.3s ease-out;
            position: static;
            width: 100%;
            box-sizing: border-box;
        }

        .flash-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .flash-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 24px;
            gap: 8px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            text-decoration: none;
            color: #4B5563;
        }

        .pagination a:hover {
            background: #F3F4F6;
        }

        .pagination .active {
            background: #3B82F6;
            color: white;
            border-color: #3B82F6;
        }

        @media (max-width: 768px) {
            .notifications-root {
                margin-left: 70px;
                padding: 8px;
            }

            .notifications-content {
                padding: 16px;
            }

            .notifications-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .notifications-header h1 {
                font-size: 1.5rem;
            }

            .notification-item {
                padding: 12px;
            }

            .notification-header {
                flex-direction: column;
                gap: 4px;
            }

            .mark-all-read-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .notifications-root {
                padding: 4px;
            }

            .notifications-container {
                border-radius: 16px;
            }

            .notifications-content {
                padding: 12px;
            }

            .notifications-header h1 {
                font-size: 1.25rem;
            }

            .notification-item {
                padding: 10px;
            }

            .notification-title {
                font-size: 0.9rem;
            }

            .notification-message {
                font-size: 0.85rem;
            }
        }
    </style>

    @include('layouts.sidebar')

    <div class="notifications-root">
        <div class="notifications-container">
            <div class="notifications-content">
                <div class="flash-messages-container">
                    @if (session('success'))
                        <div class="flash-message flash-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="flash-message flash-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="flash-message flash-error">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="notifications-header">
                    <h1>Notifications</h1>
                    <button class="mark-all-read-btn" id="markAllReadBtn">Mark All as Read</button>
                </div>

                @if($notifications->count() > 0)
                    <div id="notificationsList">
                        @foreach($notifications as $notification)
                            @php
                                // Skip expired/inactive attendance code notifications
                                if ($notification->type === 'attendance_code' && $notification->attendanceCode && !$notification->attendanceCode->isValid()) {
                                    continue;
                                }
                            @endphp
                            <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}" data-id="{{ $notification->id }}">
                                <div class="notification-header">
                                    <h3 class="notification-title">{{ $notification->title }}</h3>
                                    <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="notification-message">{{ $notification->message }}</p>
                                <span class="notification-type {{ $notification->type }}">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pagination">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <h3>No notifications yet</h3>
                        <p>You'll see notifications here when they arrive.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Mark notification as read on click
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                if (this.classList.contains('unread')) {
                    fetch(`/api/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        this.classList.remove('unread');
                    });
                }
            });
        });

        // Mark all as read
        document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
            fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('unread');
                });
            });
        });

        // Auto-hide is handled globally in layouts/app.blade.php
    </script>
@endsection

