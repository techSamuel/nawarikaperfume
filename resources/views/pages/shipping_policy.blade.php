@extends('layouts.app')

@section('title', 'শিপিং ও ডেলিভারি নীতিমালা')

@section('content')
<div class="policy-page-wrapper" style="padding: 40px 0 80px; min-height: 70vh;">
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Breadcrumb --}}
        <div style="margin-bottom: 24px;">
            <a href="{{ route('home') }}" style="color: var(--text-muted); font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: color 0.2s ease;">
                <span>←</span> হোম পেজে ফিরে যান
            </a>
        </div>

        {{-- Page Header --}}
        <div class="policy-header" style="text-align: center; margin-bottom: 36px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(19, 180, 97, 0.12); color: var(--accent-primary, #13b461); padding: 6px 16px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; margin-bottom: 16px; border: 1px solid rgba(19, 180, 97, 0.25);">
                🚚 সারা দেশে দ্রুত হোম ডেলিভারি
            </div>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--text-primary); margin: 0 0 12px 0; letter-spacing: -0.5px;">
                শিপিং ও ডেলিভারি নীতিমালা
            </h1>
            <p style="color: var(--text-muted); font-size: 1rem; max-width: 600px; margin: 0 auto;">
                নাবারিকা স্টোর সারা বাংলাদেশে বিশ্বস্ত কুরিয়ার পার্টনারদের মাধ্যমে দ্রুত ও নিরাপদে হোম ডেলিভারি নিশ্চিত করে।
            </p>

            {{-- Trust Badges --}}
            <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 16px; margin-top: 24px;">
                <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-secondary);">
                    <span>🇧🇩</span> ৬৪ জেলায় ডেলিভারি
                </div>
                <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-secondary);">
                    <span>💵</span> ক্যাশ অন ডেলিভারি (COD)
                </div>
                <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-secondary);">
                    <span>📍</span> লাইভ ট্র্যাকিং সুবিধা
                </div>
            </div>
        </div>

        {{-- Content Box --}}
        <div class="policy-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); color: var(--text-secondary); line-height: 1.85; font-size: 1.02rem;">
            @if(isset($settings['shipping_policy_content']) && trim($settings['shipping_policy_content']) !== '')
                <div class="policy-rendered-content">
                    {!! $settings['shipping_policy_content'] !!}
                </div>
            @else
                <div class="policy-rendered-content">
                    <div class="policy-highlight-box" style="background: rgba(19, 180, 97, 0.08); border-left: 4px solid var(--accent-primary, #13b461); padding: 18px 20px; border-radius: 8px; margin-bottom: 24px;">
                        <h4 style="color: var(--accent-primary, #13b461); margin-top: 0; margin-bottom: 8px;">🚀 দ্রুত ও নিরাপদ ডেলিভারি প্রতিশ্রুতি</h4>
                        <p style="margin: 0; font-size: 0.95rem; line-height: 1.6;">নাবারিকা স্টোর (Nawarika Store) সমগ্র বাংলাদেশে সর্বোচ্চ দ্রুততা ও নিরাপত্তার সাথে আপনার পছন্দের সুগন্ধি ও পণ্য পৌঁছে দিতে প্রতিশ্রুতিবদ্ধ। আমাদের অভিজ্ঞ লজিস্টিকস পার্টনারদের মাধ্যমে ঘরে বসেই পার্সেল রিসিভ করুন।</p>
                    </div>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">১. ডেলিভারি এলাকা ও কুরিয়ার পার্টনার</h3>
                    <p>আমরা সমগ্র বাংলাদেশের ৬৪টি জেলা, উপজেলা ও থানা পর্যায়ে হোম ডেলিভারি সুবিধা প্রদান করি। আমাদের অফিসিয়াল ও নির্ভরযোগ্য কুরিয়ার পার্টনারসমূহ:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;"><strong>Steadfast Courier</strong> (দেশব্যাপী দ্রুততম হোম ডেলিভারি)</li>
                        <li style="margin-bottom: 8px;"><strong>Pathao Courier</strong> (ঢাকা ও প্রধান বিভাগীয় শহরগুলোতে এক্সপ্রেস ডেলিভারি)</li>
                        <li style="margin-bottom: 8px;"><strong>RedX & eCourier</strong></li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">২. ডেলিভারির সম্ভাব্য সময়সীমা</h3>
                    <p>অর্ডার কনফার্মেশনের পর আমাদের টিম দ্রুততম সময়ে পার্সেল প্যাকিং করে কুরিয়ারে হ্যান্ডওভার সম্পন্ন করে:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;"><strong>ঢাকার ভিতরে (Inside Dhaka):</strong> অর্ডার কনফার্মেশনের <strong>২৪ থেকে ৭২ ঘণ্টার মধ্যে (১-৩ কার্যদিবস)</strong> হোম ডেলিভারি।</li>
                        <li style="margin-bottom: 8px;"><strong>ঢাকার বাইরে (Outside Dhaka):</strong> জেলা সদর ও উপজেলা পর্যায়ে <strong>২ থেকে ৫ কার্যদিবসের মধ্যে</strong> হোম ডেলিভারি।</li>
                        <li style="margin-bottom: 8px;"><em>বিশেষ দ্রষ্টব্য:</em> দুর্যোগপূর্ণ আবহাওয়া, সরকারি ছুটির দিন বা অনাকাঙ্ক্ষিত পরিস্থিতির কারণে কিছু ক্ষেত্রে ডেলিভারিতে কিছুটা অতিরিক্ত সময় লাগতে পারে।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৩. ডেলিভারি চার্জ ও ফ্রি ডেলিভারি অফার</h3>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;"><strong>ঢাকার ভিতরে ডেলিভারি চার্জ:</strong> ৬০ - ৮০ টাকা (ওজন ও এলাকা ভিত্তিক)।</li>
                        <li style="margin-bottom: 8px;"><strong>ঢাকার বাইরে ডেলিভারি চার্জ:</strong> ১২০ - ১৫০ টাকা।</li>
                        <li style="margin-bottom: 8px;"><strong>ফ্রি ডেলিভারি সুবিধা:</strong> নাবারিকা স্টোরের বিশেষ ক্যাম্পেইন চলাকালীন অথবা নির্দিষ্ট পরিমাণের কেনাকাটায় (যেমন: ৳১০০০ বা ততোধিক অর্ডারে) সারা বাংলাদেশে <strong>সম্পূর্ণ ফ্রি ডেলিভারি</strong> উপভোগ করতে পারবেন।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৪. পেমেন্ট পদ্ধতি (ক্যাশ অন ডেলিভারি)</h3>
                    <p>গ্রাহকদের সুবিধার্থে আমরা সারা বাংলাদেশে <strong>১০০% ক্যাশ অন ডেলিভারি (Cash on Delivery)</strong> সুবিধা দিয়ে থাকি।</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;">অর্ডার করার সময় কোনো আগাম পেমেন্ট করার প্রয়োজন নেই।</li>
                        <li style="margin-bottom: 8px;">ডেলিভারি ম্যানের কাছ থেকে পার্সেল বুঝে পেয়ে নগদ টাকায় মূল্য পরিশোধ করতে পারবেন।</li>
                        <li style="margin-bottom: 8px;">(অর্ডার নিশ্চিতকরণের স্বার্থে ঢাকার বাইরে কিছু ক্ষেত্রে সামান্য ডেলিভারি চার্জ অগ্রিম গ্রহণের অনুরোধ করা হতে পারে)।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৫. লাইভ অর্ডার ট্র্যাকিং (Live Order Tracking)</h3>
                    <p>আপনার অর্ডারটির বর্তমান স্ট্যাটাস জানতে আমাদের ওয়েবসাইটের ট্র্যাকিং ফিচার ব্যবহার করুন:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;">আমাদের ওয়েবসাইটের মেনু থেকে <a href="{{ route('order.track.page') }}" style="color: var(--accent-primary, #13b461); font-weight: bold; text-decoration: underline;">অর্ডার ট্র্যাক করুন</a> পেজে প্রবেশ করুন।</li>
                        <li style="margin-bottom: 8px;">আপনার <strong>মোবাইল নম্বর</strong> অথবা <strong>অর্ডার নম্বর</strong> লিখে সার্চ করলেই রিয়েল-টাইম কুরিয়ার আপডেট দেখতে পাবেন।</li>
                        <li style="margin-bottom: 8px;">ডেলিভারির দিন কুরিয়ার রাইডার আপনাকে কল দিয়ে ডেলিভারির সময় নির্ধারণ করবে।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৬. ডেলিভারি গ্রহণের সময়ে করণীয়</h3>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;">অর্ডার দেওয়ার সময় অনুগ্রহ করে আপনার সম্পূর্ণ নাম, সক্রিয় ফোন নম্বর এবং বিস্তারিত ঠিকানা (রোড/বাড়ি নং, এলাকা ও থানা) উল্লেখ করুন।</li>
                        <li style="margin-bottom: 8px;">ডেলিভারি রাইডার কল করলে ফোন রিসিভ করুন এবং পার্সেলটি গ্রহণ করুন।</li>
                        <li style="margin-bottom: 8px;">ডেলিভারি ম্যানের উপস্থিতিতে পার্সেল চেক করে নিন।</li>
                    </ul>

                    <div class="policy-contact-box" style="margin-top: 32px; padding: 22px; background: var(--bg-tertiary); border: 1px solid var(--border-color); border-radius: 14px;">
                        <h4 style="margin-top: 0; margin-bottom: 10px; color: var(--text-primary); font-size: 1.1rem;">📦 ডেলিভারি সংক্রান্ত যেকোনো তথ্য জানতে যোগাযোগ করুন</h4>
                        <p style="margin-bottom: 6px;">হটলাইন ও হোয়াটসঅ্যাপ: <strong>{{ $settings['contact_phone'] ?? '01884031111' }}</strong></p>
                        <p style="margin-bottom: 6px;">ইমেইল: <strong>{{ $settings['contact_email'] ?? 'rodalsoft@gmail.com' }}</strong></p>
                        <p style="margin: 0;">অফিসিয়াল ওয়েবসাইট: <a href="https://nawarika.store" style="color: var(--accent-primary, #13b461); font-weight: 600; text-decoration: underline;">nawarika.store</a></p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Bottom CTA --}}
        <div style="display: flex; justify-content: center; gap: 16px; margin-top: 36px; flex-wrap: wrap;">
            <a href="{{ route('order.track.page') }}" class="btn btn-primary" style="padding: 12px 28px; font-size: 1rem; border-radius: 30px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                📍 অর্ডার ট্র্যাক করুন
            </a>
            <a href="{{ route('shop') }}" class="btn btn-outline" style="padding: 12px 28px; font-size: 1rem; border-radius: 30px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                🛍️ শপিং শুরু করুন
            </a>
        </div>
    </div>
</div>

<style>
.policy-rendered-content h3 {
    color: var(--text-primary);
    margin-top: 28px;
    margin-bottom: 12px;
    font-size: 1.3rem;
    font-weight: 700;
}
.policy-rendered-content h4 {
    color: var(--text-primary);
    margin-top: 20px;
    margin-bottom: 8px;
    font-size: 1.1rem;
    font-weight: 600;
}
.policy-rendered-content p {
    margin-bottom: 16px;
    line-height: 1.8;
}
.policy-rendered-content ul, .policy-rendered-content ol {
    padding-left: 24px;
    margin-bottom: 20px;
}
.policy-rendered-content li {
    margin-bottom: 8px;
    line-height: 1.7;
}
.policy-rendered-content a {
    color: var(--accent-primary, #13b461);
    text-decoration: underline;
}
</style>
@endsection
