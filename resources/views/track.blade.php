@extends('layouts.app')
@section('title', 'Track Your Order')

@section('content')
<div style="padding: 40px 0; min-height: 60vh;">
<section class="section" id="track-order-section" style="background: radial-gradient(circle at 50% 50%, rgba(0, 128, 96, 0.12) 0%, transparent 70%); border-radius: var(--radius-lg); padding: 40px 20px;">
    <div class="container">
        <div class="section-header" style="text-align: center; max-width: 680px; margin: 0 auto 32px;">
            <div style="font-size: 2.5rem; margin-bottom: 8px;">📦</div>
            <h2 style="font-size: 2rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px;">
                Track Your Order
            </h2>
            <p style="color: var(--text-secondary); font-size: 1rem;">
                Check real-time delivery status using your <strong>Phone Number</strong> or <strong>Order Number</strong> — no login required.
            </p>
        </div>

        <div class="track-search-box" style="max-width: 640px; margin: 0 auto;">
            <form id="publicTrackForm" onsubmit="handlePublicTrack(event)" style="display: flex; gap: 12px; position: relative;">
                <input type="text" id="trackInput" placeholder="Enter Phone Number (e.g. 017...) or Order # (e.g. ORD-1234)" 
                       required 
                       style="flex: 1; padding: 16px 20px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); color: var(--text-primary); font-size: 1rem; outline: none; transition: border-color 0.2s ease;">
                <button type="submit" id="trackBtn" class="btn btn-primary" style="padding: 16px 32px; font-weight: 700; border-radius: var(--radius-md); flex-shrink: 0;">
                    <span>Track Order</span>
                </button>
            </form>

            {{-- Results Container --}}
            <div id="trackResultContainer" style="margin-top: 28px; display: none;"></div>
        </div>
    </div>
</section>
</div>
@endsection

@push('scripts')
<script>
function handlePublicTrack(e) {
    e.preventDefault();
    const input = document.getElementById('trackInput');
    const btn = document.getElementById('trackBtn');
    const container = document.getElementById('trackResultContainer');
    const query = input.value.trim();

    if (!query) return;

    btn.disabled = true;
    btn.innerHTML = 'Searching...';
    container.style.display = 'block';
    container.innerHTML = '<div style="text-align: center; padding: 24px; color: var(--text-muted);">Fetching order details...</div>';

    fetch(`{{ route('order.track') }}?query=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                container.innerHTML = `<div style="padding: 20px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--radius-md); color: #ef4444; text-align: center; font-weight: 600;">${data.message}</div>`;
                return;
            }

            let html = '';
            data.orders.forEach(order => {
                const isCancelled = order.status === 'cancelled';
                const step = order.step;

                html += `
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 24px; margin-bottom: 20px; backdrop-filter: blur(12px);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                        <div>
                            <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Order Number</span>
                            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--accent-primary); margin-top: 2px;">${order.order_number}</h3>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge ${order.status_badge}" style="font-size: 0.85rem; padding: 6px 14px; text-transform: uppercase;">${order.status_label}</span>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Placed on ${order.date}</div>
                        </div>
                    </div>

                    ${!isCancelled ? `
                    <div style="margin: 28px 0 24px;">
                        <div style="display: flex; justify-content: space-between; position: relative;">
                            <div style="position: absolute; top: 14px; left: 10%; right: 10%; height: 3px; background: var(--border-color); z-index: 1;">
                                <div style="height: 100%; background: var(--accent-primary); width: ${step >= 4 ? '100%' : step === 3 ? '66%' : step === 2 ? '33%' : '0%'}; transition: width 0.4s ease;"></div>
                            </div>

                            <div style="text-align: center; position: relative; z-index: 2; flex: 1;">
                                <div style="width: 30px; height: 30px; border-radius: 50%; background: ${step >= 1 ? 'var(--accent-primary)' : 'var(--bg-tertiary)'}; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; font-weight: bold; font-size: 0.8rem; border: 2px solid var(--bg-card); box-shadow: ${step >= 1 ? '0 0 12px var(--accent-primary)' : 'none'};">1</div>
                                <span style="font-size: 0.78rem; font-weight: 600; color: ${step >= 1 ? 'var(--text-primary)' : 'var(--text-muted)'};">Order Placed</span>
                            </div>

                            <div style="text-align: center; position: relative; z-index: 2; flex: 1;">
                                <div style="width: 30px; height: 30px; border-radius: 50%; background: ${step >= 2 ? 'var(--accent-primary)' : 'var(--bg-tertiary)'}; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; font-weight: bold; font-size: 0.8rem; border: 2px solid var(--bg-card); box-shadow: ${step >= 2 ? '0 0 12px var(--accent-primary)' : 'none'};">2</div>
                                <span style="font-size: 0.78rem; font-weight: 600; color: ${step >= 2 ? 'var(--text-primary)' : 'var(--text-muted)'};">Processing</span>
                            </div>

                            <div style="text-align: center; position: relative; z-index: 2; flex: 1;">
                                <div style="width: 30px; height: 30px; border-radius: 50%; background: ${step >= 3 ? 'var(--accent-primary)' : 'var(--bg-tertiary)'}; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; font-weight: bold; font-size: 0.8rem; border: 2px solid var(--bg-card); box-shadow: ${step >= 3 ? '0 0 12px var(--accent-primary)' : 'none'};">3</div>
                                <span style="font-size: 0.78rem; font-weight: 600; color: ${step >= 3 ? 'var(--text-primary)' : 'var(--text-muted)'};">Shipped</span>
                            </div>

                            <div style="text-align: center; position: relative; z-index: 2; flex: 1;">
                                <div style="width: 30px; height: 30px; border-radius: 50%; background: ${step >= 4 ? 'var(--accent-primary)' : 'var(--bg-tertiary)'}; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; font-weight: bold; font-size: 0.8rem; border: 2px solid var(--bg-card); box-shadow: ${step >= 4 ? '0 0 12px var(--accent-primary)' : 'none'};">4</div>
                                <span style="font-size: 0.78rem; font-weight: 600; color: ${step >= 4 ? 'var(--text-primary)' : 'var(--text-muted)'};">Delivered</span>
                            </div>
                        </div>
                    </div>
                    ` : `
                    <div style="padding: 12px; background: rgba(239, 68, 68, 0.1); border-radius: var(--radius-md); color: #ef4444; font-size: 0.9rem; text-align: center; margin-bottom: 16px;">
                        This order was cancelled.
                    </div>
                    `}

                    <div style="background: var(--bg-glass); border-radius: var(--radius-md); padding: 16px; margin-top: 16px;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 10px;">Ordered Items:</div>
                        ${order.items.map(item => `
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 0.9rem;">
                                <span>${item.name} <strong style="color: var(--accent-primary);">× ${item.qty}</strong></span>
                                <span style="font-weight: 600;">${item.total}</span>
                            </div>
                        `).join('')}
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--border-color); font-weight: 800; font-size: 1rem;">
                            <span>Total Amount (${order.payment_method}):</span>
                            <span style="color: var(--accent-primary);">${order.total}</span>
                        </div>
                    </div>
                </div>
                `;
            });

            container.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<div style="padding: 20px; color: #ef4444; text-align: center;">Network error while searching. Please try again.</div>';
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = 'Track Order';
        });
}
</script>
@endpush
