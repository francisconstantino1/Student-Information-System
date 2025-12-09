@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')
    @include('layouts.datatables')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h1 style="color: #1C6EA4; margin-bottom: 8px;">Teacher Management</h1>
                    <p style="color: #6B7280;">Manage all teacher records</p>
                </div>
                <button onclick="openModal('create-teacher-modal')" style="background: #1C6EA4; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer;">
                    ➕ Add Teacher
                </button>
            </div>

            @if (session('success'))
                <div style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="overflow-x: auto;">
                <table id="teachersTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Course Handle</th>
                            <th>Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->name }}</td>
                                <td>{{ $teacher->email }}</td>
                                <td>{{ $teacher->contact_number ?? 'N/A' }}</td>
                                <td>{{ $teacher->course ?? 'N/A' }}</td>
                                <td>{{ $teacher->position ?? 'N/A' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view-btn" onclick="openModal('view-teacher-modal-{{ $teacher->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn edit-btn" onclick="openModal('edit-teacher-modal-{{ $teacher->id }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn" data-record-id="{{ $teacher->id }}" data-record-name="{{ $teacher->name }}" data-delete-url="{{ route('admin.teachers.destroy', $teacher) }}" data-record-type="teacher">
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
                    $('#teachersTable').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        order: [[0, 'asc']],
                        language: {
                            search: "",
                            searchPlaceholder: "Search teachers..."
                        }
                    });
                });
            </script>
        </div>
    </div>

    {{-- View Teacher Modal --}}
    @foreach($teachers as $teacher)
        <div class="modal" id="view-teacher-modal-{{ $teacher->id }}">
            <div class="modal-content">
                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>View Teacher Record</p>
                    <h4>Teacher Details</h4>
                </div>
                <div class="form-section">
                    <h5><i class="fas fa-user"></i> Personal Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Name</label>
                                <div class="view-field">{{ $teacher->name }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="view-field">{{ $teacher->email }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Contact Number</label>
                                <div class="view-field">{{ $teacher->contact_number ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn exit-btn" onclick="closeModal('view-teacher-modal-{{ $teacher->id }}')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>

    @endforeach

    {{-- Edit Teacher Modals --}}
    @foreach($teachers as $teacher)
        <div class="modal" id="edit-teacher-modal-{{ $teacher->id }}">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-header">
                        <h3>Student Information System</h3>
                        <p>Edit Teacher Record</p>
                        <h4>Update Teacher Details</h4>
                    </div>

                    <div class="form-section">
                        <h5><i class="fas fa-user"></i> Personal Information</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Full Name *</label>
                                    <input type="text" name="name" required value="{{ old('name', $teacher->name) }}" class="form-control">
                                    @error('name')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" name="contact_number" value="{{ old('contact_number', $teacher->contact_number) }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" value="{{ old('address', $teacher->address) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="" @selected(old('gender', $teacher->gender) === null)>Select gender (optional)</option>
                                        <option value="Male" @selected(old('gender', $teacher->gender) === 'Male')>Male</option>
                                        <option value="Female" @selected(old('gender', $teacher->gender) === 'Female')>Female</option>
                                        <option value="Other" @selected(old('gender', $teacher->gender) === 'Other')>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5><i class="fas fa-lock"></i> Account Access & Course Assignment</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Username (Login Email) *</label>
                                    <input type="email" name="email" required value="{{ old('email', $teacher->email) }}" class="form-control" placeholder="e.g., faculty@school.edu">
                                    @error('email')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Password (Leave blank to keep current password)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter new password">
                                    @error('password')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Course/Program to Handle</label>
                                <select name="course" class="form-control">
                                    <option value="">Select course to handle</option>
                                    @foreach(($courses ?? collect()) as $course)
                                        <option value="{{ $course }}" @selected(old('course', $teacher->course) === $course)>{{ $course }}</option>
                                    @endforeach
                                    @if(old('course', $teacher->course) && !($courses ?? collect())->contains(old('course', $teacher->course)))
                                        <option value="{{ old('course', $teacher->course) }}" selected>{{ old('course', $teacher->course) }}</option>
                                    @endif
                                </select>
                                <small style="color:#6B7280;">Select an existing course or type a custom one.</small>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Position</label>
                                <input type="text" name="position" value="{{ old('position', $teacher->position) }}" class="form-control" placeholder="e.g., Instructor, Lecturer">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Year Level</label>
                                <select name="year_level" class="form-control">
                                    <option value="">Select year level</option>
                                    @php
                                        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                                    @endphp
                                    @foreach($yearLevels as $level)
                                        <option value="{{ $level }}" @selected(old('year_level', $teacher->year_level) === $level)>{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="modal-buttons">
                        <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('edit-teacher-modal-{{ $teacher->id }}')">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="modal-btn modal-btn-primary">
                            <i class="fas fa-save"></i> Update Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Create Teacher Modal --}}
    <div class="modal" id="create-teacher-modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.teachers.store') }}">
                @csrf

                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>Add New Teacher</p>
                    <h4>Create New Teacher</h4>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-user"></i> Personal Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Full Name *</label>
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
                                <label>Contact Number</label>
                                <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="" @selected(old('gender') === null)>Select gender (optional)</option>
                                    <option value="Male" @selected(old('gender') === 'Male')>Male</option>
                                    <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                                    <option value="Other" @selected(old('gender') === 'Other')>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-lock"></i> Account Access & Course Assignment</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Username (Login Email) *</label>
                                <input type="email" name="email" required value="{{ old('email') }}" class="form-control" placeholder="e.g., faculty@school.edu">
                                @error('email')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Password *</label>
                                <input type="password" name="password" required class="form-control">
                                @error('password')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Course/Program to Handle</label>
                                <select name="course" class="form-control">
                                    <option value="">Select course to handle</option>
                                    @foreach(($courses ?? collect()) as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                    @if(old('course') && !($courses ?? collect())->contains(old('course')))
                                        <option value="{{ old('course') }}" selected>{{ old('course') }}</option>
                                    @endif
                                </select>
                                <small style="color:#6B7280;">Select an existing course or type a custom one.</small>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Position</label>
                                <input type="text" name="position" value="{{ old('position') }}" class="form-control" placeholder="e.g., Instructor, Lecturer">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Year Level</label>
                                <select name="year_level" class="form-control">
                                    <option value="">Select year level</option>
                                    @php
                                        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                                    @endphp
                                    @foreach($yearLevels as $level)
                                        <option value="{{ $level }}" @selected(old('year_level') === $level)>{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-danger" onclick="closeModal('create-teacher-modal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-save"></i> Create Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal (Shared) --}}
    @include('layouts.modals')

@endsection

