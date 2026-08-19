@extends('layouts.auth')
@section('title', 'Register')

@section('content')
    <h1>Create Account</h1>
    <p class="auth-subtitle">Join {{ $settings['site_name'] ?? 'us' }} and start shopping</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required>
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">Create Account</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
@endsection
