<link rel="stylesheet" href="{{ asset('css/drawer.css') }}">
<style>
    .nav-toggle-container {
        position: fixed;
        top: 16px;
        left: 16px;
        z-index: 1200;
    }

    .custom-drawer {
        z-index: 1300;
    }

    .drawer-overlay {
        z-index: 1250;
    }
</style>

<div class="nav-toggle-container">
    <button class="nav-toggle-btn" type="button" id="nav-toggle">
        <i class="fas fa-bars"></i> Menu
    </button>
</div>

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
                <a href="{{ route('teacher.dashboard') }}" class="drawer-link {{ request()->is('teacher/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.students.index') }}" class="drawer-link {{ request()->is('teacher/students') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Students</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.subjects.index') }}" class="drawer-link {{ request()->is('teacher/subjects') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Subjects</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.grades.index') }}" class="drawer-link {{ request()->is('teacher/grades') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Grades</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.attendance.index') }}" class="drawer-link {{ request()->is('teacher/attendance') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Attendance</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.messages.index') }}" class="drawer-link {{ request()->is('teacher/messages') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Messages</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.settings.index') }}" class="drawer-link {{ request()->is('teacher/settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="drawer-profile">
        <div class="drawer-profile-avatar">
            @php
                $name = Auth::user()->name ?? 'Teacher';
                $initials = strtoupper(mb_substr($name, 0, 1));
                $avatarUrl = Auth::user()->profile_image ?? null;
                $isOnline = true;
            @endphp
            <div class="relative" style="position: relative; display: inline-block;">
                @if ($avatarUrl)
                    <img class="w-10 h-10 rounded-full" src="{{ asset('storage/' . $avatarUrl) }}" alt="{{ $name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                @else
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1C6EA4, #0EA5E9); display: grid; place-items: center; color: #fff; font-weight: 700; font-size: 14px;">
                        {{ $initials }}
                    </div>
                @endif
                @if ($isOnline)
                    <span class="status-dot" style="position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px; border-radius: 50%; background: #10B981; border: 2px solid #fff;"></span>
                @else
                    <span class="status-dot" style="position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px; border-radius: 50%; background: #EF4444; border: 2px solid #fff;"></span>
                @endif
            </div>
        </div>
        <div class="profile-info">
            <div class="name">{{ $name }}</div>
            <div class="role">Teacher</div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<div id="drawer-overlay" class="drawer-overlay"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navToggle = document.getElementById('nav-toggle');
        const drawer = document.getElementById('drawer-navigation');
        const drawerClose = document.getElementById('drawer-close');
        const overlay = document.getElementById('drawer-overlay');

        function closeDrawer() {
            drawer.classList.remove('open');
            overlay.classList.remove('open');
            document.body.classList.remove('drawer-open');
        }

        if (navToggle) {
            navToggle.addEventListener('click', function() {
                drawer.classList.add('open');
                overlay.classList.add('open');
                document.body.classList.add('drawer-open');
            });
        }

        if (drawerClose) {
            drawerClose.addEventListener('click', closeDrawer);
        }

        if (overlay) {
            overlay.addEventListener('click', closeDrawer);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDrawer();
            }
        });
    });
</script>

