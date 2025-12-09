@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-width: 800px;">
            <h1 style="color: #1C6EA4; margin-bottom: 24px;">Edit Grade</h1>

            <form method="POST" action="{{ route('admin.grades.update', $grade) }}">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Student *</label>
                        <select name="user_id" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('user_id', $grade->user_id) == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->student_id }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Subject *</label>
                        <select name="subject_id" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $grade->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Semester *</label>
                        <input type="text" name="semester" required value="{{ old('semester', $grade->semester) }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        @error('semester')
                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Academic Year *</label>
                        <input type="text" name="academic_year" required value="{{ old('academic_year', $grade->academic_year) }}" placeholder="e.g., 2024-2025" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        @error('academic_year')
                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Midterm</label>
                        <input type="text" name="midterm" value="{{ old('midterm', $grade->midterm) }}" placeholder="e.g., 85.5 or INC" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Finals</label>
                        <input type="text" name="final" value="{{ old('final', $grade->final) }}" placeholder="e.g., 90.0 or INC" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    </div>
                </div>

                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('admin.grades.index') }}" style="padding: 10px 20px; background: #6B7280; color: white; text-decoration: none; border-radius: 6px;">Cancel</a>
                    <button type="submit" style="padding: 10px 20px; background: #1C6EA4; color: white; border: none; border-radius: 6px; cursor: pointer;">Update Grade</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
            div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
            div[style*="grid-template-columns: 1fr 1fr 1fr 1fr"] {
                grid-template-columns: 1fr 1fr !important;
            }
        }
    </style>
@endsection

