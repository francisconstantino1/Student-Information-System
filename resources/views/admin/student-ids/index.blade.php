@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')
    @include('layouts.datatables')

    <style>
        .admin-container {
            margin-left: 0;
            padding: 24px;
            padding-top: 80px;
            min-height: 100vh;
            background: #F9FAFB;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
        }

        .admin-header {
            margin-bottom: 24px;
        }

        .admin-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1F2937;
            margin: 0 0 8px 0;
        }

        .admin-header p {
            color: #6B7280;
            margin: 0;
        }

        .card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
            padding: 10px 14px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background: #3B82F6;
            color: #FFFFFF;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background: #2563EB;
        }

        .btn-danger {
            background: #EF4444;
            color: #FFFFFF;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #DC2626;
        }


        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-available {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-used {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .status-assigned {
            background: #FEF3C7;
            color: #92400E;
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

        .empty-state {
            text-align: center;
            padding: 48px;
            color: #6B7280;
        }
    </style>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Institutional ID Management</h1>
            <p>Create and manage Institutional IDs for student registration</p>
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

        <!-- Create Institutional ID Form -->
        <div class="card">
            <div class="card-title">Create Institutional ID(s)</div>
            <form method="POST" action="{{ route('admin.student-ids.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="student_id">Institutional ID Prefix</label>
                    <input 
                        type="text" 
                        id="student_id" 
                        name="student_id" 
                        class="form-input" 
                        placeholder="e.g. 2025-001" 
                        required
                        value="{{ old('student_id') }}"
                    >
                    <small style="color: #6B7280; font-size: 0.75rem; display: block; margin-top: 4px;">
                        Enter a prefix. If creating multiple IDs, they will be numbered automatically (e.g., 2025-001-001, 2025-001-002).
                    </small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="count">Number of IDs to Create</label>
                    <input 
                        type="number" 
                        id="count" 
                        name="count" 
                        class="form-input" 
                        min="1" 
                        max="100" 
                        value="1"
                        required
                    >
                    <small style="color: #6B7280; font-size: 0.75rem; display: block; margin-top: 4px;">
                        Create multiple Institutional IDs at once (1-100).
                    </small>
                </div>
                <button type="submit" class="btn-primary">Create Institutional ID(s)</button>
            </form>
        </div>

        <!-- Institutional IDs List -->
        <div class="card">
            <div class="card-title">All Institutional IDs</div>
            <div style="overflow-x: auto;">
                <table id="studentIdsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Institutional ID</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentIds as $studentId)
                                <tr>
                                    <td>
                                        <strong>{{ $studentId->student_id }}</strong>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $studentId->status }}">
                                            {{ ucfirst($studentId->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($studentId->assignedUser)
                                            {{ $studentId->assignedUser->name }}
                                        @else
                                            <span style="color: #9CA3AF;">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($studentId->creator)
                                            {{ $studentId->creator->name }}
                                        @else
                                            <span style="color: #9CA3AF;">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $studentId->created_at->format('M d, Y g:i A') }}
                                    </td>
                                    <td>
                                        @if($studentId->status !== 'used')
                                            <div class="action-buttons">
                                                <button class="action-btn view-btn" onclick="openModal('view-student-id-modal-{{ $studentId->id }}')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="action-btn delete-btn" data-record-id="{{ $studentId->id }}" data-record-name="{{ $studentId->student_id }}" data-delete-url="{{ route('admin.student-ids.destroy', $studentId->id) }}" data-record-type="Institutional ID">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span style="color: #9CA3AF; font-size: 0.875rem;">Cannot delete</span>
                                        @endif
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <script src="{{ asset('js/modals.js') }}"></script>
            <script>
                $(document).ready(function() {
                    $('#studentIdsTable').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        order: [[4, 'desc']],
                        language: {
                            search: "",
                            searchPlaceholder: "Search institutional IDs..."
                        }
                    });
                });
            </script>
        </div>
    </div>

    {{-- View Institutional ID Modal --}}
    @foreach($studentIds as $studentId)
        <div class="modal" id="view-student-id-modal-{{ $studentId->id }}">
            <div class="modal-content">
                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>View Institutional ID Record</p>
                    <h4>Institutional ID Details</h4>
                </div>
                <div class="form-section">
                    <h5><i class="fas fa-id-card"></i> Institutional ID Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Institutional ID</label>
                                <div class="view-field"><strong>{{ $studentId->student_id }}</strong></div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="view-field">
                                    <span class="status-badge status-{{ $studentId->status === 'available' ? 'active' : ($studentId->status === 'used' ? 'inactive' : 'pending') }}">
                                        {{ ucfirst($studentId->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Assigned To</label>
                                <div class="view-field">{{ $studentId->assignedUser->name ?? 'Not Assigned' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Created By</label>
                                <div class="view-field">{{ $studentId->creator->name ?? 'System' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Created At</label>
                        <div class="view-field">{{ $studentId->created_at->format('M d, Y g:i A') }}</div>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn exit-btn" onclick="closeModal('view-student-id-modal-{{ $studentId->id }}')">
                        <i class="fas fa-times"></i> Exit
                    </button>
                </div>
            </div>
</div>

    @endforeach

    {{-- Delete Confirmation Modal (Shared) --}}
    @include('layouts.modals')
@endsection
