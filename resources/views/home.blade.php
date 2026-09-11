@extends('layouts.app')
@section('title', 'Home')
@section('meta_description', 'LUXE Store — Discover premium products with exclusive deals. Shop the latest collections with Cash on Delivery.')

@section('content')
@php
    $rawHeight = $settings['slider_height'] ?? '500';
    $numericHeight = intval(preg_replace('/[^0-9]/', '', $rawHeight));
    if(!$numericHeight) $numericHeight = 500;
    $minHeight = round($numericHeight * 0.5);
    $responsiveHeight = "clamp({$minHeight}px, 40vw, {$numericHeight}px)";

    $sliderEffect = $settings['slider_effect'] ?? 'fade';
    $autoplaySpeed = intval($settings['slider_autoplay_speed'] ?? 5000);
    $overlayStyle = $settings['slider_overlay_style'] ?? 'gradient';
    $overlayOpacity = intval($settings['slider_overlay_opacity'] ?? 50) / 100;
    $navStyle = $settings['slider_nav_style'] ?? 'both';
    $kenBurns = ($settings['slider_kenburns'] ?? '1') === '1';
    $textStyle = $settings['slider_text_style'] ?? 'modern';
    $textAnim = $settings['slider_text_animation'] ?? 'fade_up';

    // Count active slides
    $slideCount = 0;
    for($s = 1; $s <= 3; $s++) {
        if(!empty($settings['slider_' . $s . '_image'])) $slideCount++;
    }
@endphp

<div class="hero-slider-wrapper" style="position: relative; overflow: hidden;">
    <div class="swiper heroSwiper">
        <div class="swiper-wrapper">
            @php $hasSlides = false; $slideIndex = 0; @endphp
            @for($i = 1; $i <= 3; $i++)
                @php 
                    $imgKey = 'slider_' . $i . '_image';
                    $linkKey = 'slider_' . $i . '_link';
                    $textPos = $settings['slider_' . $i . '_text_position'] ?? 'center';
                @endphp
                @if(isset($settings[$imgKey]) && $settings[$imgKey])
                    @php $hasSlides = true; $slideIndex++; @endphp
                    <div class="swiper-slide">
                        <a href="{{ $settings[$linkKey] ?? route('shop') }}" class="hero-slide-link">
                            <div class="hero-slide">
                                {{-- Background image with Ken Burns --}}
                                <div class="hero-slide-bg {{ $kenBurns ? 'kenburns' : '' }}" 
                                     style="background-image: url('{{ asset('uploads/' . $settings[$imgKey]) }}');"></div>

                                {{-- Overlay --}}
                                @if($overlayStyle !== 'none')
                                <div class="hero-slide-overlay hero-overlay-{{ $overlayStyle }}" style="--overlay-opacity: {{ $overlayOpacity }};"></div>
                                @endif

                                {{-- Text content --}}
                                @if(!empty($settings['slider_' . $i . '_headline']) || !empty($settings['slider_' . $i . '_description']) || !empty($settings['slider_' . $i . '_button_text']) || !empty($settings['slider_' . $i . '_badge']))
                                <div class="hero-slide-content hero-text-center hero-style-{{ $textStyle }} hero-anim-{{ $textAnim }}">
                                    <div class="hero-text-inner">
                                        @if(!empty($settings['slider_' . $i . '_badge']))
                                        <div class="hero-badge-tag">
                                            {{ $settings['slider_' . $i . '_badge'] }}
                                        </div>
                                        @endif

                                        @if(!empty($settings['slider_' . $i . '_headline']))
                                        <h2 class="hero-headline">{{ $settings['slider_' . $i . '_headline'] }}</h2>
                                        @endif

                                        @if(!empty($settings['slider_' . $i . '_description']))
                                        <p class="hero-description">{{ $settings['slider_' . $i . '_description'] }}</p>
                                        @endif

                                        @if(!empty($settings['slider_' . $i . '_button_text']))
                                        <div class="hero-btn-wrap">
                                            <span class="hero-cta-btn">
                                                {{ $settings['slider_' . $i . '_button_text'] }}
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endif
            @endfor

            {{-- Fallback --}}
            @if(!$hasSlides)
                <div class="swiper-slide">
                    <div class="hero-slide">
                        <div class="hero-slide-bg" style="background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));"></div>
                        <div class="hero-slide-content hero-text-center hero-style-{{ $textStyle }} hero-anim-{{ $textAnim }}">
                            <div class="hero-text-inner">
                                <div class="hero-badge-tag">🔥 Huge Discounts</div>
                                <h2 class="hero-headline">Welcome to {{ $settings['site_name'] ?? 'Our Store' }}</h2>
                                <p class="hero-description">Upload slider images from the Admin Settings to replace this banner.</p>
                                <div class="hero-btn-wrap">
                                    <a href="{{ route('shop') }}" class="hero-cta-btn">Explore Offers <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Navigation arrows --}}
        @if(in_array($navStyle, ['both', 'arrows']))
        <div class="hero-nav-btn hero-nav-prev">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </div>
        <div class="hero-nav-btn hero-nav-next">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 6 15 12 9 18"></polyline></svg>
        </div>
        @endif

        {{-- Dots pagination --}}
        @if(in_array($navStyle, ['dots']))
        <div class="swiper-pagination hero-dots-pagination"></div>
        @endif
    </div>

    {{-- Progress bar --}}
    @if(in_array($navStyle, ['both', 'progress']))
    <div class="hero-progress-bar">
        <div class="hero-progress-fill" style="--autoplay-speed: {{ $autoplaySpeed }}ms;"></div>
    </div>
    @endif

    {{-- Slide counter --}}
    @if($slideCount > 1)
    <div class="hero-slide-counter">
        <span class="hero-counter-current">01</span>
        <span class="hero-counter-sep">/</span>
        <span class="hero-counter-total">{{ str_pad($slideCount, 2, '0', STR_PAD_LEFT) }}</span>
    </div>
    @endif
