<!DOCTYPE html>
<html lang="en" data-theme="{{ $settings['theme_mode'] ?? 'dark' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $defaultTitle = $settings['site_name'] ?? 'LUXE';
        $metaTitle = $settings['meta_title'] ?: $defaultTitle;
        $metaDesc = $settings['meta_description'] ?: 'Premium shopping experience with exclusive collections and curated products.';
        $metaKeywords = $settings['meta_keywords'] ?: 'ecommerce, shop, online store';
        $socialImage = !empty($settings['social_preview_image']) ? asset('uploads/' . $settings['social_preview_image']) : (!empty($settings['site_logo_dark']) ? asset('uploads/' . $settings['site_logo_dark']) : '');
    @endphp

    <title>@yield('title', $metaTitle)</title>
    <meta name="description" content="@yield('meta_description', $metaDesc)">
    <meta name="keywords" content="@yield('meta_keywords', $metaKeywords)">
    
    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', $metaTitle)">
    <meta property="og:description" content="@yield('meta_description', $metaDesc)">
    @if($socialImage)
    <meta property="og:image" content="@yield('meta_image', $socialImage)">
    @endif
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', $metaTitle)">
    <meta name="twitter:description" content="@yield('meta_description', $metaDesc)">
    @if($socialImage)
    <meta name="twitter:image" content="@yield('meta_image', $socialImage)">
    @endif
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Google Fonts are loaded in app.css -->
    <!-- Swiper CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    @if(isset($settings['web_icon']) && $settings['web_icon'])
        <link rel="icon" href="{{ asset('uploads/' . $settings['web_icon']) }}">
    @endif
    
    @stack('styles')
    
    <script>
        // Check local storage for theme preference, fallback to server setting
        const storedTheme = localStorage.getItem('luxe_theme');
        if (storedTheme) {
            document.documentElement.setAttribute('data-theme', storedTheme);
        }
    </script>
    <script>
        try {
            const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
            if (tz) {
                document.cookie = "visitor_tz=" + encodeURIComponent(tz) + "; path=/; max-age=86400";
            }
        } catch(e) {}
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
    
    <style>
        [data-theme="dark"] .logo-light { display: none !important; }
        [data-theme="dark"] .logo-dark { display: inline-block !important; }
        [data-theme="light"] .logo-dark { display: none !important; }
        [data-theme="light"] .logo-light { display: inline-block !important; }
    </style>

    @if(isset($settings['gtm_id']) && $settings['gtm_id'])
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $settings['gtm_id'] }}');</script>
    <!-- End Google Tag Manager -->
    @endif

    @if(isset($settings['fb_pixel_id']) && $settings['fb_pixel_id'])
    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $settings['fb_pixel_id'] }}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ $settings['fb_pixel_id'] }}&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->

    @if(session('registration_success'))
    <script>
        if (typeof fbq === 'function') {
            fbq('track', 'CompleteRegistration', {
                content_name: 'User Registration',
                status: 'success'
            });
        }
    </script>
    @endif
    @endif
