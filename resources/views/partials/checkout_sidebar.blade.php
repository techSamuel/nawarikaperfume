<div class="sidebar-checkout-header">
    <h2>Quick Checkout <span style="font-size: 0.85rem; color: var(--accent-primary); background: var(--bg-primary); border: 1px solid var(--border-color); padding: 2px 10px; border-radius: var(--radius-full); font-weight: 700; margin-left: 8px; vertical-align: middle;">({{ array_sum(array_column($cartItems, 'quantity')) }} {{ Str::plural('item', array_sum(array_column($cartItems, 'quantity'))) }})</span></h2>
    <button type="button" class="close-sidebar-btn" onclick="closeCheckoutSidebar()">&times;</button>
</div>

<div class="sidebar-checkout-content">
    @if(empty($cartItems))
        <div style="text-align:center; padding: 40px 20px;">
            <div style="font-size:3rem; margin-bottom:16px;">🛒</div>
            <p style="color:var(--text-secondary); margin-bottom: 24px;">Your cart is empty.</p>
            <button type="button" class="btn btn-outline" onclick="closeCheckoutSidebar()">Continue Shopping</button>
        </div>
    @else
        <form action="{{ route('checkout.store') }}" method="POST" id="quickCheckoutForm" style="display: flex; flex-direction: column; height: 100%;">
            @csrf
            
            <div style="flex: 1; overflow-y: auto;">
            
            {{-- CART ITEMS SUMMARY --}}
            <div class="sidebar-cart-items">
                @foreach($cartItems as $item)
                    <div class="sidebar-cart-item">
                        <div class="sidebar-cart-item-img">
                            @if($item['product']->image)
                                <img src="{{ asset('uploads/' . $item['product']->image) }}" alt="">
                            @else
                                🛍️
                            @endif
                        </div>
                        <div class="sidebar-cart-item-info">
                            <div class="sidebar-cart-item-title">{{ $item['product']->name }}</div>
                            <div class="sidebar-cart-item-controls" style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                                <div style="display: flex; align-items: center; border: 1px solid var(--border-color); border-radius: 4px; overflow: hidden; background: var(--bg-card);">
                                    <button type="button" onclick="updateCartItem({{ $item['product']->id }}, {{ $item['quantity'] - 1 }})" style="padding: 2px 10px; background: none; border: none; cursor: pointer; font-weight: bold; color: var(--text-primary);">-</button>
                                    <span style="padding: 2px 8px; font-size: 0.85rem; font-weight: 600; min-width: 20px; text-align: center;">{{ $item['quantity'] }}</span>
                                    <button type="button" onclick="updateCartItem({{ $item['product']->id }}, {{ $item['quantity'] + 1 }})" style="padding: 2px 10px; background: none; border: none; cursor: pointer; font-weight: bold; color: var(--text-primary);">+</button>
                                </div>
                                <button type="button" onclick="removeCartItem({{ $item['product']->id }})" style="color: #ef4444; background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center;" title="Remove Item">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div class="sidebar-cart-item-price">
                            ৳{{ number_format($item['total'], 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="sidebar-cart-totals">
                <div class="sidebar-cart-row">
                    <span>Subtotal</span>
                    <span>৳{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="sidebar-cart-row">
                    <span>Shipping</span>
                    <span>{{ $shipping == 0 ? 'Free' : '৳' . number_format($shipping, 2) }}</span>
                </div>
                <div class="sidebar-cart-row total">
                    <span>Total</span>
                    <span>৳{{ number_format($total, 2) }}</span>
                </div>
            </div>

            {{-- SHIPPING FORM --}}
            <div class="sidebar-shipping-form">
                <h3 style="margin-bottom: 16px; font-size: 1.1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Delivery Details</h3>
                
                @if(isset($settings['checkout_warning_text']) && !empty($settings['checkout_warning_text']))
                    <div class="alert alert-warning" style="margin-bottom: 16px; font-size: 0.8rem; padding: 10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <div>{{ $settings['checkout_warning_text'] }}</div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="sidebar_name">Full Name *</label>
                    <input type="text" id="sidebar_name" name="name" class="form-control" autocomplete="name" required>
                </div>
                <div class="form-group">
                    <label for="sidebar_phone">Phone Number *</label>
                    <input type="text" id="sidebar_phone" name="phone" class="form-control" placeholder="+880 1XXX-XXXXXX" autocomplete="tel" required>
                </div>
                <div class="form-group">
                    <label for="sidebar_address">Full Address *</label>
                    <textarea id="sidebar_address" name="address" class="form-control" rows="2" placeholder="House/Flat, Street, Area..." autocomplete="street-address" required></textarea>
                </div>
                <div class="form-group">
                    <label for="sidebar_notes">Order Notes (Optional)</label>
                    <textarea id="sidebar_notes" name="notes" class="form-control" rows="2" placeholder="Any special instructions..."></textarea>
                </div>
            </div>

            </div>

            <div class="sidebar-checkout-footer" style="flex-shrink: 0;">
                <button type="submit" class="btn btn-primary btn-block btn-lg" style="border-radius: 0; margin: 0; padding: 18px;">
                    Confirm Order — ৳{{ number_format($total, 2) }}
                </button>
            </div>
        </form>
    @endif
</div>
