@extends('layouts.app')

@section('content')
    @include('layouts.teacher-sidebar')

    <style>
        .teacher-container { width: 100%; padding: 24px; padding-top: 80px; background: #F3F4F6; }
        .settings-card {
            background: #FFFFFF; border-radius: 16px; padding: 32px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1); border: 1px solid #E5E7EB;
            max-width: 1300px; margin: 0 auto;
        }
        .tab-btn { width: 100%; text-align: left; padding: 12px; margin-bottom: 8px; background: #F3F4F6; color: #374151; border: none; border-radius: 6px; cursor: pointer; }
        .tab-btn.active { background: #1C6EA4; color: #fff; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-input { width: 100%; max-width: 500px; padding: 10px 12px; border: 1px solid #D1D5DB; border-radius: 8px; background: #F9FAFB; }
        .info-box { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; padding: 12px; }
    </style>

    <div class="teacher-container">
        <div class="settings-card">
            <h1 style="color:#1C6EA4; margin-bottom:8px;">Settings</h1>
            <p style="color:#6B7280; margin-bottom:24px;">Manage your account settings.</p>

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

            <div style="display:flex; gap:20px; margin-top: 24px;">
                <div style="width:200px; border-right:1px solid #E5E7EB; padding-right:20px;">
                    <button onclick="showTab('profile')" id="tab-profile" class="tab-btn active"><i class="fas fa-user"></i> Profile</button>
                    <button onclick="showTab('account')" id="tab-account" class="tab-btn"><i class="fas fa-key"></i> Account</button>
                    <button onclick="showTab('notifications')" id="tab-notifications" class="tab-btn"><i class="fas fa-bell"></i> Notifications</button>
                </div>

                <div style="flex:1;">
                    @php $user = Auth::user(); @endphp

                    <div id="content-profile" class="tab-content">
                        <h2 style="margin-bottom:8px;">Profile</h2>
                        <p style="color:#6B7280; margin-bottom:20px;">Update your personal information</p>
                        <form method="POST" action="{{ route('teacher.settings.profile') }}" enctype="multipart/form-data" style="display: grid; gap: 16px;">
                            @csrf
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
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-input" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-input" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}">
                                @error('contact_number')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="content-account" class="tab-content" style="display:none;">
                        <h2 style="margin-bottom:8px;">Account</h2>
                        <p style="color:#6B7280; margin-bottom:20px;">Change your password</p>
                        <form method="POST" action="{{ route('teacher.settings.password') }}" style="display:grid; gap:16px;">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-input" required>
                                @error('current_password')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-input" required minlength="8">
                                @error('password')
                                    <p style="color: #EF4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-input" required minlength="8">
                            </div>
                            <div>
                                <button type="submit" style="background: #1C6EA4; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="content-notifications" class="tab-content" style="display:none;">
                        <h2 style="margin-bottom:8px;">Notifications</h2>
                        <p style="color:#6B7280; margin-bottom:20px;">Notification preferences are managed by admin.</p>
                        <div class="info-box">
                            <div style="color:#111827; font-weight:600;">Notifications</div>
                            <div style="color:#6B7280;">All notifications are enabled by default.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tab) {
            ['profile','account','notifications'].forEach(name => {
                document.getElementById('content-' + name).style.display = name === tab ? 'block' : 'none';
                const btn = document.getElementById('tab-' + name);
                if (btn) btn.classList.toggle('active', name === tab);
            });
        }
    </script>
@endsection

