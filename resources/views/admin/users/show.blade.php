@extends('admin.layouts.app')
@section('title', 'User: ' . $user->name)
@section('page_title', 'User Profile')

@section('header_actions')
<div style="display:flex; gap:10px; align-items:center;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">← Back to Users</a>
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">✏️ Edit User</a>

    @if($user->id !== auth()->id())
        @if($user->isBanned())
            <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to UNBAN this user?');">
                @csrf
                <button type="submit" class="btn btn-sm" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                    🔓 Unban User
                </button>
            </form>
        @else
            <button type="button" onclick="document.getElementById('banModal').style.display = 'flex';" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">
                🚫 Ban User
            </button>
        @endif

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to PERMANENTLY DELETE this user account? This cannot be undone.');">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2);">
                🗑️ Delete
            </button>
        </form>
    @endif
</div>
@endsection

@section('content')
{{-- BAN MODAL --}}
<div id="banModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--bg-card); border:1px solid var(--border-color); border-radius:12px; max-width:480px; width:100%; padding:24px;">
        <h3 style="margin:0 0 12px 0; color:#ef4444; font-size:1.2rem;">🚫 Ban User: {{ $user->name }}</h3>
        <p style="color:var(--text-secondary); font-size:0.9rem; margin-bottom:16px;">
            This will immediately invalidate the user's active session and block them from logging in.
        </p>
        <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom:20px;">
                <label for="ban_reason" style="display:block; margin-bottom:8px; font-weight:600;">Reason for Ban</label>
                <input type="text" name="ban_reason" id="ban_reason" class="form-control" placeholder="e.g. Fraudulent activity, abuse, terms violation" style="width:100%; padding:10px; background:var(--bg-tertiary); border:1px solid var(--border-color); color:white; border-radius:8px;">
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="document.getElementById('banModal').style.display = 'none';" class="btn btn-outline btn-sm">Cancel</button>
                <button type="submit" class="btn btn-sm" style="background:#ef4444; color:#fff; border:none; padding:8px 18px; font-weight:700;">Confirm Ban</button>
            </div>
        </form>
    </div>
</div>

{{-- BANNED BANNER IF BANNED --}}
@if($user->isBanned())
    <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 8px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <strong style="color: #ef4444; font-size: 1rem;">🚫 This Account is Suspended / Banned</strong>
            <div style="color: #cbd5e1; font-size: 0.88rem; margin-top: 4px;">
                <strong>Reason:</strong> {{ $user->ban_reason ?: 'No reason specified' }} &bull; 
                <strong>Banned on:</strong> {{ $user->banned_at ? $user->banned_at->format('M d, Y h:i A') : 'N/A' }}
            </div>
        </div>
        <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm" style="background:#10b981; color:#fff; border:none; padding:8px 16px; font-weight:700;">
                🔓 Unban Now
            </button>
        </form>
    </div>
@endif

{{-- USER SUMMARY ROW --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="admin-card" style="padding: 20px;">
        <div style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase;">Total Orders</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--text-primary); margin-top: 4px;">{{ $user->orders_count }}</div>
    </div>
    <div class="admin-card" style="padding: 20px;">
        <div style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase;">Completed Orders</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #34d399; margin-top: 4px;">{{ $completedOrdersCount }}</div>
    </div>
    <div class="admin-card" style="padding: 20px;">
        <div style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase;">Total Lifetime Spent</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--accent-primary); margin-top: 4px;">৳{{ number_format((float) $totalSpent, 2) }}</div>
    </div>
    <div class="admin-card" style="padding: 20px;">
        <div style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase;">Account Status</div>
        <div style="margin-top: 8px;">
            @if($user->isBanned())
                <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #f87171; font-size: 0.95rem; padding: 6px 14px;">🚫 Banned</span>
            @else
                <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 0.95rem; padding: 6px 14px;">✓ Active</span>
            @endif
        </div>
    </div>
</div>

<div class="order-detail-grid">
    {{-- PROFILE CARD --}}
    <div class="order-info-card">
        <h3>User Profile</h3>
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: {{ $user->isAdmin() ? 'linear-gradient(135deg, #8b5cf6, #ec4899)' : 'var(--accent-gradient)' }}; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.5rem; flex-shrink: 0;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-size: 1.2rem; font-weight: 800; color: var(--text-primary);">{{ $user->name }}</div>
                <div style="font-size: 0.88rem; color: var(--text-muted);">{{ $user->email }}</div>
            </div>
        </div>

        <div class="order-info-row"><span>User ID</span><span>#{{ $user->id }}</span></div>
        <div class="order-info-row">
            <span>Role</span>
            <span>
                @if($user->isAdmin())
                    <span class="badge" style="background: rgba(139, 92, 246, 0.2); color: #c084fc;">👑 Administrator</span>
                @else
                    <span class="badge" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;">Customer</span>
                @endif
            </span>
        </div>
        <div class="order-info-row"><span>Phone</span><span>{{ $user->phone ?? 'Not provided' }}</span></div>
        <div class="order-info-row"><span>Email Verified</span><span>{{ $user->email_verified_at ? '✓ Verified (' . $user->email_verified_at->format('M d, Y') . ')' : 'No' }}</span></div>
        <div class="order-info-row"><span>Registered Date</span><span>{{ $user->created_at->format('M d, Y h:i A') }}</span></div>
    </div>

    {{-- ADDRESS CARD --}}
    <div class="order-info-card">
        <h3>Saved Address & Contact</h3>
        <div class="order-info-row"><span>Street Address</span><span>{{ $user->address ?? 'Not provided' }}</span></div>
        <div class="order-info-row"><span>City</span><span>{{ $user->city ?? 'Not provided' }}</span></div>
        <div class="order-info-row"><span>State / District</span><span>{{ $user->state ?? 'Not provided' }}</span></div>
        <div class="order-info-row"><span>Postal / ZIP Code</span><span>{{ $user->zip ?? 'Not provided' }}</span></div>

        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border-color);">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-block" style="text-align: center; display: block;">
                ✏️ Edit Account Information
            </a>
        </div>
    </div>
</div>

{{-- ORDER HISTORY --}}
<div class="admin-card" style="margin-top: 28px;">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Order History ({{ $user->orders_count }})</h2>
    </div>
    <div class="admin-card-body no-padding">
        @if($orders->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" style="font-weight: 700; color: var(--accent-primary);">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">
                                {{ $order->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td>
                                <span class="badge {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td style="font-weight: 700;">
                                ৳{{ number_format($order->total, 2) }}
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">
                                    View Order →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding: 16px 20px;">
                {{ $orders->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                <p>No orders placed by this user yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
