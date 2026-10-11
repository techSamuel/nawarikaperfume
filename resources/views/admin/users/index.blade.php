@extends('admin.layouts.app')
@section('title', 'Users Management')
@section('page_title', 'Users Management')

@section('content')
{{-- METRICS CARDS --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="admin-card" style="padding: 18px; border-left: 4px solid var(--accent-primary);">
        <div style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Total Users</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--text-primary); margin-top: 4px;">{{ $metrics['total'] }}</div>
    </div>
    <div class="admin-card" style="padding: 18px; border-left: 4px solid #3b82f6;">
        <div style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Customers</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #60a5fa; margin-top: 4px;">{{ $metrics['customers'] }}</div>
    </div>
    <div class="admin-card" style="padding: 18px; border-left: 4px solid #8b5cf6;">
        <div style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Administrators</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #a78bfa; margin-top: 4px;">{{ $metrics['admins'] }}</div>
    </div>
    <div class="admin-card" style="padding: 18px; border-left: 4px solid #ef4444;">
        <div style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Banned Users</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #f87171; margin-top: 4px;">{{ $metrics['banned'] }}</div>
    </div>
</div>

{{-- TOOLBAR & FILTERS --}}
<div class="admin-toolbar" style="margin-bottom: 20px;">
    <div class="admin-toolbar-left">
        <div class="admin-filters">
            <a href="{{ route('admin.users.index') }}" class="filter-btn {{ !request('role') && !request('status') ? 'active' : '' }}">All Users</a>
            <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="filter-btn {{ request('role') === 'customer' ? 'active' : '' }}">Customers</a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="filter-btn {{ request('role') === 'admin' ? 'active' : '' }}">Admins</a>
            <a href="{{ route('admin.users.index', ['status' => 'active']) }}" class="filter-btn {{ request('status') === 'active' ? 'active' : '' }}">Active</a>
            <a href="{{ route('admin.users.index', ['status' => 'banned']) }}" class="filter-btn {{ request('status') === 'banned' ? 'active' : '' }}" style="{{ request('status') === 'banned' ? 'background: #ef4444; color: #fff;' : '' }}">Banned ({{ $metrics['banned'] }})</a>
        </div>
    </div>
    <form action="{{ route('admin.users.index') }}" method="GET" style="display:flex; gap:8px;">
        @if(request('role'))
            <input type="hidden" name="role" value="{{ request('role') }}">
        @endif
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="search-bar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone...">
        </div>
        <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 14px;">Search</button>
        @if(request()->hasAny(['search', 'role', 'status']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm" style="display:flex; align-items:center; padding: 8px 12px;" title="Reset filters">✕</a>
        @endif
    </form>
</div>

{{-- USERS TABLE --}}
<div class="admin-card">
    <div class="admin-card-body no-padding">
        @if($users->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Registered</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr style="{{ $user->isBanned() ? 'background: rgba(239, 68, 68, 0.05);' : '' }}">
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 38px; height: 38px; border-radius: 50%; background: {{ $user->isAdmin() ? 'linear-gradient(135deg, #8b5cf6, #ec4899)' : 'var(--accent-gradient)' }}; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $user) }}" style="font-weight: 600; color: var(--text-primary); text-decoration: none;">
                                            {{ $user->name }}
                                        </a>
                                        <div style="font-size: 0.78rem; color: var(--text-muted);">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size: 0.85rem;">
                                {{ $user->phone ?? '—' }}
                            </td>
                            <td>
                                @if($user->isAdmin())
                                    <span class="badge" style="background: rgba(139, 92, 246, 0.2); color: #c084fc; border: 1px solid rgba(139, 92, 246, 0.4);">
                                        👑 Admin
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3);">
                                        Customer
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->isBanned())
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4);" title="{{ $user->ban_reason ? 'Reason: ' . $user->ban_reason : 'Banned' }}">
                                        🚫 Banned
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                        ✓ Active
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight: 600;">{{ $user->orders_count }}</span>
                            </td>
                            <td style="font-weight: 700; color: var(--accent-primary);">
                                ৳{{ number_format((float) ($user->total_spent ?? 0), 2) }}
                            </td>
                            <td style="font-size: 0.82rem; color: var(--text-muted);">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center;">
                                    {{-- VIEW --}}
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline btn-sm" style="padding: 5px 10px;" title="View Details">
                                        👁️ View
                                    </a>
                                    
                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-sm" style="padding: 5px 10px;" title="Edit User">
                                        ✏️ Edit
                                    </a>

                                    {{-- BAN / UNBAN TOGGLE --}}
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ $user->isBanned() ? 'Are you sure you want to UNBAN ' . addslashes($user->name) . '?' : 'Are you sure you want to BAN ' . addslashes($user->name) . '? They will immediately be logged out and cannot log in.' }}');">
                                            @csrf
                                            @if($user->isBanned())
                                                <button type="submit" class="btn btn-sm" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); padding: 5px 10px;" title="Unban this user">
                                                    🔓 Unban
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 5px 10px;" title="Ban this user">
                                                    🚫 Ban
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding: 16px 20px;">
                {{ $users->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
                <div style="font-size: 3rem; margin-bottom: 12px;">👥</div>
                <h3 style="margin-bottom: 6px; font-weight: 700; color: var(--text-primary);">No users found</h3>
                <p>Try adjusting your search criteria or filter options.</p>
                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm" style="margin-top: 12px;">Reset All Filters</a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
