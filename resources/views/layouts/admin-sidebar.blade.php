<link rel="stylesheet" href="{{ asset('css/drawer.css') }}">

<div class="nav-toggle-container">
    <button class="nav-toggle-btn" type="button" id="nav-toggle">
        <i class="fas fa-bars"></i> Menu
    </button>
</div>

<!-- Custom Drawer Navigation -->
<div id="drawer-navigation" class="custom-drawer">
    <div class="drawer-header">
        <div class="drawer-logo-section">
            <div class="drawer-title">Student Information System</div>
        </div>
        <button type="button" class="drawer-close" id="drawer-close">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="drawer-content">
        <ul class="drawer-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="drawer-link {{ request()->is('admin/dashboard') || (request()->is('admin') && !request()->is('admin/*')) ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.students.index') }}" class="drawer-link {{ request()->is('admin/students*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Students</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.enrollment.index') }}" class="drawer-link {{ request()->is('admin/enrollment*') ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i>
                    <span>Enrollment</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.teachers.index') }}" class="drawer-link {{ request()->is('admin/teachers*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Teachers</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.subjects.index') }}" class="drawer-link {{ request()->is('admin/subjects*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Subjects</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.grades.index') }}" class="drawer-link {{ request()->is('admin/grades*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Grades</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.class-sessions.index') }}" class="drawer-link {{ request()->is('admin/class-sessions*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Class Sessions</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.attendance.index') }}" class="drawer-link {{ request()->is('admin/attendance*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Attendance</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.announcements.index') }}" class="drawer-link {{ request()->is('admin/announcements*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.requests.index') }}" class="drawer-link {{ request()->is('admin/requests*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Requests</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.student-ids.index') }}" class="drawer-link {{ request()->is('admin/student-ids*') ? 'active' : '' }}">
                    <i class="fas fa-id-card"></i>
                    <span>Institutional IDs</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.messages.index') }}" class="drawer-link {{ request()->is('admin/messages*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Messages</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.archive.index') }}" class="drawer-link {{ request()->is('admin/archive*') ? 'active' : '' }}">
                    <i class="fas fa-archive"></i>
                    <span>Archive</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.settings') }}" class="drawer-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="drawer-profile">
        @php
            $adminName = Auth::user()->name ?? 'Admin';
            $adminInitials = strtoupper(mb_substr($adminName, 0, 1));
            $adminAvatar = Auth::user()->profile_image ?? null;
            $adminOnline = true;
        @endphp
        <div class="drawer-profile-avatar">
            <div class="relative" style="position: relative; display: inline-block;">
                @if ($adminAvatar)
                    <img class="w-10 h-10 rounded-full" src="{{ asset('storage/' . $adminAvatar) }}" alt="{{ $adminName }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                @else
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1C6EA4, #0EA5E9); display: grid; place-items: center; color: #fff; font-weight: 700; font-size: 14px;">
                        {{ $adminInitials }}
                    </div>
                @endif
                @if ($adminOnline)
                    <span class="status-dot" style="position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px; border-radius: 50%; background: #10B981; border: 2px solid #fff;"></span>
                @else
                    <span class="status-dot" style="position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px; border-radius: 50%; background: #EF4444; border: 2px solid #fff;"></span>
                @endif
            </div>
        </div>
        <div class="profile-info">
            <div class="name">{{ $adminName }}</div>
            <div class="role">{{ ucfirst(Auth::user()->role ?? 'Administrator') }}</div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<!-- Drawer Overlay -->
<div id="drawer-overlay" class="drawer-overlay"></div>

<script>
    // Custom Drawer Navigation JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const navToggle = document.getElementById('nav-toggle');
        const drawer = document.getElementById('drawer-navigation');
        const drawerClose = document.getElementById('drawer-close');
        const overlay = document.getElementById('drawer-overlay');

        // Open drawer
        if (navToggle) {
            navToggle.addEventListener('click', function() {
                drawer.classList.add('open');
                overlay.classList.add('open');
                document.body.classList.add('drawer-open');
            });
        }

        // Close drawer
        function closeDrawer() {
            drawer.classList.remove('open');
            overlay.classList.remove('open');
            document.body.classList.remove('drawer-open');
        }

        if (drawerClose) {
            drawerClose.addEventListener('click', closeDrawer);
        }

        if (overlay) {
            overlay.addEventListener('click', closeDrawer);
        }

        // Close drawer on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDrawer();
            }
        });
    });
</script>
