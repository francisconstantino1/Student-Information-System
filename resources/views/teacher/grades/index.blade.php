@extends('layouts.app')

@section('content')
    @include('layouts.teacher-sidebar')
    @include('layouts.datatables')

    <div class="teacher-container" style="padding:24px; padding-top:80px;">
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
                    <h5><i class="fas fa-user"></i> Student & Subject</h5>
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
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-chart-bar"></i> Grades</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Midterm</label>
                                <div class="view-field">{{ $grade->midterm ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Finals</label>
                                <div class="view-field">{{ $grade->final ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Remarks</label>
                                <div class="view-field">{{ $grade->remarks ?? 'N/A' }}</div>
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
                <form method="POST" action="{{ route('teacher.grades.update', $grade) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-header">
                        <h3>Student Information System</h3>
                        <p>Edit Grade Record</p>
                        <h4>Update Grade Details</h4>
                    </div>

                    <div class="form-section">
                        <h5><i class="fas fa-user"></i> Student & Subject</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Select Course</label>
                                    <select id="edit-course-filter-{{ $grade->id }}" class="form-control" onchange="filterEditStudentsByCourse({{ $grade->id }})">
                                        <option value="">All Courses</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course }}" @selected($grade->user && $grade->user->course === $course)>{{ $course }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Student *</label>
                                    <select name="user_id" id="edit-student-select-{{ $grade->id }}" class="form-control" required onchange="filterEditSubjectsByStudent({{ $grade->id }})">
                                        <option value="">Select Student</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}" data-course="{{ $student->course }}" @selected($student->id === $grade->user_id)>
                                                {{ $student->name }} ({{ $student->course ?? 'N/A' }})
                                            </option>
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
                                    <select name="subject_id" id="edit-subject-select-{{ $grade->id }}" class="form-control" required>
                                        <option value="">Select Subject</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}" data-course="{{ $subject->course }}" @selected($subject->id === $grade->subject_id)>
                                                {{ $subject->subject_name }} ({{ $subject->subject_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Semester *</label>
                                    <input type="text" name="semester" required value="{{ old('semester', $grade->semester) }}" class="form-control">
                                    @error('semester')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Academic Year *</label>
                                    <input type="text" name="academic_year" required value="{{ old('academic_year', $grade->academic_year) }}" class="form-control">
                                    @error('academic_year')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5><i class="fas fa-chart-bar"></i> Grades</h5>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Midterm</label>
                                    <input type="text" name="midterm" value="{{ old('midterm', $grade->midterm) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Finals</label>
                                    <input type="text" name="final" value="{{ old('final', $grade->final) }}" class="form-control">
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
            <form method="POST" action="{{ route('teacher.grades.store') }}">
                @csrf

                <div class="form-header">
                    <h3>Student Information System</h3>
                    <p>Add New Grade</p>
                    <h4>Create Grade Record</h4>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-user"></i> Student & Subject</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Select Course</label>
                                <select id="course-filter" class="form-control" onchange="filterStudentsByCourse()">
                                    <option value="">All Courses</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course }}">{{ $course }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Student *</label>
                                <select name="user_id" id="student-select" class="form-control" required onchange="filterSubjectsByStudent()">
                                    <option value="">Select Student (Choose Course First)</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" data-course="{{ $student->course }}">
                                            {{ $student->name }} ({{ $student->course ?? 'N/A' }})
                                        </option>
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
                                <select name="subject_id" id="subject-select" class="form-control" required>
                                    <option value="">Select Subject (Choose Student First)</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" data-course="{{ $subject->course }}">
                                            {{ $subject->subject_name }} ({{ $subject->subject_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Semester *</label>
                                <input type="text" name="semester" required class="form-control">
                                @error('semester')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Academic Year *</label>
                                <input type="text" name="academic_year" required class="form-control">
                                @error('academic_year')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-chart-bar"></i> Grades</h5>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Midterm</label>
                                <input type="text" name="midterm" class="form-control">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Finals</label>
                                <input type="text" name="final" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal('create-grade-modal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-save"></i> Create Grade
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Filter students by course
        function filterStudentsByCourse() {
            const courseSelect = document.getElementById('course-filter');
            const studentSelect = document.getElementById('student-select');
            const selectedCourse = courseSelect.value;

            const allOptions = studentSelect.querySelectorAll('option');

            studentSelect.value = '';
            const subjectSelect = document.getElementById('subject-select');
            if (subjectSelect) {
                subjectSelect.value = '';
            }

            allOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    option.textContent = selectedCourse ? 'Select Student' : 'Select Student (Choose Course First)';
                } else {
                    const studentCourse = option.getAttribute('data-course');
                    option.style.display = (!selectedCourse || studentCourse === selectedCourse) ? 'block' : 'none';
                }
            });

            filterSubjectsByStudent();
        }

        // Filter subjects by selected student's course
        function filterSubjectsByStudent() {
            const studentSelect = document.getElementById('student-select');
            const subjectSelect = document.getElementById('subject-select');
            const selectedStudentId = studentSelect.value;

            if (!subjectSelect) return;

            let studentCourse = null;
            if (selectedStudentId) {
                const selectedStudentOption = studentSelect.querySelector('option[value="' + selectedStudentId + '"]');
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
                    option.textContent = studentCourse ? 'Select Subject' : 'Select Subject (Choose Student First)';
                } else {
                    const subjectCourse = option.getAttribute('data-course');
                    option.style.display = (!studentCourse || subjectCourse === studentCourse) ? 'block' : 'none';
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
                    option.textContent = selectedCourse ? 'Select Student' : 'Select Student (Choose Course First)';
                } else {
                    const studentCourse = option.getAttribute('data-course');
                    option.style.display = (!selectedCourse || studentCourse === selectedCourse) ? 'block' : 'none';
                }
            });

            filterEditSubjectsByStudent(gradeId);
        }

        // Filter subjects by selected student's course in edit modals
        function filterEditSubjectsByStudent(gradeId) {
            const studentSelect = document.getElementById('edit-student-select-' + gradeId);
            const subjectSelect = document.getElementById('edit-subject-select-' + gradeId);
            const selectedStudentId = studentSelect.value;

            if (!subjectSelect) return;

            let studentCourse = null;
            if (selectedStudentId) {
                const selectedStudentOption = studentSelect.querySelector('option[value="' + selectedStudentId + '"]');
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
                    option.textContent = studentCourse ? 'Select Subject' : 'Select Subject (Choose Student First)';
                } else {
                    const subjectCourse = option.getAttribute('data-course');
                    option.style.display = (!studentCourse || subjectCourse === studentCourse) ? 'block' : 'none';
                }
            });
        }
    </script>
@endsection

