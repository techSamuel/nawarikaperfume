@extends('layouts.app')

@section('title', 'Shipping Policy | ' . ($settings['site_name'] ?? 'Store'))

@section('content')
<div class="container" style="max-width: 800px; padding: 60px 20px;">
    <h1 style="text-align: center; margin-bottom: 40px; font-size: 2.5rem; color: var(--text-primary);">Shipping Policy</h1>
    
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; color: var(--text-secondary); line-height: 1.8;">
        <h3 style="color: var(--text-primary); margin-bottom: 16px;">1. Processing Time</h3>
        <p style="margin-bottom: 24px;">All orders are processed within 1-2 business days. Orders are not shipped or delivered on weekends or holidays.</p>

        <h3 style="color: var(--text-primary); margin-bottom: 16px;">2. Shipping Rates & Delivery Estimates</h3>
        <p style="margin-bottom: 24px;">Shipping charges for your order will be calculated and displayed at checkout. Standard delivery within Dhaka takes 1-3 business days, while outside Dhaka takes 3-5 business days.</p>

        <h3 style="color: var(--text-primary); margin-bottom: 16px;">3. Shipment Confirmation & Order Tracking</h3>
        <p style="margin-bottom: 24px;">You will receive a shipment confirmation email once your order has shipped containing your tracking number(s). You can also track your order on our Track Order page.</p>

        <h3 style="color: var(--text-primary); margin-bottom: 16px;">4. Damages</h3>
        <p style="margin-bottom: 0;">If you received your order damaged, please contact us immediately to file a claim. Please save all packaging materials and damaged goods before filing a claim.</p>
    </div>
</div>
@endsection
