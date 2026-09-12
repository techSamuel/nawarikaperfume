@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="container checkout-page">
    <h1>Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="checkout-layout">
            {{-- SHIPPING FORM --}}
            <div>
                <div class="form-card">
                    <h2>Shipping Information</h2>
                    @if(!empty($settings['checkout_warning_text']))
                        <div class="alert alert-warning" style="margin-bottom: 24px; font-size: 0.85rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            <div>{{ $settings['checkout_warning_text'] }}</div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}" placeholder="+880 1XXX-XXXXXX" required>
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="address">Full Address *</label>
                        <textarea id="address" name="address" class="form-control" rows="3" placeholder="House/Flat, Street, Area..." required>{{ old('address', $user->address ?? '') }}</textarea>
                        @error('address') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes">Order Notes (Optional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ORDER SUMMARY --}}
            <div>
                <div class="cart-summary">
                    <h2>Order Summary</h2>

                    @foreach($cartItems as $item)
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid var(--border-color);">
                            <div style="display:flex; align-items:center; gap:12px; flex:1;">
                                <div style="width:50px; height:50px; border-radius:8px; overflow:hidden; background:var(--bg-tertiary); flex-shrink:0;">
                                    @if($item['product']->image)
                                        <img src="{{ asset('uploads/' . $item['product']->image) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <div class="img-placeholder" style="font-size:1.2rem;">🛍️</div>
                                    @endif
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-size:0.85rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item['product']->name }}</div>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">Qty: {{ $item['quantity'] }}</div>
                                </div>
                            </div>
                            <div style="font-weight:600; white-space:nowrap;">৳{{ number_format($item['total'], 2) }}</div>
                        </div>
                    @endforeach

                    <div class="cart-summary-row">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="cart-summary-row">
                        <span>Shipping</span>
                        <span>{{ $shipping == 0 ? 'Free' : '৳' . number_format($shipping, 2) }}</span>
                    </div>
                    <div class="cart-summary-row total">
                        <span>Total</span>
                        <span>৳{{ number_format($total, 2) }}</span>
                    </div>

                    <div class="payment-method-card" style="margin-top:20px;">
                        <div class="payment-icon">💰</div>
                        <div class="payment-info">
                            <h3>Cash on Delivery</h3>
                            <p>Pay ৳{{ number_format($total, 2) }} when you receive your order</p>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        Place Order — ৳{{ number_format($total, 2) }}
                    </button>

                    <p style="text-align:center; margin-top:12px; font-size:0.75rem; color:var(--text-muted);">
                        By placing your order, you agree to our terms and conditions.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    if (typeof fbq === 'function') {
        fbq('track', 'InitiateCheckout', {
            value: {{ $total }},
            currency: 'BDT',
            content_ids: {!! json_encode(array_keys($cartItems ?? [])) !!},
            content_type: 'product',
            num_items: {{ count($cartItems ?? []) }}
        });
    }
</script>
@endpush
