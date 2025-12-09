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
            background: #F9FAFB;
            color: #1F2937;
        }

        .dashboard-root {
            margin-left: 0;
            padding: 24px;
            padding-top: 80px;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .dashboard-root {
                padding-top: 70px;
            }
        }

        .dashboard-container {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-header h1 {
            font-size: 2rem;
            color: #0046FF;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #6B7280;
        }

        .success-message {
            background: #D1FAE5;
            color: #065F46;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #A7F3D0;
        }

        .error-message {
            background: #FEE2E2;
            color: #991B1B;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #FCA5A5;
        }

        .code-input-card {
            background: linear-gradient(135deg, #0046FF, #0033CC);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            color: #FFFFFF;
        }

        .code-input-card h2 {
            margin: 0 0 8px 0;
            font-size: 1.5rem;
        }

        .code-input-card p {
            margin: 0 0 24px 0;
            opacity: 0.9;
        }

        .code-form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        .code-input-group {
            flex: 1;
        }

        .code-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 8px;
            opacity: 0.9;
        }

        .code-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            font-size: 1.25rem;
            font-weight: 600;
            text-align: center;
            letter-spacing: 4px;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            font-family: 'Courier New', monospace;
        }

        .code-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 2px;
        }

        .code-input:focus {
            outline: none;
            border-color: #FFFFFF;
            background: rgba(255, 255, 255, 0.15);
        }

        .btn-submit {
            padding: 14px 32px;
            background: #FFFFFF;
            color: #0046FF;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .records-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #F9FAFB;
            border-bottom: 2px solid #E5E7EB;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            color: #374151;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 0.875rem;
        }

        tbody tr:hover {
            background: #F9FAFB;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-present {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-absent {
            background: #FEE2E2;
            color: #991B1B;
        }

        .status-late {
            background: #FEF3C7;
            color: #92400E;
        }

        .empty-state {
            text-align: center;
            padding: 48px;
            color: #6B7280;
        }
    </style>

    <div class="dashboard-root">
        <div class="dashboard-container">
            <div class="page-header">
                <h1>Attendance</h1>
                <p>Enter your attendance code or view your attendance records</p>
            </div>

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Notifications Section -->
            @if($unreadNotifications->isNotEmpty())
                <div style="background: #EFF6FF; border: 2px solid #0046FF; border-radius: 16px; padding: 24px; margin-bottom: 24px;">
                    <h3 style="margin: 0 0 16px 0; color: #0046FF; font-size: 1.25rem;">📬 New Attendance Codes</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($unreadNotifications as $notification)
                            <div style="background: #FFFFFF; border-radius: 8px; padding: 16px; border-left: 4px solid #0046FF;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                    <div style="flex: 1;">
                                        <h4 style="margin: 0 0 4px 0; color: #1F2937; font-size: 1rem;">{{ $notification->title }}</h4>
                                        <p style="margin: 0; color: #6B7280; font-size: 0.875rem;">{{ $notification->message }}</p>
                                        @if($notification->attendanceCode)
                                            <div style="margin-top: 12px; padding: 12px; background: #F9FAFB; border-radius: 6px;">
                                                <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Your Code:</div>
                                                <div style="font-size: 1.5rem; font-weight: bold; color: #0046FF; letter-spacing: 4px; font-family: 'Courier New', monospace;">
                                                    {{ $notification->attendanceCode->code }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="background: #0046FF; color: #FFFFFF; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; cursor: pointer;">Mark Read</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Active Session Display -->
            @if($activeSession)
                <div style="background: linear-gradient(135deg, #10B981, #059669); border-radius: 16px; padding: 24px; margin-bottom: 24px; color: #FFFFFF;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="font-size: 2rem;">⏰</div>
                        <div>
                            <h3 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 600;">Active Session</h3>
                            <p style="margin: 0; opacity: 0.9; font-size: 1rem;">{{ $activeSession->name }}: {{ $activeSession->time_range }}</p>
                        </div>
                    </div>
                    <p style="margin: 8px 0 0 0; font-size: 0.875rem; opacity: 0.9;">
                        You are currently enrolled in this session. Enter your attendance code below to mark your attendance.
                    </p>
                </div>
            @endif

            <!-- Code Input Form -->
            <div class="code-input-card">
                <h2>Enter Attendance Code</h2>
                <p>Enter the 6-character code provided by your instructor</p>
                <form method="POST" action="{{ route('attendance.submit-code') }}" class="code-form">
                    @csrf
                    <div class="code-input-group">
                        <label class="code-label" for="code">Attendance Code</label>
                        <input 
                            type="text" 
                            name="code" 
                            id="code" 
                            class="code-input" 
                            placeholder="ABC123" 
                            maxlength="6" 
                            required
                            autocomplete="off"
                            style="text-transform: uppercase;"
                        >
                    </div>
                    <button type="submit" class="btn-submit">Submit</button>
                </form>
            </div>

            <!-- Attendance Records -->
            <div class="records-card">
                <div class="card-title">Your Attendance Records</div>
                @if($attendances->isEmpty())
                    <div class="empty-state">
                        <p>No attendance records found.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table id="studentAttendanceTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Session Time</th>
                                    <th>Time In</th>
                                    <th>Status</th>
                                    <th>Code Used</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date->format('M d, Y') }}</td>
                                        <td>{{ $attendance->attendanceCode && $attendance->attendanceCode->classSession ? $attendance->attendanceCode->classSession->time_range : 'N/A' }}</td>
                                        <td>{{ $attendance->time_in ?? 'N/A' }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $attendance->status }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($attendance->attendanceCode)
                                                <span class="code-badge" style="display: inline-block; padding: 4px 8px; background: #EFF6FF; color: #0046FF; border-radius: 6px; font-family: 'Courier New', monospace; font-weight: 600; font-size: 0.875rem;">{{ $attendance->attendanceCode->code }}</span>
                                            @else
                                                <span style="color: #6B7280;">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('#studentAttendanceTable').DataTable({
                                pageLength: 10,
                                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                order: [[0, 'desc']],
                                language: {
                                    search: "",
                                    searchPlaceholder: "Search records..."
                                }
                            });
                        });
                    </script>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto-uppercase and limit to 6 characters
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 6);
        });
    </script>
@endsection
