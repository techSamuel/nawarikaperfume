<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অর্ডার আপডেট - {{ $brand['siteName'] }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Hind Siliguri', 'SolaimanLipi', Arial, sans-serif; background-color: #0b0f19; color: #e2e8f0; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 20px auto; background-color: #131d2e; border-radius: 14px; overflow: hidden; border: 1px solid #2d3748; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .header { text-align: center; padding: 30px 20px 22px 20px; background: linear-gradient(180deg, #1e293b, #0f172a); border-bottom: 1px solid #334155; }
        .site-name { font-size: 24px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; text-transform: uppercase; }
        .site-tagline { font-size: 11px; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase; margin-top: 4px; }
        .content { padding: 28px 24px; }
        
        .status-box { background-color: #0f172a; border-radius: 12px; padding: 24px 20px; margin-bottom: 24px; text-align: center; border: 1px solid #334155; }
        .status-badge-title { font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; font-weight: 700; }
        .status-pill { display: inline-block; padding: 10px 24px; border-radius: 9999px; font-weight: 800; font-size: 16px; letter-spacing: 0.5px; }
        .status-pending { background: rgba(234, 179, 8, 0.15); color: #fbbf24; border: 1px solid rgba(234, 179, 8, 0.4); }
        .status-processing { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4); }
        .status-confirm { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
        .status-shipped { background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.4); }
        .status-delivered { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.4); }
        .status-cancelled { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }
        .status-desc { font-size: 14px; color: #cbd5e1; margin-top: 12px; line-height: 1.5; }

        .changes-card { background: #0f172a; border-left: 4px solid #10b981; border-radius: 0 10px 10px 0; padding: 18px 20px; margin-bottom: 24px; border-top: 1px solid #334155; border-right: 1px solid #334155; border-bottom: 1px solid #334155; }
        .changes-title { color: #34d399; margin: 0 0 6px 0; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }

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
        {{-- BRAND HEADER --}}
        <div class="header">
            @if(!empty($brand['siteLogoUrl']))
                <img src="{{ $brand['siteLogoUrl'] }}" alt="{{ $brand['siteName'] }}" style="max-height: 52px; max-width: 220px; object-fit: contain; margin-bottom: 10px;">
            @endif
            <div class="site-name">{{ $brand['siteName'] }}</div>
            <div class="site-tagline">অফিসিয়াল অনলাইন স্টোর &bull; বাংলাদেশ</div>
        </div>

        <div class="content">
            <p style="font-size: 15px; color: #cbd5e1; line-height: 1.6; margin: 0 0 20px 0;">
                আসসালামু আলাইকুম <strong>{{ $order->name }}</strong>,<br>
                আপনার অর্ডারটির (অর্ডার নং: <strong style="color: #38bdf8;">#{{ $order->order_number }}</strong>) বর্তমান অবস্থা ও বিবরণ নিচে আপডেট করা হয়েছে।
            </p>

            {{-- CHANGED DETAILS BOX IF EDITED BY ADMIN --}}
            @if(!empty($changes))
            <div class="changes-card">
                <div class="changes-title">📝 অর্ডারের পরিবর্তিত তথ্যের বিবরণ</div>
                <div style="font-size: 12px; color: #94a3b8; margin-bottom: 10px;">আপনার অর্ডারে নিম্নের তথ্যগুলো সফলভাবে আপডেট করা হয়েছে:</div>
                @foreach($changes as $label => $change)
                    <div class="info-row">
                        <span class="info-label" style="font-weight: 700; color: #e2e8f0;">{{ $label }}:</span>
                        <span class="info-value">
                            @if(is_array($change) && isset($change['from']) && isset($change['to']))
                                <span style="color: #94a3b8; text-decoration: line-through; margin-right: 6px;">{{ $change['from'] }}</span>
                                <span style="color: #34d399; font-weight: 800;">→ {{ $change['to'] }}</span>
                            @else
                                <span style="color: #34d399; font-weight: 800;">{{ is_array($change) ? json_encode($change) : $change }}</span>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- STATUS BOX --}}
            <div class="status-box">
                <div class="status-badge-title">বর্তমান অর্ডারের অবস্থা (Current Status)</div>
                <div class="status-pill status-{{ $newStatus }}">
                    @if($newStatus === 'confirm')
                        ✓ কনফার্মড (Confirmed)
                    @elseif($newStatus === 'processing')
                        ⚙️ প্রসেসিং ও প্যাকিং চলছে (Processing)
                    @elseif($newStatus === 'shipped')
                        🚚 কুরিয়ারে হস্তান্তর (Shipped)
                    @elseif($newStatus === 'delivered')
                        🎉 ডেলিভারি সম্পন্ন (Delivered)
                    @elseif($newStatus === 'cancelled')
                        ✕ অর্ডার বাতিল (Cancelled)
                    @else
                        ⏳ পর্যালোচনায় রয়েছে (Pending)
                    @endif
                </div>
                <div class="status-desc">
                    @if($newStatus === 'confirm')
                        ✓ আপনার অর্ডারটি সফলভাবে কনফার্ম করা হয়েছে এবং ওয়্যারহাউসে প্যাকিং শুরু হয়েছে।
                    @elseif($newStatus === 'processing')
                        ⚙️ আপনার পণ্যটি যত্নসহকারে প্যাকেজিং ও কোয়ালিটি চেকিং সম্পন্ন হচ্ছে।
                    @elseif($newStatus === 'shipped')
                        🚚 আপনার পার্সেলটি কুরিয়ারে হ্যান্ডওভার করা হয়েছে। কুরিয়ার রাইডার ডেলিভারির পূর্বে আপনাকে ফোন করবে।
                    @elseif($newStatus === 'delivered')
                        🎉 আপনার পার্সেলটি সফলভাবে ডেলিভারি সম্পন্ন হয়েছে। {{ $brand['siteName'] }}-এর সাথে কেনাকাটা করার জন্য ধন্যবাদ!
                    @elseif($newStatus === 'cancelled')
                        ✕ আপনার অর্ডারটি বাতিল করা হয়েছে। যেকোনো সহায়তার জন্য অনুগ্রহ করে আমাদের হেল্পলাইনে কল করুন।
                    @else
                        ⏳ আপনার অর্ডারটি আমাদের টিম কর্তৃক রিভিউ করা হচ্ছে।
                    @endif
                </div>
            </div>

            {{-- DELIVERY DETAILS CARD --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">ডেলিভারি ঠিকানা ও যোগাযোগের তথ্য</span>
                    <span style="font-size: 12px; color: #4ade80; font-weight: 700;">ক্যাশ অন ডেলিভারি</span>
                </div>
                <div class="info-row">
                    <span class="info-label">গ্রাহকের নাম:</span>
                    <span class="info-value">{{ $order->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">মোবাইল নম্বর:</span>
                    <span class="info-value">
                        <a href="tel:{{ $order->phone }}" style="color: #38bdf8; text-decoration: none;">{{ $order->phone }}</a>
                    </span>
                </div>
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
                <div class="info-row">
                    <span class="info-label">পেমেন্ট মেথড:</span>
                    <span class="info-value" style="color: #34d399;">ক্যাশ অন ডেলিভারি (পণ্য দেখে মূল্য পরিশোধ)</span>
                </div>
            </div>

            {{-- ORDER ITEMS TABLE --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">অর্ডারের আইটেম ও মূল্যের হিসাব</span>
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
                    <div class="info-row" style="font-size: 17px; font-weight: 800; color: #34d399; padding-top: 10px; margin-top: 6px; border-top: 1px solid #1e293b;">
                        <span style="color: #34d399;">সর্বমোট প্রদেয় (Total Payable):</span>
                        <span style="color: #34d399;">৳{{ number_format($order->total, 0) }}</span>
                    </div>
                </div>
            </div>

            {{-- TIMELINE & COURIER ADVICE FOR BD CUSTOMERS --}}
            <div class="timeline-box">
                <div class="timeline-title">💡 প্রয়োজনীয় ডেলিভারি তথ্য</div>
                <div class="timeline-step">
                    <span>📞</span>
                    <span><strong>কুরিয়ার রাইডার ডেলিভারির পূর্বে আপনাকে ফোন করবে।</strong> অনুগ্রহ করে কলটি রিসিভ করুন।</span>
                </div>
                <div class="timeline-step">
                    <span>📦</span>
                    <span>ডেলিভারি ম্যানের সামনে পার্সেলটি চেক করে ক্যাশ পরিশোধ করুন।</span>
                </div>
            </div>

            {{-- CALL TO ACTIONS --}}
            <a href="{{ route('order.confirmation', $order->order_number) }}" class="btn-track">
                🚚 লাইভ অর্ডার ট্র্যাকিং দেখুন →
            </a>

            <div class="action-grid">
                <a href="{{ $brand['whatsappLink'] }}" class="btn-secondary">
                    💬 হোয়াটসঅ্যাপ সহায়তা
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
