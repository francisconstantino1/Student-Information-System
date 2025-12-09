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

        .dashboard-root {
            min-height: 100vh;
            padding: 16px;
            padding-top: 80px;
            background: #FFFFFF;
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out, background 0.3s ease;
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

        .cards-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-auto-rows: 1fr;
            gap: 16px;
            flex: 1;
            align-items: stretch;
        }

        .card {
            border-radius: 16px;
            background: #FFFFFF;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 20px;
            border: 1px solid #E5E7EB;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
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

        .teacher-info-card {
            grid-column: 1;
            grid-row: span 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #FFFFFF 0%, #F9FBFF 100%);
            border: 1px solid #E5EAF2;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
        }

        .teacher-info-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(28, 110, 164, 0.08), transparent 40%),
                        radial-gradient(circle at 80% 0%, rgba(17, 94, 164, 0.06), transparent 40%);
            pointer-events: none;
        }

        .teacher-info-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            gap: 16px;
            padding-bottom: 4px;
        }

        .teacher-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #E5E7EB;
        }

        .teacher-avatar {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            background: linear-gradient(135deg, #1C6EA4, #0E5AA8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 10px 20px rgba(12, 74, 110, 0.25);
        }

        .teacher-name {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
        }

        .teacher-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 10px;
            background: #F0F7FF;
            border: 1px solid #DCEAFE;
            color: #0F4FA8;
            font-weight: 600;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .info-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #1C6EA4;
        }

        .info-label {
            font-size: 0.75rem;
            color: #6B7280;
            font-weight: 500;
        }

        .info-value {
            font-size: 0.9rem;
            color: #111827;
            font-weight: 600;
            background: #F3F6FB;
            border: 1px solid #E0E7F1;
            border-radius: 8px;
            padding: 10px 12px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .calendar-card {
            grid-column: 2;
            grid-row: span 2;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .announcements-card {
            grid-column: 1;
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
                grid-column: 1;
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

    @include('layouts.teacher-sidebar')

    <div class="dashboard-root" id="dashboardRoot">
        <div class="dashboard-container">
            <main class="main-content">
                <div class="main-overlay"></div>
                <div class="main-inner">
                    <div class="top-bar">
                        <div class="top-bar-left">
                            <h1>Teacher Dashboard</h1>
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
                        @php
                            $user = auth()->user();
                        @endphp

                        <section class="card teacher-info-card">
                            <div class="teacher-info-inner">
                                <div class="card-title">Quick Teacher Info</div>
                                <div class="teacher-header">
                                    <div class="teacher-avatar">
                                        {{ strtoupper(mb_substr($user->name ?? 'T', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="teacher-name">{{ $user->name ?? 'Teacher' }}</div>
                                        <div class="card-subtitle">{{ $user->email ?? '' }}</div>
                                    </div>
                                </div>
                                <div class="teacher-info-grid">
                                    <div class="info-item">
                                        <span class="info-label">Course Teaching</span>
                                        <span class="info-value">{{ $user->course ?? 'Not Set' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Position</span>
                                        <span class="info-value">{{ $user->position ?? 'Not Set' }}</span>
                                    </div>
                                </div>
                            </div>
                        </section>

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
                                    <a href="{{ route('teacher.dashboard', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="calendar-nav-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                    </a>
                                    <div class="calendar-month">
                                        {{ isset($selectedDate) ? $selectedDate->format('F Y') : Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                                    </div>
                                    <a href="{{ route('teacher.dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="calendar-nav-btn">
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
    </script>
@endsection

