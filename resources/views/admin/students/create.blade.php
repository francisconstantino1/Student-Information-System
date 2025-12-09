@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-width: 800px;">
            <h1 style="color: #1C6EA4; margin-bottom: 24px;">Add New Student</h1>

            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Institutional ID *</label>
                        <input type="text" name="student_id" required value="{{ old('student_id') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        @error('student_id')
                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Full Name *</label>
                        <input type="text" name="name" required value="{{ old('name') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        @error('name')
                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Email *</label>
                    <input type="email" name="email" required value="{{ old('email') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    @error('email')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Password *</label>
                    <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    @error('password')
                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Course *</label>
                        <input type="text" name="course" required value="{{ old('course') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        @error('course')
                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Year Level *</label>
                        <select name="year_level" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
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

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Section</label>
                    <select name="section_id" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Birthday</label>
                        <input type="date" name="birthday" value="{{ old('birthday') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Gender</label>
                        <select name="gender" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Address</label>
                    <textarea name="address" rows="3" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">{{ old('address') }}</textarea>
                </div>

                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('admin.students.index') }}" style="padding: 10px 20px; background: #6B7280; color: white; text-decoration: none; border-radius: 6px;">Cancel</a>
                    <button type="submit" style="padding: 10px 20px; background: #1C6EA4; color: white; border: none; border-radius: 6px; cursor: pointer;">Create Student</button>
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
        }
    </style>
@endsection

