@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')
    @include('layouts.datatables')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h1 style="color: #1C6EA4; margin-bottom: 8px;">Grade Management</h1>
                    <p style="color: #6B7280;">Manage student grades</p>
                </div>
                <button onclick="openModal('create-grade-modal')" style="background: #1C6EA4; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer;">
                    ➕ Add Grade
                </button>
            </div>

            @if (session('success'))
                <div class="flash-message success-message" style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="overflow-x: auto;">
                <table id="gradesTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Semester</th>
                            <th>Academic Year</th>
                            <th>Midterm</th>
                            <th>Finals</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                            <tr>
                                <td>{{ $grade->user->name }}</td>
                                <td>{{ $grade->subject->subject_name }}</td>
                                <td>{{ $grade->semester }}</td>
                                <td>{{ $grade->academic_year }}</td>
                                <td style="font-weight: 500;">{{ $grade->midterm ?? 'N/A' }}</td>
                                <td style="font-weight: 500;">{{ $grade->final ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $isComplete = ($grade->remarks ?? '') == 'Complete';
                                    @endphp
                                    <span class="status-badge {{ $isComplete ? 'status-active' : 'status-inactive' }}">
                                        {{ $grade->remarks ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $grade->status === 'approved' ? 'status-active' : 'status-inactive' }}">
                                        {{ ucfirst($grade->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view-btn" onclick="openModal('view-grade-modal-{{ $grade->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn edit-btn" onclick="openModal('edit-grade-modal-{{ $grade->id }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(($grade->status ?? 'pending') !== 'approved')
                                            <form action="{{ route('admin.grades.approve', $grade) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn approve-btn" title="Approve" style="background:#10B981;color:#FFFFFF;">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button class="action-btn delete-btn" 
                                                data-record-id="{{ $grade->id }}" 
                                                data-record-name="{{ $grade->user->name }} - {{ $grade->subject->subject_name }}" 
                                                data-delete-url="{{ route('admin.grades.destroy', $grade) }}" 
                                                data-record-type="grade">
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
                    $('#gradesTable').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        order: [[3, 'desc']],
                        language: {
                            search: "",
                            searchPlaceholder: "Search grades..."
                        }
                    });
                });

                // Filter students by course
                function filterStudentsByCourse() {
                    const courseSelect = document.getElementById('course-filter');
                    const studentSelect = document.getElementById('student-select');
                    const selectedCourse = courseSelect.value;

                    // Get all student options
                    const allOptions = studentSelect.querySelectorAll('option');
                    
                    // Clear current selection
                    studentSelect.value = '';
                    // Also clear subject selection when student changes
                    const subjectSelect = document.getElementById('subject-select');
                    if (subjectSelect) {
                        subjectSelect.value = '';
                    }
                    
                    // Show/hide options based on selected course
                    allOptions.forEach(option => {
                        if (option.value === '') {
                            // Keep the placeholder option
                            option.style.display = 'block';
                            if (selectedCourse) {
                                option.textContent = 'Select Student';
                            } else {
                                option.textContent = 'Select Student (Choose Course First)';
                            }
                        } else {
                            const studentCourse = option.getAttribute('data-course');
                            if (!selectedCourse || studentCourse === selectedCourse) {
                                option.style.display = 'block';
                            } else {
                                option.style.display = 'none';
                            }
                        }
                    });
                    
                    // Filter subjects when course is selected
                    filterSubjectsByStudent();
                }

                // Filter subjects by selected student's course
                function filterSubjectsByStudent() {
                    const studentSelect = document.getElementById('student-select');
                    const subjectSelect = document.getElementById('subject-select');
                    const selectedStudentId = studentSelect.value;

                    if (!subjectSelect) return;

                    // Get the selected student's course
                    let studentCourse = null;
                    if (selectedStudentId) {
                        const selectedStudentOption = studentSelect.querySelector(`option[value="${selectedStudentId}"]`);
                        if (selectedStudentOption) {
                            studentCourse = selectedStudentOption.getAttribute('data-course');
                        }
                    }

                    // Get all subject options
                    const allSubjectOptions = subjectSelect.querySelectorAll('option');
                    
                    // Clear current selection if student changed
                    if (!selectedStudentId) {
                        subjectSelect.value = '';
                    }
                    
                    // Show/hide options based on student's course
                    allSubjectOptions.forEach(option => {
                        if (option.value === '') {
                            // Keep the placeholder option
                            option.style.display = 'block';
                            if (studentCourse) {
                                option.textContent = 'Select Subject';
                            } else {
                                option.textContent = 'Select Subject (Choose Student First)';
                            }
                        } else {
                            const subjectCourse = option.getAttribute('data-course');
                            if (!studentCourse || subjectCourse === studentCourse) {
                                option.style.display = 'block';
                            } else {
                                option.style.display = 'none';
                            }
                        }
                    });
                }

                // Filter students by course for edit modals
                function filterEditStudentsByCourse(gradeId) {
                    const courseSelect = document.getElementById('edit-course-filter-' + gradeId);
                    const studentSelect = document.getElementById('edit-student-select-' + gradeId);
                    if (!courseSelect || !studentSelect) return;
                    
                    const selectedCourse = courseSelect.value;

                    const allOptions = studentSelect.querySelectorAll('option');
                    studentSelect.value = '';
                    
                    const subjectSelect = document.getElementById('edit-subject-select-' + gradeId);
                    if (subjectSelect) {
                        subjectSelect.value = '';
                    }
                    
                    allOptions.forEach(option => {
                        if (option.value === '') {
                            option.style.display = 'block';
                            if (selectedCourse) {
                                option.textContent = 'Select Student';
                            } else {
                                option.textContent = 'Select Student (Choose Course First)';
                            }
                        } else {
                            const studentCourse = option.getAttribute('data-course');
                            if (!selectedCourse || studentCourse === selectedCourse) {
                                option.style.display = 'block';
                            } else {
                                option.style.display = 'none';
                            }
                        }
                    });
                    
                    filterEditSubjectsByStudent(gradeId);
                }

                // Filter subjects by selected student's course for edit modals
                function filterEditSubjectsByStudent(gradeId) {
                    const studentSelect = document.getElementById('edit-student-select-' + gradeId);
                    const subjectSelect = document.getElementById('edit-subject-select-' + gradeId);
                    if (!studentSelect || !subjectSelect) return;
                    
                    const selectedStudentId = studentSelect.value;

                    let studentCourse = null;
                    if (selectedStudentId) {
                        const selectedStudentOption = studentSelect.querySelector(`option[value="${selectedStudentId}"]`);
                        if (selectedStudentOption) {
                            studentCourse = selectedStudentOption.getAttribute('data-course');
                        }
                    }

                    const allSubjectOptions = subjectSelect.querySelectorAll('option');
                    
                    if (!selectedStudentId) {
                        subjectSelect.value = '';
                    }
                    
                    allSubjectOptions.forEach(option => {
                        if (option.value === '') {
                            option.style.display = 'block';
                            if (studentCourse) {
                                option.textContent = 'Select Subject';
                            } else {
                                option.textContent = 'Select Subject (Choose Student First)';
                            }
                        } else {
                            const subjectCourse = option.getAttribute('data-course');
                            if (!studentCourse || subjectCourse === studentCourse) {
                                option.style.display = 'block';
                            } else {
                                option.style.display = 'none';
                            }
                        }
                    });
                }

                // Initialize: hide all students and subjects until course/student is selected
                document.addEventListener('DOMContentLoaded', function() {
                    const studentSelect = document.getElementById('student-select');
                    const courseSelect = document.getElementById('course-filter');
                    const subjectSelect = document.getElementById('subject-select');
                    
                    if (studentSelect && courseSelect) {
                        // Check if there's a pre-selected student (from form validation errors)
                        const selectedStudent = studentSelect.querySelector('option[selected]');
                        if (selectedStudent && selectedStudent.value) {
                            // Show the selected student and set the course
                            const studentCourse = selectedStudent.getAttribute('data-course');
                            if (studentCourse) {
                                courseSelect.value = studentCourse;
                                filterStudentsByCourse();
                                studentSelect.value = selectedStudent.value;
                                // Filter subjects based on selected student
                                filterSubjectsByStudent();
                            }
                        } else {
                            // Hide all students until course is selected
                            const allOptions = studentSelect.querySelectorAll('option[data-course]');
                            allOptions.forEach(option => {
                                option.style.display = 'none';
                            });
                        }
                    }

                    // Hide all subjects initially
                    if (subjectSelect) {
                        const allSubjectOptions = subjectSelect.querySelectorAll('option[data-course]');
                        allSubjectOptions.forEach(option => {
                            option.style.display = 'none';
                        });
                    }
                });
                
                // Initialize edit modals when opened
                const originalOpenModal = window.openModal;
                window.openModal = function(modalId) {
                    originalOpenModal(modalId);
                    
                    // If it's an edit modal, initialize filters
                    if (modalId && modalId.startsWith('edit-grade-modal-')) {
                        const gradeId = modalId.replace('edit-grade-modal-', '');
                        const editCourseSelect = document.getElementById('edit-course-filter-' + gradeId);
                        const editStudentSelect = document.getElementById('edit-student-select-' + gradeId);
                        
                        if (editCourseSelect && editStudentSelect) {
                            // If course is already selected, filter students
                            if (editCourseSelect.value) {
                                filterEditStudentsByCourse(gradeId);
                            }
                            // If student is already selected, filter subjects
                            if (editStudentSelect.value) {
                                filterEditSubjectsByStudent(gradeId);
                            }
                        }
                    }
                };
            </script>
        </div>
    </div>

    {{-- View Grade Modal --}}
    @foreach($grades as $grade)
        <div class="modal" id="view-grade-modal-{{ $grade->id }}">
            <div class="modal-content">
                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>View Grade Record</p>
                    <h4>Grade Details</h4>
                </div>
                <div class="form-section">
                    <h5><i class="fas fa-graduation-cap"></i> Grade Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Student</label>
                                <div class="view-field">{{ $grade->user->name }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Subject</label>
                                <div class="view-field">{{ $grade->subject->subject_name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Semester</label>
                                <div class="view-field">{{ $grade->semester }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Academic Year</label>
                                <div class="view-field">{{ $grade->academic_year }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Midterm</label>
                                <div class="view-field" style="font-weight: 500;">{{ $grade->midterm ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Finals</label>
                                <div class="view-field" style="font-weight: 500;">{{ $grade->final ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Remarks</label>
                                <div class="view-field">
                                    <span class="status-badge {{ ($grade->remarks ?? '') == 'Complete' ? 'status-active' : 'status-inactive' }}">
                                        {{ $grade->remarks ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn exit-btn" onclick="closeModal('view-grade-modal-{{ $grade->id }}')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>

    @endforeach

    {{-- Edit Grade Modals --}}
    @foreach($grades as $grade)
        <div class="modal" id="edit-grade-modal-{{ $grade->id }}">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.grades.update', $grade) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-header">
                        <h3>Student Information System</h3>
                        <p>Edit Grade Record</p>
                        <h4>Update Grade Details</h4>
                    </div>

                    <div class="form-section">
                        <h5><i class="fas fa-graduation-cap"></i> Grade Information</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Course *</label>
                                    <select id="edit-course-filter-{{ $grade->id }}" class="form-control" onchange="filterEditStudentsByCourse('{{ $grade->id }}')">
                                        <option value="">Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course }}" {{ $grade->user && $grade->user->course == $course ? 'selected' : '' }}>{{ $course }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Student *</label>
                                    <select name="user_id" id="edit-student-select-{{ $grade->id }}" required class="form-control" onchange="filterEditSubjectsByStudent('{{ $grade->id }}')">
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" data-course="{{ $student->course }}" {{ old('user_id', $grade->user_id) == $student->id ? 'selected' : '' }}>{{ $student->name }} (ID: {{ $student->student_id }})</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Subject *</label>
                                    <select name="subject_id" id="edit-subject-select-{{ $grade->id }}" required class="form-control">
                                        <option value="">Select Subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" data-course="{{ $subject->course }}" {{ old('subject_id', $grade->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Semester *</label>
                                    <input type="text" name="semester" required value="{{ old('semester', $grade->semester) }}" class="form-control">
                                    @error('semester')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Academic Year *</label>
                                    <input type="text" name="academic_year" required value="{{ old('academic_year', $grade->academic_year) }}" placeholder="e.g., 2024-2025" class="form-control">
                                    @error('academic_year')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Midterm</label>
                                    <input type="text" name="midterm" value="{{ old('midterm', $grade->midterm) }}" placeholder="e.g., 85.5 or INC" class="form-control">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Finals</label>
                                    <input type="text" name="final" value="{{ old('final', $grade->final) }}" placeholder="e.g., 90.0 or INC" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-buttons">
                        <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('edit-grade-modal-{{ $grade->id }}')">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="modal-btn modal-btn-primary">
                            <i class="fas fa-save"></i> Update Grade
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Create Grade Modal --}}
    <div class="modal" id="create-grade-modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.grades.store') }}">
                @csrf

                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>Add New Grade</p>
                    <h4>Create New Grade</h4>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-graduation-cap"></i> Grade Information</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Course *</label>
                                <select id="course-filter" class="form-control" onchange="filterStudentsByCourse()">
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course }}">{{ $course }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Student *</label>
                                <select name="user_id" id="student-select" required class="form-control" onchange="filterSubjectsByStudent()">
                                    <option value="">Select Student (Choose Course First)</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" data-course="{{ $student->course }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} (ID: {{ $student->student_id }})</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Subject *</label>
                                <select name="subject_id" id="subject-select" required class="form-control">
                                    <option value="">Select Subject (Choose Student First)</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" data-course="{{ $subject->course }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Semester *</label>
                                <input type="text" name="semester" required value="{{ old('semester') }}" class="form-control">
                                @error('semester')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Academic Year *</label>
                                <input type="text" name="academic_year" required value="{{ old('academic_year') }}" placeholder="e.g., 2024-2025" class="form-control">
                                @error('academic_year')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Midterm</label>
                                <input type="text" name="midterm" value="{{ old('midterm') }}" placeholder="e.g., 85.5 or INC" class="form-control">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Finals</label>
                                <input type="text" name="final" value="{{ old('final') }}" placeholder="e.g., 90.0 or INC" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-danger" onclick="closeModal('create-grade-modal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-save"></i> Create Grade
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal (Shared) --}}
    @include('layouts.modals')

@endsection

