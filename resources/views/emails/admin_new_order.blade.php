<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নতুন অর্ডার নোটিফিকেশন - {{ $brand['siteName'] }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Hind Siliguri', 'SolaimanLipi', Arial, sans-serif; background-color: #0b0f19; color: #e2e8f0; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 20px auto; background-color: #131d2e; border-radius: 14px; overflow: hidden; border: 1px solid #2d3748; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .header { text-align: center; padding: 30px 20px 22px 20px; background: linear-gradient(180deg, #1e293b, #0f172a); border-bottom: 1px solid #334155; }
        .site-name { font-size: 24px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; text-transform: uppercase; }
        .site-tagline { font-size: 11px; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase; margin-top: 4px; }
        .content { padding: 28px 24px; }

        .alert-banner { background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(245, 158, 11, 0.15)); border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 12px; padding: 18px 20px; text-align: center; margin-bottom: 24px; }
        .alert-badge { display: inline-block; background: #ef4444; color: #ffffff; padding: 5px 14px; border-radius: 9999px; font-size: 12px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 8px; text-transform: uppercase; }
        .alert-title { margin: 0 0 4px 0; color: #ffffff; font-size: 20px; font-weight: 800; }
        .alert-sub { margin: 0; color: #cbd5e1; font-size: 13px; }

        .card { background-color: #0f172a; border-radius: 10px; padding: 18px 20px; margin-bottom: 20px; border: 1px solid #334155; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #1e293b; }
        .card-title { margin: 0; font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }

        .info-row { display: flex; justify-content: space-between; padding: 7px 0; font-size: 14px; border-bottom: 1px solid #1e293b; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #94a3b8; }
        .info-value { color: #f8fafc; font-weight: 600; text-align: right; }

        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th { text-align: left; padding: 10px 8px; font-size: 12px; color: #94a3b8; text-transform: uppercase; border-bottom: 1px solid #334155; }
        td { padding: 12px 8px; font-size: 14px; color: #e2e8f0; border-bottom: 1px solid #1e293b; }
        .text-right { text-align: right; }

        .btn-admin { display: block; text-align: center; background: linear-gradient(135deg, #0ea5e9, #6366f1); color: #ffffff !important; text-decoration: none; padding: 15px 24px; border-radius: 10px; font-weight: 800; font-size: 15px; margin: 24px 0 16px 0; box-shadow: 0 4px 15px rgba(14, 165, 233, 0.35); }
        .footer { text-align: center; padding: 20px; color: #64748b; font-size: 12px; border-top: 1px solid #334155; background: #0f172a; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        {{-- BRAND HEADER (Site Name & Logo from General Settings) --}}
        <div class="header">
            @if(!empty($brand['siteLogoUrl']))
                <img src="{{ $brand['siteLogoUrl'] }}" alt="{{ $brand['siteName'] }}" style="max-height: 52px; max-width: 220px; object-fit: contain; margin-bottom: 10px;">
            @endif
            <div class="site-name">{{ $brand['siteName'] }}</div>
            <div class="site-tagline">অ্যাডমিন নোটিফিকেশন &bull; বাংলাদেশ</div>
        </div>

        <div class="content">
            {{-- ALERT BANNER --}}
            <div class="alert-banner">
                <span class="alert-badge">নতুন অর্ডার অ্যালার্ট 🚨</span>
                <h1 class="alert-title">অর্ডার নম্বর: #{{ $order->order_number }}</h1>
                <p class="alert-sub">একজন গ্রাহক মাত্র ওয়েবসাইটে নতুন অর্ডার দিয়েছেন। অবিলম্বে চেক করে নিশ্চিত করুন।</p>
                <div style="margin-top: 8px; font-size: 12px; color: #94a3b8;">
                    সময়: <strong>{{ $order->created_at->format('d M, Y - h:i A') }} (বাংলাদেশ সময়)</strong>
                </div>
            </div>

            {{-- CUSTOMER DETAILS --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">গ্রাহকের তথ্য ও ডেলিভারি ঠিকানা</span>
                    <span style="font-size: 12px; color: #fbbf24; font-weight: 700;">ক্যাশ অন ডেলিভারি (COD)</span>
                </div>
                <div class="info-row">
                    <span class="info-label">গ্রাহকের নাম:</span>
                    <span class="info-value" style="font-size: 15px;">{{ $order->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">মোবাইল নম্বর:</span>
                    <span class="info-value">
                        <a href="tel:{{ $order->phone }}" style="color: #38bdf8; text-decoration: none; font-size: 15px;">📞 {{ $order->phone }}</a>
                    </span>
                </div>
                @if($order->email)
                <div class="info-row">
                    <span class="info-label">ইমেইল:</span>
                    <span class="info-value">{{ $order->email }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">ডেলিভারি ঠিকানা:</span>
                    <span class="info-value" style="max-width: 280px;">{{ $order->address }}</span>
                </div>
                @if($order->city)
                <div class="info-row">
                    <span class="info-label">শহর / এরিয়া:</span>
                    <span class="info-value">{{ $order->city }}</span>
                </div>
                @endif
                @if($order->notes)
                <div class="info-row">
                    <span class="info-label">অর্ডার নোট:</span>
                    <span class="info-value" style="color: #fbbf24;">{{ $order->notes }}</span>
                </div>
                @endif
            </div>

            {{-- ORDER ITEMS TABLE --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">অর্ডারকৃত পণ্য তালিকা ({{ $order->items->count() }} টি আইটেম)</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>পণ্য</th>
                            <th class="text-right">পরিমাণ</th>
                            <th class="text-right">মূল্য</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #f8fafc;">{{ $item->product_name }}</div>
                                    <div style="font-size: 12px; color: #94a3b8;">৳{{ number_format($item->price, 0) }}</div>
                                </td>
                                <td class="text-right" style="font-weight: 700;">{{ $item->quantity }}</td>
                                <td class="text-right" style="font-weight: 700; color: #f8fafc;">৳{{ number_format($item->price * $item->quantity, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid #334155;">
                    <div class="info-row">
                        <span class="info-label">সাবটোটাল:</span>
                        <span class="info-value">৳{{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ডেলিভারি চার্জ:</span>
                        <span class="info-value">
                            {{ $order->shipping == 0 ? 'ফ্রি ডেলিভারি' : '৳' . number_format($order->shipping, 0) }}
                        </span>
                    </div>
                    <div class="info-row" style="font-size: 17px; font-weight: 800; color: #38bdf8; padding-top: 10px; margin-top: 6px; border-top: 1px solid #1e293b;">
                        <span style="color: #38bdf8;">মোট আদায়যোগ্য (Grand Total COD):</span>
                        <span style="color: #38bdf8;">৳{{ number_format($order->total, 0) }}</span>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTON --}}
            <a href="{{ url('/admin/orders/' . $order->id) }}" class="btn-admin">
                🔗 অ্যাডমিন প্যানেলে অর্ডার দেখুন ও প্রসেস করুন →
            </a>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            স্বয়ংক্রিয় অ্যাডমিন অ্যালার্ট &bull; <strong>{{ $brand['siteName'] }}</strong><br>
            প্রেরণের সময়: {{ now()->format('d M, Y - h:i:s A') }}
        </div>
    </div>
</body>
</html>
