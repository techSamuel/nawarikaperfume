@extends('layouts.app')

@section('title', 'My Wishlist | ' . ($settings['site_name'] ?? 'LUXE'))

@section('content')
<div class="container" style="padding: 40px 20px; min-height: 60vh;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin: 0 0 6px 0;">My Wishlist</h1>
            <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">Manage the items you've saved for later.</p>
        </div>
        <a href="{{ route('shop') }}" class="btn btn-outline">Continue Shopping →</a>
    </div>

    @if($wishlistItems->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px;">
            @foreach($wishlistItems as $item)
                @php $product = $item->product; @endphp
                @if($product)
                    <div class="product-card" id="wishlist-item-{{ $product->id }}" style="display: flex; flex-direction: column; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; position: relative;">
                        <button type="button" onclick="removeFromWishlist({{ $product->id }})" title="Remove from wishlist" 
                                style="position: absolute; top: 12px; right: 12px; z-index: 10; background: rgba(0,0,0,0.5); border: none; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; transition: all 0.2s ease;">
                            ✕
                        </button>

                        <a href="{{ route('product.show', $product->slug) }}" style="display: block; width: 100%; aspect-ratio: 1; background: var(--bg-secondary); overflow: hidden;">
                            @if($product->image)
                                <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                            @else
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 3rem; color: var(--text-muted);">🛍️</div>
                            @endif
                        </a>

                        <div style="padding: 20px; display: flex; flex-direction: column; flex: 1;">
                            <div style="font-size: 0.8rem; color: var(--accent-primary); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">
                                {{ $product->category->name ?? 'General' }}
                            </div>
                            <h3 style="font-size: 1.05rem; font-weight: 600; margin: 0 0 10px 0; line-height: 1.3;">
                                <a href="{{ route('product.show', $product->slug) }}" style="color: var(--text-primary); text-decoration: none;">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                                <span style="font-size: 1.15rem; font-weight: 700; color: var(--accent-primary);">৳{{ number_format($product->display_price, 2) }}</span>
                                @if($product->isOnSale())
                                    <span style="font-size: 0.9rem; text-decoration: line-through; color: var(--text-muted);">৳{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>

                            <div style="margin-top: auto; display: flex; gap: 8px;">
                                @if($product->isInStock())
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; font-size: 0.9rem; border-radius: var(--radius-sm);">
                                            Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="btn btn-outline" style="width: 100%; opacity: 0.6; padding: 10px; font-size: 0.9rem;">
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 80px 20px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); margin-top: 20px;">
            <div style="font-size: 3.5rem; margin-bottom: 16px;">🤍</div>
            <h2 style="font-size: 1.5rem; color: var(--text-primary); margin-bottom: 10px;">Your Wishlist is Empty</h2>
            <p style="color: var(--text-secondary); max-width: 450px; margin: 0 auto 24px auto;">
                Explore our store and tap the heart icon on any product to save your favorite perfumes and items for later!
            </p>
            <a href="{{ route('shop') }}" class="btn btn-primary" style="padding: 12px 28px; font-size: 1rem;">
                Start Exploring
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function removeFromWishlist(productId) {
        fetch(`/wishlist/remove/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById(`wishlist-item-${productId}`);
                if (el) el.remove();

                const badge = document.getElementById('navWishlistBadge');
                if (badge) {
                    badge.textContent = data.count;
                    badge.style.display = data.count > 0 ? 'inline-flex' : 'none';
                }

                showToast(data.message || 'Removed from wishlist', 'info');

                if (data.count === 0) {
                    location.reload();
                }
            }
        })
        .catch(() => {
            showToast('Failed to remove item.', 'error');
        });
    }
</script>
@endpush
