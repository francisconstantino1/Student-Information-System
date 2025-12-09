@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')

    <div class="dashboard-root">
        <div class="dashboard-container" style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <h1 style="color: #1C6EA4; margin-bottom: 8px;">Settings</h1>
            <p style="color: #6B7280; margin-bottom: 24px;">Manage your account settings and preferences</p>

            @if (session('success'))
                <div style="background: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="display: flex; gap: 20px; margin-top: 24px;">
                <!-- Tabs -->
                <div style="width: 200px; border-right: 1px solid #E5E7EB; padding-right: 20px;">
                    <button onclick="showTab('student-info')" id="tab-student-info" class="tab-btn active" style="width: 100%; text-align: left; padding: 12px; margin-bottom: 8px; background: #1C6EA4; color: white; border: none; border-radius: 6px; cursor: pointer;">Student Information</button>
                    <button onclick="showTab('account')" id="tab-account" class="tab-btn" style="width: 100%; text-align: left; padding: 12px; margin-bottom: 8px; background: #F3F4F6; color: #374151; border: none; border-radius: 6px; cursor: pointer;">Account Settings</button>
                    <button onclick="showTab('profile')" id="tab-profile" class="tab-btn" style="width: 100%; text-align: left; padding: 12px; margin-bottom: 8px; background: #F3F4F6; color: #374151; border: none; border-radius: 6px; cursor: pointer;">Profile Settings</button>
                    <button onclick="showTab('notifications')" id="tab-notifications" class="tab-btn" style="width: 100%; text-align: left; padding: 12px; margin-bottom: 8px; background: #F3F4F6; color: #374151; border: none; border-radius: 6px; cursor: pointer;">Notifications</button>
                </div>

                <!-- Tab Content -->
                <div style="flex: 1;">
                    <!-- Student Information -->
                    <div id="content-student-info" class="tab-content">
                        <h2 style="color: #111827; margin-bottom: 8px;">Student Information</h2>
                        <p style="color: #6B7280; margin-bottom: 20px;">Complete profile details</p>
                        <div style="position: relative; margin-bottom: 20px; display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                            <button type="button" onclick="editStudentInfo()" id="crudButtons" style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #1C6EA4, #0EA5E9); color: #fff; border: none; border-radius: 10px; padding: 10px 16px; font-weight: 600; box-shadow: 0 6px 16px rgba(12, 74, 110, 0.25); cursor: pointer;">
                                <i class="fas fa-pen"></i> Edit Information
                            </button>
                        </div>

                        <form id="studentInfoForm" method="POST" action="{{ route('student-info.update') }}">
                            @csrf
                            <div class="details-section">
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <span class="detail-label">Address</span>
                                        <span class="detail-value" id="addressDisplay">{{ $user->address ?? 'Not provided' }}</span>
                                        <div id="addressInputWrapper" class="floating-group" style="display: none;">
                                            <input type="text" name="address" id="addressInput" class="floating-input" placeholder=" " value="{{ $user->address ?? '' }}">
                                            <label for="addressInput" class="floating-label">Address</label>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Email Address</span>
                                        <span class="detail-value" id="emailDisplay">{{ $user->email ?? 'Not provided' }}</span>
                                        <div id="emailInputWrapper" class="floating-group" style="display: none;">
                                            <input type="email" name="email" id="emailInput" class="floating-input" placeholder=" " value="{{ $user->email ?? '' }}">
                                            <label for="emailInput" class="floating-label">Email Address</label>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Birthday</span>
                                        @php
                                            $birthday = $user->birthday;
                                            $birthdayFormatted = $birthday instanceof \Illuminate\Support\Carbon ? $birthday->format('F d, Y') : 'Not provided';
                                            $birthdayInputValue = $birthday instanceof \Illuminate\Support\Carbon ? $birthday->format('Y-m-d') : '';
                                        @endphp
                                        <span class="detail-value" id="birthdayDisplay">{{ $birthdayFormatted }}</span>
                                        <div id="birthdayInputWrapper" class="floating-group" style="display: none;">
                                            <input type="date" name="birthday" id="birthdayInput" class="floating-input" placeholder=" " value="{{ $birthdayInputValue }}">
                                            <label for="birthdayInput" class="floating-label">Birthday</label>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Contact Number</span>
                                        <span class="detail-value" id="contactDisplay">{{ $user->contact_number ?? 'Not provided' }}</span>
                                        <div id="contactInputWrapper" class="floating-group" style="display: none;">
                                            <input type="text" name="contact_number" id="contactInput" class="floating-input" placeholder=" " value="{{ $user->contact_number ?? '' }}">
                                            <label for="contactInput" class="floating-label">Contact Number</label>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Gender</span>
                                        <span class="detail-value" id="genderDisplay">{{ $user->gender ?? 'Not provided' }}</span>
                                        <div id="genderInputWrapper" class="floating-group" style="display: none;">
                                            <select name="gender" id="genderInput" class="floating-input" placeholder=" ">
                                                <option value="" {{ $user->gender ? '' : 'selected' }}>Select Gender</option>
                                                <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Other" {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            <label for="genderInput" class="floating-label">Gender</label>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Institutional ID</span>
                                        <span class="detail-value">{{ $user->student_id ?? 'Not Set' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Course</span>
                                        <span class="detail-value">{{ $user->course ?? 'Not Set' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Year Level</span>
                                        <span class="detail-value">{{ $user->year_level ?? 'Not Set' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Section</span>
                                        <span class="detail-value">{{ $user->section->name ?? 'Not Assigned' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Guardian Name</span>
                                        <span class="detail-value" id="guardianNameDisplay">{{ $user->guardian_name ?? 'Not provided' }}</span>
                                        <div id="guardianNameInputWrapper" class="floating-group" style="display: none;">
                                            <input type="text" name="guardian_name" id="guardianNameInput" class="floating-input" placeholder=" " value="{{ $user->guardian_name ?? '' }}">
                                            <label for="guardianNameInput" class="floating-label">Guardian Name</label>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Guardian Contact</span>
                                        <span class="detail-value" id="guardianContactDisplay">{{ $user->guardian_contact ?? 'Not provided' }}</span>
                                        <div id="guardianContactInputWrapper" class="floating-group" style="display: none;">
                                            <input type="text" name="guardian_contact" id="guardianContactInput" class="floating-input" placeholder=" " value="{{ $user->guardian_contact ?? '' }}">
                                            <label for="guardianContactInput" class="floating-label">Guardian Contact</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="crud-buttons" id="saveCancelButtons" style="display: none; position: static; margin-top: 16px; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                                <button type="button" onclick="cancelEdit()" style="background: #F3F4F6; color: #111827; border: 1px solid #E5E7EB; border-radius: 10px; padding: 10px 16px; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                <button type="submit" style="background: #10B981; color: #fff; border: none; border-radius: 10px; padding: 10px 16px; font-weight: 700; cursor: pointer; box-shadow: 0 6px 16px rgba(16, 185, 129, 0.25);">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Account Settings -->
                    <div id="content-account" class="tab-content" style="display: none;">
                        <h2 style="color: #111827; margin-bottom: 20px;">Account Settings</h2>
                        <form method="POST" action="{{ route('settings.account') }}">
                            @csrf
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Current Password</label>
                                <input type="password" name="current_password" required style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                @error('current_password')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">New Password</label>
                                <input type="password" name="password" required style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Confirm New Password</label>
                                <input type="password" name="password_confirmation" required style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            </div>
                            <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">Save Changes</button>
                        </form>
                    </div>

                    <!-- Profile Settings -->
                    <div id="content-profile" class="tab-content" style="display: none;">
                        <h2 style="color: #111827; margin-bottom: 20px;">Profile Settings</h2>
                        <form method="POST" action="{{ route('settings.profile') }}" enctype="multipart/form-data">
                            @csrf
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Profile Image</label>
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 12px; display: block;">
                                @endif
                                <input type="file" name="profile_image" accept="image/*" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Contact Number</label>
                                <input type="text" name="contact_number" value="{{ $user->contact_number ?? '' }}" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Address</label>
                                <textarea name="address" rows="3" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">{{ $user->address ?? '' }}</textarea>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Guardian Name</label>
                                <input type="text" name="guardian_name" value="{{ $user->guardian_name ?? '' }}" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Guardian Contact</label>
                                <input type="text" name="guardian_contact" value="{{ $user->guardian_contact ?? '' }}" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Bio / About Me</label>
                                <textarea name="bio" rows="4" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">{{ $preference->bio ?? '' }}</textarea>
                            </div>
                            <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">Save Changes</button>
                        </form>
                    </div>

                    <!-- Notifications -->
                    <div id="content-notifications" class="tab-content" style="display: none;">
                        <h2 style="color: #111827; margin-bottom: 20px;">Notification Settings</h2>
                        <form method="POST" action="{{ route('settings.notifications') }}">
                            @csrf
                            @php
                                $notifications = $preference->notifications ?? [];
                            @endphp
                            <div style="margin-bottom: 16px;">
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="grade_updates" value="1" {{ ($notifications['grade_updates'] ?? true) ? 'checked' : '' }}>
                                    <span style="color: #374151;">Grade Updates</span>
                                </label>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="enrollment_status" value="1" {{ ($notifications['enrollment_status'] ?? true) ? 'checked' : '' }}>
                                    <span style="color: #374151;">Enrollment Status</span>
                                </label>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="announcements" value="1" {{ ($notifications['announcements'] ?? true) ? 'checked' : '' }}>
                                    <span style="color: #374151;">Announcements</span>
                                </label>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="attendance_alerts" value="1" {{ ($notifications['attendance_alerts'] ?? true) ? 'checked' : '' }}>
                                    <span style="color: #374151;">Attendance Alerts</span>
                                </label>
                            </div>
                            <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">Save Changes</button>
                        </form>
                    </div>

                    <!-- Interface -->
                    <div id="content-interface" class="tab-content" style="display: none;">
                        <h2 style="color: #111827; margin-bottom: 20px;">Interface Settings</h2>
                        <form method="POST" action="{{ route('settings.interface') }}">
                            @csrf
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Theme</label>
                                <select name="theme" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                    <option value="light" {{ ($preference->theme ?? 'light') == 'light' ? 'selected' : '' }}>Light</option>
                                    <option value="dark" {{ ($preference->theme ?? 'light') == 'dark' ? 'selected' : '' }}>Dark</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Language</label>
                                <select name="language" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                    <option value="en" {{ ($preference->language ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="tl" {{ ($preference->language ?? 'en') == 'tl' ? 'selected' : '' }}>Tagalog</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Sidebar Mode</label>
                                <select name="sidebar_mode" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                    <option value="expanded" {{ ($preference->sidebar_mode ?? 'expanded') == 'expanded' ? 'selected' : '' }}>Expanded</option>
                                    <option value="compact" {{ ($preference->sidebar_mode ?? 'expanded') == 'compact' ? 'selected' : '' }}>Compact</option>
                                </select>
                            </div>
                            <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">Save Changes</button>
                        </form>
                    </div>

                    <!-- Documents & Downloads -->
                    <div id="content-documents" class="tab-content" style="display: none;">
                        <h2 style="color: #111827; margin-bottom: 20px;">Documents & Downloads</h2>
                        <div style="margin-bottom: 24px;">
                            <h3 style="color: #374151; margin-bottom: 12px;">Upload School Documents</h3>
                            <a href="{{ route('documents') }}" style="display: inline-block; background: #1C6EA4; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none;">Go to Documents Page</a>
                        </div>
                        <div>
                            <h3 style="color: #374151; margin-bottom: 12px;">Download Forms</h3>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <button style="text-align: left; padding: 12px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 6px; cursor: pointer;">📄 Certificate of Registration</button>
                                <button style="text-align: left; padding: 12px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 6px; cursor: pointer;">📄 Registration Form</button>
                                <button style="text-align: left; padding: 12px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 6px; cursor: pointer;">📄 Certificate of Enrollment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .dashboard-root {
                padding-top: 70px;
            }
        }

        .details-section {
            margin-top: 16px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 12px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding: 12px;
            background: #F9FAFB;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #6B7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 0.9rem;
            color: #111827;
            font-weight: 500;
        }

        .crud-buttons {
            display: flex;
            gap: 6px;
        }

        .crud-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .crud-btn-edit {
            background: #1C6EA4;
            color: #FFFFFF;
        }

        .crud-btn-edit:hover {
            background: #155A8A;
            transform: scale(1.05);
        }

        .crud-btn-save {
            background: #10B981;
            color: #FFFFFF;
        }

        .crud-btn-save:hover {
            background: #059669;
        }

        .crud-btn-cancel {
            background: #6B7280;
            color: #FFFFFF;
        }

        .crud-btn-cancel:hover {
            background: #4B5563;
        }

        .floating-group {
            position: relative;
            width: 100%;
        }

        .floating-input {
            padding: 12px 0 6px;
            border: none;
            border-bottom: 2px solid #D1D5DB;
            width: 100%;
            background: transparent;
            font-size: 0.95rem;
            color: #111827;
            transition: all 0.2s ease;
        }

        .floating-input:focus {
            outline: none;
            border-bottom-color: #1C6EA4;
            box-shadow: none;
        }

        .floating-label {
            position: absolute;
            left: 0;
            top: 10px;
            font-size: 0.9rem;
            color: #6B7280;
            pointer-events: none;
            transition: all 0.2s ease;
        }

        .floating-input:focus + .floating-label,
        .floating-input:not(:placeholder-shown) + .floating-label,
        select.floating-input:focus + .floating-label,
        select.floating-input:not([value=""]) + .floating-label {
            transform: translateY(-14px);
            font-size: 0.75rem;
            color: #1C6EA4;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.style.background = '#F3F4F6';
                btn.style.color = '#374151';
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).style.display = 'block';
            
            // Add active class to selected tab
            const activeBtn = document.getElementById('tab-' + tabName);
            activeBtn.style.background = '#1C6EA4';
            activeBtn.style.color = 'white';
        }

        function editStudentInfo() {
            // Hide all display elements (excluding read-only: Institutional ID, Course, Year Level, Section)
            const displayElements = ['addressDisplay', 'emailDisplay', 'birthdayDisplay', 'contactDisplay', 'genderDisplay', 'guardianNameDisplay', 'guardianContactDisplay'];
            displayElements.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
            
            // Show all input wrappers
            const inputWrappers = ['addressInputWrapper', 'emailInputWrapper', 'birthdayInputWrapper', 'contactInputWrapper', 'genderInputWrapper', 'guardianNameInputWrapper', 'guardianContactInputWrapper'];
            inputWrappers.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'block';
            });
            
            // Toggle buttons
            document.getElementById('crudButtons').style.display = 'none';
            document.getElementById('saveCancelButtons').style.display = 'flex';
        }

        function cancelEdit() {
            // Show all display elements
            const displayElements = ['addressDisplay', 'emailDisplay', 'birthdayDisplay', 'contactDisplay', 'genderDisplay', 'guardianNameDisplay', 'guardianContactDisplay'];
            displayElements.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'block';
            });
            
            // Hide all input wrappers
            const inputWrappers = ['addressInputWrapper', 'emailInputWrapper', 'birthdayInputWrapper', 'contactInputWrapper', 'genderInputWrapper', 'guardianNameInputWrapper', 'guardianContactInputWrapper'];
            inputWrappers.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
            
            // Toggle buttons
            document.getElementById('crudButtons').style.display = 'flex';
            document.getElementById('saveCancelButtons').style.display = 'none';
        }
    </script>
@endsection
