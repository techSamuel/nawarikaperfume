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

    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px;">
        <h3 style="margin-bottom: 24px; color: var(--text-primary);">Send us a message</h3>
        
        <form action="{{ route('contact.submit') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="name" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">Your Name *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                           style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">
                    @error('name') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">Your Email *</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                           style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">
                    @error('email') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="phone" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">
                    @error('phone') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="subject" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">Subject *</label>
                    <input type="text" id="subject" name="subject" required value="{{ old('subject') }}"
                           style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">
                    @error('subject') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label for="message" style="display: block; margin-bottom: 8px; color: var(--text-primary); font-weight: 500;">Message *</label>
                <textarea id="message" name="message" rows="5" required
                          style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-secondary); color: var(--text-primary);">{{ old('message') }}</textarea>
                @error('message') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 1.1rem; border-radius: var(--radius-md);">
                Send Message
            </button>
        </form>
    </div>
</div>
@endsection