</div>


{{-- FEATURED PRODUCTS --}}
@if($featuredProducts->count() > 0)
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>{{ __('messages.featured_products') }}</h2>
            <p>{{ __('messages.featured_subtitle') }}</p>
        </div>
        <div class="product-grid">
            @foreach($featuredProducts as $product)
                <div class="product-card animate-fade-in-up">
                    <a href="{{ route('product.show', $product->slug) }}" class="product-card-image" onmouseenter="let v=this.querySelector('video'); if(v) v.play()" onmouseleave="let v=this.querySelector('video'); if(v) { v.pause(); v.currentTime = 0; }">
                        @if(is_array($product->videos) && count($product->videos) > 0)
                            <video src="{{ asset('uploads/' . $product->videos[0]) }}" muted loop playsinline class="hover-video"></video>
                        @endif
                        @if($product->image)
                            <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->translated_name }}">
                        @else
                            <div class="img-placeholder">🛍️</div>
                        @endif
                        @if($product->isOnSale())
                            <span class="product-card-badge badge-sale">-{{ $product->discount_percent }}%</span>
                        @endif
                        @if(!$product->isInStock())
                            <span class="product-card-badge badge-out">Sold Out</span>
                        @endif
                    </a>
                    <div class="product-card-body" style="padding: 24px; display: flex; flex-direction: column; flex: 1;">
                        <h3 class="product-card-title" style="margin-bottom: 12px; font-size: 1.15rem; font-weight: 600; letter-spacing: -0.2px;">
                            <a href="{{ route('product.show', $product->slug) }}">{{ $product->translated_name }}</a>
                        </h3>
                        <div class="product-card-price" style="margin-bottom: 20px; display: flex; gap: 8px; align-items: center;">
                            <span class="price-current" style="font-weight: 800; font-size: 1.15rem; color: var(--accent-primary);">৳{{ number_format($product->display_price, 2) }}</span>
                            @if($product->isOnSale())
                                <span class="price-original" style="text-decoration: line-through; color: var(--text-muted); font-size: 0.9rem;">৳{{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        <div class="product-card-actions" style="margin-top: auto;">
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" style="width: 100%;">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-block" style="border-radius: 0; padding: 14px;" {{ !$product->isInStock() ? 'disabled' : '' }}>
                                    {{ $product->isInStock() ? __('messages.add_to_cart') : __('messages.out_of_stock') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CATEGORIES --}}
@if($categories->count() > 0)
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <h2>{{ __('messages.shop_by_category') }}</h2>
            <p>{{ __('messages.category_subtitle') }}</p>
        </div>
        <div class="category-grid">
            @foreach($categories as $category)
                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="category-card animate-fade-in-up">
                    <div class="category-card-bg">
                        @if($category->image)
                            <img src="{{ asset('uploads/' . $category->image) }}" alt="{{ $category->translated_name }}">
                        @else
                            <div class="img-placeholder" style="font-size:3rem;">📦</div>
                        @endif
                    </div>
                    <div class="category-card-overlay">
                        <h3>{{ $category->translated_name }}</h3>
                        <span>{{ $category->products_count }} Products</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- NEW ARRIVALS --}}
