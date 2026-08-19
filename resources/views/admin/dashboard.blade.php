@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
        <div class="stat-desc">{{ $stats['pending_orders'] }} pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">৳{{ number_format($stats['total_revenue'], 0) }}</div>
        <div class="stat-desc">Cash on Delivery</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Products</div>
        <div class="stat-value">{{ $stats['total_products'] }}</div>
        <div class="stat-desc">{{ $outOfStockProducts }} out of stock</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Customers</div>
        <div class="stat-value">{{ $stats['total_customers'] }}</div>
        <div class="stat-desc">Registered users</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:24px;">
    {{-- RECENT ORDERS --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="admin-card-body no-padding">
            @if($recentOrders->count() > 0)
                <table class="admin-table">
                    <thead>
                        <tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}" style="font-weight:600;">{{ $order->order_number }}</a></td>
                                <td>{{ $order->name }}</td>
                                <td style="font-weight:600;">৳{{ number_format($order->total, 2) }}</td>
                                <td><span class="badge {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                                <td style="color:var(--text-muted);">{{ $order->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state" style="padding:40px;"><p>No orders yet.</p></div>
            @endif
        </div>
    </div>

    {{-- LOW STOCK --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Low Stock Alert</h2>
        </div>
        <div class="admin-card-body">
            @if($lowStockProducts->count() > 0)
                @foreach($lowStockProducts as $product)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid var(--border-color);">
                        <div>
                            <div style="font-weight:600; font-size:0.88rem;">{{ $product->name }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $product->category->name ?? '' }}</div>
                        </div>
                        <span class="badge badge-warning">{{ $product->stock }} left</span>
                    </div>
                @endforeach
            @else
                <div class="empty-state" style="padding:20px;"><p>All products are well stocked! 🎉</p></div>
            @endif

            @if($outOfStockProducts > 0)
                <div style="margin-top:16px; padding:12px; background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.15); border-radius:var(--radius-md);">
                    <span style="color:var(--danger); font-weight:600; font-size:0.85rem;">⚠️ {{ $outOfStockProducts }} product(s) out of stock</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
