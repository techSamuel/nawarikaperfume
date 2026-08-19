@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="container account-page">
    <h1>My Orders</h1>

    @if($orders->count() > 0)
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="font-weight:600;">{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ $order->items->count() }} items</td>
                            <td style="font-weight:600;">৳{{ number_format($order->total, 2) }}</td>
                            <td><span class="badge {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                            <td><a href="{{ route('account.order-detail', $order->order_number) }}" class="btn btn-outline btn-sm">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">
            {{ $orders->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h3>No orders yet</h3>
            <p>You haven't placed any orders. Start shopping to see your orders here.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">Start Shopping →</a>
        </div>
    @endif
</div>
@endsection
