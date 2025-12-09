@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')
    @include('layouts.datatables')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h1 style="color: #1C6EA4; margin-bottom: 8px;">Student Management</h1>
                    <p style="color: #6B7280;">Manage all student records</p>
                </div>
                <button onclick="openModal('create-student-modal')" style="background: #1C6EA4; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer;">
                    ➕ Add Student
                </button>
            </div>

            @if (session('success'))
                <div class="flash-message success-message" style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="flash-message error-message" style="background: #FEE2E2; color: #991B1B; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <div style="overflow-x: auto;">
                <table id="studentsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Institutional ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Section</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->student_id ?? 'N/A' }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->course ?? 'Not Set' }}</td>
                                <td>{{ $student->year_level ?? 'Not Set' }}</td>
                                <td>
                                    @php
                                        $sectionName = 'Not Assigned';
                                        if ($student->section_id) {
                                            if ($student->section) {
                                                $sectionName = $student->section->name;
                                            } else {
                                                // Section ID exists but relationship not loaded, try to get it
                                                $section = \App\Models\Section::find($student->section_id);
                                                $sectionName = $section ? $section->name : 'Section #' . $student->section_id;
                                            }
                                        }
                                    @endphp
                                    {{ $sectionName }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view-btn" onclick="openModal('view-student-modal-{{ $student->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn edit-btn" onclick="openModal('edit-student-modal-{{ $student->id }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn archive-btn" data-record-id="{{ $student->id }}" data-record-name="{{ $student->name }}" data-archive-url="{{ route('admin.students.archive', $student) }}" data-record-type="student" title="Archive">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                        <button class="action-btn delete-btn" data-record-id="{{ $student->id }}" data-record-name="{{ $student->name }}" data-delete-url="{{ route('admin.students.destroy', $student) }}" data-record-type="student" title="Permanently Delete">
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
                    $('#studentsTable').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        order: [[1, 'asc']],
                        language: {
                            search: "",
                            searchPlaceholder: "Search students..."
                        }
                    });
                });
            </script>
        </div>
    </div>

    {{-- View Student Modals --}}
    @foreach($students as $student)
        <div class="modal" id="view-student-modal-{{ $student->id }}">
            <div class="modal-content">
                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>View Student Record</p>
                    <h4>Student Details</h4>
                </div>
                <div class="form-section">
                    <h5><i class="fas fa-user"></i> Personal Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Institutional ID</label>
                                <div class="view-field">{{ $student->student_id ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Full Name</label>
                                <div class="view-field">{{ $student->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="view-field">{{ $student->email }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Birthday</label>
                                <div class="view-field">{{ $student->birthday?->format('F d, Y') ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="view-field">{{ $student->gender ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Contact Number</label>
                                <div class="view-field">{{ $student->contact_number ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Address</label>
                                <div class="view-field">{{ $student->address ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <h5><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Course</label>
                                <div class="view-field">{{ $student->course ?? 'Not Set' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Year Level</label>
                                <div class="view-field">{{ $student->year_level ?? 'Not Set' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Section</label>
                                <div class="view-field">
                                    @if($student->section_id)
                                        {{ $student->section ? $student->section->name : 'Section #' . $student->section_id }}
                                    @else
                                        Not Assigned
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <h5><i class="fas fa-users"></i> Guardian Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Guardian Name</label>
                                <div class="view-field">{{ $student->guardian_name ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Guardian Contact</label>
                                <div class="view-field">{{ $student->guardian_contact ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($student->documents && $student->documents->count() > 0)
                <div class="form-section">
                    <h5><i class="fas fa-file-alt"></i> Documents</h5>
                    <div class="form-row">
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                @foreach($student->documents as $document)
                                    <div style="background: #F9FAFB; padding: 12px; border-radius: 8px; margin-bottom: 8px;">
                                        <div style="color: #111827; font-weight: 500;">{{ $document->document_name ?? 'Document' }}</div>
                                        <div style="color: #6B7280; font-size: 0.875rem; margin-top: 4px;">
                                            Uploaded: {{ $document->uploaded_at?->format('M d, Y') ?? 'N/A' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="modal-buttons">
                    <button type="button" class="btn exit-btn" onclick="closeModal('view-student-modal-{{ $student->id }}')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Edit Student Modals --}}
    @foreach($students as $student)
        <div class="modal" id="edit-student-modal-{{ $student->id }}">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.students.update', $student) }}" id="edit-student-form-{{ $student->id }}">
                    @csrf
                    @method('PUT')

                    <div class="form-header">
                        <h3>Student Information System</h3>
                        <p>Edit Student Record</p>
                        <h4>Update Student Details</h4>
                    </div>

                    <div class="form-section">
                        <h5><i class="fas fa-user"></i> Personal Information</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Institutional ID *</label>
                                    <input type="text" name="student_id" required value="{{ old('student_id', $student->student_id) }}" class="form-control">
                                    @error('student_id')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Full Name *</label>
                                    <input type="text" name="name" required value="{{ old('name', $student->name) }}" class="form-control">
                                    @error('name')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" name="email" required value="{{ old('email', $student->email) }}" class="form-control">
                                    @error('email')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Birthday</label>
                                    <input type="date" name="birthday" value="{{ old('birthday', $student->birthday?->format('Y-m-d')) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" name="contact_number" value="{{ old('contact_number', $student->contact_number) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col" style="grid-column: 1 / -1;">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea name="address" rows="3" class="form-control">{{ old('address', $student->address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Course *</label>
                                    <input type="text" name="course" required value="{{ old('course', $student->course) }}" class="form-control">
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
                                        <option value="1st Year" {{ old('year_level', $student->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level', $student->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level', $student->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level', $student->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
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
                                    <label>Section</label>
                                    <select name="section_id" class="form-control">
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>New Password (leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control">
                                    @error('password')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5><i class="fas fa-users"></i> Guardian Information</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Guardian Name</label>
                                    <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Guardian Contact</label>
                                    <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-buttons">
                        <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('edit-student-modal-{{ $student->id }}')">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="modal-btn modal-btn-primary">
                            <i class="fas fa-save"></i> Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Create Student Modal --}}
    <div class="modal" id="create-student-modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf

                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>Add New Student</p>
                    <h4>Create New Student</h4>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-user"></i> Personal Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Institutional ID *</label>
                                <input type="text" name="student_id" required value="{{ old('student_id') }}" class="form-control">
                                @error('student_id')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
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
                                <label>Email *</label>
                                <input type="email" name="email" required value="{{ old('email') }}" class="form-control">
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
                                <label>Birthday</label>
                                <input type="date" name="birthday" value="{{ old('birthday') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
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
                        <div class="form-col" style="grid-column: 1 / -1;">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" rows="3" class="form-control">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Course *</label>
                                <input type="text" name="course" required value="{{ old('course') }}" class="form-control">
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
                                <label>Section</label>
                                <select name="section_id" class="form-control">
                                    <option value="">Select Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-users"></i> Guardian Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Guardian Name</label>
                                <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Guardian Contact</label>
                                <input type="text" name="guardian_contact" value="{{ old('guardian_contact') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-danger" onclick="closeModal('create-student-modal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-save"></i> Create Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal (Shared) --}}
    @include('layouts.modals')

@endsection

