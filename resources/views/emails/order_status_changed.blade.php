<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0b0f19; color: #e2e8f0; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 24px auto; background-color: #161e2e; border-radius: 12px; overflow: hidden; border: 1px solid #2d3748; }
        .header { background: linear-gradient(135deg, #10b981, #06b6d4); padding: 32px 24px; text-align: center; }
        .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 800; }
        .badge { display: inline-block; background: rgba(0, 0, 0, 0.25); color: #fff; padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: 700; margin-top: 10px; }
        .content { padding: 28px 24px; }
        .status-box { background-color: #0f172a; border-radius: 10px; padding: 20px; margin-bottom: 24px; text-align: center; border: 1px solid #334155; }
        .status-title { font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .status-pill { display: inline-block; padding: 8px 20px; border-radius: 8px; font-weight: 800; font-size: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-pending { background: rgba(234, 179, 8, 0.2); color: #fbbf24; border: 1px solid rgba(234, 179, 8, 0.4); }
        .status-processing { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4); }
        .status-confirm { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
        .status-shipped { background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.4); }
        .status-delivered { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.4); }
        .status-cancelled { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }
        .message-p { font-size: 15px; color: #cbd5e1; line-height: 1.6; margin: 16px 0; }
        .card { background-color: #0f172a; border-radius: 8px; padding: 18px; margin-bottom: 20px; border: 1px solid #334155; }
        .card h2 { margin: 0 0 12px 0; font-size: 14px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; border-bottom: 1px solid #1e293b; }
        .row:last-child { border-bottom: none; }
        .label { color: #94a3b8; }
        .val { color: #f8fafc; font-weight: 600; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { text-align: left; padding: 8px; font-size: 12px; color: #94a3b8; text-transform: uppercase; border-bottom: 1px solid #334155; }
        td { padding: 10px 8px; font-size: 14px; color: #e2e8f0; border-bottom: 1px solid #1e293b; }
        .text-right { text-align: right; }
        .btn { display: block; text-align: center; background: linear-gradient(135deg, #10b981, #06b6d4); color: #ffffff; text-decoration: none; padding: 14px 20px; border-radius: 8px; font-weight: 700; font-size: 15px; margin-top: 24px; }
        .footer { text-align: center; padding: 20px; color: #64748b; font-size: 12px; border-top: 1px solid #334155; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ config('app.name', 'Nawarika Perfume') }}</h1>
            <div class="badge">Order #{{ $order->order_number }}</div>
        </div>

        <div class="content">
            <p class="message-p">
                Hello <strong>{{ $order->name }}</strong>,<br>
                The details or status of your order have just been updated by our team.
            </p>

            @if(!empty($changes))
            <div class="card" style="border-left: 4px solid #10b981; background: #0f172a; margin-bottom: 20px;">
                <h2 style="color: #34d399; margin: 0 0 10px 0; font-size: 14px;">📝 Details Updated On Your Order</h2>
                <div style="font-size: 13px; color: #94a3b8; margin-bottom: 12px;">The following details of your order have been modified:</div>
                @foreach($changes as $label => $change)
                    <div class="row">
                        <span class="label" style="font-weight: 600;">{{ $label }}:</span>
                        <span class="val">
                            @if(is_array($change) && isset($change['from']) && isset($change['to']))
                                <span style="color: #94a3b8; text-decoration: line-through; margin-right: 6px;">{{ $change['from'] }}</span>
                                <span style="color: #34d399; font-weight: 700;">→ {{ $change['to'] }}</span>
                            @else
                                <span style="color: #34d399; font-weight: 700;">{{ is_array($change) ? json_encode($change) : $change }}</span>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
            @endif

            <div class="status-box">
                <div class="status-title">Current Order Status</div>
                <div class="status-pill status-{{ $newStatus }}">
                    {{ ucfirst($newStatus) }}
                </div>
                <div style="font-size: 13px; color: #94a3b8; margin-top: 10px;">
                    @if($newStatus === 'confirm')
                        ✓ Your order has been confirmed and is now being packaged.
                    @elseif($newStatus === 'processing')
                        ⚙️ Your order is currently being processed at our warehouse.
                    @elseif($newStatus === 'shipped')
                        🚚 Your package is on the way with our courier partner!
                    @elseif($newStatus === 'delivered')
                        🎉 Your package has been delivered. Thank you for shopping with us!
                    @elseif($newStatus === 'cancelled')
                        ✕ Your order has been marked as cancelled. Please contact us if you have any questions.
                    @else
                        ⏳ Your order is currently pending review.
                    @endif
                </div>
            </div>

            <div class="card">
                <h2>Delivery Details</h2>
                <div class="row"><span class="label">Recipient:</span><span class="val">{{ $order->name }}</span></div>
                <div class="row"><span class="label">Phone:</span><span class="val">{{ $order->phone }}</span></div>
                <div class="row"><span class="label">Address:</span><span class="val">{{ $order->address }}</span></div>
                <div class="row"><span class="label">Payment Method:</span><span class="val" style="color:#34d399;">Cash on Delivery</span></div>
            </div>

            <div class="card">
                <h2>Items in this Order</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-right">{{ $item->quantity }}</td>
                                <td class="text-right">৳{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #334155;">
                    <div class="row"><span class="label">Subtotal:</span><span class="val">৳{{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="row"><span class="label">Shipping:</span><span class="val">{{ $order->shipping == 0 ? 'Free' : '৳' . number_format($order->shipping, 2) }}</span></div>
                    <div class="row" style="font-size: 16px; font-weight: 700; color: #34d399;"><span class="label" style="color:#34d399;">Total:</span><span class="val" style="color:#34d399;">৳{{ number_format($order->total, 2) }}</span></div>
                </div>
            </div>

            <a href="{{ route('order.confirmation', $order->order_number) }}" class="btn">View Live Order Tracking →</a>
        </div>

        <div class="footer">
            If you have questions about your order, reply directly to this email or call us.<br>
            &copy; {{ date('Y') }} {{ config('app.name', 'Nawarika Perfume') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
