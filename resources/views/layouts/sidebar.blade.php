<link rel="stylesheet" href="{{ asset('css/drawer.css') }}">

<div class="nav-toggle-container">
    <button class="nav-toggle-btn" type="button" id="nav-toggle">
        <i class="fas fa-bars"></i> Menu
    </button>
</div>

<!-- Custom Drawer Navigation -->
<div id="drawer-navigation" class="custom-drawer">
    <div class="drawer-header">
        @php
            $currentRole = Auth::user()->role ?? 'student';
            $dashboardRoute = $currentRole === 'teacher'
                ? route('teacher.dashboard')
                : ($currentRole === 'admin' ? route('admin.dashboard') : route('dashboard'));
        @endphp
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
                <a href="{{ $dashboardRoute }}" class="drawer-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('instructors') }}" class="drawer-link {{ request()->is('instructors') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Instructors</span>
                </a>
            </li>

            <li>
                <a href="{{ route('academics') }}" class="drawer-link {{ request()->is('academics') || request()->is('courses') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Schedule</span>
                </a>
            </li>

            <li>
                <a href="{{ route('attendance') }}" class="drawer-link {{ request()->is('attendance') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Attendance</span>
                </a>
            </li>

            <li>
                <a href="{{ route('grades') }}" class="drawer-link {{ request()->is('grades') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Grades</span>
                </a>
            </li>

            <li>
                <a href="{{ route('documents') }}" class="drawer-link {{ request()->is('documents') || request()->is('requests') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Shift Request</span>
                </a>
            </li>

            <li>
                <a href="{{ route('notifications') }}" class="drawer-link {{ request()->is('notifications') ? 'active' : '' }}" id="notificationsLink">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                    <span class="notification-badge" id="notificationBadge" style="display: none; margin-left: auto; background: #ef4444; color: white; border-radius: 10px; padding: 2px 6px; font-size: 11px; font-weight: 600;">0</span>
                </a>
            </li>

            <li>
                <a href="{{ route('messages') }}" class="drawer-link {{ request()->is('messages') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Messages</span>
                </a>
            </li>

            <li>
                <a href="{{ route('settings') }}" class="drawer-link {{ request()->is('settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="drawer-profile">
        <div class="drawer-profile-avatar">
            @php
                $name = Auth::user()->name ?? 'Student';
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
            <div class="role">{{ ucfirst($currentRole) }}</div>
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

        // Real-time Notification Polling for Drawer
        (function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const drawerBadge = document.getElementById('notificationBadge');

            function updateNotificationBadge(count) {
                if (drawerBadge) {
                    if (count > 0) {
                        drawerBadge.textContent = count > 99 ? '99+' : count;
                        drawerBadge.style.display = 'flex';
                    } else {
                        drawerBadge.style.display = 'none';
                    }
                }
            }

            function fetchNotifications() {
                if (!csrfToken) return;
                
                fetch('/api/notifications', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.unread_count !== undefined) {
                        updateNotificationBadge(data.unread_count);
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                });
            }

            // Initial fetch
            fetchNotifications();
            
            // Poll every 2 seconds for real-time updates
            setInterval(fetchNotifications, 2000);
        })();
    });
</script>
