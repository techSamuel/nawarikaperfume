@extends('layouts.app')

@section('title', 'Return Policy | ' . ($settings['site_name'] ?? 'Store'))

@section('content')
<div class="container" style="max-width: 800px; padding: 60px 20px;">
    <h1 style="text-align: center; margin-bottom: 40px; font-size: 2.5rem; color: var(--text-primary);">Return Policy</h1>
    
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; color: var(--text-secondary); line-height: 1.8;">
        <h3 style="color: var(--text-primary); margin-bottom: 16px;">1. Returns</h3>
        <p style="margin-bottom: 24px;">You have 7 calendar days to return an item from the date you received it. To be eligible for a return, your item must be unused and in the same condition that you received it. Your item must be in the original packaging.</p>

        <h3 style="color: var(--text-primary); margin-bottom: 16px;">2. Refunds</h3>
        <p style="margin-bottom: 24px;">Once we receive your item, we will inspect it and notify you that we have received your returned item. If your return is approved, we will initiate a refund to your original method of payment.</p>

        <h3 style="color: var(--text-primary); margin-bottom: 16px;">3. Shipping for Returns</h3>
        <p style="margin-bottom: 24px;">You will be responsible for paying for your own shipping costs for returning your item. Shipping costs are non-refundable.</p>

        <h3 style="color: var(--text-primary); margin-bottom: 16px;">4. Contact Us</h3>
        <p style="margin-bottom: 0;">If you have any questions on how to return your item to us, contact us via our Contact page.</p>
    </div>
</div>
@endsection
