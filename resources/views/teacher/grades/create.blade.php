@extends('layouts.app')

@section('content')
    <style>
        .teacher-container {
            width: 100%;
            padding: 24px;
            padding-top: 80px;
            background: #F3F4F6;
        }

        .form-card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #E5E7EB;
            max-width: 640px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 6px;
        }

        select, input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }

        .btn-primary {
            padding: 10px 14px;
            background: #1C6EA4;
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-secondary {
            padding: 10px 14px;
            background: #E5E7EB;
            color: #111827;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    @include('layouts.teacher-sidebar')

    <div class="teacher-container">
        <div style="margin-bottom:16px;">
            <h1 style="margin:0;color:#1F2937;font-size:1.4rem;">Add Grade</h1>
        </div>

        <div class="form-card">
            <form method="POST" action="{{ route('teacher.grades.store') }}">
                @csrf

                <div class="form-group">
                    <label for="user_id">Student</label>
                    <select name="user_id" id="user_id" required>
                        <option value="">Select student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->course ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject_id">Subject</label>
                    <select name="subject_id" id="subject_id" required>
                        <option value="">Select subject</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }} ({{ $subject->subject_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="semester">Semester</label>
                    <input type="text" name="semester" id="semester" required>
                </div>

                <div class="form-group">
                    <label for="academic_year">Academic Year</label>
                    <input type="text" name="academic_year" id="academic_year" required>
                </div>

                <div class="form-group">
                    <label for="midterm">Midterm</label>
                    <input type="text" name="midterm" id="midterm">
                </div>

                <div class="form-group">
                    <label for="final">Final</label>
                    <input type="text" name="final" id="final">
                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary">Save</button>
                    <a href="{{ route('teacher.grades.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

