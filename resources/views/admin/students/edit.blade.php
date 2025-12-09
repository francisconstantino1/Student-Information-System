@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); width: 100%; max-width: 1000px; margin: 0 auto;">
            <form method="POST" action="{{ route('admin.students.update', $student) }}">
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
                    <a href="{{ route('admin.students.index') }}" class="modal-btn modal-btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-save"></i> Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
            .form-row {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
