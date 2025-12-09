@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

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

        .admin-container {
            width: 100%;
            margin: 0;
            margin-left: 0;
            padding: 24px;
            padding-top: 80px;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
        }

        .admin-header {
            margin-bottom: 32px;
        }

        .admin-header h1 {
            font-size: 2rem;
            color: #0046FF;
            margin-bottom: 8px;
        }

        .admin-header p {
            color: #6B7280;
        }

        .metrics-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            text-align: center;
        }

        .metrics-card-title {
            font-size: 1rem;
            color: #6B7280;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .metrics-card-value {
            font-size: 3.5rem;
            font-weight: 700;
            color: #0046FF;
            margin-bottom: 8px;
        }

        .metrics-card-label {
            font-size: 1.125rem;
            color: #1F2937;
            font-weight: 600;
        }

        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            gap: 16px;
        }

        .top-bar-left h1 {
            font-size: 1.4rem;
            margin: 0;
            color: #1C6EA4;
            font-weight: 600;
        }

        .top-bar-left p {
            margin: 4px 0 0 0;
            color: #6B7280;
            font-size: 0.95rem;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .notification-icon {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            background: #FFFFFF;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .notification-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .notification-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #EF4444;
            color: #FFFFFF;
            border-radius: 999px;
            padding: 2px 6px;
            font-size: 0.7rem;
            font-weight: 700;
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

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 16px;
            align-items: stretch;
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

        .calendar-card {
            grid-column: span 2;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .calendar-nav {
            display: inline-flex;
            align-items: center;
            gap: 8px;
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

        .calendar-month {
            font-weight: 600;
            color: #1C6EA4;
            font-size: 0.85rem;
            flex: 1;
            text-align: center;
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

        .date-number {
            font-weight: 600;
            color: #111827;
            font-size: 0.75rem;
        }

        .calendar-marker {
            display: inline-flex;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.65rem;
            margin-top: 2px;
        }

        .calendar-legend {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #4B5563;
        }

    </style>

    <div class="admin-container">
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>Admin Dashboard</h1>
                <p>Overview of key system metrics and schedules.</p>
            </div>
            <div class="top-bar-right">
                <div class="clock-widget" id="clockWidget">
                    <div class="clock-time">--:--</div>
                    <div class="clock-date">-- -- ----</div>
                </div>
                <a href="{{ route('notifications') }}" class="notification-icon" id="dashboardNotificationIcon" title="Notifications" style="text-decoration: none; color: inherit;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="notification-badge" id="dashboardNotificationBadge" style="display: none;">0</span>
                </a>
            </div>
        </div>

        <div class="cards-container" style="margin-bottom: 16px;">
            <section class="card" style="grid-column: span 2;">
                <div class="card-title">Enrollment Summary</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; align-items: stretch;">
                    <div class="metrics-card" style="padding: 20px; text-align: left;">
                        <div class="metrics-card-title">Currently Enrolled</div>
                        <div class="metrics-card-value" style="margin: 0;">{{ number_format($totalEnrolledStudents) }}</div>
                        <div class="card-subtitle">Active students</div>
                    </div>
                    <div class="metrics-card" style="padding: 20px; text-align: left;">
                        <div class="metrics-card-title">Teachers</div>
                        <div class="metrics-card-value" style="margin: 0; color: #111827;">{{ number_format($totalTeachers) }}</div>
                        <div class="card-subtitle">Active teachers</div>
                    </div>
                    <div class="metrics-card" style="padding: 20px; text-align: left;">
                        <div class="metrics-card-title">Approved</div>
                        <div class="metrics-card-value" style="margin: 0; color: #10B981;">{{ number_format($approvedEnrollments) }}</div>
                        <div class="card-subtitle">Approved requests</div>
                    </div>
                    <div class="metrics-card" style="padding: 20px; text-align: left;">
                        <div class="metrics-card-title">Pending</div>
                        <div class="metrics-card-value" style="margin: 0; color: #F59E0B;">{{ number_format($pendingEnrollments) }}</div>
                        <div class="card-subtitle">Awaiting review</div>
                    </div>
                    <div class="metrics-card" style="padding: 20px; text-align: left;">
                        <div class="metrics-card-title">Rejected</div>
                        <div class="metrics-card-value" style="margin: 0; color: #EF4444;">{{ number_format($rejectedEnrollments) }}</div>
                        <div class="card-subtitle">Marked rejected</div>
                    </div>
                    <div class="metrics-card" style="padding: 20px; text-align: left;">
                        <div class="metrics-card-title">Total Requests</div>
                        <div class="metrics-card-value" style="margin: 0; color: #1C6EA4;">{{ number_format($totalEnrollments) }}</div>
                        <div class="card-subtitle">All statuses</div>
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
                        <a href="{{ route('admin.dashboard', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="calendar-nav-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </a>
                        <div class="calendar-month">
                            {{ isset($selectedDate) ? $selectedDate->format('F Y') : Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                        </div>
                        <a href="{{ route('admin.dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="calendar-nav-btn">
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
@endsection

@push('scripts')
<script>
    (function () {
        const clockWidget = document.getElementById('clockWidget');
        const clockTime = clockWidget?.querySelector('.clock-time');
        const clockDate = clockWidget?.querySelector('.clock-date');

        function pad(n) { return n.toString().padStart(2, '0'); }

        function updateClock() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = pad(now.getMinutes());
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const displayHour = hours % 12 || 12;

            if (clockTime) {
                clockTime.textContent = `${displayHour}:${minutes} ${ampm}`;
            }

            if (clockDate) {
                const options = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
                clockDate.textContent = now.toLocaleDateString(undefined, options);
            }
        }

        if (clockWidget) {
            updateClock();
            setInterval(updateClock, 1000);
        }
    })();
</script>
@endpush