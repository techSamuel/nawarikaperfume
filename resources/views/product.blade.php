@extends('layouts.app')
@section('title', $product->name)
@section('meta_description', Str::limit($product->description, 160))

@php
    // Prepare unified media array (Main image, gallery images, videos)
    $mediaList = [];
    if (is_array($product->videos)) {
        foreach ($product->videos as $vid) {
            $mediaList[] = ['type' => 'video', 'url' => asset('uploads/' . $vid)];
        }
    }
    if ($product->image) {
        $mediaList[] = ['type' => 'image', 'url' => asset('uploads/' . $product->image)];
    }
    if (is_array($product->gallery)) {
        foreach ($product->gallery as $gImg) {
            $mediaList[] = ['type' => 'image', 'url' => asset('uploads/' . $gImg)];
        }
    }
    $hasMedia = count($mediaList) > 0;

    // Structured Data for Google Rich Snippets
    $schemaImages = [];
    if ($product->image) {
        $schemaImages[] = asset('uploads/' . $product->image);
    }
    if (is_array($product->gallery)) {
        foreach ($product->gallery as $img) {
            $schemaImages[] = asset('uploads/' . $img);
        }
    }
    $schemaData = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => $schemaImages,
        'description' => Str::limit($product->description, 300),
        'sku' => 'PROD-' . $product->id,
        'brand' => [
            '@type' => 'Brand',
            'name' => $settings['site_name'] ?? 'LUXE',
        ],
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'BDT',
            'price' => (string) $product->display_price,
            'itemCondition' => 'https://schema.org/NewCondition',
            'availability' => $product->isInStock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        ]
    ];
@endphp

