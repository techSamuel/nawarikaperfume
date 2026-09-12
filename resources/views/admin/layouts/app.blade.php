<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — LUXE Admin</title>
    @if(isset($settings['web_icon']) && $settings['web_icon'])
        <link rel="icon" href="{{ asset('uploads/' . $settings['web_icon']) }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-wrapper">
        {{-- SIDEBAR --}}
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    @if(isset($settings['site_logo_dark']) || isset($settings['site_logo_light']))
                        @php 
                            $adminLogo = isset($settings['site_logo_dark']) && $settings['site_logo_dark'] ? $settings['site_logo_dark'] : $settings['site_logo_light'];
                        @endphp
                        <img src="{{ asset('uploads/' . $adminLogo) }}" alt="{{ $settings['site_name'] ?? 'LUXE' }}" style="height: 32px;">
                    @else
                        {{ $settings['site_name'] ?? 'LUXE' }}
                    @endif
                </div>
                <div class="sidebar-subtitle">Admin Panel</div>
            </div>
            <nav class="sidebar-nav">
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Main</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="icon">📊</span> Dashboard
                    </a>
                </div>
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Catalog</div>
                    <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <span class="icon">📂</span> Categories
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <span class="icon">📦</span> Products
                    </a>
                </div>
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Sales</div>
                    <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <span class="icon">🛒</span> Orders
                        @php $pendingCount = \App\Models\Order::where('status', 'pending')->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="sidebar-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </div>
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Analytics</div>
                    <a href="{{ route('admin.visitors.index') }}" class="sidebar-link {{ request()->routeIs('admin.visitors.*') ? 'active' : '' }}">
                        <span class="icon">📊</span> Visitor Logs
                    </a>
                </div>
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Configuration</div>
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" class="sidebar-link {{ request('tab', 'general') == 'general' && request()->routeIs('admin.settings.*') ? 'active' : '' }}" style="padding-left: 20px;">
                            <span class="icon">⚙️</span> General
                        </a>
                        <a href="{{ route('admin.settings.index', ['tab' => 'home']) }}" class="sidebar-link {{ request('tab') == 'home' && request()->routeIs('admin.settings.*') ? 'active' : '' }}" style="padding-left: 20px;">
                            <span class="icon">🏠</span> Home Page
                        </a>
                        <a href="{{ route('admin.settings.index', ['tab' => 'contact']) }}" class="sidebar-link {{ request('tab') == 'contact' && request()->routeIs('admin.settings.*') ? 'active' : '' }}" style="padding-left: 20px;">
                            <span class="icon">📞</span> Contact Info
                        </a>
                        <a href="{{ route('admin.settings.index', ['tab' => 'checkout']) }}" class="sidebar-link {{ request('tab') == 'checkout' && request()->routeIs('admin.settings.*') ? 'active' : '' }}" style="padding-left: 20px;">
                            <span class="icon">🛒</span> Checkout
                        </a>
                        <a href="{{ route('admin.settings.index', ['tab' => 'tracking']) }}" class="sidebar-link {{ request('tab') == 'tracking' && request()->routeIs('admin.settings.*') ? 'active' : '' }}" style="padding-left: 20px;">
                            <span class="icon">📈</span> Tracking & API
                        </a>
                    </div>
                </div>
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Links</div>
                    <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                        <span class="icon">🌐</span> View Store
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit; font-size:inherit;">
                            <span class="icon">🚪</span> Logout
                        </button>
                    </form>
                </div>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                        <div class="sidebar-user-role">Administrator</div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="admin-main">
            <header class="admin-header">
                <div style="display:flex; align-items:center; gap:16px;">
                    <button class="admin-sidebar-toggle" id="sidebarToggle">☰</button>
                    <h1>@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="admin-header-actions">
                    @yield('header_actions')
                </div>
            </header>

            <div class="admin-content">
                {{-- ALERTS --}}
                @if(session('success'))
                    <div class="alert alert-success">✓ {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">✕ {{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('active');
        });
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => { alert.style.opacity = '0'; setTimeout(() => alert.remove(), 300); }, 4000);
        });
    </script>
    @stack('scripts')
</body>
</html>
