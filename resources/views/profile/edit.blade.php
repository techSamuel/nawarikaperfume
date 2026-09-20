@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="container" style="padding: 120px 20px 60px;">
    <div class="profile-layout">
        
        {{-- SIDEBAR --}}
        <div class="profile-sidebar">
            <div style="padding: 24px; text-align: center; border-bottom: 1px solid var(--border-color);">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--accent-primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; margin: 0 auto 16px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h3 style="margin-bottom: 4px; color: var(--text-primary); font-size: 1.2rem;">{{ $user->name }}</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem;">{{ $user->email }}</p>
            </div>
            <div class="profile-tabs-container">
                <button class="profile-tab active" onclick="switchTab('profile')">Personal Info</button>
                <button class="profile-tab" onclick="switchTab('security')">Security</button>
                <button class="profile-tab" onclick="switchTab('orders')">Recent Orders</button>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;" class="profile-tab" id="logout-form-btn">
                    @csrf
                    <button type="submit" style="width: 100%; text-align: left; color: #ef4444; background: none; border: none; padding: 0; font: inherit; cursor: pointer;">Logout</button>
                </form>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="profile-content">
            
            {{-- TAB: PROFILE --}}
            <div id="tab-profile" class="tab-pane active profile-tab-pane" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px;">
                <h2 style="margin-bottom: 8px; color: var(--text-primary);">Personal Information</h2>
                <p style="color: var(--text-secondary); margin-bottom: 24px;">Update your account's profile information and email address.</p>

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div style="margin-bottom: 20px;">
                        <label for="name" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">
                        @error('name') <span style="color: #ef4444; font-size: 0.85rem; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label for="email" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">
                        @error('email') <span style="color: #ef4444; font-size: 0.85rem; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        @if (session('status') === 'profile-updated')
                            <span style="color: #10b981; font-size: 0.9rem;">Saved successfully.</span>
                        @endif
                    </div>
                </form>
            </div>

            {{-- TAB: SECURITY --}}
            <div id="tab-security" class="tab-pane profile-tab-pane" style="display: none; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px;">
                <h2 style="margin-bottom: 8px; color: var(--text-primary);">Update Password</h2>
                <p style="color: var(--text-secondary); margin-bottom: 24px;">Ensure your account is using a long, random password to stay secure.</p>

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div style="margin-bottom: 20px;">
                        <label for="current_password" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required
                               style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">
                        @error('current_password') <span style="color: #ef4444; font-size: 0.85rem; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="password" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">New Password</label>
                        <input type="password" id="password" name="password" required
                               style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">
                        @error('password') <span style="color: #ef4444; font-size: 0.85rem; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label for="password_confirmation" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">
                        @error('password_confirmation') <span style="color: #ef4444; font-size: 0.85rem; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                        @if (session('status') === 'password-updated')
                            <span style="color: #10b981; font-size: 0.9rem;">Password updated successfully.</span>
                        @endif
                    </div>
                </form>
            </div>

            {{-- TAB: ORDERS --}}
            <div id="tab-orders" class="tab-pane profile-tab-pane" style="display: none; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 10px;">
                    <h2 style="margin: 0; color: var(--text-primary);">Recent Orders</h2>
                    <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline">View All</a>
                </div>

                @if(isset($orders) && $orders->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 500px;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.9rem;">
                                    <th style="padding: 12px;">Order #</th>
                                    <th style="padding: 12px;">Date</th>
                                    <th style="padding: 12px;">Total</th>
                                    <th style="padding: 12px;">Status</th>
                                    <th style="padding: 12px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 16px 12px; font-weight: 600; color: var(--text-primary);">{{ $order->order_number }}</td>
                                        <td style="padding: 16px 12px; color: var(--text-secondary);">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td style="padding: 16px 12px; font-weight: 600; color: var(--accent-primary);">৳{{ number_format($order->total, 2) }}</td>
                                        <td style="padding: 16px 12px;">
                                            <span class="badge {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                                        </td>
                                        <td style="padding: 16px 12px; text-align: right;">
                                            <a href="{{ route('account.order-detail', $order->order_number) }}" class="btn btn-sm btn-outline">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px 20px; background: var(--bg-secondary); border-radius: 12px;">
                        <div style="font-size: 2.5rem; margin-bottom: 16px;">📦</div>
                        <h4 style="color: var(--text-primary); margin-bottom: 8px;">No orders yet</h4>
                        <p style="color: var(--text-secondary); margin-bottom: 16px;">You haven't placed any orders. Start shopping to see your orders here.</p>
                        <a href="{{ route('shop') }}" class="btn btn-primary">Start Shopping</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<style>
    .profile-layout {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 32px;
        align-items: start;
    }
    
    .profile-sidebar {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        position: sticky;
        top: 100px;
    }

    .profile-tab {
        background: transparent;
        border: none;
        border-left: 3px solid transparent;
        padding: 16px 24px;
        text-align: left;
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--border-color);
        width: 100%;
        display: block;
    }
    .profile-tab:last-child {
        border-bottom: none;
    }
    .profile-tab:hover {
        background: var(--bg-secondary);
        color: var(--text-primary);
    }
    .profile-tab.active {
        background: var(--bg-secondary);
        color: var(--accent-primary);
        border-left-color: var(--accent-primary);
    }
    
    @media (max-width: 768px) {
        .profile-layout {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .profile-sidebar {
            position: static;
        }
        .profile-tab {
            border-left: none;
            border-bottom: 2px solid transparent;
            padding: 12px 16px;
            text-align: center;
        }
        .profile-tab.active {
            border-left: none;
            border-bottom-color: var(--accent-primary);
        }
        .profile-tabs-container {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        .profile-tab {
            width: auto;
            flex: 1;
        }
        .profile-tab-pane {
            padding: 20px !important;
        }
    }
    @media (max-width: 480px) {
        .profile-tabs-container {
            flex-direction: column;
        }
        .profile-tab {
            border-left: 3px solid transparent;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }
        .profile-tab.active {
            border-bottom-color: var(--border-color);
            border-left-color: var(--accent-primary);
        }
        .profile-tab:last-child {
            border-bottom: none;
        }
    }
</style>

<script>
    function switchTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('.tab-pane').forEach(el => el.style.display = 'none');
        // Remove active class from all buttons
        document.querySelectorAll('.profile-tab').forEach(el => el.classList.remove('active'));
        
        // Show selected tab
        document.getElementById('tab-' + tabId).style.display = 'block';
        // Add active class to clicked button
        event.currentTarget.classList.add('active');
        
        // Update URL hash without jumping
        history.pushState(null, null, '#' + tabId);
    }

    // Check hash on load
    document.addEventListener('DOMContentLoaded', () => {
        if(window.location.hash) {
            const hash = window.location.hash.substring(1);
            if(['profile', 'security', 'orders'].includes(hash)) {
                // Find the button and trigger click
                document.querySelector(`button[onclick="switchTab('${hash}')"]`).click();
            }
        }
    });
</script>
@endsection
