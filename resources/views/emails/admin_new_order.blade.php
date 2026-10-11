<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0f172a; color: #e2e8f0; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 24px auto; background-color: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #334155; }
        .header { background: linear-gradient(135deg, #0ea5e9, #6366f1); padding: 28px 24px; text-align: center; }
        .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; }
        .badge { display: inline-block; background: rgba(255, 255, 255, 0.2); color: #fff; padding: 4px 12px; border-radius: 9999px; font-size: 13px; font-weight: 700; margin-top: 8px; }
        .content { padding: 24px; }
        .card { background-color: #0f172a; border-radius: 8px; padding: 16px; margin-bottom: 20px; border: 1px solid #334155; }
        .card h2 { margin: 0 0 12px 0; font-size: 15px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; border-bottom: 1px solid #1e293b; }
        .row:last-child { border-bottom: none; }
        .label { color: #94a3b8; }
        .val { color: #f8fafc; font-weight: 600; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { text-align: left; padding: 8px; font-size: 12px; color: #94a3b8; text-transform: uppercase; border-bottom: 1px solid #334155; }
        td { padding: 10px 8px; font-size: 14px; color: #e2e8f0; border-bottom: 1px solid #1e293b; }
        .text-right { text-align: right; }
        .total-row { font-size: 16px; font-weight: 700; color: #38bdf8; }
        .btn { display: block; text-align: center; background: linear-gradient(135deg, #0ea5e9, #6366f1); color: #ffffff; text-decoration: none; padding: 14px 20px; border-radius: 8px; font-weight: 700; font-size: 15px; margin-top: 24px; }
        .footer { text-align: center; padding: 16px; color: #64748b; font-size: 12px; border-top: 1px solid #334155; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>🚨 New Order Notification</h1>
            <div class="badge">Order #{{ $order->order_number }}</div>
        </div>

        <div class="content">
            <div class="card">
                <h2>Customer Details</h2>
                <div class="row"><span class="label">Name:</span><span class="val">{{ $order->name }}</span></div>
                <div class="row"><span class="label">Phone:</span><span class="val"><a href="tel:{{ $order->phone }}" style="color:#38bdf8; text-decoration:none;">{{ $order->phone }}</a></span></div>
                @if($order->email)
                    <div class="row"><span class="label">Email:</span><span class="val">{{ $order->email }}</span></div>
                @endif
                <div class="row"><span class="label">Address:</span><span class="val">{{ $order->address }}</span></div>
                @if($order->notes)
                    <div class="row"><span class="label">Notes:</span><span class="val" style="color:#fbbf24;">{{ $order->notes }}</span></div>
                @endif
            </div>

            <div class="card">
                <h2>Ordered Products</h2>
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
                    <div class="row total-row"><span class="label" style="color:#38bdf8;">Grand Total:</span><span class="val" style="color:#38bdf8;">৳{{ number_format($order->total, 2) }} (COD)</span></div>
                </div>
            </div>

            <a href="{{ url('/admin/orders/' . $order->id) }}" class="btn">View & Process Order in Admin Dashboard →</a>
        </div>

        <div class="footer">
            Automated notification from {{ config('app.name', 'Nawarika Store') }} &bull; {{ now()->format('M d, Y h:i A') }}
        </div>
    </div>
</body>
</html>
