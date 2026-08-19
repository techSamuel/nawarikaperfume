@extends('layouts.app')
@section('title', 'Shop')
@section('meta_description', 'Browse our complete collection of premium products. Filter by category, price, and more.')

@section('content')
<div class="container">
    <div class="shop-layout">
        {{-- SIDEBAR --}}
        <aside class="shop-sidebar">
            <div class="filter-card">
                <h3>Categories</h3>
                <ul class="filter-list">
                    <li><a href="{{ route('shop') }}" class="{{ !request('category') ? 'active' : '' }}">{{ __('messages.all_categories') }}</a></li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('shop', ['category' => $category->slug]) }}" class="{{ request('category') === $category->slug ? 'active' : '' }}">
                                {{ $category->translated_name }}
                                <span class="filter-count">{{ $category->products_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="filter-card">
                <h3>Search</h3>
                <form action="{{ route('shop') }}" method="GET">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="search-bar" style="max-width:100%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_placeholder') }}">
                    </div>
                </form>
            </div>
        </aside>

        {{-- PRODUCTS --}}
        <div>
            <div class="shop-header">
                <h1>
                    @if(request('category'))
                        {{ $categories->firstWhere('slug', request('category'))->translated_name ?? __('messages.shop') }}
                    @elseif(request('search'))
                        Results for "{{ request('search') }}"
                    @else
                        {{ __('messages.shop') }}
                    @endif
                </h1>
                <div class="shop-sort">
                    <form action="{{ route('shop') }}" method="GET" id="sortForm">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="sort" onchange="document.getElementById('sortForm').submit()">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name: A-Z</option>
                        </select>
                    </form>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="product-grid">
                    @foreach($products as $product)
                        <div class="product-card animate-fade-in-up">
                            <a href="{{ route('product.show', $product->slug) }}" class="product-card-image" onmouseenter="let v=this.querySelector('video'); if(v) v.play()" onmouseleave="let v=this.querySelector('video'); if(v) { v.pause(); v.currentTime = 0; }">
                                @if(is_array($product->videos) && count($product->videos) > 0)
                                    <video src="{{ asset('storage/' . $product->videos[0]) }}" muted loop playsinline class="hover-video"></video>
                                @endif
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->translated_name }}">
                                @else
                                    <div class="img-placeholder">🛍️</div>
                                @endif
                                @if($product->isOnSale())
                                    <span class="product-card-badge badge-sale">-{{ $product->discount_percent }}%</span>
                                @elseif($product->created_at->diffInDays(now()) < 7)
                                    <span class="product-card-badge badge-new">New</span>
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

                <div class="pagination-wrapper">
                    {{ $products->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">🔍</div>
                    <h3>No products found</h3>
                    <p>Try adjusting your filters or search terms</p>
                    <a href="{{ route('shop') }}" class="btn btn-outline">Clear Filters</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
