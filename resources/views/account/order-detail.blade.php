@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="container account-page">
    <div style="display:flex; align-items:center; gap:16px; margin-bottom:32px;">
        <a href="{{ route('account.orders') }}" class="btn btn-outline btn-sm">← Back to Orders</a>
        <h1 style="margin:0;">Order {{ $order->order_number }}</h1>
        <span class="badge {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">
        <div class="form-card">
            <h2>Order Info</h2>
            <div class="product-meta" style="margin:0;">
                <div class="product-meta-item"><span>Order Date</span><span>{{ $order->created_at->format('M d, Y h:i A') }}</span></div>
                <div class="product-meta-item"><span>Payment</span><span style="color:var(--success);">Cash on Delivery</span></div>
                <div class="product-meta-item"><span>Subtotal</span><span>৳{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="product-meta-item"><span>Shipping</span><span>{{ $order->shipping == 0 ? 'Free' : '৳' . number_format($order->shipping, 2) }}</span></div>
                <div class="product-meta-item"><span>Total</span><span style="font-size:1.1rem;">৳{{ number_format($order->total, 2) }}</span></div>
            </div>
        </div>
        <div class="form-card">
            <h2>Shipping Address</h2>
            <p style="color:var(--text-secondary); line-height:1.9;">
                <strong>{{ $order->name }}</strong><br>
                📞 {{ $order->phone }}<br>
                📧 {{ $order->email }}<br>
                📍 {{ $order->address }}<br>
                {{ $order->city }}{{ $order->state ? ', ' . $order->state : '' }}{{ $order->zip ? ' - ' . $order->zip : '' }}
            </p>
            @if($order->notes)
                <p style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border-color); color:var(--text-muted); font-size:0.85rem;">
                    <strong>Notes:</strong> {{ $order->notes }}
                </p>
            @endif
        </div>
    </div>

    <div class="form-card">
        <h2>Items</h2>
        <div class="table-wrapper" style="border:none;">
            <table>
                <thead>
                    <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    @if($item->product && $item->product->image)
                                        <div style="width:45px; height:45px; border-radius:8px; overflow:hidden; background:var(--bg-tertiary);">
                                            <img src="{{ asset('uploads/' . $item->product->image) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                                        </div>
                                    @endif
                                    <span style="font-weight:600;">{{ $item->product_name }}</span>
                                </div>
                            </td>
                            <td>৳{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td style="font-weight:600;">৳{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
