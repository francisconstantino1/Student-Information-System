<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register | Student Information System</title>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center py-8">
<div class="w-full max-w-md px-6">
    <div class="bg-slate-900/80 border border-slate-700 shadow-2xl shadow-slate-900/60 rounded-2xl p-8 backdrop-blur">
        <h1 class="text-2xl font-semibold text-slate-50 mb-1 text-center">Create Account</h1>
        <p class="text-sm text-slate-400 mb-6 text-center">Register to access the Student Information System.</p>

        <div id="errorContainer" class="mb-4 rounded-lg border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-200 hidden">
            <ul id="errorList" class="list-disc list-inside space-y-1"></ul>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form id="registrationForm" action="{{ route('register.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label for="name" class="block text-sm font-medium text-slate-200">
                    Full Name
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    class="block w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2.5 text-sm text-slate-50 placeholder:text-slate-500 shadow-inner focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition"
                    placeholder="Juan Dela Cruz"
                >
            </div>

            <div class="space-y-1.5">
                <label for="student_id" class="block text-sm font-medium text-slate-200">
                    Institutional ID <span class="text-rose-400">*</span>
                </label>
                <input
                    id="student_id"
                    name="student_id"
                    type="text"
                    value="{{ old('student_id') }}"
                    required
                    class="block w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2.5 text-sm text-slate-50 placeholder:text-slate-500 shadow-inner focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition"
                    placeholder="Enter your assigned Institutional ID"
                >
                <p class="mt-1 text-xs text-slate-400">You must have an admin-assigned Institutional ID to register. Please contact the administrator if you don't have one.</p>
            </div>

            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-medium text-slate-200">
                    Email
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2.5 text-sm text-slate-50 placeholder:text-slate-500 shadow-inner focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition"
                    placeholder="you@example.com"
                >
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-sm font-medium text-slate-200">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2.5 text-sm text-slate-50 placeholder:text-slate-500 shadow-inner focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition"
                    placeholder="At least 8 characters"
                >
            </div>

            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-sm font-medium text-slate-200">
                    Confirm Password
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2.5 text-sm text-slate-50 placeholder:text-slate-500 shadow-inner focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition"
                    placeholder="Re-type your password"
                >
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 hover:bg-indigo-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950 transition"
            >
                Create Account
            </button>
        </form>

        <p class="mt-4 text-center text-xs text-slate-400">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-indigo-400 hover:text-indigo-300 underline-offset-2 hover:underline">
                Log in
            </a>
        </p>
    </div>

    <p class="mt-6 text-center text-xs text-slate-500">
        &copy; <span id="year"></span> Student Information System
    </p>
</div>

<script>
    // Set current year
    document.getElementById('year').textContent = new Date().getFullYear();

    // Form validation and submission
    const form = document.getElementById('registrationForm');
    const errorContainer = document.getElementById('errorContainer');
    const errorList = document.getElementById('errorList');

    // Display Laravel validation errors if any
    @if ($errors && $errors->any())
        errorContainer.classList.remove('hidden');
        @foreach ($errors->all() as $error)
            const li = document.createElement('li');
            li.textContent = '{{ $error }}';
            errorList.appendChild(li);
        @endforeach
    @endif

    form.addEventListener('submit', function(e) {
        // Clear previous messages
        errorContainer.classList.add('hidden');
        errorList.innerHTML = '';

        // Get form values
        const formData = {
            name: document.getElementById('name').value.trim(),
            student_id: document.getElementById('student_id').value.trim(),
            email: document.getElementById('email').value.trim(),
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value
        };

        // Client-side validation
        const errors = [];

        if (formData.name.length < 2) {
            errors.push('Full name must be at least 2 characters long');
        }

        if (formData.student_id.length < 3) {
            errors.push('Institutional ID must be at least 3 characters long');
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            errors.push('Please enter a valid email address');
        }

        if (formData.password.length < 8) {
            errors.push('Password must be at least 8 characters long');
        }

        if (formData.password !== formData.password_confirmation) {
            errors.push('Passwords do not match');
        }

        // Display errors or submit
        if (errors.length > 0) {
            e.preventDefault();
            errors.forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                errorList.appendChild(li);
            });
            errorContainer.classList.remove('hidden');
        }
        // If no errors, form will submit normally to Laravel backend
    });
    </script>

    <script>
        // Auto-hide flash messages after 3 seconds
        (function() {
            function autoHideFlashMessages() {
                // Find all flash messages
                document.querySelectorAll('[class*="success"], [class*="error"], [class*="status"]').forEach(message => {
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


