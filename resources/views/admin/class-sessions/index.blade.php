@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            font-size: 2rem;
            color: #0046FF;
            margin-bottom: 8px;
        }

        .admin-header p {
            color: #6B7280;
            margin: 0;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #0046FF;
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background: #0033CC;
            transform: translateY(-1px);
        }

        .btn-edit {
            background: #10B981;
            color: #FFFFFF;
            padding: 6px 12px;
            font-size: 0.75rem;
        }

        .btn-edit:hover {
            background: #059669;
        }

        .btn-danger {
            background: #EF4444;
            color: #FFFFFF;
            padding: 6px 12px;
            font-size: 0.75rem;
        }

        .btn-danger:hover {
            background: #DC2626;
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
        }


        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-active {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-inactive {
            background: #FEE2E2;
            color: #991B1B;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 48px;
            color: #6B7280;
        }
    </style>

    <div class="admin-container">
        <div class="admin-header">
            <div>
                <h1>Class Sessions</h1>
                <p>Manage time-based class sessions for attendance</p>
            </div>
            <button onclick="openModal('create-session-modal')" class="btn btn-primary" style="cursor: pointer;">+ Add New Session</button>
        </div>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div class="error-message">
                {{ session('error') }}
                @if ($errors->any())
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <div class="card">
            @if($sessions->isEmpty())
                <div class="empty-state">
                    <p>No class sessions found. <button onclick="openModal('create-session-modal')" style="background: none; border: none; color: #0046FF; cursor: pointer; text-decoration: underline;">Create your first session</button></p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table id="classSessionsTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Schedule</th>
                                <th>Time</th>
                                <th>Instructor</th>
                                <th>Room</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                                <tr>
                                    <td>{{ $session->course ?? 'N/A' }}</td>
                                    <td><strong>{{ $session->course_id ?? 'N/A' }}</strong></td>
                                    <td>{{ $session->subject ?? 'N/A' }}</td>
                                    <td>{{ $session->schedule ?? 'N/A' }}</td>
                                    <td>{{ $session->time ?? 'N/A' }}</td>
                                    <td>{{ $session->instructor ?? 'N/A' }}</td>
                                    <td>{{ $session->room ?? 'N/A' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $session->is_active ? 'active' : 'inactive' }}">
                                            {{ $session->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn view-btn" onclick="openModal('view-session-modal-{{ $session->id }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.class-sessions.edit', $session->id) }}" class="action-btn edit-btn">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="action-btn delete-btn" data-record-id="{{ $session->id }}" data-record-name="{{ $session->course_id ?? $session->subject ?? 'Session' }}" data-delete-url="{{ route('admin.class-sessions.destroy', $session->id) }}" data-record-type="session">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <script src="{{ asset('js/modals.js') }}"></script>
                <script>
                    $(document).ready(function() {
                        $('#classSessionsTable').DataTable({
                            pageLength: 10,
                            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                            order: [[0, 'asc']],
                            language: {
                                search: "",
                                searchPlaceholder: "Search sessions..."
                            }
                        });
                    });
                </script>
            @endif
        </div>
    </div>

    {{-- View Session Modal --}}
    @foreach($sessions as $session)
        <div class="modal" id="view-session-modal-{{ $session->id }}">
            <div class="modal-content">
                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>View Class Session Record</p>
                    <h4>Session Details</h4>
                </div>
                <div class="form-section">
                    <h5><i class="fas fa-clock"></i> Session Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Course</label>
                                <div class="view-field">{{ $session->course ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Code</label>
                                <div class="view-field">{{ $session->course_id ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Subject</label>
                                <div class="view-field">{{ $session->subject ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Schedule (Day/s)</label>
                                <div class="view-field">{{ $session->schedule ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Time</label>
                                <div class="view-field">{{ $session->time ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Instructor</label>
                                <div class="view-field">{{ $session->instructor ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Room</label>
                                <div class="view-field">{{ $session->room ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <div class="view-field">{{ $session->description ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="view-field">
                            <span class="status-badge {{ $session->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $session->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-buttons">
                    <a href="{{ route('admin.class-sessions.edit', $session->id) }}" class="btn print-btn">
                        <i class="fas fa-edit"></i> Edit Session
                    </a>
                    <button type="button" class="btn exit-btn" onclick="closeModal('view-session-modal-{{ $session->id }}')">
                        <i class="fas fa-times"></i> Exit
                    </button>
                </div>
            </div>
        </div>

    @endforeach

    {{-- Create Class Session Modal --}}
    <div class="modal" id="create-session-modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.class-sessions.store') }}">
                @csrf

                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>Add New Class Session</p>
                    <h4>Create New Session</h4>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-clock"></i> Session Information</h5>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Course *</label>
                                <select name="course" id="modal-course" required class="form-control">
                                    <option value="">Select Course</option>
                                    @foreach($courses as $courseOption)
                                        <option value="{{ $courseOption }}" {{ old('course') === $courseOption ? 'selected' : '' }}>
                                            {{ $courseOption }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Code *</label>
                                <select name="code" id="modal-code" required class="form-control">
                                    <option value="">Select Code</option>
                                    @foreach($subjects as $subjectOption)
                                        <option value="{{ $subjectOption->subject_code }}" 
                                            data-course="{{ $subjectOption->course }}"
                                            data-subject-name="{{ $subjectOption->subject_name }}"
                                            {{ old('code') === $subjectOption->subject_code ? 'selected' : '' }}>
                                            {{ $subjectOption->subject_code }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Subject *</label>
                                <select name="subject" id="modal-subject" required class="form-control">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subjectOption)
                                        <option value="{{ $subjectOption->subject_name }}" 
                                            data-course="{{ $subjectOption->course }}"
                                            data-code="{{ $subjectOption->subject_code }}"
                                            {{ old('subject') === $subjectOption->subject_name ? 'selected' : '' }}>
                                            {{ $subjectOption->subject_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Schedule (Day/s) *</label>
                                <input type="text" name="schedule" required value="{{ old('schedule') }}" placeholder="e.g., MWF" class="form-control">
                                @error('schedule')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Time *</label>
                                <input type="text" name="time" required value="{{ old('time') }}" placeholder="e.g., 8:00 AM - 9:30 AM" class="form-control">
                                @error('time')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Instructor *</label>
                                <select name="instructor" required class="form-control">
                                    <option value="">Select Instructor</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->name }}" {{ old('instructor') === $instructor->name ? 'selected' : '' }}>
                                            {{ $instructor->name }} @if($instructor->position)({{ $instructor->position }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Room *</label>
                                <input type="text" name="room" required value="{{ old('room') }}" placeholder="e.g., Room 201" class="form-control">
                                @error('room')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="3" placeholder="Optional description for this session" class="form-control">{{ old('description') }}</textarea>
                                @error('description')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" name="is_active" value="1" class="form-control" style="width: auto;" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <span>Active Session</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-danger" onclick="closeModal('create-session-modal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-save"></i> Create Session
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal (Shared) --}}
    @include('layouts.modals')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalCourseSelect = document.getElementById('modal-course');
            const modalCodeSelect = document.getElementById('modal-code');
            const modalSubjectSelect = document.getElementById('modal-subject');

            if (modalCourseSelect && modalCodeSelect && modalSubjectSelect) {
                // Filter code and subject dropdowns based on selected course
                function filterModalDropdowns() {
                    const selectedCourse = modalCourseSelect.value;
                    
                    // Filter code dropdown
                    Array.from(modalCodeSelect.options).forEach(option => {
                        if (option.value === '') {
                            option.style.display = 'block';
                        } else {
                            const optionCourse = option.getAttribute('data-course');
                            option.style.display = (selectedCourse === '' || optionCourse === selectedCourse) ? 'block' : 'none';
                        }
                    });

                    // Filter subject dropdown
                    Array.from(modalSubjectSelect.options).forEach(option => {
                        if (option.value === '') {
                            option.style.display = 'block';
                        } else {
                            const optionCourse = option.getAttribute('data-course');
                            option.style.display = (selectedCourse === '' || optionCourse === selectedCourse) ? 'block' : 'none';
                        }
                    });

                    // Reset selections if current selection doesn't match course
                    if (selectedCourse) {
                        const selectedCodeCourse = modalCodeSelect.options[modalCodeSelect.selectedIndex]?.getAttribute('data-course');
                        if (selectedCodeCourse && selectedCodeCourse !== selectedCourse) {
                            modalCodeSelect.value = '';
                        }

                        const selectedSubjectCourse = modalSubjectSelect.options[modalSubjectSelect.selectedIndex]?.getAttribute('data-course');
                        if (selectedSubjectCourse && selectedSubjectCourse !== selectedCourse) {
                            modalSubjectSelect.value = '';
                        }
                    }
                }

                // When course changes, filter dropdowns
                modalCourseSelect.addEventListener('change', filterModalDropdowns);

                // When code is selected, auto-select corresponding subject
                modalCodeSelect.addEventListener('change', function() {
                    const selectedCode = this.value;
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.getAttribute('data-subject-name')) {
                        const subjectName = selectedOption.getAttribute('data-subject-name');
                        modalSubjectSelect.value = subjectName;
                    }
                });

                // When subject is selected, auto-select corresponding code
                modalSubjectSelect.addEventListener('change', function() {
                    const selectedSubject = this.value;
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.getAttribute('data-code')) {
                        const code = selectedOption.getAttribute('data-code');
                        modalCodeSelect.value = code;
                    }
                });

                // Initial filter
                filterModalDropdowns();

                // Reset form when modal is closed
                const createModal = document.getElementById('create-session-modal');
                if (createModal) {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                                const isHidden = createModal.style.display === 'none' || !createModal.style.display;
                                if (isHidden) {
                                    // Reset form
                                    modalCourseSelect.value = '';
                                    modalCodeSelect.value = '';
                                    modalSubjectSelect.value = '';
                                    filterModalDropdowns();
                                }
                            }
                        });
                    });
                    observer.observe(createModal, { attributes: true });
                }
            }
        });
    </script>
@endsection

