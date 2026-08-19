@extends('layouts.auth')
@section('title', 'Login')

@section('content')
    <h1>Welcome Back</h1>
    <p class="auth-subtitle">Sign in to your account</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <label style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:var(--text-secondary); cursor:pointer;">
                <input type="checkbox" name="remember" style="accent-color:var(--accent-primary);">
                Remember me
            </label>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">Sign In</button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
    </div>
@endsection