@push('scripts')
<script type="application/ld+json">
{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<div class="cyber-product-wrapper">
    <div class="container">
        
        {{-- CYBER BREADCRUMB --}}
        <nav class="cyber-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator">/</span>
            <a href="{{ route('shop') }}">Shop</a>
            @if($product->category)
                <span class="separator">/</span>
                <a href="{{ route('shop', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
            @endif
            <span class="separator">/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        {{-- MAIN PRODUCT MATRIX --}}
        <div class="product-cyber-grid">
            
            {{-- 1. FUTURISTIC MEDIA GALLERY STAGE --}}
            <div class="cyber-gallery">
                <div class="cyber-media-stage-wrapper">
                    
                    {{-- Media Type Badge Overlay --}}
                    <div class="media-type-badge" id="mediaBadge">
                        <span class="dot"></span>
                        <span id="badgeText">MEDIA 01 / {{ max(1, count($mediaList)) }}</span>
                    </div>

                    {{-- Lightbox Zoom Button --}}
                    <button type="button" class="cyber-zoom-btn" onclick="openLightbox()" aria-label="Expand view">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h6v6M14 10l7-7M9 21H3v-6M10 14l-7 7"></path>
                        </svg>
                    </button>

                    {{-- Main Viewport Container --}}
                    <div class="cyber-main-viewport" id="mainViewport" onclick="openLightbox()">
                        @if($hasMedia)
                            @if($mediaList[0]['type'] === 'image')
                                <img src="{{ $mediaList[0]['url'] }}" alt="{{ $product->name }}" id="mainMediaElement">
                            @else
                                <video src="{{ $mediaList[0]['url'] }}" controls autoplay loop muted id="mainMediaElement"></video>
                            @endif
                        @else
                            <div style="font-size: 5rem; opacity: 0.5;">🛍️</div>
                        @endif
                    </div>
                </div>

                {{-- Thumbnail Matrix Bar --}}
                @if(count($mediaList) > 1)
                    <div class="cyber-thumb-matrix" id="thumbMatrix">
                        @foreach($mediaList as $index => $media)
                            <div class="cyber-thumb-item {{ $index === 0 ? 'active' : '' }}" 
                                 onclick="switchMedia({{ $index }})" 
                                 data-type="{{ $media['type'] }}" 
                                 data-url="{{ $media['url'] }}">
                                @if($media['type'] === 'image')
                                    <img src="{{ $media['url'] }}" alt="Thumbnail {{ $index + 1 }}">
                                @else
                                    <video src="{{ $media['url'] }}" muted></video>
                                    <div class="video-thumb-play">▶</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 2. PRODUCT PURCHASE & INFO CONSOLE (HUD) --}}
            <div class="cyber-info-console">
                @if($product->category)
                    <div class="cyber-category-tag">
                        <span>●</span> {{ $product->category->translated_name }}
                    </div>
                @endif

                <h1 class="cyber-product-title">{{ $product->translated_name }}</h1>

                {{-- PRICE DISPLAY PANEL --}}
                <div class="cyber-price-panel">
                    <span class="cyber-price-current" id="unitPriceDisplay">৳{{ number_format($product->display_price, 2) }}</span>
                    @if($product->isOnSale())
                        <span class="cyber-price-original">৳{{ number_format($product->price, 2) }}</span>
                        <span class="cyber-discount-pill">{{ $product->discount_percent }}% OFF</span>
                    @endif
                </div>

                {{-- DYNAMIC STOCK METER --}}
                <div class="cyber-stock-meter {{ $product->isInStock() ? '' : 'out-of-stock' }}">
                    <div class="stock-meter-header">
                        <div class="stock-meter-status">
                            <span class="stock-dot"></span>
                            @if($product->isInStock())
                                <span>In Stock & Ready to Ship</span>
                            @else
                                <span>Currently Out of Stock</span>
                            @endif
                        </div>
                        <span style="font-weight: 700;">
                            {{ $product->isInStock() ? $product->stock . ' units left' : '0 available' }}
                        </span>
                    </div>
                    <div class="stock-meter-bar-track">
                        @php
                            $stockPercentage = $product->isInStock() ? min(100, max(15, ($product->stock / 50) * 100)) : 0;
                        @endphp
                        <div class="stock-meter-bar-fill" style="width: {{ $stockPercentage }}%;"></div>
                    </div>
                </div>

                {{-- PURCHASE CONTROLS --}}
                @if($product->isInStock())
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="cyber-actions-group">
                        @csrf
                        
                        <div class="cyber-qty-row">
                            <span class="cyber-qty-label">Quantity:</span>
                            <div class="cyber-qty-box">
                                <button type="button" class="cyber-qty-btn" onclick="updateQty(-1)">−</button>
                                <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="{{ $product->stock }}" readonly class="cyber-qty-input">
                                <button type="button" class="cyber-qty-btn" onclick="updateQty(1)">+</button>
                            </div>
                            <span style="margin-left: auto; font-size: 0.85rem; color: var(--text-muted);">
                                Total: <strong id="totalPriceCalc" style="color: var(--accent-primary);">৳{{ number_format($product->display_price, 2) }}</strong>
                            </span>
                        </div>

                        <div class="cyber-cta-buttons">
                            <button type="submit" class="btn-cyber-primary">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span>Add to Cart</span>
                            </button>
                        </div>
                    </form>
                @else
                    <div style="margin-bottom: 24px;">
                        <button type="button" class="btn btn-outline btn-lg btn-block" disabled style="opacity: 0.6; cursor: not-allowed;">
                            Currently Out of Stock
                        </button>
                    </div>
                @endif

                {{-- TRUST BADGES MATRIX --}}
                <div class="cyber-trust-matrix">
                    <div class="cyber-trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <span>Cash on Delivery</span>
                    </div>
                    <div class="cyber-trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                        </svg>
                        <span>Express Delivery</span>
                    </div>
                    <div class="cyber-trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span>100% Authentic</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- 3. FUTURISTIC TABS & SPECIFICATIONS STATION --}}
        <div class="cyber-tabs-station">
            <div class="cyber-tabs-nav">
                <button type="button" class="cyber-tab-btn active" onclick="switchCyberTab('desc', this)">
                    Description
                </button>
                <button type="button" class="cyber-tab-btn" onclick="switchCyberTab('specs', this)">
                    Specifications
                </button>
                <button type="button" class="cyber-tab-btn" onclick="switchCyberTab('reviews', this)">
                    Reviews ({{ $product->reviews ? $product->reviews->count() : 0 }})
                </button>
            </div>

            {{-- TAB 1: DESCRIPTION --}}
            <div class="cyber-tab-content active" id="tab-desc">
                @if($product->translated_description)
                    <div class="product-description-content">
                        {!! nl2br(e($product->translated_description)) !!}
                    </div>
                @else
                    <div style="padding: 40px 0; text-align: center; color: var(--text-muted);">
                        No description provided for this product yet.
                    </div>
                @endif
            </div>

            {{-- TAB 2: SPECIFICATIONS --}}
            <div class="cyber-tab-content" id="tab-specs">
                <div class="cyber-spec-matrix">
                    <div class="cyber-spec-card">
                        <span class="cyber-spec-label">Product Name</span>
                        <span class="cyber-spec-val">{{ $product->name }}</span>
                    </div>
                    <div class="cyber-spec-card">
                        <span class="cyber-spec-label">Category</span>
                        <span class="cyber-spec-val">{{ $product->category->name ?? 'General' }}</span>
                    </div>
                    <div class="cyber-spec-card">
                        <span class="cyber-spec-label">Stock Status</span>
                        <span class="cyber-spec-val" style="color: {{ $product->isInStock() ? 'var(--success)' : 'var(--danger)' }};">
                            {{ $product->isInStock() ? 'In Stock (' . $product->stock . ' Units)' : 'Out of Stock' }}
                        </span>
                    </div>
                    <div class="cyber-spec-card">
                        <span class="cyber-spec-label">Payment Options</span>
                        <span class="cyber-spec-val" style="color: var(--success);">Cash on Delivery</span>
                    </div>
                    <div class="cyber-spec-card">
                        <span class="cyber-spec-label">Shipping Warranty</span>
                        <span class="cyber-spec-val">Free shipping on orders over ৳500</span>
                    </div>
                    <div class="cyber-spec-card">
                        <span class="cyber-spec-label">Product ID</span>
                        <span class="cyber-spec-val">LUXE-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>

            {{-- TAB 3: REVIEWS --}}
            <div class="cyber-tab-content" id="tab-reviews">
                @if($product->reviews && $product->reviews->count() > 0)
                    <div class="cyber-reviews-grid">
                        @foreach($product->reviews as $review)
                            <div class="cyber-review-card">
                                <div class="cyber-review-header">
                                    <div class="cyber-review-author">
                                        <div class="cyber-avatar">
                                            {{ strtoupper(substr($review->customer_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong style="display: block; color: var(--text-primary);">{{ $review->customer_name }}</strong>
                                            <span style="font-size: 0.78rem; color: var(--text-muted);">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="cyber-stars">
                                        {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                    </div>
                                </div>
                                <p style="color: var(--text-secondary); font-size: 0.94rem; line-height: 1.6;">
                                    "{{ $review->review_text }}"
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 40px 0; text-align: center;">
                        <div style="font-size: 3rem; margin-bottom: 12px;">⭐</div>
                        <h3 style="margin-bottom: 6px; font-weight: 700;">No reviews yet</h3>
                        <p style="color: var(--text-muted);">Be the first to share feedback on this item!</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- 4. RELATED PRODUCTS CYBER SECTION --}}
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <section style="margin-top: 60px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px;">
                        Recommended Products
                    </h2>
                    <a href="{{ route('shop') }}" style="font-size: 0.85rem; font-weight: 700; color: var(--accent-primary); text-decoration: none;">
                        View All →
                    </a>
                </div>

                <div class="product-grid">
                    @foreach($relatedProducts as $related)
                        <div class="product-card">
                            <div class="product-card-image">
                                @if($related->image)
                                    <img src="{{ asset('uploads/' . $related->image) }}" alt="{{ $related->name }}">
                                @else
                                    <div class="img-placeholder">🛍️</div>
                                @endif
                            </div>
                            <div class="product-card-body">
                                <h3 class="product-card-title">
                                    <a href="{{ route('product.show', $related->slug) }}">{{ $related->name }}</a>
                                </h3>
                                <div class="product-card-price">
                                    <span class="price-current">৳{{ number_format($related->display_price, 2) }}</span>
                                    @if($related->isOnSale())
                                        <span class="price-original">৳{{ number_format($related->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</div>

{{-- 5. FULLSCREEN LIGHTBOX MODAL --}}
<div class="cyber-lightbox" id="cyberLightbox">
    <button type="button" class="lightbox-close-btn" onclick="closeLightbox()" aria-label="Close modal">&times;</button>
    
    @if(count($mediaList) > 1)
        <button type="button" class="lightbox-nav lightbox-prev" onclick="navLightbox(-1)" aria-label="Previous media">‹</button>
        <button type="button" class="lightbox-nav lightbox-next" onclick="navLightbox(1)" aria-label="Next media">›</button>
    @endif

    <div class="lightbox-content" id="lightboxContent">
        {{-- Injected dynamically --}}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Media List JSON from Blade
    const mediaItems = @json($mediaList);
    let currentMediaIndex = 0;
    const unitPrice = {{ $product->display_price }};

    function switchMedia(index) {
        if (!mediaItems || !mediaItems[index]) return;
        currentMediaIndex = index;
        const media = mediaItems[index];

        const viewport = document.getElementById('mainViewport');
        const badgeText = document.getElementById('badgeText');
        
        // Update Media Element
        if (media.type === 'image') {
            viewport.innerHTML = `<img src="${media.url}" alt="{{ e($product->name) }}" id="mainMediaElement">`;
        } else {
            viewport.innerHTML = `<video src="${media.url}" controls autoplay loop muted id="mainMediaElement"></video>`;
        }

        // Update Badge
        if (badgeText) {
            badgeText.textContent = `${media.type.toUpperCase()} ${String(index + 1).padStart(2, '0')} / ${String(mediaItems.length).padStart(2, '0')}`;
        }

        // Update active thumbnail
        const thumbs = document.querySelectorAll('.cyber-thumb-item');
        thumbs.forEach((t, i) => {
            if (i === index) t.classList.add('active');
            else t.classList.remove('active');
        });
    }

    // Lightbox Controls
    function openLightbox() {
        if (!mediaItems || mediaItems.length === 0) return;
        const modal = document.getElementById('cyberLightbox');
        const content = document.getElementById('lightboxContent');
        const media = mediaItems[currentMediaIndex];

        if (media.type === 'image') {
            content.innerHTML = `<img src="${media.url}" alt="{{ e($product->name) }}">`;
        } else {
            content.innerHTML = `<video src="${media.url}" controls autoplay loop></video>`;
        }

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const modal = document.getElementById('cyberLightbox');
        modal.classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('lightboxContent').innerHTML = '';
    }

    function navLightbox(delta) {
        if (!mediaItems || mediaItems.length <= 1) return;
        let newIndex = currentMediaIndex + delta;
        if (newIndex < 0) newIndex = mediaItems.length - 1;
        if (newIndex >= mediaItems.length) newIndex = 0;
        switchMedia(newIndex);
        openLightbox();
    }

    // Keyboard support for Lightbox
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('cyberLightbox');
        if (!modal || !modal.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') navLightbox(-1);
        if (e.key === 'ArrowRight') navLightbox(1);
    });

    // Quantity & Dynamic Price Calculation
    function updateQty(delta) {
        const input = document.getElementById('qtyInput');
        if (!input) return;
        let val = parseInt(input.value) + delta;
        const max = parseInt(input.max) || 999;
        val = Math.max(1, Math.min(val, max));
        input.value = val;

        // Recalculate price
        const calc = document.getElementById('totalPriceCalc');
        if (calc) {
            const total = val * unitPrice;
            calc.textContent = '৳' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    // Tab Switcher
    function switchCyberTab(tabName, btn) {
        document.querySelectorAll('.cyber-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.cyber-tab-content').forEach(c => c.classList.remove('active'));
        
        btn.classList.add('active');
        const target = document.getElementById('tab-' + tabName);
        if (target) target.classList.add('active');
    }
</script>
@endpush
