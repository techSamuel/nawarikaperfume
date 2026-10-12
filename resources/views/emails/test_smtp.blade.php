<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMTP টেস্ট সফল - {{ $brand['siteName'] ?? config('app.name', 'Nawarika') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Hind Siliguri', 'SolaimanLipi', Arial, sans-serif; background-color: #0b0f19; color: #e2e8f0; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 540px; margin: 24px auto; background-color: #131d2e; border-radius: 14px; overflow: hidden; border: 1px solid #2d3748; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .header { text-align: center; padding: 28px 20px 20px 20px; background: linear-gradient(180deg, #1e293b, #0f172a); border-bottom: 1px solid #334155; }
        .site-name { font-size: 24px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; text-transform: uppercase; }
        .site-tagline { font-size: 11px; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase; margin-top: 4px; }
        .content { padding: 26px 24px; }
        .hero { text-align: center; padding: 20px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(6, 182, 212, 0.12)); border: 1px solid rgba(16, 185, 129, 0.35); border-radius: 12px; margin-bottom: 22px; }
        .hero-badge { display: inline-block; background: #10b981; color: #ffffff; padding: 5px 14px; border-radius: 9999px; font-size: 12px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 10px; }
        .hero h2 { margin: 0 0 8px 0; color: #ffffff; font-size: 20px; font-weight: 800; }
        .hero p { margin: 0; color: #cbd5e1; font-size: 14px; line-height: 1.5; }
        .info-card { background: #0f172a; border-radius: 10px; padding: 18px 20px; border: 1px solid #334155; margin-bottom: 20px; }
        .info-card-title { font-size: 13px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #1e293b; }
        .info-row { display: flex; justify-content: space-between; padding: 7px 0; font-size: 13px; border-bottom: 1px solid #1e293b; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #94a3b8; }
        .info-val { color: #f8fafc; font-weight: 600; text-align: right; }
        .note-box { background: rgba(59, 130, 246, 0.08); border-left: 4px solid #3b82f6; border-radius: 0 8px 8px 0; padding: 12px 16px; margin-bottom: 20px; font-size: 13px; color: #cbd5e1; line-height: 1.6; }
        .footer { text-align: center; padding: 18px 20px; color: #64748b; font-size: 12px; border-top: 1px solid #334155; background: #0f172a; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        {{-- BRAND HEADER --}}
        <div class="header">
            @if(!empty($brand['siteLogoUrl']))
                <img src="{{ $brand['siteLogoUrl'] }}" alt="{{ $brand['siteName'] }}" style="max-height: 50px; max-width: 220px; object-fit: contain; margin-bottom: 10px;">
            @endif
            <div class="site-name">{{ $brand['siteName'] }}</div>
            <div class="site-tagline">অফিসিয়াল সিস্টেম টেস্ট ডায়াগনস্টিক</div>
        </div>

        <div class="content">
            <div class="hero">
                <div class="hero-badge">✓ SMTP সক্রিয় ও সচল</div>
                <h2>অভিনন্দন! মেইল সফলভাবে এসেছে</h2>
                <p>
                    <strong>{{ $brand['siteName'] }}</strong>-এর SMTP সার্ভার কনফিগারেশন সফলভাবে কাজ করছে। গ্রাহক এবং অ্যাডমিন নোটিফিকেশন মেইলসমূহ এখন নিয়মিত ডেলিভার হবে।
                </p>
            </div>

            <div class="info-card">
                <div class="info-card-title">SMTP কনফিগারেশন তথ্য</div>
                <div class="info-row">
                    <span class="info-label">মেইল হোস্ট (Host):</span>
                    <span class="info-val">{{ config('mail.mailers.smtp.host') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">পোর্ট (Port):</span>
                    <span class="info-val">{{ config('mail.mailers.smtp.port') }} ({{ strtoupper(config('mail.mailers.smtp.encryption', 'TLS')) }})</span>
                </div>
                <div class="info-row">
                    <span class="info-label">প্রেরক (From Address):</span>
                    <span class="info-val">{{ config('mail.from.address') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">প্রেরক নাম (Sender Name):</span>
                    <span class="info-val">{{ config('mail.from.name') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">পাঠানোর সময় (Dispatched At):</span>
                    <span class="info-val">{{ now()->setTimezone('Asia/Dhaka')->format('d M, Y h:i A') }} (বাংলাদেশ সময়)</span>
                </div>
            </div>

            <div class="note-box">
                ℹ️ <strong>টিপস:</strong> গ্রাহকরা যেকোনো অর্ডার দিলে বা অ্যাডমিন প্যানেল থেকে অর্ডারের স্ট্যাটাস পরিবর্তন করলে স্বয়ংক্রিয়ভাবে বিস্তারিত আপডেট ইমেইল পাবেন।
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} <strong>{{ $brand['siteName'] }}</strong> &bull; সর্বস্বত্ব সংরক্ষিত।<br>
            @if(!empty($brand['contactPhone']))
                হটলাইন: {{ $brand['contactPhone'] }} &bull;
            @endif
            @if(!empty($brand['contactEmail']))
                ইমেইল: {{ $brand['contactEmail'] }}
            @endif
        </div>
    </div>
</body>
</html>
