@extends('admin.layouts.app')
@section('title', 'Edit User: ' . $user->name)
@section('page_title', 'Edit User')

@section('header_actions')
<a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline btn-sm">← Back to Profile</a>
@endsection

@section('content')
<div class="card" style="background: var(--bg-card); padding: 28px; border-radius: 12px; border: 1px solid var(--border-color); max-width: 900px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--accent-gradient); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.3rem;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h2 style="margin: 0; font-size: 1.3rem; font-weight: 800; color: var(--text-primary);">Edit Account: {{ $user->name }}</h2>
            <div style="font-size: 0.85rem; color: var(--text-muted);">User ID: #{{ $user->id }} &bull; Registered {{ $user->created_at->format('M d, Y') }}</div>
        </div>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="alert alert-error" style="margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            {{-- BASIC DETAILS --}}
            <div>
                <h3 style="margin-bottom: 16px; font-size: 1.05rem; color: var(--accent-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    👤 Personal Information
                </h3>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="name" style="display:block; margin-bottom: 8px; font-weight: 600;">Full Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="email" style="display:block; margin-bottom: 8px; font-weight: 600;">Email Address <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="phone" style="display:block; margin-bottom: 8px; font-weight: 600;">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 01700000000" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="role" style="display:block; margin-bottom: 8px; font-weight: 600;">Account Role <span style="color:#ef4444;">*</span></label>
                    <select name="role" id="role" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>👑 Administrator (Full Access)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="password" style="display:block; margin-bottom: 8px; font-weight: 600;">Change Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    <small style="color: var(--text-muted); display:block; margin-top: 4px;">Only enter a password if you wish to reset it for this user.</small>
                </div>
            </div>

            {{-- ADDRESS DETAILS --}}
            <div>
                <h3 style="margin-bottom: 16px; font-size: 1.05rem; color: var(--accent-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    📍 Address & Location
                </h3>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="address" style="display:block; margin-bottom: 8px; font-weight: 600;">Street Address</label>
                    <textarea name="address" id="address" rows="3" class="form-control" placeholder="House, Road, Area..." style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="city" style="display:block; margin-bottom: 8px; font-weight: 600;">City / District</label>
                    <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $user->city) }}" placeholder="e.g. Dhaka, Chittagong" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="state" style="display:block; margin-bottom: 8px; font-weight: 600;">State / Division</label>
                    <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $user->state) }}" placeholder="e.g. Dhaka Division" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="zip" style="display:block; margin-bottom: 8px; font-weight: 600;">ZIP / Postal Code</label>
                    <input type="text" name="zip" id="zip" class="form-control" value="{{ old('zip', $user->zip) }}" placeholder="e.g. 1205" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>
            </div>
        </div>

        <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                💾 Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
