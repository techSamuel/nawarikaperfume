@extends('layouts.app')

@section('title', 'Contact Us | ' . ($settings['site_name'] ?? 'Store'))

@section('content')
<div class="container" style="max-width: 800px; padding: 60px 20px;">
    <h1 style="text-align: center; margin-bottom: 24px; font-size: 2.5rem; color: var(--text-primary);">Contact Us</h1>
    <p style="text-align: center; color: var(--text-secondary); margin-bottom: 40px; font-size: 1.1rem;">
        We would love to hear from you. Reach out to us for any inquiries.
    </p>

    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; margin-bottom: 40px;">
        <h3 style="margin-bottom: 16px; color: var(--text-primary);">Get in touch</h3>
        @if(isset($settings['contact_email']) && $settings['contact_email'])
            <p style="margin-bottom: 12px; color: var(--text-secondary);">
                <strong>Email:</strong> <a href="mailto:{{ $settings['contact_email'] }}" style="color: var(--accent-primary);">{{ $settings['contact_email'] }}</a>
            </p>
        @endif
        @if(isset($settings['contact_phone']) && $settings['contact_phone'])
            <p style="margin-bottom: 12px; color: var(--text-secondary);">
                <strong>Phone:</strong> <a href="tel:{{ $settings['contact_phone'] }}" style="color: var(--accent-primary);">{{ $settings['contact_phone'] }}</a>
            </p>
        @endif
        <p style="margin-bottom: 12px; color: var(--text-secondary);">
            <strong>Address:</strong> Dhaka, Bangladesh
        </p>
    </div>
</div>
@endsection
