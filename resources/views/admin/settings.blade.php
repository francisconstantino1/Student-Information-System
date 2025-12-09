@extends('layouts.app')

@section('content')
    @include('layouts.admin-sidebar')

    <div class="admin-container">
        <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); width: 100%; max-width: 1300px; margin: 0 auto;">
            <h1 style="color: #1C6EA4; margin-bottom: 8px;">Settings</h1>
            <p style="color: #6B7280; margin-bottom: 24px;">Manage your account settings and preferences</p>

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

            <div style="display: flex; gap: 20px; margin-top: 24px;">
                <!-- Tabs -->
                <div style="width: 200px; border-right: 1px solid #E5E7EB; padding-right: 20px;">
                    <button onclick="showTab('profile')" id="tab-profile" class="tab-btn active" style="width: 100%; text-align: left; padding: 12px; margin-bottom: 8px; background: #1C6EA4; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        <i class="fas fa-user"></i> Profile
                    </button>
                    <button onclick="showTab('account')" id="tab-account" class="tab-btn" style="width: 100%; text-align: left; padding: 12px; margin-bottom: 8px; background: #F3F4F6; color: #374151; border: none; border-radius: 6px; cursor: pointer;">
                        <i class="fas fa-key"></i> Account
                    </button>
                    <button onclick="showTab('notifications')" id="tab-notifications" class="tab-btn" style="width: 100%; text-align: left; padding: 12px; margin-bottom: 8px; background: #F3F4F6; color: #374151; border: none; border-radius: 6px; cursor: pointer;">
                        <i class="fas fa-bell"></i> Notifications
                    </button>
                </div>

                <!-- Tab Content -->
                <div style="flex: 1;">
                    @php
                        $user = Auth::user();
                    @endphp

                    <!-- Profile Settings -->
                    <div id="content-profile" class="tab-content">
                        <h2 style="color: #111827; margin-bottom: 8px;">Profile Settings</h2>
                        <p style="color: #6B7280; margin-bottom: 20px;">Update your personal information</p>
                        
                        <form method="POST" action="{{ route('admin.settings.profile') }}" enctype="multipart/form-data">
                            @csrf
                            <div style="display: grid; gap: 16px;">
                                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                                    <div style="position: relative; width: 56px; height: 56px;">
                                        @if ($user->profile_image)
                                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 1px solid #E5E7EB;">
                                        @else
                                            <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #1C6EA4, #0EA5E9); display: grid; place-items: center; color: #fff; font-weight: 700;">
                                                {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div style="flex: 1; min-width: 220px;">
                                        <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Profile Picture</label>
                                        <input type="file" name="profile_image" accept="image/*" style="width: 100%; max-width: 320px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                        <p style="color: #6B7280; font-size: 0.85rem; margin-top: 4px;">PNG/JPG, max 2MB.</p>
                                        @error('profile_image')
                                            <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Full Name</label>
                                    <input type="text" name="name" value="{{ $user->name }}" required style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                    @error('name')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Email Address</label>
                                    <input type="email" name="email" value="{{ $user->email }}" required style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                    @error('email')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Contact Number</label>
                                    <input type="text" name="contact_number" value="{{ $user->contact_number ?? '' }}" style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                    @error('contact_number')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Account Settings -->
                    <div id="content-account" class="tab-content" style="display: none;">
                        <h2 style="color: #111827; margin-bottom: 8px;">Account Settings</h2>
                        <p style="color: #6B7280; margin-bottom: 20px;">Change your password and security settings</p>
                        
                        <form method="POST" action="{{ route('admin.settings.password') }}">
                            @csrf
                            <div style="display: grid; gap: 16px;">
                                <div>
                                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Current Password</label>
                                    <input type="password" name="current_password" required style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                    @error('current_password')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">New Password</label>
                                    <input type="password" name="password" required minlength="8" style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                    @error('password')
                                        <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" required minlength="8" style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                </div>
                                <div>
                                    <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                        <i class="fas fa-key"></i> Update Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Notification Settings -->
                    <div id="content-notifications" class="tab-content" style="display: none;">
                        <h2 style="color: #111827; margin-bottom: 8px;">Notification Settings</h2>
                        <p style="color: #6B7280; margin-bottom: 20px;">Manage your notification preferences</p>
                        
                        <form method="POST" action="{{ route('admin.settings.notifications') }}">
                            @csrf
                            <div style="display: grid; gap: 16px;">
                                <div style="padding: 16px; background: #F9FAFB; border-radius: 8px; border: 1px solid #E5E7EB;">
                                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                        <input type="checkbox" name="enrollment_notifications" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                                        <div>
                                            <div style="font-weight: 500; color: #111827;">Enrollment Notifications</div>
                                            <div style="font-size: 0.875rem; color: #6B7280;">Receive notifications when students submit enrollment requests</div>
                                        </div>
                                    </label>
                                </div>
                                <div style="padding: 16px; background: #F9FAFB; border-radius: 8px; border: 1px solid #E5E7EB;">
                                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                        <input type="checkbox" name="request_notifications" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                                        <div>
                                            <div style="font-weight: 500; color: #111827;">Request Notifications</div>
                                            <div style="font-size: 0.875rem; color: #6B7280;">Receive notifications when students submit shift requests</div>
                                        </div>
                                    </label>
                                </div>
                                <div style="padding: 16px; background: #F9FAFB; border-radius: 8px; border: 1px solid #E5E7EB;">
                                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                        <input type="checkbox" name="message_notifications" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                                        <div>
                                            <div style="font-weight: 500; color: #111827;">Message Notifications</div>
                                            <div style="font-size: 0.875rem; color: #6B7280;">Receive notifications when students send messages</div>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                        <i class="fas fa-save"></i> Save Preferences
                                    </button>
                                </div>
                            </div>
                        </form>
                </div>

                    {{-- Preferences and System Info removed --}}
                    {{-- <div id="content-preferences" class="tab-content" style="display: none;">
                        <h2 style="color: #111827; margin-bottom: 8px;">Preferences</h2>
                        <p style="color: #6B7280; margin-bottom: 20px;">Customize your interface and system preferences</p>
                        
                        <form method="POST" action="{{ route('admin.settings.preferences') }}">
                            @csrf
                            <div style="display: grid; gap: 20px;">
                                <div style="background: #F9FAFB; padding: 20px; border-radius: 12px; border: 1px solid #E5E7EB;">
                                    <h3 style="color: #374151; margin-bottom: 16px; font-size: 1rem; font-weight: 600;">Display Settings</h3>
                                    <div style="display: grid; gap: 16px;">
                                        <div>
                                            <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Theme</label>
                                            <select name="theme" style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                                <option value="light" {{ ($preference->theme ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                                                <option value="dark" {{ ($preference->theme ?? 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                                                <option value="auto" {{ ($preference->theme ?? 'light') === 'auto' ? 'selected' : '' }}>Auto (System)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Language</label>
                                            <select name="language" style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                                <option value="en" {{ ($preference->language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                                <option value="tl" {{ ($preference->language ?? 'en') === 'tl' ? 'selected' : '' }}>Tagalog</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Items Per Page</label>
                                            <select name="items_per_page" style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Date Format</label>
                                            <select name="date_format" style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                                <option value="Y-m-d" selected>YYYY-MM-DD</option>
                                                <option value="m/d/Y">MM/DD/YYYY</option>
                                                <option value="d/m/Y">DD/MM/YYYY</option>
                                                <option value="M d, Y">Jan 01, 2024</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 6px; color: #374151; font-weight: 500;">Time Format</label>
                                            <select name="time_format" style="width: 100%; max-width: 500px; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                                                <option value="12" selected>12 Hour (AM/PM)</option>
                                                <option value="24">24 Hour</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div style="background: #F9FAFB; padding: 20px; border-radius: 12px; border: 1px solid #E5E7EB;">
                                    <h3 style="color: #374151; margin-bottom: 16px; font-size: 1rem; font-weight: 600;">Dashboard Settings</h3>
                                    <div style="display: grid; gap: 16px;">
                                        <div>
                                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                                <input type="checkbox" name="show_statistics" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                                                <span style="color: #374151; font-weight: 500;">Show Statistics on Dashboard</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                                <input type="checkbox" name="show_recent_activity" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                                                <span style="color: #374151; font-weight: 500;">Show Recent Activity</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                                <input type="checkbox" name="auto_refresh" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                                                <span style="color: #374151; font-weight: 500;">Auto-refresh Data Tables</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                        <i class="fas fa-save"></i> Save Preferences
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- System Information -->
                    <div id="content-system" class="tab-content" style="display: none;">
                        <h2 style="color: #111827; margin-bottom: 8px;">System Information</h2>
                        <p style="color: #6B7280; margin-bottom: 20px;">View system details and statistics</p>
                        
                        <div style="display: grid; gap: 20px;">
                            <!-- System Details -->
                            <div style="background: #F9FAFB; padding: 20px; border-radius: 12px; border: 1px solid #E5E7EB;">
                                <h3 style="color: #374151; margin-bottom: 16px; font-size: 1.125rem; font-weight: 600;">
                                    <i class="fas fa-server" style="color: #1C6EA4; margin-right: 8px;"></i>System Details
                                </h3>
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Laravel Version</div>
                                        <div style="font-weight: 500; color: #111827;">{{ app()->version() }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">PHP Version</div>
                                        <div style="font-weight: 500; color: #111827;">{{ PHP_VERSION }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Environment</div>
                                        <div style="font-weight: 500; color: #111827;">{{ app()->environment() }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Server Time</div>
                                        <div style="font-weight: 500; color: #111827;">{{ now()->format('M d, Y h:i A') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div style="background: #F9FAFB; padding: 20px; border-radius: 12px; border: 1px solid #E5E7EB;">
                                <h3 style="color: #374151; margin-bottom: 16px; font-size: 1.125rem; font-weight: 600;">
                                    <i class="fas fa-chart-bar" style="color: #1C6EA4; margin-right: 8px;"></i>System Statistics
                                </h3>
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                                    @php
                                        $totalStudents = \App\Models\User::where('role', 'student')->count();
                                        $totalTeachers = \App\Models\User::where('role', 'teacher')->count();
                                        $totalSections = \App\Models\Section::count();
                                        $totalSubjects = \App\Models\Subject::count();
                                        $pendingEnrollments = \App\Models\Enrollment::where('status', 'pending')->count();
                                        $approvedEnrollments = \App\Models\Enrollment::where('status', 'approved')->count();
                                        $pendingRequests = \App\Models\StudentRequest::where('status', 'pending')->count();
                                        $totalAnnouncements = \App\Models\Announcement::count();
                                    @endphp
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Total Students</div>
                                        <div style="font-weight: 600; color: #1C6EA4; font-size: 1.25rem;">{{ number_format($totalStudents) }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Total Teachers</div>
                                        <div style="font-weight: 600; color: #10B981; font-size: 1.25rem;">{{ number_format($totalTeachers) }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Total Sections</div>
                                        <div style="font-weight: 600; color: #F59E0B; font-size: 1.25rem;">{{ number_format($totalSections) }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Total Subjects</div>
                                        <div style="font-weight: 600; color: #8B5CF6; font-size: 1.25rem;">{{ number_format($totalSubjects) }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Pending Enrollments</div>
                                        <div style="font-weight: 600; color: #EF4444; font-size: 1.25rem;">{{ number_format($pendingEnrollments) }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Approved Enrollments</div>
                                        <div style="font-weight: 600; color: #10B981; font-size: 1.25rem;">{{ number_format($approvedEnrollments) }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Pending Requests</div>
                                        <div style="font-weight: 600; color: #F59E0B; font-size: 1.25rem;">{{ number_format($pendingRequests) }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Total Announcements</div>
                                        <div style="font-weight: 600; color: #6366F1; font-size: 1.25rem;">{{ number_format($totalAnnouncements) }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Activity -->
                            <div style="background: #F9FAFB; padding: 20px; border-radius: 12px; border: 1px solid #E5E7EB;">
                                <h3 style="color: #374151; margin-bottom: 16px; font-size: 1.125rem; font-weight: 600;">
                                    <i class="fas fa-user-clock" style="color: #1C6EA4; margin-right: 8px;"></i>Account Activity
                                </h3>
                                <div style="display: grid; gap: 12px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 8px;">
                                        <div>
                                            <div style="font-weight: 500; color: #111827;">Account Created</div>
                                            <div style="font-size: 0.875rem; color: #6B7280;">{{ $user->created_at->format('M d, Y h:i A') }}</div>
                                        </div>
                                        <div style="color: #6B7280;">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 8px;">
                                        <div>
                                            <div style="font-weight: 500; color: #111827;">Last Updated</div>
                                            <div style="font-size: 0.875rem; color: #6B7280;">{{ $user->updated_at->format('M d, Y h:i A') }}</div>
                                        </div>
                                        <div style="color: #6B7280;">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 8px;">
                                        <div>
                                            <div style="font-weight: 500; color: #111827;">Role</div>
                                            <div style="font-size: 0.875rem; color: #6B7280;">{{ ucfirst($user->role) }}</div>
                                        </div>
                                        <div style="color: #6B7280;">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Links -->
                            <div style="background: #F9FAFB; padding: 20px; border-radius: 12px; border: 1px solid #E5E7EB;">
                                <h3 style="color: #374151; margin-bottom: 16px; font-size: 1.125rem; font-weight: 600;">
                                    <i class="fas fa-link" style="color: #1C6EA4; margin-right: 8px;"></i>Quick Links
                                </h3>
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                                    <a href="{{ route('admin.students.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border-radius: 8px; text-decoration: none; color: #111827; border: 1px solid #E5E7EB; transition: all 0.2s;">
                                        <i class="fas fa-users" style="color: #1C6EA4; font-size: 1.25rem;"></i>
                                        <span style="font-weight: 500;">Manage Students</span>
                                    </a>
                                    <a href="{{ route('admin.teachers.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border-radius: 8px; text-decoration: none; color: #111827; border: 1px solid #E5E7EB; transition: all 0.2s;">
                                        <i class="fas fa-chalkboard-teacher" style="color: #10B981; font-size: 1.25rem;"></i>
                                        <span style="font-weight: 500;">Manage Teachers</span>
                                    </a>
                                    <a href="{{ route('admin.subjects.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border-radius: 8px; text-decoration: none; color: #111827; border: 1px solid #E5E7EB; transition: all 0.2s;">
                                        <i class="fas fa-book" style="color: #F59E0B; font-size: 1.25rem;"></i>
                                        <span style="font-weight: 500;">Manage Subjects</span>
                                    </a>
                                    <a href="{{ route('admin.announcements.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border-radius: 8px; text-decoration: none; color: #111827; border: 1px solid #E5E7EB; transition: all 0.2s;">
                                        <i class="fas fa-bullhorn" style="color: #8B5CF6; font-size: 1.25rem;"></i>
                                        <span style="font-weight: 500;">Announcements</span>
                                    </a>
                                    <a href="{{ route('admin.requests.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border-radius: 8px; text-decoration: none; color: #111827; border: 1px solid #E5E7EB; transition: all 0.2s;">
                                        <i class="fas fa-clipboard-list" style="color: #EF4444; font-size: 1.25rem;"></i>
                                        <span style="font-weight: 500;">Student Requests</span>
                                    </a>
                                    <a href="{{ route('admin.archive.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border-radius: 8px; text-decoration: none; color: #111827; border: 1px solid #E5E7EB; transition: all 0.2s;">
                                        <i class="fas fa-archive" style="color: #6B7280; font-size: 1.25rem;"></i>
                                        <span style="font-weight: 500;">Archive</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Database Information -->
                            <div style="background: #F9FAFB; padding: 20px; border-radius: 12px; border: 1px solid #E5E7EB;">
                                <h3 style="color: #374151; margin-bottom: 16px; font-size: 1.125rem; font-weight: 600;">
                                    <i class="fas fa-database" style="color: #1C6EA4; margin-right: 8px;"></i>Database Information
                                </h3>
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Database Driver</div>
                                        <div style="font-weight: 500; color: #111827;">{{ ucfirst(config('database.default')) }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Database Name</div>
                                        <div style="font-weight: 500; color: #111827;">{{ config('database.connections.' . config('database.default') . '.database') }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Timezone</div>
                                        <div style="font-weight: 500; color: #111827;">{{ config('app.timezone') }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 4px;">Locale</div>
                                        <div style="font-weight: 500; color: #111827;">{{ config('app.locale') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .admin-container {
                padding-top: 70px;
            }
            
            .tab-content > div > div[style*="grid-template-columns: repeat(2, 1fr)"] {
                grid-template-columns: 1fr !important;
            }
        }

        .tab-btn {
            transition: all 0.2s ease;
        }

        .tab-btn:hover {
            transform: translateX(4px);
        }

        .tab-content a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-color: #1C6EA4;
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
    </script>
@endsection
