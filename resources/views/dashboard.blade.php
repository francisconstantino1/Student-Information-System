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
            transition: background 0.3s ease, color 0.3s ease;
        }

        body.dark-mode {
            background: #111827;
            color: #E5E7EB;
        }

        .dashboard-root {
            min-height: 100vh;
            padding: 16px;
            padding-top: 80px;
            background: #FFFFFF;
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out, background 0.3s ease;
        }

        body.dark-mode .dashboard-root {
            background: #111827;
        }

        @media (max-width: 768px) {
            .dashboard-root {
                padding-top: 70px;
            }
        }

        .dashboard-container {
            width: 100%;
            min-height: calc(100vh - 32px);
            border-radius: 24px;
            overflow: hidden;
            background: #FFFFFF;
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .main-content {
            background: #FFFFFF;
            position: relative;
            overflow-y: auto;
            padding: 18px 22px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.3), transparent 50%);
            pointer-events: none;
        }

        .main-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .top-bar-left h1 {
            font-size: 1.4rem;
            margin: 0;
            color: #1C6EA4;
            font-weight: 600;
        }

        .top-bar-left p {
            font-size: 0.85rem;
            color: #6B7280;
            margin-top: 4px;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .clock-widget {
            padding: 8px 14px;
            border-radius: 8px;
            background-color: #FFFFFF;
            color: #1F2937;
            font-size: 0.8rem;
            display: flex;
            flex-direction: column;
            min-width: 135px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .clock-time {
            font-weight: 600;
            color: #111827;
        }

        .clock-date {
            font-size: 0.7rem;
            color: #6B7280;
        }

        .notification-icon {
            position: relative;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .notification-icon:hover {
            transform: translateY(-2px);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            background-color: #EF4444;
            color: #FFFFFF;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .avatar-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            background: linear-gradient(135deg, #1C6EA4, #155A8A);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .cards-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-auto-rows: minmax(0, auto);
            gap: 16px;
            flex: 1;
        }

        .card {
            border-radius: 16px;
            background: #FFFFFF;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 20px;
            border: 1px solid #E5E7EB;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-color: rgba(28, 110, 164, 0.3);
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1C6EA4;
            margin-bottom: 8px;
        }

        .card-subtitle {
            font-size: 0.8rem;
            color: #6B7280;
            margin-bottom: 12px;
        }

        .tuition-card {
            grid-column: 1;
        }

        .tuition-inner {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 12px;
        }

        .circle-graph {
            width: 140px;
            height: 140px;
            border-radius: 999px;
            background: conic-gradient(#1C6EA4 0 100%, #E5E7EB 100% 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
        }

        .circle-graph::after {
            content: "";
            position: absolute;
            inset: 16px;
            border-radius: inherit;
            background: #FFFFFF;
        }

        .circle-label {
            position: relative;
            text-align: center;
            z-index: 1;
        }

        .circle-label-main {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1C6EA4;
        }

        .circle-label-sub {
            font-size: 0.7rem;
            color: #6B7280;
        }

        .enrollment-meta {
            font-size: 0.85rem;
            color: #4B5563;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .enrollment-meta strong {
            color: #111827;
        }

        .student-info-card {
            grid-row: span 2;
        }

        .calendar-card {
            grid-column: 2;
            grid-row: span 2;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .calendar-month {
            font-size: 0.85rem;
            color: #1C6EA4;
            font-weight: 600;
            flex: 1;
            text-align: center;
        }

        .calendar-nav {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .calendar-nav-btn {
            padding: 8px 12px;
            background: #1C6EA4;
            color: #FFFFFF;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .calendar-nav-btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
        }

        .calendar-nav-btn:hover {
            background: #155A8A;
            transform: scale(1.05);
        }

        .calendar-nav-btn:active {
            transform: scale(0.95);
        }

        body.dark-mode .card {
            background: rgba(31, 41, 55, 0.95);
            border-color: rgba(255, 255, 255, 0.1);
            color: #E5E7EB;
        }

        body.dark-mode .card-title {
            color: #FFFFFF;
        }

        body.dark-mode .card-subtitle {
            color: #9CA3AF;
        }

        body.dark-mode .top-bar-left h1 {
            color: #FFFFFF;
        }

        body.dark-mode .top-bar-left p {
            color: #9CA3AF;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            margin-top: 8px;
            font-size: 0.75rem;
        }

        .calendar-day-header {
            text-align: center;
            color: #6B7280;
            font-weight: 600;
            padding: 6px 0;
        }

        .calendar-cell {
            min-height: 60px;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            background-color: #F9FAFB;
            padding: 6px 4px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .calendar-cell .date-number {
            font-size: 0.75rem;
            font-weight: 600;
            color: #111827;
        }

        .calendar-marker {
            display: inline-flex;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.65rem;
            margin-top: 2px;
        }

        .marker-event {
            background-color: #1C6EA4;
            color: #FFFFFF;
        }

        .marker-exam {
            background-color: #4A90E2;
            color: #FFFFFF;
        }

        .marker-holiday {
            border: 1px solid #D1D5DB;
            background-color: #FFFFFF;
            color: #6B7280;
        }

        .calendar-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 12px;
            font-size: 0.7rem;
            color: #6B7280;
        }


        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .student-info-card {
            grid-column: 1;
            position: relative;
        }

        .announcements-card {
            grid-column: 1;
            max-height: 500px;
            overflow-y: auto;
        }

        .crud-buttons {
            display: flex;
            gap: 6px;
        }


        .crud-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .crud-btn-edit {
            background: #1C6EA4;
            color: #FFFFFF;
        }

        .crud-btn-edit:hover {
            background: #155A8A;
            transform: scale(1.05);
        }

        .crud-btn-save {
            background: #10B981;
            color: #FFFFFF;
        }

        .crud-btn-save:hover {
            background: #059669;
        }

        .crud-btn-cancel {
            background: #6B7280;
            color: #FFFFFF;
        }

        .crud-btn-cancel:hover {
            background: #4B5563;
        }

        .info-value.editable {
            padding: 4px 8px;
            border: 1px solid #D1D5DB;
            border-radius: 4px;
            background: #FFFFFF;
            cursor: text;
        }

        .info-value.editable:focus {
            outline: none;
            border-color: #1C6EA4;
            box-shadow: 0 0 0 2px rgba(28, 110, 164, 0.1);
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 24px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            color: #1C6EA4;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6B7280;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .modal-close:hover {
            background: #F3F4F6;
            color: #111827;
        }

        .student-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #E5E7EB;
        }

        .student-avatar {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            background: linear-gradient(135deg, #1C6EA4, #155A8A);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .student-name {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
        }

        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-top: 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 0.75rem;
            color: #6B7280;
            font-weight: 500;
        }

        .info-value {
            font-size: 0.85rem;
            color: #111827;
            font-weight: 500;
        }

        .status-approved {
            color: #10B981;
            font-weight: 600;
        }

        .status-rejected {
            color: #EF4444;
            font-weight: 600;
        }

        .status-pending {
            color: #F59E0B;
            font-weight: 600;
        }


        @media (max-width: 1200px) {
            .dashboard-container {
                height: auto;
                max-height: none;
            }

            .cards-container {
                grid-template-columns: 1fr;
            }

            .calendar-card {
                grid-row: auto;
            }
        }

        @media (max-width: 1024px) {
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .top-bar-right {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .main-content {
                padding: 12px;
            }
        }

        @media (max-width: 768px) {
            .enrollment-inner {
                flex-direction: column;
                align-items: center;
            }

            .calendar-grid {
                gap: 4px;
            }

            .calendar-cell {
                min-height: 50px;
                padding: 4px 2px;
            }

            .calendar-marker {
                font-size: 0.6rem;
                padding: 1px 4px;
            }

            .crud-buttons {
                position: static;
                margin-top: 12px;
                justify-content: flex-end;
            }

            .calendar-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .calendar-nav {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 480px) {
            .dashboard-root {
                padding: 8px;
            }

            .card {
                padding: 16px;
            }

            .clock-widget {
                min-width: 100px;
                font-size: 0.75rem;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    @include('layouts.sidebar')

    <div class="dashboard-root" id="dashboardRoot">
        <div class="dashboard-container">

            <!-- MAIN CONTENT -->
            <main class="main-content">
                <div class="main-overlay"></div>
                <div class="main-inner">
                    <div class="top-bar">
                        <div class="top-bar-left">
                            <h1>Dashboard</h1>
                            <p>Overview of your student activity and campus schedule.</p>
                        </div>

                        <div class="top-bar-right">
                            <div class="clock-widget" id="clockWidget">
                                <div class="clock-time">--:--</div>
                                <div class="clock-date">-- -- ----</div>
                            </div>

                            <a href="{{ route('notifications') }}" class="notification-icon" id="dashboardNotificationIcon" style="text-decoration: none; color: inherit;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                                <span class="notification-badge" id="dashboardNotificationBadge" style="display: none;">0</span>
                            </a>

                        </div>
                    </div>


                    <div class="cards-container">
                        <!-- CARD 1 – QUICK STUDENT INFO (moved into first slot) -->
                        <section class="card student-info-card">
                            <div class="card-title">Quick Student Info</div>
                            <div class="student-header">
                                <div class="student-avatar">
                                    {{ strtoupper(mb_substr($user->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="student-name">{{ $user->name ?? 'Student' }}</div>
                                    <div class="card-subtitle">Institutional ID: {{ $user->student_id ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="student-info-grid">
                                <div class="info-item">
                                    <span class="info-label">Enrollment Status</span>
                                    @php
                                        $statusClass = $enrollmentStatus == 'approved' ? 'status-approved' : ($enrollmentStatus == 'rejected' ? 'status-rejected' : 'status-pending');
                                    @endphp
                                    <span class="info-value {{ $statusClass }}">
                                        @if($enrollmentStatus == 'approved')
                                            ✓ ENROLLED
                                        @elseif($enrollmentStatus == 'rejected')
                                            ✗ REJECTED
                                        @else
                                            ⏳ PENDING
                                        @endif
                                    </span>
                                </div>
                                @if($user->section)
                                    <div class="info-item">
                                        <span class="info-label">Section</span>
                                        <span class="info-value">{{ $user->section->name ?? 'Not Assigned' }}</span>
                                    </div>
                                @endif
                                <div class="info-item">
                                    <span class="info-label">Course</span>
                                    <span class="info-value">{{ $user->course ?? 'Not Set' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Year Level</span>
                                    <span class="info-value">{{ $user->year_level ?? 'Not Set' }}</span>
                                </div>
                            </div>
                        </section>

                        <!-- CARD 2 – SCHOOL CALENDAR -->
                        @php
                            use Illuminate\Support\Carbon;

                            $startOfMonth = Carbon::create($currentYear, $currentMonth, 1);
                            $endOfMonth = $startOfMonth->copy()->endOfMonth();
                            $startWeekday = $startOfMonth->dayOfWeekIso;
                        @endphp

                        <section class="card calendar-card">
                            <div class="calendar-header">
                                <div class="card-title">School Calendar</div>
                                <div class="calendar-nav">
                                    <a href="{{ route('dashboard', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="calendar-nav-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                    </a>
                                    <div class="calendar-month">
                                        {{ isset($selectedDate) ? $selectedDate->format('F Y') : Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                                    </div>
                                    <a href="{{ route('dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="calendar-nav-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="calendar-grid">
                                <div class="calendar-day-header">Mon</div>
                                <div class="calendar-day-header">Tue</div>
                                <div class="calendar-day-header">Wed</div>
                                <div class="calendar-day-header">Thu</div>
                                <div class="calendar-day-header">Fri</div>
                                <div class="calendar-day-header">Sat</div>
                                <div class="calendar-day-header">Sun</div>

                                @for ($i = 1; $i < $startWeekday; $i++)
                                    <div></div>
                                @endfor

                                @for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay())
                                    @php
                                        $dateKey = $date->format('Y-m-d');
                                        $dayEvents = $eventsByDate[$dateKey] ?? collect();
                                    @endphp
                                    <div class="calendar-cell">
                                        <div class="date-number">{{ $date->day }}</div>
                                        <div>
                                            @foreach ($dayEvents as $event)
                                                @php
                                                    $markerClass = match ($event->type) {
                                                        'exam' => 'marker-exam',
                                                        'holiday' => 'marker-holiday',
                                                        default => 'marker-event',
                                                    };
                                                @endphp
                                                <span class="calendar-marker {{ $markerClass }}">
                                                    {{ \Illuminate\Support\Str::limit($event->title, 8) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor
                            </div>

                        </section>

                    </div>
                </div>
            </main>
        </div>
    </div>

    @if (session('success'))
        <div class="flash-message success-message" style="position: fixed; top: 20px; right: 20px; background: #D1FAE5; color: #065F46; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1001; max-width: 400px; border-left: 4px solid #10B981; font-weight: 500;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 20px;">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <script>
        (function () {
            const clockWidget = document.getElementById('clockWidget');

            function updateClock() {
                if (!clockWidget) {
                    return;
                }

                const now = new Date();
                const timeEl = clockWidget.querySelector('.clock-time');
                const dateEl = clockWidget.querySelector('.clock-date');

                const hours = now.getHours();
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const displayHour = hours % 12 || 12;

                if (timeEl) {
                    timeEl.textContent = `${displayHour}:${minutes} ${ampm}`;
                }

                if (dateEl) {
                const date = now.toLocaleDateString(undefined, {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
                    dateEl.textContent = date;
                }
            }

            updateClock();
            setInterval(updateClock, 1000);
        })();

        // Real-time Notification Polling
        (function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            let lastNotificationCount = 0;
            let lastNotificationId = null;

            function updateNotificationBadge(count) {
                const sidebarBadge = document.getElementById('notificationBadge');
                const dashboardBadge = document.getElementById('dashboardNotificationBadge');
                
                if (count > 0) {
                    if (sidebarBadge) {
                        sidebarBadge.textContent = count > 99 ? '99+' : count;
                        sidebarBadge.style.display = 'flex';
                    }
                    if (dashboardBadge) {
                        dashboardBadge.textContent = count > 99 ? '99+' : count;
                        dashboardBadge.style.display = 'flex';
                    }
                } else {
                    if (sidebarBadge) sidebarBadge.style.display = 'none';
                    if (dashboardBadge) dashboardBadge.style.display = 'none';
                }
            }

            function showNotificationAlert(notification) {
                // Pop-up announcement removed; do nothing
                return;
            }

            function fetchNotifications() {
                fetch('/api/notifications', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.unread_count !== undefined) {
                        // Check if there's a new notification
                        if (data.notifications && data.notifications.length > 0) {
                            const latestNotification = data.notifications[0];
                            if (latestNotification.id !== lastNotificationId && latestNotification.id) {
                                lastNotificationId = latestNotification.id;
                                if (!latestNotification.is_read) {
                                    showNotificationAlert(latestNotification);
                                }
                            }
                        }
                        
                        // Update badge if count changed
                        if (data.unread_count !== lastNotificationCount) {
                            lastNotificationCount = data.unread_count;
                            updateNotificationBadge(data.unread_count);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                });
            }

            // Initial fetch
            fetchNotifications();
            
            // Poll every 2 seconds for real-time updates
            setInterval(fetchNotifications, 2000);
        })();

    </script>
@endsection
