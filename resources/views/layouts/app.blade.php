<!DOCTYPE html>
<html lang="en" data-theme="{{ Auth::check() && Auth::user()->preference ? Auth::user()->preference->theme : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Information System')</title>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    @yield('content')

    <script>
        // Auto-hide all flash messages after 3 seconds
        (function() {
            function autoHideFlashMessages() {
                // Find all flash messages by common patterns
                const selectors = [
                    '[class*="success-message"]',
                    '[class*="error-message"]',
                    '[class*="status-message"]',
                    '[class*="flash-message"]',
                    '[class*="flash-success"]',
                    '[class*="flash-error"]',
                    '[style*="position: fixed"]', // Fixed position messages
                ];

                selectors.forEach(selector => {
                    document.querySelectorAll(selector).forEach(message => {
                        // Skip if already has auto-hide
                        if (message.dataset.autoHide === 'true') {
                            return;
                        }
                        message.dataset.autoHide = 'true';

                        setTimeout(() => {
                            // Add fade-out animation
                            message.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                            message.style.opacity = '0';
                            message.style.transform = 'translateY(-10px)';
                            
                            // Remove after animation
                            setTimeout(() => {
                                message.remove();
                            }, 500);
                        }, 3000); // 3 seconds
                    });
                });

                // Also handle inline flash messages (like dashboard)
                document.querySelectorAll('div').forEach(div => {
                    if (div.dataset.autoHide === 'true') {
                        return;
                    }
                    
                    // Check if it's a flash message by content or style
                    const hasFlashStyle = div.style.background && (
                        div.style.background.includes('#D1FAE5') || // success green
                        div.style.background.includes('#FEE2E2') || // error red
                        div.style.background.includes('#EFF6FF')   // info blue
                    );
                    
                    const hasFlashContent = div.textContent && (
                        div.textContent.includes('successfully') ||
                        div.textContent.includes('logged out') ||
                        div.textContent.includes('error') ||
                        div.textContent.includes('Error')
                    );

                    // Check if it has flash message class or matches flash message patterns
                    const hasFlashClass = div.classList.contains('flash-message') || 
                                         div.classList.contains('success-message') || 
                                         div.classList.contains('error-message');

                    if ((hasFlashClass || (hasFlashStyle || hasFlashContent)) && (div.style.position === 'fixed' || hasFlashClass)) {
                        div.dataset.autoHide = 'true';
                        setTimeout(() => {
                            div.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                            div.style.opacity = '0';
                            div.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                div.remove();
                            }, 500);
                        }, 3000);
                    }
                });
            }

            // Run on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', autoHideFlashMessages);
            } else {
                autoHideFlashMessages();
            }

            // Also run after a short delay to catch dynamically added messages
            setTimeout(autoHideFlashMessages, 100);
        })();

        // Theme Application Script
        (function() {
            function applyTheme() {
                const html = document.documentElement;
                const theme = html.getAttribute('data-theme');
                
                // Handle auto theme
                if (theme === 'auto') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    html.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
                    
                    // Listen for system theme changes
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                        html.setAttribute('data-theme', e.matches ? 'dark' : 'light');
                    });
                }
            }

            // Apply theme on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', applyTheme);
            } else {
                applyTheme();
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>


