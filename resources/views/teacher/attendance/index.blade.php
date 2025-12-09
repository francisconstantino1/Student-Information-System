@extends('layouts.app')

@section('content')
    @include('layouts.teacher-sidebar')
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

        .teacher-container {
            width: 100%;
            margin: 0;
            margin-left: 0;
            padding: 24px;
            padding-top: 80px;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .teacher-container {
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

        .card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #0046FF;
            box-shadow: 0 0 0 3px rgba(0, 70, 255, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 0.875rem;
            background: #FFFFFF;
            cursor: pointer;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: #0046FF;
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background: #0033CC;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #EF4444;
            color: #FFFFFF;
        }

        .btn-danger:hover {
            background: #DC2626;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.75rem;
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

        .code-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #EFF6FF;
            color: #0046FF;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .code-expired {
            background: #FEE2E2;
            color: #991B1B;
        }

        .code-active {
            background: #D1FAE5;
            color: #065F46;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 1024px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="teacher-container">
        <div class="admin-header">
            <h1>Attendance Control</h1>
            <p>Generate attendance codes and manage student attendance records</p>
        </div>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid-2">
            <div class="card">
                <div class="card-title">Generate Attendance Code</div>
                <form method="POST" action="{{ route('teacher.attendance.generate-code') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="class_session_id">Class Session</label>
                        <select name="class_session_id" id="class_session_id" class="form-select" required>
                            <option value="">Select Class Session</option>
                            @foreach($classSessions as $session)
                                <option value="{{ $session->id }}">{{ $session->name }} ({{ $session->time_range ?? ($session->start_time.' - '.$session->end_time) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="date">Date</label>
                        <input type="date" name="date" id="date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        <small style="color: #6B7280; font-size: 0.75rem; display: block; margin-top: 4px;">Code will automatically expire at the end of the selected session time</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate Code</button>
                </form>
            </div>

            <div class="card">
                <div class="card-title">Recent Codes</div>
                @if($recentCodes->isEmpty())
                    <p style="color: #6B7280; text-align: center; padding: 24px;">No codes generated yet.</p>
                @else
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($recentCodes as $code)
                            <div style="padding: 12px; background: #F9FAFB; border-radius: 8px; border: 1px solid #E5E7EB;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <span class="code-badge {{ $code->isExpired() ? 'code-expired' : 'code-active' }}">
                                        {{ $code->code }}
                                    </span>
                                    @if($code->is_active && !$code->isExpired())
                                        <form method="POST" action="{{ route('teacher.attendance.deactivate-code', $code->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                                        </form>
                                    @endif
                                </div>
                                <div style="font-size: 0.75rem; color: #6B7280;">
                                    <div><strong>Session:</strong> {{ $code->classSession->name ?? 'N/A' }} @if($code->classSession)({{ $code->classSession->time_range ?? ($code->classSession->start_time.' - '.$code->classSession->end_time) }})@endif</div>
                                    <div><strong>Date:</strong> {{ $code->date }}</div>
                                    <div><strong>Expires:</strong> {{ $code->expires_at }}</div>
                                    <div><strong>Status:</strong>
                                        @if(method_exists($code, 'isExpired') && $code->isExpired())
                                            <span style="color: #991B1B;">Expired</span>
                                        @elseif($code->is_active)
                                            <span style="color: #065F46;">Active</span>
                                        @else
                                            <span style="color: #92400E;">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if($attendances->count() > 0)
        <div class="card">
            <div class="card-title">Attendance Records</div>
            <p class="muted" style="margin-bottom:12px;">Student attendance submissions</p>
            <div style="overflow-x: auto;">
                <table id="attendanceRecordsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Institutional ID</th>
                            <th>Student Name</th>
                            <th>Date</th>
                            <th>Session Time</th>
                            <th>Time In</th>
                            <th>Status</th>
                            <th>Code Used</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->user->student_id ?? 'N/A' }}</td>
                                <td>{{ $attendance->user->name }}</td>
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
                                        <span class="code-badge">{{ $attendance->attendanceCode->code }}</span>
                                    @else
                                        <span class="muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view-btn" onclick="openModal('view-attendance-modal-{{ $attendance->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="card" style="margin-top: 24px;">
            <div class="card-title">All Attendance Codes</div>
            <p style="color: #6B7280; font-size: 0.875rem; margin-bottom: 16px;">View all generated codes including deactivated and expired ones</p>
            <div style="overflow-x: auto;">
                <table id="allCodesTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Session</th>
                            <th>Date</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allCodes as $code)
                            <tr>
                                <td>
                                    <span class="code-badge {{ method_exists($code, 'isExpired') && ($code->isExpired() || ! $code->is_active) ? 'code-expired' : 'code-active' }}">
                                        {{ $code->code }}
                                    </span>
                                </td>
                                <td>{{ $code->classSession->name ?? 'N/A' }} @if($code->classSession)({{ $code->classSession->time_range ?? ($code->classSession->start_time.' - '.$code->classSession->end_time) }})@endif</td>
                                <td>{{ $code->date }}</td>
                                <td>{{ $code->expires_at }}</td>
                                <td>
                                    @if(method_exists($code, 'isExpired') && $code->isExpired())
                                        <span style="color: #991B1B; font-weight: 600;">Expired</span>
                                    @elseif($code->is_active)
                                        <span style="color: #065F46; font-weight: 600;">Active</span>
                                    @else
                                        <span style="color: #92400E; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $code->creator->name ?? 'N/A' }}</td>
                                <td>
                                    @if($code->is_active && (!method_exists($code, 'isExpired') || ! $code->isExpired()))
                                        <form method="POST" action="{{ route('teacher.attendance.deactivate-code', $code->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                                        </form>
                                    @else
                                        <span style="color: #6B7280; font-size: 0.875rem;">No actions</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#allCodesTable')) $('#allCodesTable').DataTable().destroy();
            $('#allCodesTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                order: [[2, 'desc']],
                language: {
                    search: "",
                    searchPlaceholder: "Search codes..."
                }
            });

            if ($('#attendanceRecordsTable').length) {
                if ($.fn.DataTable.isDataTable('#attendanceRecordsTable')) $('#attendanceRecordsTable').DataTable().destroy();
                $('#attendanceRecordsTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    order: [[2, 'desc']],
                    language: {
                        search: "",
                        searchPlaceholder: "Search attendance records..."
                    }
                });
            }
        });
    </script>
@endsection

