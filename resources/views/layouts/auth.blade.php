<!DOCTYPE html>
<html lang="en" data-theme="{{ $settings['theme_mode'] ?? 'dark' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication') — {{ $settings['site_name'] ?? 'LUXE Store' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script>
        (function() {
            const storedTheme = localStorage.getItem('luxe_theme');
            if (storedTheme) {
                document.documentElement.setAttribute('data-theme', storedTheme);
            }
        })();
    </script>
    @if(isset($settings['primary_color']) || isset($settings['secondary_color']))
    <style>
        :root, [data-theme="light"], [data-theme="dark"] {
            @if(isset($settings['primary_color']))
            --accent-primary: {{ $settings['primary_color'] }} !important;
            @endif
            @if(isset($settings['secondary_color']))
            --accent-secondary: {{ $settings['secondary_color'] }} !important;
            @endif
            --accent-gradient: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary)) !important;
            --accent-glow: color-mix(in srgb, var(--accent-primary) 30%, transparent) !important;
        }
    </style>
    @endif
</head>
<body style="background: var(--bg-primary); color: var(--text-primary); transition: background-color 0.3s ease, color 0.3s ease;">
    <div class="auth-page" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; position: relative; background: radial-gradient(circle at 50% 30%, var(--accent-glow) 0%, transparent 70%);">
        
        {{-- Floating Theme Toggle Button --}}
        <div style="position: absolute; top: 24px; right: 24px; z-index: 10;">
            <button id="themeToggle" class="btn btn-sm btn-outline" style="width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0; background: var(--bg-card); border: 1px solid var(--border-color);" aria-label="Toggle Theme">
                <span class="light-icon" style="display:none; font-size: 1.2rem;">🌙</span>
                <span class="dark-icon" style="font-size: 1.2rem;">☀️</span>
            </button>
        </div>

        <div class="auth-card" style="width: 100%; max-width: 440px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 36px 30px; box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); margin: auto;">
            
            {{-- App Logo & Business Name --}}
            <div style="text-align: center; margin-bottom: 24px;">
                <a href="{{ route('home') }}" class="navbar-brand" style="display: inline-flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none;">
                    @if(isset($settings['site_logo']) && $settings['site_logo'])
                        <img src="{{ asset('uploads/' . $settings['site_logo']) }}" alt="{{ $settings['site_name'] ?? 'LUXE' }}" style="max-height: 54px; max-width: 220px; object-fit: contain;">
                    @else
                        <span style="font-size: 2rem; font-weight: 800; background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $settings['site_name'] ?? 'LUXE Store' }}
                        </span>
                    @endif
                </a>
            </div>

            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const themeToggle = document.getElementById('themeToggle');
            const lightIcon = document.querySelector('.light-icon');
            const darkIcon = document.querySelector('.dark-icon');
            
            function updateThemeIcon(theme) {
                if (theme === 'light') {
                    if (lightIcon) lightIcon.style.display = 'inline';
                    if (darkIcon) darkIcon.style.display = 'none';
                } else {
                    if (lightIcon) lightIcon.style.display = 'none';
                    if (darkIcon) darkIcon.style.display = 'inline';
                }
            }

            const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            updateThemeIcon(currentTheme);

            themeToggle?.addEventListener('click', () => {
                const activeTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = activeTheme === 'dark' ? 'light' : 'dark';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('luxe_theme', newTheme);
                updateThemeIcon(newTheme);
            });
        });
    </script>
</body>
</html>
