@extends('admin.layouts.app')
@section('title', 'Orders')
@section('page_title', 'Orders')

@section('content')
<div class="admin-toolbar">
    <div class="admin-toolbar-left">
        <div class="admin-filters">
            <a href="{{ route('admin.orders.index') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">All <span class="count">({{ $statusCounts['all'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="filter-btn {{ request('status') === 'pending' ? 'active' : '' }}">Pending <span class="count">({{ $statusCounts['pending'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="filter-btn {{ request('status') === 'processing' ? 'active' : '' }}">Processing <span class="count">({{ $statusCounts['processing'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'confirm']) }}" class="filter-btn {{ request('status') === 'confirm' ? 'active' : '' }}">Confirm <span class="count">({{ $statusCounts['confirm'] ?? 0 }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="filter-btn {{ request('status') === 'shipped' ? 'active' : '' }}">Shipped <span class="count">({{ $statusCounts['shipped'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="filter-btn {{ request('status') === 'delivered' ? 'active' : '' }}">Delivered <span class="count">({{ $statusCounts['delivered'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="filter-btn {{ request('status') === 'cancelled' ? 'active' : '' }}">Cancelled <span class="count">({{ $statusCounts['cancelled'] }})</span></a>
        </div>
    </div>
    <form action="{{ route('admin.orders.index') }}" method="GET">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="search-bar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders...">
        </div>
    </form>
</div>

<div class="admin-card">
    <div class="admin-card-body no-padding">
        @if($orders->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr><th>Order #</th><th>Customer</th><th>Phone</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Action</th></tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}" style="font-weight:600;">{{ $order->order_number }}</a></td>
                            <td>
                                <div>
                                    <div style="font-weight:600;">{{ $order->name }}</div>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $order->email }}</div>
                                </div>
                            </td>
                            <td style="font-size:0.85rem;">{{ $order->phone }}</td>
                            <td>{{ $order->items->count() }}</td>
                            <td style="font-weight:700;">৳{{ number_format($order->total, 2) }}</td>
                            <td><span class="badge badge-info">COD</span></td>
                            <td><span class="badge {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                            <td style="color:var(--text-muted); font-size:0.85rem;">{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex; gap:6px; align-items:center;">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">View</a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete Order #{{ $order->order_number }}? This action cannot be undone.');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 4px 10px;" title="Delete Order">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state"><div class="empty-icon">🛒</div><h3>No orders found</h3><p>Orders will appear here when customers place them.</p></div>
        @endif
    </div>
</div>
<div class="pagination-wrapper">{{ $orders->links() }}</div>
@endsection
