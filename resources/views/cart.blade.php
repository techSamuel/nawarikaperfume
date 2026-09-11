@extends('layouts.app')
@section('title', 'Shopping Cart')

@section('content')
<div class="container cart-page">
    <h1>Shopping Cart <span style="font-size: 1.1rem; color: var(--accent-primary); font-weight: 600; margin-left: 8px;">({{ array_sum(array_column($cartItems, 'quantity')) }} {{ Str::plural('item', array_sum(array_column($cartItems, 'quantity'))) }})</span></h1>

    @if(count($cartItems) > 0)
        <div class="cart-layout">
            <div class="cart-items">
                @foreach($cartItems as $item)
                    <div class="cart-item">
                        <div class="cart-item-image">
                            @if($item['product']->image)
                                <img src="{{ asset('uploads/' . $item['product']->image) }}" alt="{{ $item['product']->name }}">
                            @else
                                <div class="img-placeholder">🛍️</div>
                            @endif
                        </div>
                        <div class="cart-item-info">
                            <h3><a href="{{ route('product.show', $item['product']->slug) }}">{{ $item['product']->name }}</a></h3>
                            <div class="cart-item-price">৳{{ number_format($item['price'], 2) }}</div>
                        </div>
                        <div class="cart-item-quantity">
                            <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" style="display:flex; align-items:center; gap:8px;">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" onchange="this.form.submit()">
                            </form>
                        </div>
                        <div class="cart-item-total">৳{{ number_format($item['total'], 2) }}</div>
                        <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cart-item-remove" title="Remove">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                @endforeach

                <div style="display:flex; justify-content:space-between; margin-top:8px;">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Clear Cart</button>
                    </form>
                    <a href="{{ route('shop') }}" class="btn btn-outline btn-sm">← Continue Shopping</a>
                </div>
            </div>

            <div class="cart-summary">
                <h2>Order Summary</h2>
                <div class="cart-summary-row">
                    <span>Subtotal</span>
                    <span>৳{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="cart-summary-row">
                    <span>Shipping</span>
                    <span>{{ $shipping == 0 ? 'Free' : '৳' . number_format($shipping, 2) }}</span>
                </div>
                @if($subtotal < 500 && $subtotal > 0)
                    <div class="free-shipping-note" style="color:var(--warning); text-align:left; margin-bottom:8px;">
                        Add ৳{{ number_format(500 - $subtotal, 2) }} more for free shipping!
                    </div>
                @endif
                <div class="cart-summary-row total">
                    <span>Total</span>
                    <span>৳{{ number_format($total, 2) }}</span>
                </div>
                <div class="cod-badge" style="margin-top:16px; margin-bottom:16px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    Cash on Delivery
                </div>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block btn-lg">Proceed to Checkout →</a>
            </div>
        </div>
    @else
        <div class="cart-empty">
            <div style="font-size:4rem; margin-bottom:16px;">🛒</div>
            <h2>Your cart is empty</h2>
            <p style="color:var(--text-secondary);">Looks like you haven't added any items yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">Start Shopping →</a>
        </div>
    @endif
</div>
@endsection
