@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')
    @include('layouts.datatables')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h1 style="color: #1C6EA4; margin-bottom: 8px;">Subject Management</h1>
                    <p style="color: #6B7280;">Manage all subjects and courses</p>
                </div>
                <button onclick="openModal('create-subject-modal')" style="background: #1C6EA4; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer;">
                    ➕ Add Subject
                </button>
            </div>

            @if (session('success'))
                <div style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="overflow-x: auto;">
                <table id="subjectsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Subject Name</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Units</th>
                            <th>Instructor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                            <tr>
                                <td>{{ $subject->subject_code }}</td>
                                <td>{{ $subject->subject_name }}</td>
                                <td>{{ $subject->course }}</td>
                                <td>{{ $subject->year_level }}</td>
                                <td>{{ $subject->units }}</td>
                                <td>{{ $subject->instructor->name ?? 'Not Assigned' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view-btn" onclick="openModal('view-subject-modal-{{ $subject->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn edit-btn" onclick="openModal('edit-subject-modal-{{ $subject->id }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn" data-record-id="{{ $subject->id }}" data-record-name="{{ $subject->subject_name }}" data-delete-url="{{ route('admin.subjects.destroy', $subject) }}" data-record-type="subject">
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
                    $('#subjectsTable').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        order: [[0, 'asc']],
                        language: {
                            search: "",
                            searchPlaceholder: "Search subjects..."
                        }
                    });
                });
            </script>
        </div>
    </div>

    {{-- View Subject Modal --}}
    @foreach($subjects as $subject)
        <div class="modal" id="view-subject-modal-{{ $subject->id }}">
            <div class="modal-content">
                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>View Subject Record</p>
                    <h4>Subject Details</h4>
                </div>
                <div class="form-section">
                    <h5><i class="fas fa-book"></i> Subject Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Subject Code</label>
                                <div class="view-field">{{ $subject->subject_code }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Subject Name</label>
                                <div class="view-field">{{ $subject->subject_name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Course</label>
                                <div class="view-field">{{ $subject->course }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Year Level</label>
                                <div class="view-field">{{ $subject->year_level }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Units</label>
                                <div class="view-field">{{ $subject->units }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Instructor</label>
                                <div class="view-field">{{ $subject->instructor->name ?? 'Not Assigned' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn exit-btn" onclick="closeModal('view-subject-modal-{{ $subject->id }}')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>

    @endforeach

    {{-- Edit Subject Modals --}}
    @foreach($subjects as $subject)
        <div class="modal" id="edit-subject-modal-{{ $subject->id }}">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-header">
                        <h3>Student Information System</h3>
                        <p>Edit Subject Record</p>
                        <h4>Update Subject Details</h4>
                    </div>

                    <div class="form-section">
                        <h5><i class="fas fa-book"></i> Subject Information</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Subject Code *</label>
                                    <input type="text" name="code" required value="{{ old('code', $subject->subject_code) }}" class="form-control">
                                    @error('code')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Units *</label>
                                    <input type="number" name="units" required min="1" value="{{ old('units', $subject->units) }}" class="form-control">
                                    @error('units')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col" style="grid-column: 1 / -1;">
                                <div class="form-group">
                                    <label>Subject Name *</label>
                                    <input type="text" name="name" required value="{{ old('name', $subject->subject_name) }}" class="form-control">
                                    @error('name')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Course *</label>
                                    <select name="course" required class="form-control">
                                        <option value="">Select Course</option>
                                        @foreach($courses ?? [] as $course)
                                            <option value="{{ $course }}" @selected(old('course', $subject->course) === $course)>{{ $course }}</option>
                                        @endforeach
                                        @if($subject->course && ($courses ?? collect())->doesntContain($subject->course))
                                            <option value="{{ $subject->course }}" selected>{{ $subject->course }}</option>
                                        @endif
                                    </select>
                                    @error('course')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Year Level *</label>
                                    <select name="year_level" required class="form-control">
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year" {{ old('year_level', $subject->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level', $subject->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level', $subject->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level', $subject->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                    @error('year_level')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Semester</label>
                                    <input type="text" name="semester" value="{{ old('semester', $subject->semester) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Hours Per Week</label>
                                    <input type="number" name="hours_per_week" min="1" value="{{ old('hours_per_week', $subject->hours_per_week) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Instructor</label>
                                    <select name="instructor_id" class="form-control">
                                        <option value="">Select Instructor</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ old('instructor_id', $subject->instructor_id) == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col" style="grid-column: 1 / -1;">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" rows="3" class="form-control">{{ old('description', $subject->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-buttons">
                        <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('edit-subject-modal-{{ $subject->id }}')">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="modal-btn modal-btn-primary">
                            <i class="fas fa-save"></i> Update Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Create Subject Modal --}}
    <div class="modal" id="create-subject-modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.subjects.store') }}">
                @csrf

                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>Add New Subject</p>
                    <h4>Create New Subject</h4>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-book"></i> Subject Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Subject Code *</label>
                                <input type="text" name="code" required value="{{ old('code') }}" class="form-control">
                                @error('code')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Units *</label>
                                <input type="number" name="units" required min="1" value="{{ old('units') }}" class="form-control">
                                @error('units')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Subject Name *</label>
                                <input type="text" name="name" required value="{{ old('name') }}" class="form-control">
                                @error('name')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Course *</label>
                                <select name="course" required class="form-control">
                                    <option value="">Select Course</option>
                                    @foreach($courses ?? [] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </select>
                                @error('course')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Year Level *</label>
                                <select name="year_level" required class="form-control">
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                </select>
                                @error('year_level')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Semester</label>
                                <input type="text" name="semester" value="{{ old('semester') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Hours Per Week</label>
                                <input type="number" name="hours_per_week" min="1" value="{{ old('hours_per_week') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Instructor</label>
                                <select name="instructor_id" class="form-control">
                                    <option value="">Select Instructor</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-danger" onclick="closeModal('create-subject-modal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-save"></i> Create Subject
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal (Shared) --}}
    @include('layouts.modals')

@endsection