@if($newArrivals->count() > 0)
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>{{ __('messages.new_arrivals') }}</h2>
            <p>{{ __('messages.new_arrivals_subtitle') }}</p>
        </div>
        <div class="product-grid">
            @foreach($newArrivals as $product)
                <div class="product-card animate-fade-in-up">
                    <a href="{{ route('product.show', $product->slug) }}" class="product-card-image" onmouseenter="let v=this.querySelector('video'); if(v) v.play()" onmouseleave="let v=this.querySelector('video'); if(v) { v.pause(); v.currentTime = 0; }">
                        @if(is_array($product->videos) && count($product->videos) > 0)
                            <video src="{{ asset('uploads/' . $product->videos[0]) }}" muted loop playsinline class="hover-video"></video>
                        @endif
                        @if($product->image)
                            <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->translated_name }}">
                        @else
                            <div class="img-placeholder">🛍️</div>
                        @endif
                        <span class="product-card-badge badge-new">New</span>
                    </a>
                    <div class="product-card-body" style="padding: 24px; display: flex; flex-direction: column; flex: 1;">
                        <h3 class="product-card-title" style="margin-bottom: 12px; font-size: 1.15rem; font-weight: 600; letter-spacing: -0.2px;">
                            <a href="{{ route('product.show', $product->slug) }}">{{ $product->translated_name }}</a>
                        </h3>
                        <div class="product-card-price" style="margin-bottom: 20px; display: flex; gap: 8px; align-items: center;">
                            <span class="price-current" style="font-weight: 800; font-size: 1.15rem; color: var(--accent-primary);">৳{{ number_format($product->display_price, 2) }}</span>
                            @if($product->isOnSale())
                                <span class="price-original" style="text-decoration: line-through; color: var(--text-muted); font-size: 0.9rem;">৳{{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        <div class="product-card-actions" style="margin-top: auto;">
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" style="width: 100%;">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-block" style="border-radius: 0; padding: 14px;">{{ __('messages.add_to_cart') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="text-align:center; margin-top:40px;">
            <a href="{{ route('shop') }}" class="btn btn-outline btn-lg">{{ __('messages.view_all_products') }}</a>
        </div>
    </div>
</section>
@endif

{{-- FAQ SECTION --}}
<section class="section" style="background: var(--bg-card); border-top: 1px solid var(--border-color);">
    <div class="container">
        <div class="section-header" style="text-align: center; max-width: 680px; margin: 0 auto 40px;">
            <h2 style="font-size: 2rem; font-weight: 800; color: var(--text-primary);">Frequently Asked Questions</h2>
            <p style="color: var(--text-secondary); font-size: 1.05rem;">সাধারণ জিজ্ঞাসা ও তার উত্তর</p>
        </div>

        <div class="faq-container" style="max-width: 800px; margin: 0 auto;">
            <div class="faq-list">
                <details class="faq-item">
                    <summary class="faq-question">১. অর্ডার কিভাবে করবো?</summary>
                    <div class="faq-answer">
                        আমাদের ওয়েবসাইট থেকে সরাসরি অথবা ফেসবুক পেইজে ইনবক্স করে অর্ডার করতে পারেন। অর্ডার প্লেস করার পর আমাদের প্রতিনিধি আপনাকে কল করে অর্ডারটি কনফার্ম করবেন।
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">২. অর্ডার কনফার্ম হতে কত সময় লাগে?</summary>
                    <div class="faq-answer">
                        সাধারণত অর্ডার প্লেস করার ২৪-৭২ ঘণ্টার মধ্যে ফোন কলের মাধ্যমে কনফার্মেশন সম্পন্ন করা হয়।
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">৩. পণ্য কত দিনে ডেলিভারি পাবো?</summary>
                    <div class="faq-answer">
                        • <strong>ঢাকার ভেতরে:</strong> ১-৩ কার্যদিবস<br>
                        • <strong>ঢাকার বাইরে:</strong> ২-৫ কার্যদিবস
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">৪. কোন কুরিয়ার সার্ভিস ব্যবহার করা হয়?</summary>
                    <div class="faq-answer">
                        আমরা বাংলাদেশের নির্ভরযোগ্য কুরিয়ার সার্ভিস <strong>Pathao</strong> ও <strong>Steadfast</strong>-এর মাধ্যমে ডেলিভারি দিয়ে থাকি।
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">৫. পেমেন্ট কিভাবে করবো?</summary>
                    <div class="faq-answer">
                        আমরা সম্পূর্ণ <strong>Cash on Delivery (COD)</strong> সাপোর্ট করি। পণ্য হাতে পেয়ে ডেলিভারি ম্যানের কাছে পেমেন্ট করতে পারবেন।
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">৬. কখন Advance Payment দিতে হয়?</summary>
                    <div class="faq-answer">
                        সাধারণত অ্যাডভান্স পেমেন্টের প্রয়োজন নেই। তবে নিম্নোক্ত ক্ষেত্রে আংশিক বা সম্পূর্ণ অ্যাডভান্স প্রয়োজন হতে পারে:<br>
                        • আপনার পূর্বে ডেলিভারি ফেইল বা রিটার্ন রেট বেশি থাকলে।<br>
                        • বড় অর্ডারের ক্ষেত্রে (৳৫,০০০ বা তার বেশি)।<br>
                        <em>(বিকাশ/নগদ/রকেট নম্বর ফোন কনফার্মেশন কলে জানিয়ে দেওয়া হবে)</em>
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">৭. পণ্য রিটার্ন করা যাবে কি?</summary>
                    <div class="faq-answer">
                        হ্যাঁ, তবে শুধুমাত্র নিম্নোক্ত শর্তে:<br>
                        • ডেলিভারি ম্যানের সামনে প্যাকেট খুলে চেক করার পর যদি ভুল, ত্রুটিপূর্ণ বা ক্ষতিগ্রস্ত পণ্য পান।<br>
                        পণ্য পছন্দ না হলে ডেলিভারি ম্যানের সামনেই শুধুমাত্র ডেলিভারি চার্জ দিয়ে পণ্যটি রিটার্ন করতে পারবেন। ডেলিভারি ম্যান চলে আসার পর রিটার্ন গ্রহণ করা হয় না। তবে উপযুক্ত প্রমাণ থাকলে আমরা এক্সচেঞ্জ করে দেবো।
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">৮. রিপ্লেসমেন্ট পলিসি কী?</summary>
                    <div class="faq-answer">
                        পণ্য রিসিভের ৩ / ৫ / ৭ দিনের মধ্যে (পণ্যভেদে প্রযোজ্য) রিপ্লেসমেন্ট সুবিধা রয়েছে।<br><br>
                        <strong>শর্তসমূহ:</strong><br>
                        ১. শুধুমাত্র ইলেক্ট্রনিক পণ্যের ক্ষেত্রে এটি প্রযোজ্য। নন-ইলেক্ট্রনিক পণ্যের জন্য কোনো রিপ্লেসমেন্ট নেই।<br>
                        ২. 'পছন্দ হয়নি' (Change of mind) এই কারণে রিপ্লেসমেন্ট প্রযোজ্য নয়।<br>
                        ৩. পণ্য সম্পূর্ণ অব্যবহৃত থাকতে হবে।<br>
                        ৪. ব্যবহারজনিত কারণে বা গ্রাহকের অসাবধানতাবশত নষ্ট বা ভাঙলে তা রিপ্লেসমেন্টের আওতাভুক্ত হবে না।<br>
                        ৫. অরিজিনাল প্যাকেজিং থাকতে হবে।<br>
                        ৬. ভুল, ত্রুটিপূর্ণ বা ক্ষতিগ্রস্ত পণ্য পেলে এটি প্রযোজ্য।
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">৯. রিফান্ড কিভাবে পাবো?</summary>
                    <div class="faq-answer">
                        আমাদের সরাসরি কোনো রিফান্ড পলিসি নেই। বিশেষ ক্ষেত্রে পণ্য এক্সচেঞ্জ করে দেওয়া হয়। যদি এক্সচেঞ্জের জন্য একই পণ্য স্টকে না থাকে, তবে উন্নত মানের অন্য কোনো পণ্য নিতে পারবেন (প্রয়োজনে অতিরিক্ত মূল্য সমন্বয় করে)। এতে আপনাদের কোনো আপত্তি থাকবে না আশা করছি।
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">১০. রিটার্ন শিপিং চার্জ কে বহন করবে?</summary>
                    <div class="faq-answer">
                        যৌক্তিক কারণে রিটার্ন বা এক্সচেঞ্জের ক্ষেত্রে শিপিং চার্জ <strong>৫০% কাস্টমার</strong> এবং <strong>৫০% HT Bazar</strong> বহন করবে।
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">১১. ভাঙা বা ক্ষতিগ্রস্ত পণ্য পেলে কী করবো?</summary>
                    <div class="faq-answer">
                        ডেলিভারি ম্যানের সামনে প্যাকেট খুলে চেক করুন, সমস্যা পেলে সাথে সাথেই রিটার্ন করুন। অথবা আনবক্সিং ভিডিও রেকর্ড করুন (স্পষ্ট প্রমাণসহ) এবং আমাদের ইনবক্সে পাঠান।
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">১২. পণ্যের নিরাপত্তা কিভাবে নিশ্চিত করেন?</summary>
                    <div class="faq-answer">
                        পণ্য নিরাপদে আপনার হাতে পৌঁছে দিতে আমরা সর্বোচ্চ সতর্কতা অবলম্বন করি:<br>
                        ১. বাবল র‍্যাপ প্রোটেকশন (Bubble Wrap Protection)<br>
                        ২. শক্ত কার্ডবোর্ড বক্স<br>
                        ৩. সিসিটিভি (CCTV) মনিটরিং-এর মাধ্যমে প্যাকিং<br>
                        ৪. প্রতিটি পণ্যের ছবি সংরক্ষণ
                    </div>
                </details>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    /* ===== FAQ STYLES ===== */
    .faq-item {
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        margin-bottom: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .faq-item:hover {
        border-color: var(--accent-primary);
    }
    .faq-question {
        padding: 16px 20px;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
    }
    .faq-question::-webkit-details-marker {
        display: none;
    }
    .faq-question::after {
        content: '+';
        font-size: 1.5rem;
        line-height: 1;
        transition: transform 0.3s ease;
        color: var(--accent-primary);
    }
    details[open] .faq-question::after {
        transform: rotate(45deg);
    }
    .faq-answer {
        padding: 0 20px 20px;
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.6;
        animation: fadeInDown 0.3s ease-in-out;
    }
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== HERO SLIDER WRAPPER ===== */
    .hero-slider-wrapper { 
        position: relative; 
        margin-top: var(--header-height);
    }
    .hero-slide-link { display: block; width: 100%; text-decoration: none; color: white; }

    .hero-slide {
        position: relative;
        width: 100%;
        overflow: hidden;
        aspect-ratio: 21 / 9; /* Ultra-wide for large desktop screens */
    }
    
    /* Make all direct children overlap perfectly */
    .hero-slide > * {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* Background layer */
    .hero-slide-bg {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: 1;
        will-change: transform;
    }

    /* Ken Burns zoom animation */
    .hero-slide-bg.kenburns {
        animation: heroKenBurns 12s ease-in-out infinite alternate;
    }
    .swiper-slide-active .hero-slide-bg.kenburns {
        animation: heroKenBurns 12s ease-in-out infinite alternate;
    }
    @keyframes heroKenBurns {
        0% { transform: scale(1); }
        100% { transform: scale(1.08); }
    }

    /* Overlay variants */
    .hero-slide-overlay {
        width: 100%;
        height: 100%;
        z-index: 2;
        pointer-events: none;
    }
    .hero-overlay-gradient {
        background: linear-gradient(
            to top,
            rgba(0,0,0, var(--overlay-opacity, 0.5)) 0%,
            rgba(0,0,0, calc(var(--overlay-opacity, 0.5) * 0.4)) 50%,
            transparent 100%
        );
    }
    .hero-overlay-dark {
        background: rgba(0,0,0, var(--overlay-opacity, 0.5));
    }
    .hero-overlay-glass {
        background: rgba(0,0,0, calc(var(--overlay-opacity, 0.5) * 0.6));
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    /* Text content positioning */
    .hero-slide-content {
        width: 100%;
        height: 100%;
        z-index: 3;
        display: flex;
        padding: 5%;
        box-sizing: border-box;
        color: white;
    }
    .hero-text-center { align-items: center; justify-content: center; text-align: center; }
    .hero-text-left { align-items: center; justify-content: flex-start; text-align: left; }
    .hero-text-right { align-items: center; justify-content: flex-end; text-align: right; }
    .hero-text-bottom-left { align-items: flex-end; justify-content: flex-start; text-align: left; padding-bottom: 60px; }
    .hero-text-bottom-center { align-items: flex-end; justify-content: center; text-align: center; padding-bottom: 60px; }

    .hero-text-inner {
        max-width: 640px;
    }

    /* Badge */
    .hero-badge-tag {
        display: inline-block;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        color: white;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        opacity: 0;
        transform: translateY(20px);
        transition: none;
    }
    .swiper-slide-active .hero-badge-tag {
        animation: heroFadeUp 0.6s 0.1s ease forwards;
    }

    /* Headline */
    .hero-headline {
        font-size: clamp(1.8rem, 4.5vw, 3.5rem);
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 16px;
        text-shadow: 0 4px 20px rgba(0,0,0,0.4);
        letter-spacing: -0.5px;
        opacity: 0;
        transform: translateY(30px);
        transition: none;
    }
    .swiper-slide-active .hero-headline {
        animation: heroFadeUp 0.7s 0.25s ease forwards;
    }

    /* Description */
    .hero-description {
        font-size: clamp(0.95rem, 1.8vw, 1.15rem);
        line-height: 1.6;
        margin-bottom: 28px;
        color: rgba(255,255,255,0.9);
        text-shadow: 0 2px 8px rgba(0,0,0,0.3);
        opacity: 0;
        transform: translateY(20px);
        transition: none;
    }
    .swiper-slide-active .hero-description {
        animation: heroFadeUp 0.7s 0.4s ease forwards;
    }

    /* CTA Button */
    .hero-btn-wrap {
        opacity: 0;
        transform: translateY(20px);
        transition: none;
    }
    .swiper-slide-active .hero-btn-wrap {
        animation: heroFadeUp 0.6s 0.55s ease forwards;
    }
    .hero-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 32px;
        background: white;
        color: #111;
        font-weight: 800;
        font-size: 1rem;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 8px 30px rgba(0,0,0,0.25);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: 0.3px;
    }
    .hero-cta-btn:hover {
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 12px 40px rgba(0,0,0,0.35);
        color: #111;
    }
    .hero-cta-btn svg {
        transition: transform 0.3s ease;
    }
    .hero-cta-btn:hover svg {
        transform: translateX(4px);
    }

    @keyframes heroFadeUp {
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 1024px) {
        .hero-slide {
            aspect-ratio: 16 / 9; /* Standard wide for laptops/tablets */
        }
    }

    @media (max-width: 768px) {
        .hero-slide {
            aspect-ratio: 4 / 3; /* Taller standard for mobile to fit content */
            min-height: unset;
        }
        .hero-slide-content {
            padding: 15px;
        }
        .hero-text-inner {
            max-width: 100%;
        }
        /* Override style-specific large paddings on mobile */
        .hero-style-futuristic_glass .hero-text-inner,
        .hero-style-cyber_neon .hero-text-inner {
            padding: 15px !important;
            border-radius: 8px !important;
        }
        .hero-badge-tag {
            padding: 3px 10px;
            font-size: 0.65rem;
            margin-bottom: 6px;
        }
        .hero-headline {
            font-size: clamp(1.1rem, 4vw, 1.8rem);
            margin-bottom: 6px;
            line-height: 1.15;
        }
        .hero-description {
            font-size: clamp(0.75rem, 2.5vw, 0.9rem);
            margin-bottom: 10px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .hero-cta-btn {
            padding: 8px 16px;
            font-size: 0.8rem;
        }
        .hero-cta-btn svg {
            width: 14px;
            height: 14px;
        }
    }

    /* ===== FUTURISTIC TEXT STYLES ===== */
    /* Futuristic Glassmorphism */
    .hero-style-futuristic_glass .hero-text-inner {
        background: rgba(20, 20, 30, 0.5);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.6), inset 0 0 0 1px rgba(255,255,255,0.05);
        position: relative;
        overflow: hidden;
    }
    .hero-style-futuristic_glass .hero-text-inner::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 50%; height: 100%;
        background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent);
        transform: skewX(-20deg);
        animation: glassShine 4s infinite;
    }
    @keyframes glassShine {
        0% { left: -100%; }
        20% { left: 200%; }
        100% { left: 200%; }
    }
    .hero-style-futuristic_glass .hero-headline {
        font-family: 'Space Grotesk', system-ui, sans-serif;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* Cyber Neon */
    .hero-style-cyber_neon .hero-text-inner {
        padding: 35px;
        border-left: 4px solid var(--accent-primary, #0ff);
        background: linear-gradient(90deg, rgba(0,255,255,0.1) 0%, transparent 100%);
        box-shadow: -10px 0 30px -10px var(--accent-primary, #0ff);
    }
    .hero-style-cyber_neon .hero-headline {
        font-family: 'Orbitron', 'Space Grotesk', system-ui, sans-serif;
        color: #fff;
        text-shadow: 0 0 10px var(--accent-primary, #0ff), 0 0 20px var(--accent-primary, #0ff);
        text-transform: uppercase;
        letter-spacing: 4px;
    }
    .hero-style-cyber_neon .hero-badge-tag {
        background: transparent;
        border: 1px solid var(--accent-primary, #0ff);
        color: var(--accent-primary, #0ff);
        box-shadow: 0 0 10px rgba(0,255,255,0.3), inset 0 0 10px rgba(0,255,255,0.3);
        border-radius: 2px;
    }
    .hero-style-cyber_neon .hero-cta-btn {
        background: transparent;
        border: 2px solid var(--accent-secondary, #f0f);
        color: #fff;
        border-radius: 0;
        box-shadow: 0 0 15px rgba(255,0,255,0.4), inset 0 0 15px rgba(255,0,255,0.2);
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .hero-style-cyber_neon .hero-cta-btn:hover {
        background: var(--accent-secondary, #f0f);
        box-shadow: 0 0 30px var(--accent-secondary, #f0f);
    }

    /* Holographic Minimal */
    .hero-style-holographic .hero-headline {
        font-weight: 300;
        letter-spacing: 8px;
        text-transform: uppercase;
        background: linear-gradient(90deg, #fff, #a855f7, #0ff, #fff);
        background-size: 200% auto;
        color: transparent;
        -webkit-background-clip: text;
        background-clip: text;
        animation: holoGradient 3s linear infinite;
    }
    @keyframes holoGradient {
        to { background-position: 200% center; }
    }
    .hero-style-holographic .hero-badge-tag {
        border: none;
        background: rgba(255,255,255,0.05);
        letter-spacing: 4px;
        text-transform: uppercase;
    }
    
    /* ===== FUTURISTIC ANIMATIONS ===== */
    /* Tech Reveal */
    .hero-anim-tech_reveal .hero-badge-tag,
    .hero-anim-tech_reveal .hero-headline,
    .hero-anim-tech_reveal .hero-description,
    .hero-anim-tech_reveal .hero-btn-wrap {
        opacity: 0;
        clip-path: polygon(0 0, 0 0, 0 100%, 0% 100%);
        transform: translateX(-30px);
    }
    .swiper-slide-active .hero-anim-tech_reveal .hero-badge-tag { animation: techReveal 0.6s 0.2s cubic-bezier(0.77, 0, 0.175, 1) forwards; }
    .swiper-slide-active .hero-anim-tech_reveal .hero-headline { animation: techReveal 0.8s 0.4s cubic-bezier(0.77, 0, 0.175, 1) forwards; }
    .swiper-slide-active .hero-anim-tech_reveal .hero-description { animation: techReveal 0.8s 0.6s cubic-bezier(0.77, 0, 0.175, 1) forwards; }
    .swiper-slide-active .hero-anim-tech_reveal .hero-btn-wrap { animation: techReveal 0.6s 0.8s cubic-bezier(0.77, 0, 0.175, 1) forwards; }
    
    @keyframes techReveal {
        to {
            opacity: 1;
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
            transform: translateX(0);
        }
    }

    /* Glitch Effect */
    .swiper-slide-active .hero-anim-glitch .hero-headline {
        animation: glitchAnim 1s 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards, glitchSkew 3s 1.2s infinite;
    }
    .hero-anim-glitch .hero-badge-tag,
    .hero-anim-glitch .hero-description,
    .hero-anim-glitch .hero-btn-wrap {
        opacity: 0;
        transform: scale(0.9);
    }
    .swiper-slide-active .hero-anim-glitch .hero-badge-tag { animation: heroFadeUp 0.5s 0.3s forwards; }
    .swiper-slide-active .hero-anim-glitch .hero-description { animation: heroFadeUp 0.5s 0.6s forwards; }
    .swiper-slide-active .hero-anim-glitch .hero-btn-wrap { animation: heroFadeUp 0.5s 0.8s forwards; }

    @keyframes glitchAnim {
        0% { opacity: 0; transform: translate(-20px, 20px) skewX(-20deg); clip-path: polygon(0 0, 100% 0, 100% 10%, 0 10%); }
        20% { opacity: 1; transform: translate(20px, -20px) skewX(20deg); clip-path: polygon(0 20%, 100% 20%, 100% 40%, 0 40%); }
        40% { transform: translate(-10px, 10px) skewX(-10deg); clip-path: polygon(0 40%, 100% 40%, 100% 60%, 0 60%); }
        60% { transform: translate(10px, -10px) skewX(10deg); clip-path: polygon(0 60%, 100% 60%, 100% 80%, 0 80%); }
        80% { transform: translate(-5px, 5px) skewX(-5deg); clip-path: polygon(0 80%, 100% 80%, 100% 100%, 0 100%); }
        100% { opacity: 1; transform: translate(0, 0) skewX(0); clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); }
    }
    @keyframes glitchSkew {
        0%, 95%, 100% { transform: skewX(0); }
        96% { transform: skewX(-10deg); }
        98% { transform: skewX(10deg); }
    }
    /* Navigation buttons */
    .hero-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: white;
        transition: all 0.3s ease;
        opacity: 0;
    }
    .hero-slider-wrapper:hover .hero-nav-btn {
        opacity: 1;
    }
    .hero-nav-btn:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-50%) scale(1.1);
    }
    .hero-nav-prev { left: 20px; }
    .hero-nav-next { right: 20px; }

    /* Progress bar */
    .hero-progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: rgba(255,255,255,0.15);
        z-index: 10;
    }
    .hero-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent-primary, #7c3aed), var(--accent-secondary, #a855f7));
        width: 0%;
        animation: heroProgress var(--autoplay-speed, 5000ms) linear forwards;
    }
    .swiper-slide-active ~ .hero-progress-fill,
    .hero-progress-bar .hero-progress-fill {
        animation: heroProgress var(--autoplay-speed, 5000ms) linear forwards;
    }
    @keyframes heroProgress {
        from { width: 0%; }
        to { width: 100%; }
    }

    /* Slide counter */
    .hero-slide-counter {
        position: absolute;
        bottom: 20px;
        right: 24px;
        z-index: 10;
        display: flex;
        align-items: baseline;
        gap: 4px;
        color: white;
        font-family: 'Inter', system-ui, sans-serif;
    }
    .hero-counter-current {
        font-size: 1.6rem;
        font-weight: 900;
        letter-spacing: -1px;
    }
    .hero-counter-sep {
        font-size: 1rem;
        color: rgba(255,255,255,0.4);
        margin: 0 2px;
    }
    .hero-counter-total {
        font-size: 0.95rem;
        font-weight: 600;
        color: rgba(255,255,255,0.5);
    }

    /* Dots pagination */
    .hero-dots-pagination {
        position: absolute;
        bottom: 16px !important;
        z-index: 10;
    }
    .hero-dots-pagination .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        background: rgba(255,255,255,0.4);
        opacity: 1;
        transition: all 0.3s ease;
    }
    .hero-dots-pagination .swiper-pagination-bullet-active {
        background: white;
        width: 28px;
        border-radius: 5px;
    }

    /* Hide default swiper nav */
    .heroSwiper .swiper-button-next,
    .heroSwiper .swiper-button-prev {
        display: none !important;
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .hero-slide-content { padding: 24px 20px; }
        .hero-text-bottom-left,
        .hero-text-bottom-center { padding-bottom: 40px; }
        .hero-nav-btn { width: 36px; height: 36px; }
        .hero-nav-prev { left: 10px; }
        .hero-nav-next { right: 10px; }
        .hero-slide-counter { bottom: 12px; right: 16px; }
        .hero-counter-current { font-size: 1.2rem; }
        .hero-cta-btn { padding: 12px 24px; font-size: 0.9rem; }
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof Swiper === 'undefined') return;

        const effect = '{{ $sliderEffect }}';
        const autoplaySpeed = {{ $autoplaySpeed }};

        const swiperConfig = {
            loop: true,
            autoHeight: true,
            autoplay: {
                delay: autoplaySpeed,
                disableOnInteraction: false,
            },
            speed: 900,
            navigation: {
                nextEl: '.hero-nav-next',
                prevEl: '.hero-nav-prev',
            },
            pagination: {
                el: '.hero-dots-pagination',
                clickable: true,
            },
            on: {
                slideChange: function() {
                    // Update counter
                    const current = document.querySelector('.hero-counter-current');
                    if (current) {
                        current.textContent = String(this.realIndex + 1).padStart(2, '0');
                    }
                    // Restart progress bar
                    const fill = document.querySelector('.hero-progress-fill');
                    if (fill) {
                        fill.style.animation = 'none';
                        fill.offsetHeight; // trigger reflow
                        fill.style.animation = `heroProgress ${autoplaySpeed}ms linear forwards`;
                    }
                },
                init: function() {
                    // Start progress bar on init
                    const fill = document.querySelector('.hero-progress-fill');
                    if (fill) {
                        fill.style.animation = `heroProgress ${autoplaySpeed}ms linear forwards`;
                    }
                }
            }
        };

        // Apply effect
        if (effect === 'fade') {
            swiperConfig.effect = 'fade';
            swiperConfig.fadeEffect = { crossFade: true };
        } else if (effect === 'cube') {
            swiperConfig.effect = 'cube';
            swiperConfig.cubeEffect = { shadow: true, slideShadows: true, shadowOffset: 20, shadowScale: 0.94 };
        } else if (effect === 'coverflow') {
            swiperConfig.effect = 'coverflow';
            swiperConfig.coverflowEffect = { rotate: 30, stretch: 0, depth: 100, modifier: 1, slideShadows: true };
        } else if (effect === 'creative') {
            swiperConfig.effect = 'creative';
            swiperConfig.creativeEffect = {
                prev: { shadow: true, translate: ['-20%', 0, -1] },
                next: { translate: ['100%', 0, 0] },
            };
        }
        // 'slide' is default, no extra config needed

        new Swiper(".heroSwiper", swiperConfig);
    });
</script>
@endpush

