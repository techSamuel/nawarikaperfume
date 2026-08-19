@extends('layouts.app')
@section('title', 'Order Confirmed')

@section('content')
<div class="container confirmation-page">
    <div class="confirmation-icon">✓</div>
    <h1>Order Confirmed!</h1>
    <p class="order-number">Order #{{ $order->order_number }}</p>
    <p style="color:var(--text-secondary); margin-bottom:40px; max-width:500px; margin-left:auto; margin-right:auto;">
        Thank you for your order! Your order has been placed successfully and will be delivered to your doorstep. Pay <strong>৳{{ number_format($order->total, 2) }}</strong> upon delivery.
    </p>

    <div class="order-detail-card">
        <div class="form-card" style="margin-bottom:20px;">
            <h2>Order Details</h2>
            <div class="product-meta">
                <div class="product-meta-item">
                    <span>Order Number</span>
                    <span>{{ $order->order_number }}</span>
                </div>
                <div class="product-meta-item">
                    <span>Date</span>
                    <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="product-meta-item">
                    <span>Status</span>
                    <span><span class="badge badge-warning">{{ ucfirst($order->status) }}</span></span>
                </div>
                <div class="product-meta-item">
                    <span>Payment</span>
                    <span style="color:var(--success);">Cash on Delivery</span>
                </div>
            </div>
        </div>

        <div class="form-card" style="margin-bottom:20px;">
            <h2>Items Ordered</h2>
            @foreach($order->items as $item)
                <div style="display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid var(--border-color);">
                    <div>
                        <div style="font-weight:600;">{{ $item->product_name }}</div>
                        <div style="font-size:0.8rem; color:var(--text-muted);">Qty: {{ $item->quantity }} × ৳{{ number_format($item->price, 2) }}</div>
                    </div>
                    <div style="font-weight:600;">৳{{ number_format($item->price * $item->quantity, 2) }}</div>
                </div>
            @endforeach

            <div style="margin-top:16px;">
                <div class="cart-summary-row">
                    <span>Subtotal</span>
                    <span>৳{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="cart-summary-row">
                    <span>Shipping</span>
                    <span>{{ $order->shipping == 0 ? 'Free' : '৳' . number_format($order->shipping, 2) }}</span>
                </div>
                <div class="cart-summary-row total">
                    <span>Total (COD)</span>
                    <span>৳{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="form-card">
            <h2>Delivery Address</h2>
            <p style="color:var(--text-secondary); line-height:1.8;">
                {{ $order->name }}<br>
                {{ $order->phone }}<br>
                {{ $order->address }}<br>
                {{ $order->city }}{{ $order->state ? ', ' . $order->state : '' }}{{ $order->zip ? ' - ' . $order->zip : '' }}
            </p>
        </div>
    </div>

    <div style="margin-top:32px; display:flex; gap:16px; justify-content:center;">
        <a href="{{ route('account.orders') }}" class="btn btn-outline">View My Orders</a>
        <a href="{{ route('shop') }}" class="btn btn-primary">Continue Shopping →</a>
    </div>
</div>
@endsection
