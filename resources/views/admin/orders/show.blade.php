@extends('admin.layouts.app')
@section('title', 'Order ' . $order->order_number)
@section('page_title', 'Order ' . $order->order_number)
@section('header_actions')
    <div style="display:flex; gap:10px; align-items:center;">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">← Back to Orders</a>
        <button type="button" onclick="document.getElementById('editOrderModal').style.display = 'flex';" class="btn btn-primary btn-sm">
            ✏️ Edit Order
        </button>
        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete Order #{{ $order->order_number }}? This action cannot be undone.');" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 8px 16px; font-weight:600;">
                🗑️ Delete Order
            </button>
        </form>
    </div>
@endsection

@section('content')
{{-- EDIT ORDER MODAL --}}
<div id="editOrderModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:9999; align-items:center; justify-content:center; padding:16px; overflow-y:auto;">
    <div style="background:var(--bg-card); border:1px solid var(--border-color); border-radius:12px; max-width:620px; width:100%; max-height:90vh; overflow-y:auto; padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; border-bottom:1px solid var(--border-color); padding-bottom:12px;">
            <h3 style="margin:0; font-size:1.15rem; color:var(--text-primary);">✏️ Edit Order #{{ $order->order_number }}</h3>
            <button type="button" onclick="document.getElementById('editOrderModal').style.display = 'none';" style="background:none; border:none; color:var(--text-muted); font-size:1.3rem; cursor:pointer;">&times;</button>
        </div>
        <p style="color:var(--text-secondary); font-size:0.85rem; margin-bottom:16px;">
            Update customer info, address, delivery charge, or status. The customer will automatically receive an email detailing any changes made.
        </p>

        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                <div class="form-group">
                    <label style="display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600;">Recipient Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $order->name) }}" required class="form-control" style="width:100%; padding:9px 12px; background:var(--bg-tertiary); border:1px solid var(--border-color); color:white; border-radius:6px;">
                </div>
                <div class="form-group">
                    <label style="display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600;">Phone Number <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $order->phone) }}" required class="form-control" style="width:100%; padding:9px 12px; background:var(--bg-tertiary); border:1px solid var(--border-color); color:white; border-radius:6px;">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                <div class="form-group">
                    <label style="display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600;">Customer Email</label>
                    <input type="email" name="email" value="{{ old('email', $order->email) }}" placeholder="Optional (for notifications)" class="form-control" style="width:100%; padding:9px 12px; background:var(--bg-tertiary); border:1px solid var(--border-color); color:white; border-radius:6px;">
                </div>
                <div class="form-group">
                    <label style="display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600;">City / Area</label>
                    <input type="text" name="city" value="{{ old('city', $order->city) }}" placeholder="e.g. Dhaka, Chittagong" class="form-control" style="width:100%; padding:9px 12px; background:var(--bg-tertiary); border:1px solid var(--border-color); color:white; border-radius:6px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:14px;">
                <label style="display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600;">Delivery Address <span style="color:#ef4444;">*</span></label>
                <textarea name="address" rows="2" required class="form-control" style="width:100%; padding:9px 12px; background:var(--bg-tertiary); border:1px solid var(--border-color); color:white; border-radius:6px;">{{ old('address', $order->address) }}</textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                <div class="form-group">
                    <label style="display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600;">Order Status <span style="color:#ef4444;">*</span></label>
                    <select name="status" class="form-control" style="width:100%; padding:9px 12px; background:var(--bg-tertiary); border:1px solid var(--border-color); color:white; border-radius:6px;">
                        <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="confirm" {{ old('status', $order->status) === 'confirm' ? 'selected' : '' }}>Confirm</option>
                        <option value="shipped" {{ old('status', $order->status) === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ old('status', $order->status) === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600;">Shipping Charge (৳) <span style="color:#ef4444;">*</span></label>
                    <input type="number" step="0.01" min="0" name="shipping" value="{{ old('shipping', $order->shipping) }}" required class="form-control" style="width:100%; padding:9px 12px; background:var(--bg-tertiary); border:1px solid var(--border-color); color:white; border-radius:6px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600;">Order Notes</label>
                <textarea name="notes" rows="2" placeholder="Special customer instructions..." class="form-control" style="width:100%; padding:9px 12px; background:var(--bg-tertiary); border:1px solid var(--border-color); color:white; border-radius:6px;">{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; border-top:1px solid var(--border-color); padding-top:14px;">
                <button type="button" onclick="document.getElementById('editOrderModal').style.display = 'none';" class="btn btn-outline btn-sm">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="font-weight:700;">💾 Save Changes & Notify</button>
            </div>
        </form>
    </div>
</div>
{{-- STATUS UPDATE --}}
<div class="admin-card" style="margin-bottom:24px;">
    <div class="admin-card-body" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div style="display:flex; align-items:center; gap:16px;">
            <span style="font-weight:600;">Status:</span>
            <span class="badge {{ $order->status_badge }}" style="font-size:0.85rem; padding:6px 16px;">{{ ucfirst($order->status) }}</span>
        </div>
        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="status-select">
            @csrf @method('PATCH')
            <select name="status">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="confirm" {{ $order->status === 'confirm' ? 'selected' : '' }}>Confirm</option>
                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
        </form>
    </div>
</div>

<div class="order-detail-grid">
    {{-- ORDER INFO --}}
    <div class="order-info-card">
        <h3>Order Information</h3>
        <div class="order-info-row"><span>Order Number</span><span>{{ $order->order_number }}</span></div>
        <div class="order-info-row"><span>Date</span><span>{{ $order->created_at->format('M d, Y h:i A') }}</span></div>
        <div class="order-info-row"><span>Payment</span><span style="color:var(--success);">Cash on Delivery</span></div>
        <div class="order-info-row"><span>Subtotal</span><span>৳{{ number_format($order->subtotal, 2) }}</span></div>
        <div class="order-info-row"><span>Shipping</span><span>{{ $order->shipping == 0 ? 'Free' : '৳' . number_format($order->shipping, 2) }}</span></div>
        <div class="order-info-row" style="font-size:1.05rem; font-weight:700; padding-top:12px; border-top:1px solid var(--border-color); margin-top:8px;">
            <span>Total</span><span>৳{{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    {{-- CUSTOMER INFO --}}
    <div class="order-info-card">
        <h3>Customer Details</h3>
        <div class="order-info-row"><span>Name</span><span>{{ $order->name }}</span></div>
        <div class="order-info-row"><span>Email</span><span>{{ $order->email }}</span></div>
        <div class="order-info-row"><span>Phone</span><span>{{ $order->phone }}</span></div>
        <div class="order-info-row"><span>Address</span><span style="text-align:right; max-width:200px;">{{ $order->address }}</span></div>
        <div class="order-info-row"><span>City</span><span>{{ $order->city }}</span></div>
        @if($order->state)
            <div class="order-info-row"><span>State</span><span>{{ $order->state }}</span></div>
        @endif
        @if($order->zip)
            <div class="order-info-row"><span>ZIP</span><span>{{ $order->zip }}</span></div>
        @endif
        @if($order->notes)
            <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border-color);">
                <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px;">Notes</span>
                <p style="margin-top:6px; color:var(--text-secondary); font-size:0.9rem;">{{ $order->notes }}</p>
            </div>
        @endif
    </div>
</div>

<div class="order-detail-grid" style="margin-top: 24px;">
    {{-- CUSTOMER TECHNICAL DETAILS --}}
    <div class="order-info-card" style="grid-column: 1 / -1;">
        <h3>Customer Technical Details & Meta Tracking</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div class="order-info-row" style="margin-bottom:0;"><span>IP Address</span><span>{{ $order->ip_address ?? 'N/A' }}</span></div>
            <div class="order-info-row" style="margin-bottom:0;"><span>Country</span><span>{{ $order->country ?? 'N/A' }}</span></div>
            <div class="order-info-row" style="margin-bottom:0;"><span>Device</span><span>{{ $order->device_type ?? 'N/A' }}</span></div>
            <div class="order-info-row" style="margin-bottom:0;"><span>Platform</span><span>{{ $order->platform ?? 'N/A' }}</span></div>
            <div class="order-info-row" style="margin-bottom:0;"><span>Browser</span><span>{{ $order->browser ?? 'N/A' }}</span></div>
            <div class="order-info-row" style="margin-bottom:0;"><span>Timezone</span><span>{{ $order->timezone ?? 'N/A' }}</span></div>
            <div class="order-info-row" style="margin-bottom:0;">
                <span>Meta CAPI Status</span>
                <span>
                    @if($order->capi_purchase_sent_at)
                        <span class="badge badge-success" style="font-size:0.75rem;">✓ Sent ({{ $order->capi_purchase_sent_at->format('M d, h:i A') }})</span>
                    @else
                        <span class="badge badge-warning" style="font-size:0.75rem;">⏳ On Hold (Sends on Confirm)</span>
                    @endif
                </span>
            </div>
            @if($order->fbp)
                <div class="order-info-row" style="margin-bottom:0;"><span>Meta FBP Cookie</span><span style="font-size:0.75rem; font-family:monospace;">{{ Str::limit($order->fbp, 22) }}</span></div>
            @endif
        </div>
        @if($order->user_agent)
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--border-color);">
                <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px;">User Agent</span>
                <p style="margin-top:6px; color:var(--text-secondary); font-size:0.85rem; word-break: break-all;">{{ $order->user_agent }}</p>
            </div>
        @endif
    </div>
</div>

{{-- ORDER ITEMS --}}
<div class="admin-card" style="margin-top:24px;">
    <div class="admin-card-header">
        <h2>Order Items ({{ $order->items->count() }})</h2>
    </div>
    <div class="admin-card-body no-padding">
        <table class="admin-table">
            <thead>
                <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="table-product">
                                <div class="table-product-image">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('uploads/' . $item->product->image) }}" alt="">
                                    @else
                                        <div class="img-placeholder" style="font-size:1rem;">📦</div>
                                    @endif
                                </div>
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
@endsection
