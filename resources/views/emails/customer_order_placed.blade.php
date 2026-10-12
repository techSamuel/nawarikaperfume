<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অর্ডার কনফার্মেশন - {{ $brand['siteName'] }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Hind Siliguri', 'SolaimanLipi', Arial, sans-serif; background-color: #0b0f19; color: #e2e8f0; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 20px auto; background-color: #131d2e; border-radius: 14px; overflow: hidden; border: 1px solid #2d3748; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .header { text-align: center; padding: 30px 20px 22px 20px; background: linear-gradient(180deg, #1e293b, #0f172a); border-bottom: 1px solid #334155; }
        .site-name { font-size: 24px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; text-transform: uppercase; }
        .site-tagline { font-size: 11px; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase; margin-top: 4px; }
        .content { padding: 28px 24px; }
        .hero-banner { background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(6, 182, 212, 0.15)); border: 1px solid rgba(16, 185, 129, 0.35); border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 24px; }
        .order-badge { display: inline-block; background: #10b981; color: #ffffff; padding: 6px 16px; border-radius: 9999px; font-size: 13px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 10px; }
        .hero-title { margin: 0 0 6px 0; color: #ffffff; font-size: 20px; font-weight: 800; }
        .hero-desc { margin: 0; color: #cbd5e1; font-size: 14px; line-height: 1.5; }
        
        .notice-box { background: rgba(59, 130, 246, 0.08); border-left: 4px solid #3b82f6; border-radius: 0 8px 8px 0; padding: 14px 16px; margin-bottom: 22px; font-size: 13px; color: #cbd5e1; line-height: 1.6; }
        .cod-badge { display: inline-block; background: rgba(34, 197, 94, 0.18); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.35); padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 13px; }

        .card { background-color: #0f172a; border-radius: 10px; padding: 18px 20px; margin-bottom: 20px; border: 1px solid #334155; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #1e293b; }
        .card-title { margin: 0; font-size: 14px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
        
        .info-row { display: flex; justify-content: space-between; padding: 7px 0; font-size: 14px; border-bottom: 1px solid #1e293b; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #94a3b8; }
        .info-value { color: #f8fafc; font-weight: 600; text-align: right; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th { text-align: left; padding: 10px 8px; font-size: 12px; color: #94a3b8; text-transform: uppercase; border-bottom: 1px solid #334155; }
        td { padding: 12px 8px; font-size: 14px; color: #e2e8f0; border-bottom: 1px solid #1e293b; }
        .text-right { text-align: right; }
        
        .btn-track { display: block; text-align: center; background: linear-gradient(135deg, #10b981, #06b6d4); color: #ffffff !important; text-decoration: none; padding: 15px 24px; border-radius: 10px; font-weight: 800; font-size: 16px; margin: 24px 0 16px 0; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35); }
        .action-grid { display: flex; gap: 12px; margin-bottom: 24px; }
        .btn-secondary { flex: 1; text-align: center; background: #1e293b; color: #cbd5e1 !important; border: 1px solid #334155; padding: 11px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; }

        .timeline-box { background: #0f172a; border-radius: 10px; padding: 16px; margin-bottom: 22px; border: 1px solid #334155; }
        .timeline-title { font-size: 13px; font-weight: 700; color: #38bdf8; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .timeline-step { font-size: 13px; color: #cbd5e1; margin-bottom: 6px; display: flex; align-items: flex-start; gap: 8px; }

        .footer { text-align: center; padding: 24px 20px; color: #64748b; font-size: 12px; border-top: 1px solid #334155; background: #0f172a; line-height: 1.6; }
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
            <div class="site-tagline">অফিসিয়াল অনলাইন স্টোর &bull; বাংলাদেশ</div>
        </div>

        <div class="content">
            {{-- HERO BANNER --}}
            <div class="hero-banner">
                <span class="order-badge">অর্ডার কনফার্মড ✓</span>
                <h1 class="hero-title">ধন্যবাদ {{ $order->name }}!</h1>
                <p class="hero-desc">আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে। আমরা দ্রুততম সময়ে আপনার পার্সেল পৌঁছে দেব।</p>
                <div style="margin-top: 10px; font-size: 13px; color: #94a3b8;">
                    অর্ডার নম্বর: <strong style="color: #38bdf8; font-family: monospace;">#{{ $order->order_number }}</strong>
                    &bull; তারিখ: <strong>{{ $order->created_at->format('d M, Y - h:i A') }}</strong>
                </div>
            </div>

            {{-- BD CASH ON DELIVERY & DELIVERY TIMELINE NOTICE --}}
            <div class="notice-box">
                <div style="font-weight: 700; color: #38bdf8; margin-bottom: 4px; font-size: 14px;">
                    💵 ক্যাশ অন ডেলিভারি (Cash on Delivery)
                </div>
                <div style="margin-bottom: 6px;">
                    পার্সেলটি আপনার ঠিকানায় পৌঁছানোর পর ডেলিভারি ম্যানের সামনে চেক করে মূল্য পরিশোধ করতে পারবেন।
                </div>
                <div style="color: #94a3b8; font-size: 12px;">
                    🚚 <strong>ডেলিভারি সময়সীমা:</strong> ঢাকা সিটির ভিতরে ২৪-৪৮ ঘণ্টা | সারা বাংলাদেশে ২-৪ কার্যদিবস।
                </div>
            </div>

            {{-- DELIVERY DETAILS CARD --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">ডেলিভারি ঠিকানা ও গ্রাহকের তথ্য</span>
                    <span class="cod-badge">ক্যাশ অন ডেলিভারি</span>
                </div>
                <div class="info-row">
                    <span class="info-label">প্রাপকের নাম:</span>
                    <span class="info-value">{{ $order->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">মোবাইল নম্বর:</span>
                    <span class="info-value">
                        <a href="tel:{{ $order->phone }}" style="color: #38bdf8; text-decoration: none;">{{ $order->phone }}</a>
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
                    <span class="info-label">বিশেষ নির্দেশনা:</span>
                    <span class="info-value" style="color: #fbbf24;">{{ $order->notes }}</span>
                </div>
                @endif
            </div>

            {{-- ORDER ITEMS TABLE --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">অর্ডারকৃত পণ্যের তালিকা ({{ $order->items->count() }} টি আইটেম)</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>পণ্যের বিবরণ</th>
                            <th class="text-right">পরিমাণ</th>
                            <th class="text-right">মূল্য</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #f8fafc;">{{ $item->product_name }}</div>
                                    <div style="font-size: 12px; color: #94a3b8;">একক মূল্য: ৳{{ number_format($item->price, 0) }}</div>
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
                    <div class="info-row" style="font-size: 17px; font-weight: 800; color: #34d399; padding-top: 10px; margin-top: 6px; border-top: 1px solid #1e293b;">
                        <span style="color: #34d399;">মোট প্রদেয় মূল্য (Total COD):</span>
                        <span style="color: #34d399;">৳{{ number_format($order->total, 0) }}</span>
                    </div>
                </div>
            </div>

            {{-- TIMELINE & COURIER ADVICE FOR BD CUSTOMERS --}}
            <div class="timeline-box">
                <div class="timeline-title">📦 কুরিয়ার ডেলিভারি সংক্রান্ত পরামর্শ</div>
                <div class="timeline-step">
                    <span>1️⃣</span>
                    <span>আমাদের টিম আপনার অর্ডারটি সুন্দর ও সুরক্ষিতভাবে প্যাকিং করবে।</span>
                </div>
                <div class="timeline-step">
                    <span>2️⃣</span>
                    <span>পার্সেল কুরিয়ারে হস্তান্তর করার সাথে সাথে আপনাকে ট্র্যাকিং মেসেজ পাঠানো হবে।</span>
                </div>
                <div class="timeline-step">
                    <span>3️⃣</span>
                    <span><strong>কুরিয়ার রাইডার ডেলিভারির পূর্বে আপনাকে কল করবে।</strong> অনুগ্রহ করে কলটি রিসিভ করুন এবং সচল রাখুন।</span>
                </div>
            </div>

            {{-- CALL TO ACTIONS --}}
            <a href="{{ route('order.confirmation', $order->order_number) }}" class="btn-track">
                🚚 লাইভ অর্ডার ট্র্যাকিং দেখুন →
            </a>

            <div class="action-grid">
                <a href="{{ $brand['whatsappLink'] }}" class="btn-secondary">
                    💬 হোয়াটসঅ্যাপে মেসেজ দিন
                </a>
                <a href="tel:{{ $brand['contactPhone'] }}" class="btn-secondary">
                    📞 হটলাইন: {{ $brand['contactPhone'] }}
                </a>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <strong>{{ $brand['siteName'] }}</strong> &bull; প্রিমিয়াম লাইফস্টাইল ও পারফিউম কালেকশন<br>
            হটলাইন: <a href="tel:{{ $brand['contactPhone'] }}" style="color: #38bdf8; text-decoration: none;">{{ $brand['contactPhone'] }}</a>
            &bull; ইমেইল: <a href="mailto:{{ $brand['contactEmail'] }}" style="color: #38bdf8; text-decoration: none;">{{ $brand['contactEmail'] }}</a><br>
            {{ $brand['contactAddress'] }}<br>
            <div style="margin-top: 8px; font-size: 11px; color: #475569;">
                &copy; {{ date('Y') }} {{ $brand['siteName'] }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
