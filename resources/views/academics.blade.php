@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')
    @include('layouts.datatables')

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

        .academics-container {
            max-width: 1600px;
            margin: 0 auto;
            margin-left: 0;
            padding: 24px;
            padding-top: 80px;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .academics-container {
                padding-top: 70px;
            }
        }

        .academics-header {
            margin-bottom: 32px;
        }

        .academics-header h1 {
            font-size: 2rem;
            color: #0046FF;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: linear-gradient(135deg, #0046FF 0%, #0033CC 100%);
            border-radius: 12px;
            padding: 24px;
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(0, 70, 255, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 70, 255, 0.3);
        }

        .stat-card.secondary {
            background: linear-gradient(135deg, #0046FF 0%, #0033CC 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #0046FF 0%, #0033CC 100%);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #0046FF 0%, #0033CC 100%);
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-description {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        .section-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            margin-bottom: 24px;
        }

        .info-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            height: fit-content;
        }

        .info-card h3 {
            font-size: 1.25rem;
            color: #111827;
            margin-bottom: 16px;
            font-weight: 600;
            padding-bottom: 12px;
            border-bottom: 2px solid #E5E7EB;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #F3F4F6;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6B7280;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .info-value {
            color: #111827;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .section-title {
            font-size: 1.5rem;
            color: #111827;
            margin-bottom: 20px;
            font-weight: 600;
            padding-bottom: 12px;
            border-bottom: 2px solid #E5E7EB;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }

        .schedule-table thead {
            background: linear-gradient(135deg, #0046FF, #0033CC);
            color: #FFFFFF;
        }

        .schedule-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .schedule-table td {
            padding: 12px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 0.9rem;
        }

        .schedule-table tbody tr:hover {
            background: #F9FAFB;
        }

        .curriculum-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .curriculum-year {
            background: #F9FAFB;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #E5E7EB;
        }

        .curriculum-year-title {
            font-size: 1.2rem;
            color: #111827;
            font-weight: 600;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #D1D5DB;
        }

        .subject-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: #FFFFFF;
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid #E5E7EB;
        }

        .subject-code {
            font-weight: 600;
            color: #374151;
        }

        .subject-name {
            flex: 1;
            margin-left: 16px;
            color: #111827;
        }

        .subject-units {
            color: #6B7280;
            font-size: 0.9rem;
        }

        .certificate-section {
            text-align: center;
            padding: 40px;
        }

        .certificate-preview {
            background: #F9FAFB;
            border: 2px dashed #D1D5DB;
            border-radius: 12px;
            padding: 40px;
            margin: 24px 0;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6B7280;
        }

        .btn-download {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #0046FF, #0033CC);
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 70, 255, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 48px;
            color: #6B7280;
        }
    </style>

    <div class="academics-container">
        <div class="academics-header">
            <h1>Class Schedule</h1>
            <p style="color: #6B7280; margin: 0;">View your complete class schedule and academic information</p>
        </div>

        @php
            $totalSubjects = $academics->count();
            $uniqueInstructors = $academics->pluck('instructor')->filter()->unique()->count();
            $uniqueRooms = $academics->pluck('room')->filter()->unique()->count();
            $currentCourse = Auth::user()->course ?? 'N/A';
            $currentYearLevel = Auth::user()->year_level ?? 'N/A';
        @endphp

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Subjects</div>
                <div class="stat-value">{{ $totalSubjects }}</div>
                <div class="stat-description">Enrolled this semester</div>
            </div>
            <div class="stat-card secondary">
                <div class="stat-label">Instructors</div>
                <div class="stat-value">{{ $uniqueInstructors }}</div>
                <div class="stat-description">Teaching your classes</div>
            </div>
            <div class="stat-card warning">
                <div class="stat-label">Classrooms</div>
                <div class="stat-value">{{ $uniqueRooms }}</div>
                <div class="stat-description">Different locations</div>
            </div>
            <div class="stat-card info">
                <div class="stat-label">Course</div>
                <div class="stat-value" style="font-size: 1.5rem;">{{ $currentCourse }}</div>
                <div class="stat-description">{{ $currentYearLevel }}</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid">
            <!-- Schedule Table -->
            <div class="section-card">
                <h2 class="section-title">Class Schedule</h2>
                @if ($academics->isEmpty())
                    <div class="empty-state">
                        <p>No class schedule available.</p>
                    </div>
                @else
                    <table id="scheduleTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Schedule</th>
                                <th>Time</th>
                                <th>Instructor</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($academics as $academic)
                                <tr>
                                    <td>{{ $academic->course ?? 'N/A' }}</td>
                                    <td class="subject-code"><strong>{{ $academic->course_id ?? 'N/A' }}</strong></td>
                                    <td>{{ $academic->subject ?? 'N/A' }}</td>
                                    <td>{{ $academic->schedule ?? 'TBA' }}</td>
                                    <td>{{ $academic->time ?? 'TBA' }}</td>
                                    <td>{{ $academic->instructor ?? 'TBA' }}</td>
                                    <td>{{ $academic->room ?? 'TBA' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @push('scripts')
                    <script>
                        $(document).ready(function() {
                            if ($.fn.DataTable.isDataTable('#scheduleTable')) {
                                $('#scheduleTable').DataTable().destroy();
                            }
                            $('#scheduleTable').DataTable({
                                pageLength: 10,
                                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                order: [[2, 'asc']],
                                language: {
                                    search: "",
                                    searchPlaceholder: "Search schedule..."
                                }
                            });
                        });
                    </script>
                    @endpush
                @endif
            </div>

            <!-- Quick Info Sidebar -->
            <div>
                <div class="info-card">
                    <h3>Quick Information</h3>
                    <div class="info-item">
                        <span class="info-label">Course</span>
                        <span class="info-value">{{ $currentCourse }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Year Level</span>
                        <span class="info-value">{{ $currentYearLevel }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total Subjects</span>
                        <span class="info-value">{{ $totalSubjects }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Instructors</span>
                        <span class="info-value">{{ $uniqueInstructors }}</span>
                    </div>
                </div>

                @if($academics->isNotEmpty())
                    <div class="info-card" style="margin-top: 24px;">
                        <h3>Upcoming Today</h3>
                        @php
                            $today = now()->format('l');
                            $todaySchedule = $academics->filter(function($item) use ($today) {
                                $schedule = strtoupper($item->schedule ?? '');
                                $dayMap = [
                                    'MONDAY' => 'M',
                                    'TUESDAY' => 'T',
                                    'WEDNESDAY' => 'W',
                                    'THURSDAY' => 'TH',
                                    'FRIDAY' => 'F',
                                    'SATURDAY' => 'S',
                                    'SUNDAY' => 'SU'
                                ];
                                $todayLetter = $dayMap[$today] ?? '';
                                return str_contains($schedule, $todayLetter);
                            })->take(3);
                        @endphp
                        @if($todaySchedule->isEmpty())
                            <div style="color: #6B7280; font-size: 0.875rem; padding: 12px 0;">
                                No classes scheduled for today
                            </div>
                        @else
                            @foreach($todaySchedule as $item)
                                <div class="info-item">
                                    <div>
                                        <div style="font-weight: 600; color: #111827; font-size: 0.875rem;">{{ $item->subject ?? 'N/A' }}</div>
                                        <div style="color: #6B7280; font-size: 0.75rem; margin-top: 4px;">{{ $item->time ?? 'TBA' }} • {{ $item->room ?? 'TBA' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

