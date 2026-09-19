@extends('layouts.app')

@section('title', 'FAQs | ' . ($settings['site_name'] ?? 'Store'))

@section('content')
<div class="container" style="max-width: 800px; padding: 60px 20px;">
    <h1 style="text-align: center; margin-bottom: 40px; font-size: 2.5rem; color: var(--text-primary);">Frequently Asked Questions</h1>
    
    <div style="display: flex; flex-direction: column; gap: 16px;">
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
            <h4 style="margin-bottom: 12px; color: var(--text-primary);">How can I track my order?</h4>
            <p style="color: var(--text-secondary); line-height: 1.6; margin: 0;">
                You can easily track your order by visiting our <a href="{{ route('order.track.page') }}" style="color: var(--accent-primary);">Track Order</a> page and entering your order number or phone number.
            </p>
        </div>

        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
            <h4 style="margin-bottom: 12px; color: var(--text-primary);">Do you offer Cash on Delivery (COD)?</h4>
            <p style="color: var(--text-secondary); line-height: 1.6; margin: 0;">
                Yes, we offer Cash on Delivery for all orders across the country. You can pay when you receive your package.
            </p>
        </div>

        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
            <h4 style="margin-bottom: 12px; color: var(--text-primary);">What is the delivery time?</h4>
            <p style="color: var(--text-secondary); line-height: 1.6; margin: 0;">
                Standard delivery takes 2-5 business days depending on your location. Deliveries outside Dhaka might take slightly longer.
            </p>
        </div>

        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
            <h4 style="margin-bottom: 12px; color: var(--text-primary);">Can I cancel my order?</h4>
            <p style="color: var(--text-secondary); line-height: 1.6; margin: 0;">
                You can cancel your order before it has been shipped. Please contact our support team as soon as possible if you wish to cancel.
            </p>
        </div>
    </div>
</div>
@endsection
