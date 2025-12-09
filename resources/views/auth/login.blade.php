<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Student Information System</title>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<div class="page-wrapper">
    <div class="left-panel">
        <div class="login-card">
            <h1 class="login-title">Student Information Portal</h1>
            <p class="login-subtitle">Sign in to continue</p>

            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="email" class="label">
                        <span class="field-icon" aria-hidden="true"></span>
                        <span>Email / Institutional ID</span>
                    </label>
                    <input
                        id="email"
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        class="input @error('email') input-error @enderror"
                        placeholder="e.g. student@example.com or 2025-12345"
                    >
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="label">
                        <span class="field-icon" aria-hidden="true"></span>
                        <span>Password</span>
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="input @error('password') input-error @enderror"
                        placeholder="Enter your password"
                    >
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>

                    @if (\Illuminate\Support\Facades\Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link-muted">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    Login
                </button>

                <p class="extra-text">
                    Don't have an account?
                    <a href="{{ route('register') }}">Register</a>
                </p>
            </form>
        </div>
    </div>

    <div class="right-panel">
        <div class="right-overlay"></div>

        <div class="glow-circle sm"></div>
        <div class="glow-circle md"></div>
        <div class="glow-circle lg"></div>

        <div class="right-content">
            <h2 class="right-title">Welcome to Student Information System</h2>
            <p class="right-subtitle">
                Keep track of your enrollment, schedules, and school updates in one modern dashboard experience.
            </p>
        </div>
    </div>
</div>

<script>
    // Ensure CSRF token is sent with form
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            input.value = token.getAttribute('content');
            this.appendChild(input);
        }
    });

    // Auto-hide flash messages after 3 seconds
    (function() {
        function autoHideFlashMessages() {
            // Find status message (logout message)
            document.querySelectorAll('.status-message').forEach(message => {
                if (message.dataset.autoHide === 'true') {
                    return;
                }
                message.dataset.autoHide = 'true';

                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        message.remove();
                    }, 500);
                }, 3000);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', autoHideFlashMessages);
        } else {
            autoHideFlashMessages();
        }

        setTimeout(autoHideFlashMessages, 100);
    })();
</script>
</body>
</html>