</head>
<body>
    @if(isset($settings['gtm_id']) && $settings['gtm_id'])
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $settings['gtm_id'] }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    {{-- NAVBAR --}}
    <nav class="navbar" id="navbar">
        <div style="background: #000; color: #fff; text-align: center; padding: 8px 0; font-size: 0.85rem; font-weight: 500; width: 100%; letter-spacing: 0.5px;">
            {{ $settings['announcement_text'] ?? 'Bangladesh\'s Most Trusted E-Commerce Brand' }}
        </div>
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand">
                @if(isset($settings['site_logo_dark']) || isset($settings['site_logo_light']))
                    @php 
                        $darkLogo = isset($settings['site_logo_dark']) && $settings['site_logo_dark'] ? $settings['site_logo_dark'] : $settings['site_logo_light'];
                        $lightLogo = isset($settings['site_logo_light']) && $settings['site_logo_light'] ? $settings['site_logo_light'] : $settings['site_logo_dark'];
                    @endphp
                    <img src="{{ asset('uploads/' . $darkLogo) }}" class="logo-dark" alt="{{ $settings['site_name'] ?? 'LUXE' }}" style="height: 32px;">
                    <img src="{{ asset('uploads/' . $lightLogo) }}" class="logo-light" alt="{{ $settings['site_name'] ?? 'LUXE' }}" style="height: 32px;">
                @else
                    {{ $settings['site_name'] ?? 'LUXE' }}
                @endif
            </a>

            <ul class="navbar-nav" id="navMenu">
                <div class="sidebar-header" style="display: none;">
                    <a href="{{ route('home') }}" class="navbar-brand">
                        @if(isset($settings['site_logo_dark']) || isset($settings['site_logo_light']))
                            @php 
                                $darkLogo = isset($settings['site_logo_dark']) && $settings['site_logo_dark'] ? $settings['site_logo_dark'] : $settings['site_logo_light'];
                                $lightLogo = isset($settings['site_logo_light']) && $settings['site_logo_light'] ? $settings['site_logo_light'] : $settings['site_logo_dark'];
                            @endphp
                            <img src="{{ asset('uploads/' . $darkLogo) }}" class="logo-dark" alt="{{ $settings['site_name'] ?? 'LUXE' }}" style="height: 32px;">
                            <img src="{{ asset('uploads/' . $lightLogo) }}" class="logo-light" alt="{{ $settings['site_name'] ?? 'LUXE' }}" style="height: 32px;">
                        @else
                            {{ $settings['site_name'] ?? 'LUXE' }}
                        @endif
                    </a>
                    <button class="close-sidebar" id="closeSidebar">&times;</button>
                </div>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.home') }}</a></li>
                <li><a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">{{ __('messages.shop') }}</a></li>
                <li><a href="{{ route('order.track.page') }}">{{ __('messages.track_order') }}</a></li>
                @auth
                    <li><a href="{{ route('account.orders') }}" class="{{ request()->routeIs('account.*') ? 'active' : '' }}">{{ __('messages.my_orders') }}</a></li>
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}">{{ __('messages.admin_panel') }}</a></li>
                    @endif
                @endauth
                
                <div class="mobile-auth-links" style="display: none; padding-top: 20px; margin-top: auto; border-top: 1px solid var(--border-color); flex-direction: column; gap: 10px;">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline btn-block">{{ __('messages.login') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-block">{{ __('messages.register') }}</a>
                    @else
                        <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-block">{{ __('messages.logout') }}</button>
                        </form>
                    @endguest
                </div>
            </ul>

            <div class="navbar-actions">
                <button id="themeToggle" class="btn btn-sm btn-outline" style="padding: 6px 10px; border-radius: 50%;" aria-label="Toggle Theme">
                    <span class="light-icon" style="display:none;">🌙</span>
                    <span class="dark-icon">☀️</span>
                </button>
                    <a href="javascript:void(0)" onclick="openGlobalSearch()" class="nav-icon-link" title="Search">
                        <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </a>
                    <a href="javascript:void(0)" onclick="openCheckoutSidebar()" class="nav-icon-link" title="View Cart">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        @php
                            $cartCount = 0;
                            if(session()->has('cart')) {
                                foreach(session('cart') as $item) {
                                    $cartCount += $item['quantity'];
                                }
                            }
                        @endphp
                        <span class="cart-badge" id="navCartBadge" style="{{ $cartCount > 0 ? 'display:inline-flex;' : 'display:none;' }}">{{ $cartCount }}</span>
                    </a>

                <div class="desktop-auth">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Sign Up</a>
                    @else
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline">Logout</button>
                        </form>
                    @endguest
                </div>

                <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ALERTS --}}
    <div style="position:fixed; top:80px; right:20px; z-index:9999; width:360px;">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        @if(isset($settings['site_logo_dark']) || isset($settings['site_logo_light']))
                            @php 
                                $darkLogo = isset($settings['site_logo_dark']) && $settings['site_logo_dark'] ? $settings['site_logo_dark'] : $settings['site_logo_light'];
                                $lightLogo = isset($settings['site_logo_light']) && $settings['site_logo_light'] ? $settings['site_logo_light'] : $settings['site_logo_dark'];
                            @endphp
                            <img src="{{ asset('uploads/' . $darkLogo) }}" class="logo-dark" alt="{{ $settings['site_name'] ?? 'LUXE' }}" style="height: 40px; margin-bottom: 16px;">
                            <img src="{{ asset('uploads/' . $lightLogo) }}" class="logo-light" alt="{{ $settings['site_name'] ?? 'LUXE' }}" style="height: 40px; margin-bottom: 16px;">
                        @else
                            {{ $settings['site_name'] ?? 'LUXE' }}
                        @endif
                    </div>
                    <p class="footer-desc">Your premium destination for curated collections and exclusive products. Experience luxury shopping redefined.</p>
                    <div style="margin-top: 16px; color: var(--text-secondary); font-size: 0.9rem;">
                        @if(isset($settings['contact_email']) && $settings['contact_email'])
                            <p style="margin-bottom: 4px;">✉️ {{ $settings['contact_email'] }}</p>
                        @endif
                        @if(isset($settings['contact_phone']) && $settings['contact_phone'])
                            <p>📞 {{ $settings['contact_phone'] }}</p>
                        @endif
                    </div>
                </div>
                <div>
                    <h4>Shop</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('shop') }}">All Products</a></li>
                        <li><a href="{{ route('shop', ['sort' => 'latest']) }}">New Arrivals</a></li>
                        <li><a href="{{ route('shop', ['sort' => 'price_low']) }}">Best Deals</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Account</h4>
                    <ul class="footer-links">
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li><a href="{{ route('account.orders') }}">My Orders</a></li>
                            <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                        @endguest
                        <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Support</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="{{ route('faq') }}">FAQs</a></li>
                        <li><a href="{{ route('shipping.policy') }}">Shipping Policy</a></li>
                        <li><a href="{{ route('return.policy') }}">Return Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'LUXE' }} Store. All rights reserved. | Cash on Delivery Only</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            navMenu.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebarFunc() {
            navMenu.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        mobileToggle?.addEventListener('click', openSidebar);
        closeSidebar?.addEventListener('click', closeSidebarFunc);
        sidebarOverlay?.addEventListener('click', closeSidebarFunc);

        // Auto-dismiss alerts
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }, 4000);
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';
            if (window.scrollY > 50) {
                navbar.style.background = isLight ? 'rgba(255, 255, 255, 0.95)' : 'rgba(10, 10, 15, 0.95)';
            } else {
                navbar.style.background = isLight ? 'rgba(255, 255, 255, 0.85)' : 'rgba(10, 10, 15, 0.85)';
            }
        });

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const lightIcon = document.querySelector('.light-icon');
        const darkIcon = document.querySelector('.dark-icon');
        
        function updateThemeIcon(theme) {
            if (theme === 'light') {
                lightIcon.style.display = 'inline';
                darkIcon.style.display = 'none';
            } else {
                lightIcon.style.display = 'none';
                darkIcon.style.display = 'inline';
            }
            // Trigger scroll event to update navbar bg if needed
            window.dispatchEvent(new Event('scroll'));
        }

        // Initialize icon
        updateThemeIcon(document.documentElement.getAttribute('data-theme'));

        themeToggle?.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('luxe_theme', newTheme);
            updateThemeIcon(newTheme);
        });
    </script>
    {{-- STICKY BOTTOM CART BAR --}}
    @php
        $cart = session('cart', []);
        $cartCount = 0;
        $cartTotal = 0;
        if(count($cart) > 0) {
            $productIds = array_keys($cart);
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
            foreach($cart as $id => $item) {
                if(isset($products[$id])) {
                    $p = $products[$id];
                    $price = $p->sale_price ?? $p->price;
                    $cartCount += $item['quantity'];
                    $cartTotal += $price * $item['quantity'];
                }
            }
        }
    @endphp

    @if($cartCount > 0)
    <div class="bottom-cart-bar">
        <div class="bottom-cart-left">
            <div class="cart-icon-bg">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <span class="cart-badge-bottom">{{ $cartCount }}</span>
            </div>
            <div class="bottom-cart-info">
                <span class="bottom-cart-total">৳{{ number_format($cartTotal, 2) }}</span>
                <span class="bottom-cart-text">Total Price</span>
            </div>
        </div>
        <button type="button" onclick="openCheckoutSidebar()" class="btn-bottom-order">Order Now</button>
    </div>

    <style>
        body {
            padding-bottom: 70px; /* Make room for bottom bar */
        }
        .bottom-cart-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            z-index: 9999;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .bottom-cart-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .cart-icon-bg {
            position: relative;
            background: rgba(0, 128, 96, 0.1);
            color: var(--accent-primary);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cart-badge-bottom {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ef4444;
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bottom-cart-info {
            display: flex;
            flex-direction: column;
        }
        .bottom-cart-total {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--accent-primary);
            line-height: 1.2;
        }
        .bottom-cart-text {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .btn-bottom-order {
            background: linear-gradient(135deg, #008060, #006b52);
            color: white;
            padding: 12px 32px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(0, 128, 96, 0.3);
            transition: all var(--transition-fast);
            border: none;
            cursor: pointer;
        }
        .btn-bottom-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 128, 96, 0.4);
            color: white;
        }
    </style>
    @endif

    {{-- QUICK CHECKOUT SIDEBAR --}}
    <div id="quick-checkout-overlay" class="sidebar-checkout-overlay" onclick="closeCheckoutSidebar()"></div>
    <div id="quick-checkout-sidebar" class="sidebar-checkout-wrapper">
        <div style="padding: 40px 20px; text-align: center;">Loading...</div>
    </div>

    <script>
        document.addEventListener('submit', function(e) {
            if (e.target.matches('form[action*="/cart/add"]')) {
                e.preventDefault();
                
                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Adding...';

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        if (typeof fbq === 'function') {
                            fbq('track', 'AddToCart');
                        }
                        openCheckoutSidebar();
                    } else {
                        alert(data.message || 'Error adding to cart');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Network error. Please try again.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            }
        });

        function refreshNavCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('navCartBadge');
                    if (badge) {
                        badge.innerText = data.count;
                        badge.style.display = data.count > 0 ? 'inline-flex' : 'none';
                    }
                    const bottomBadge = document.querySelector('.cart-badge-bottom');
                    if (bottomBadge) {
                        bottomBadge.innerText = data.count;
                    }
                })
                .catch(err => console.error(err));
        }

        function openCheckoutSidebar() {
            refreshNavCartCount();
            document.getElementById('quick-checkout-overlay').classList.add('active');
            document.getElementById('quick-checkout-sidebar').classList.add('active');
            
            // Advanced iOS Body Scroll Lock
            const scrollY = window.scrollY;
            document.body.dataset.scrollY = scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollY}px`;
            document.body.style.width = '100%';
            document.body.style.overflow = 'hidden';

            if (typeof fbq === 'function') {
                fbq('track', 'InitiateCheckout');
            }

            fetch('{{ route("checkout.sidebar") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('quick-checkout-sidebar').innerHTML = html;
                const bottomBar = document.querySelector('.bottom-cart-bar');
                if(bottomBar) bottomBar.style.display = 'none';
            })
            .catch(err => {
                console.error(err);
                document.getElementById('quick-checkout-sidebar').innerHTML = '<div style="padding: 20px;">Error loading checkout.</div>';
            });
        }

        function closeCheckoutSidebar() {
            document.getElementById('quick-checkout-overlay').classList.remove('active');
            document.getElementById('quick-checkout-sidebar').classList.remove('active');
            
            // Restore Body Scroll Lock
            const scrollY = document.body.dataset.scrollY || 0;
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.body.style.overflow = '';
            window.scrollTo(0, scrollY);
            
            const bottomBar = document.querySelector('.bottom-cart-bar');       
            // Reload page to reflect updated cart totals in header and bottom bar
            window.location.reload();
        }

        function updateCartItem(id, qty) {
            if (qty < 1) {
                removeCartItem(id);
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');
            formData.append('quantity', qty);

            fetch(`/cart/update/${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    refreshNavCartCount();
                    // Refresh the sidebar content
                    openCheckoutSidebar();
                }
            });
        }

        function removeCartItem(id) {
            if(!confirm('Remove this item?')) return;
            
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch(`/cart/remove/${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    refreshNavCartCount();
                    // Refresh the sidebar content
                    openCheckoutSidebar();
                }
            });
        }
    </script>
    <script>
        (function() {
            let pingInterval = setInterval(function() {
                if (!document.hidden) {
                    fetch('{{ route("api.visitor.ping") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ duration: 60 })
                    }).catch(function(e) {});
                }
            }, 60000);
        })();
    </script>

    {{-- FULL PAGE SEARCH OVERLAY --}}
    <div id="globalSearchOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 10000; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); flex-direction: column; align-items: center; justify-content: flex-start; padding-top: 15vh; opacity: 0; transition: opacity 0.3s ease;">
        <button type="button" onclick="closeGlobalSearch()" style="position: absolute; top: 30px; right: 40px; background: none; border: none; color: white; font-size: 3rem; cursor: pointer; line-height: 1; z-index: 10001;">&times;</button>
        
        <div style="width: 90%; max-width: 840px; position: relative;">
            <form action="{{ route('shop') }}" method="GET" id="globalSearchForm" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 10px 14px; box-shadow: 0 12px 40px rgba(0,0,0,0.3);">
                
                @php 
                    $allCategories = \App\Models\Category::all(); 
                    $quickProducts = \App\Models\Product::where('is_featured', true)->take(4)->get();
                @endphp
                
                <div class="cyber-category-select" style="position: relative; flex-shrink: 0;">
                    <select name="category" id="searchCategorySelect" onchange="triggerLiveSearch()" 
                            style="padding: 12px 36px 12px 14px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); color: var(--text-primary); font-size: 0.9rem; font-weight: 600; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none;">
                        <option value="">All Categories</option>
                        @foreach($allCategories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.75rem; color: var(--text-muted);">▼</span>
                </div>

                <div style="flex: 1; min-width: 220px; position: relative; display: flex; align-items: center;">
                    <span style="position: absolute; left: 14px; color: var(--text-muted); font-size: 1.1rem;">🔍</span>
                    <input type="text" name="search" id="homeSearchInput" placeholder="Search products, brands, or categories..." 
                           autocomplete="off" 
                           style="width: 100%; padding: 14px 40px 14px 44px; background: transparent; border: none; color: var(--text-primary); font-size: 1.1rem; outline: none;">
                    <button type="button" id="clearSearchBtn" onclick="clearHomeSearch()" style="display: none; position: absolute; right: 12px; background: none; border: none; color: var(--text-muted); font-size: 1.1rem; cursor: pointer; padding: 4px;">✕</button>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 14px 28px; border-radius: var(--radius-md); font-weight: 700; flex-shrink: 0; display: flex; align-items: center; gap: 8px;">
                    <span>Search</span>
                </button>
            </form>

            {{-- LIVE SEARCH DROPDOWN RESULTS --}}
            <div id="liveSearchResults" style="display: none; position: absolute; top: calc(100% + 8px); left: 0; right: 0; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: 0 20px 50px rgba(0,0,0,0.3); max-height: 440px; overflow-y: auto; z-index: 100; padding: 12px;"></div>
            
            @if($quickProducts->count() > 0)
            <div style="margin: 16px auto 0; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; font-size: 0.9rem; color: #fff;">
                <span style="font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Popular:</span>
                @foreach($quickProducts as $fp)
                    <button type="button" onclick="setQuickSearch('{{ addslashes($fp->name) }}')" 
                            style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: var(--radius-full); padding: 6px 14px; color: #fff; font-size: 0.85rem; cursor: pointer; transition: all 0.2s ease; backdrop-filter: blur(4px);">
                        ⚡ {{ $fp->name }}
                    </button>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <script>
        // GLOBAL LIVE SEARCH LOGIC
        let searchDebounceTimer = null;
        let currentFocusIndex = -1;

        function openGlobalSearch() {
            const overlay = document.getElementById('globalSearchOverlay');
            overlay.style.display = 'flex';
            setTimeout(() => {
                overlay.style.opacity = '1';
                document.getElementById('homeSearchInput').focus();
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeGlobalSearch() {
            const overlay = document.getElementById('globalSearchOverlay');
            overlay.style.opacity = '0';
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300);
            document.body.style.overflow = '';
        }

        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('homeSearchInput');
            const resultsBox = document.getElementById('liveSearchResults');
            const clearBtn = document.getElementById('clearSearchBtn');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    if (this.value.trim().length > 0) {
                        if(clearBtn) clearBtn.style.display = 'block';
                    } else {
                        if(clearBtn) clearBtn.style.display = 'none';
                    }
                    triggerLiveSearch();
                });

                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeGlobalSearch();
                        return;
                    }
                    if (!resultsBox) return;
                    const items = resultsBox.querySelectorAll('.live-search-item');
                    if (items.length === 0) return;

                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        currentFocusIndex = (currentFocusIndex + 1) % items.length;
                        highlightFocusedItem(items);
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        currentFocusIndex = (currentFocusIndex - 1 + items.length) % items.length;
                        highlightFocusedItem(items);
                    } else if (e.key === 'Enter' && currentFocusIndex >= 0) {
                        e.preventDefault();
                        items[currentFocusIndex].click();
                    }
                });
            }

            document.getElementById('globalSearchOverlay').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeGlobalSearch();
                }
            });
        });

        function setQuickSearch(term) {
            const searchInput = document.getElementById('homeSearchInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            if (searchInput) {
                searchInput.value = term;
                if(clearBtn) clearBtn.style.display = 'block';
                triggerLiveSearch();
            }
        }

        function clearHomeSearch() {
            const searchInput = document.getElementById('homeSearchInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            const resultsBox = document.getElementById('liveSearchResults');
            if (searchInput) {
                searchInput.value = '';
                if(clearBtn) clearBtn.style.display = 'none';
                if(resultsBox) resultsBox.style.display = 'none';
                searchInput.focus();
            }
        }

        function highlightFocusedItem(items) {
            items.forEach((item, index) => {
                if (index === currentFocusIndex) {
                    item.style.background = 'rgba(0, 128, 96, 0.15)';
                    item.style.borderColor = 'var(--accent-primary)';
                    item.scrollIntoView({ block: 'nearest' });
                } else {
                    item.style.background = 'var(--bg-glass)';
                    item.style.borderColor = 'transparent';
                }
            });
        }

        function triggerLiveSearch() {
            clearTimeout(searchDebounceTimer);
            const searchInput = document.getElementById('homeSearchInput');
            const categorySelect = document.getElementById('searchCategorySelect');
            const resultsBox = document.getElementById('liveSearchResults');

            const query = searchInput ? searchInput.value.trim() : '';
            const category = categorySelect ? categorySelect.value : '';

            if (query.length < 1 && !category) {
                if(resultsBox) resultsBox.style.display = 'none';
                return;
            }

            searchDebounceTimer = setTimeout(() => {
                if (!resultsBox) return;
                resultsBox.style.display = 'block';
                resultsBox.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.9rem;">Searching products...</div>';

                fetch(`{{ route('api.products.search') }}?q=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`)
                    .then(res => res.json())
                    .then(data => {
                        currentFocusIndex = -1;
                        if (!data.results || data.results.length === 0) {
                            resultsBox.innerHTML = '<div style="text-align: center; padding: 24px; color: var(--text-muted);"><div style="font-size: 2rem; margin-bottom: 6px;">🔍</div>No matching products found.</div>';
                            return;
                        }

                        let html = '<div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 700; margin-bottom: 10px; padding: 0 6px;">Search Results (' + data.results.length + ')</div>';
                        
                        data.results.forEach(product => {
                            html += `
                            <a href="${product.url}" class="live-search-item" style="display: flex; align-items: center; gap: 14px; padding: 10px 12px; border-radius: var(--radius-md); border: 1px solid transparent; text-decoration: none; transition: all 0.2s ease; margin-bottom: 6px; background: var(--bg-glass);">
                                <div style="width: 52px; height: 52px; border-radius: var(--radius-sm); overflow: hidden; background: var(--bg-secondary); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                    ${product.image_url ? `<img src="${product.image_url}" alt="" style="width: 100%; height: 100%; object-fit: cover;">` : '🛍️'}
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; color: var(--text-primary); font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${product.name}</div>
                                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 2px;">
                                        <span style="font-size: 0.75rem; color: var(--accent-primary); background: rgba(0, 128, 96, 0.1); padding: 2px 8px; border-radius: 4px; font-weight: 600;">${product.category_name}</span>
                                        ${product.in_stock ? '<span style="font-size: 0.75rem; color: #10b981; font-weight: 600;">In Stock</span>' : '<span style="font-size: 0.75rem; color: #ef4444; font-weight: 600;">Out of Stock</span>'}
                                    </div>
                                </div>
                                <div style="text-align: right; flex-shrink: 0;">
                                    <div style="font-weight: 800; color: var(--accent-primary); font-size: 1rem;">${product.price}</div>
                                    ${product.original_price ? `<div style="font-size: 0.78rem; text-decoration: line-through; color: var(--text-muted);">${product.original_price}</div>` : ''}
                                </div>
                            </a>
                            `;
                        });

                        if (query) {
                            html += `
                            <a href="{{ route('shop') }}?search=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}" 
                               style="display: block; text-align: center; padding: 12px; margin-top: 8px; border-top: 1px solid var(--border-color); color: var(--accent-primary); font-weight: 700; font-size: 0.9rem; text-decoration: none;">
                                View All Results for "${query}" →
                            </a>
                            `;
                        }

                        resultsBox.innerHTML = html;
                    })
                    .catch(err => {
                        console.error(err);
                        resultsBox.innerHTML = '<div style="text-align: center; padding: 16px; color: #ef4444;">Failed to load search results.</div>';
                    });
            }, 250);
        }
    </script>

    @stack('scripts')
</body>
</html>
