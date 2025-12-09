@extends('layouts.app')

@section('content')
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

        .enrollment-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px;
        }

        .enrollment-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .enrollment-header h1 {
            font-size: 2rem;
            color: #0046FF;
            margin-bottom: 8px;
        }

        .enrollment-header p {
            color: #6B7280;
            font-size: 0.95rem;
        }

        .enrollment-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 70, 255, 0.1);
            border: 1px solid rgba(0, 70, 255, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #111827;
            background: #FFFFFF;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0046FF;
            box-shadow: 0 0 0 3px rgba(0, 70, 255, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .error-text {
            color: #DC2626;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .success-message {
            background: #D1FAE5;
            color: #065F46;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #A7F3D0;
        }

        .btn-submit {
            width: 100%;
            padding: 12px 24px;
            background: linear-gradient(135deg, #0046FF, #0033CC);
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 70, 255, 0.3);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="enrollment-container">
        <div class="enrollment-header">
            <h1>Student Enrollment Form</h1>
            <p>Please fill out all required fields to complete your enrollment</p>
        </div>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background: #FEE2E2; color: #991B1B; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #FCA5A5;">
                {{ session('error') }}
            </div>
        @endif

        @if (session('info'))
            <div style="background: #DBEAFE; color: #1E40AF; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #93C5FD;">
                {{ session('info') }}
            </div>
        @endif

        @if (!Auth::user()->student_id)
            <div style="background: #FEF3C7; color: #92400E; padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #FCD34D;">
                <h3 style="margin: 0 0 8px 0; font-size: 1.1rem;">⚠️ Institutional ID Required</h3>
                <p style="margin: 0; font-size: 0.9rem;">You must have a valid admin-assigned Institutional ID to access the enrollment form. Please contact the administrator to obtain your Institutional ID.</p>
            </div>
        @endif

        <div class="enrollment-card">
            <form method="POST" action="{{ route('enrollment.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="full_name">Full Name <span style="color: #DC2626;">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                    @error('full_name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Address <span style="color: #DC2626;">*</span></label>
                    <textarea id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address <span style="color: #DC2626;">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="birthday">Birthday <span style="color: #DC2626;">*</span></label>
                        <input type="date" id="birthday" name="birthday" value="{{ old('birthday') }}" required>
                        @error('birthday')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender <span style="color: #DC2626;">*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="previous_school">Previous School</label>
                        <input type="text" id="previous_school" name="previous_school" value="{{ old('previous_school') }}">
                        @error('previous_school')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="course_selected">Course Selected <span style="color: #DC2626;">*</span></label>
                        <select id="course_selected" name="course_selected" required>
                            <option value="">Select Course</option>
                            <option value="Computer Science" {{ old('course_selected') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                            <option value="Information Technology" {{ old('course_selected') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                            <option value="Business Administration" {{ old('course_selected') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                            <option value="Engineering" {{ old('course_selected') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="Education" {{ old('course_selected') == 'Education' ? 'selected' : '' }}>Education</option>
                        </select>
                        @error('course_selected')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="year_level">Year Level <span style="color: #DC2626;">*</span></label>
                        <select id="year_level" name="year_level" required>
                            <option value="">Select Year Level</option>
                            <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                        @error('year_level')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label style="font-size: 1rem; font-weight: 600; color: #0046FF; margin-top: 24px; margin-bottom: 16px;">Guardian Information</label>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="guardian_name">Guardian Name <span style="color: #DC2626;">*</span></label>
                        <input type="text" id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}" required>
                        @error('guardian_name')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="guardian_contact">Guardian Contact <span style="color: #DC2626;">*</span></label>
                        <input type="text" id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact') }}" required>
                        @error('guardian_contact')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit">Submit Enrollment</button>
            </form>
        </div>
    </div>
@endsection

