@extends('layouts.app')

@section('title', 'রিটার্ন ও রিফান্ড নীতিমালা')

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
                🔄 সহজ ও নিরাপদ কেনাকাটা
            </div>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--text-primary); margin: 0 0 12px 0; letter-spacing: -0.5px;">
                রিটার্ন ও রিফান্ড নীতিমালা
            </h1>
            <p style="color: var(--text-muted); font-size: 1rem; max-width: 600px; margin: 0 auto;">
                নাবারিকা স্টোরে আপনার প্রতিটি কেনাকাটা শতভাগ নিরাপদ ও ঝুঁকিমুক্ত। ডেলিভারি ও রিটার্ন সংক্রান্ত সকল তথ্য জেনে নিন।
            </p>

            {{-- Trust Badges --}}
            <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 16px; margin-top: 24px;">
                <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-secondary);">
                    <span>✅</span> ১০০% অরিজিনাল পণ্য
                </div>
                <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-secondary);">
                    <span>📦</span> চেক করে গ্রহণের সুবিধা
                </div>
                <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-secondary);">
                    <span>⚡</span> দ্রুত রিফান্ড প্রসেসিং
                </div>
            </div>
        </div>

        {{-- Content Box --}}
        <div class="policy-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); color: var(--text-secondary); line-height: 1.85; font-size: 1.02rem;">
            @if(isset($settings['return_policy_content']) && trim($settings['return_policy_content']) !== '')
                <div class="policy-rendered-content">
                    {!! $settings['return_policy_content'] !!}
                </div>
            @else
                <div class="policy-rendered-content">
                    <div class="policy-highlight-box" style="background: rgba(19, 180, 97, 0.08); border-left: 4px solid var(--accent-primary, #13b461); padding: 18px 20px; border-radius: 8px; margin-bottom: 24px;">
                        <h4 style="color: var(--accent-primary, #13b461); margin-top: 0; margin-bottom: 8px;">✨ গ্রাহক সন্তুষ্টি আমাদের সর্বোচ্চ অগ্রাধিকার</h4>
                        <p style="margin: 0; font-size: 0.95rem; line-height: 1.6;">নাবারিকা স্টোর (Nawarika Store)-এ শপিং করার জন্য আপনাকে ধন্যবাদ। আমরা চাই আপনি কোনো রকম দ্বিধা বা সংশয় ছাড়াই আপনার পছন্দের সুবাস ও পণ্য অর্ডার করুন। অর্ডারের পূর্বে আমাদের রিটার্ন ও রিফান্ড নীতিমালাটি ভালো করে পড়ে নেওয়ার অনুরোধ করা হচ্ছে।</p>
                    </div>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">১. ডেলিভারির সময় পার্সেল চেক করার নিয়ম (ইনস্ট্যান্ট রিটার্ন)</h3>
                    <p>আমাদের সকল পণ্য সারা বাংলাদেশে <strong>ক্যাশ অন ডেলিভারি (Cash on Delivery)</strong> মাধ্যমে কুরিয়ারে পাঠানো হয়। ডেলিভারি গ্রহণের সময় গ্রাহকের করণীয়:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;">পার্সেল রিসিভ করার সময় অবশ্যই <strong>ডেলিভারি ম্যানের সামনে প্যাকেট খুলে পণ্যটি ভালোভাবে দেখে ও মিলিয়ে নিবেন</strong>।</li>
                        <li style="margin-bottom: 8px;">পণ্য ভাঙা, লিক হওয়া, মেয়াদোত্তীর্ণ অথবা আপনার অর্ডারকৃত পণ্যের সাথে অমিল থাকলে ডেলিভারি ম্যানের কাছে সাথে সাথে পার্সেলটি ফেরত দিয়ে দিন।</li>
                        <li style="margin-bottom: 8px;">ডেলিভারি ম্যানের উপস্থিতিতে পণ্য দেখে তাৎক্ষণিক রিটার্ন করলে আপনাকে কোনো টাকা বা চার্জ দিতে হবে না।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">২. পার্সেল রিসিভ করার পর রিটার্ন বা এক্সচেঞ্জের নিয়মাবলী</h3>
                    <p>যদি ডেলিভারি ম্যান চলে যাওয়ার পর পণ্যে কোনো প্রকার অপ্রত্যাশিত সমস্যা ধরা পড়ে, তবে নিম্নলিখিত শর্তে রিটার্ন বা এক্সচেঞ্জ গ্রহণযোগ্য হবে:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;">পার্সেল খোলার সময় একটি স্পষ্ট <strong>আনবক্সিং ভিডিও (Unboxing Video)</strong> ধারণ করা বাধ্যতামূলক।</li>
                        <li style="margin-bottom: 8px;">পণ্য পাওয়ার <strong>২৪ থেকে ৪৮ ঘণ্টার মধ্যে</strong> আমাদের অফিসিয়াল হটলাইন <strong>{{ $settings['contact_phone'] ?? '01884031111' }}</strong> অথবা ফেসবুক পেজ/ইমেইলে অভিযোগ জানাতে হবে।</li>
                        <li style="margin-bottom: 8px;">যদি আমাদের পক্ষ থেকে ভুল বা ডিফেক্টিভ প্রোডাক্ট পাঠানো হয়, আমরা সম্পূর্ণ নিজস্ব খরচে নতুন প্রোডাক্ট এক্সচেঞ্জ করে দিব।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৩. পারফিউম ও কসমেটিকস পণ্যের ক্ষেত্রে বিশেষ শর্তাবলী</h3>
                    <p>সুগন্ধি ও কসমেটিকস পণ্যগুলোর স্বাস্থ্যবিধি (Hygiene) ও মৌলিক গুণগত মান বজায় রাখতে কিছু বিশেষ নিয়ম প্রযোজ্য:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;"><strong>ব্যবহৃত বা সিল খোলা পারফিউম রিটার্নযোগ্য নয়:</strong> স্বাস্থ্যবিধি ও পণ্যের নিরাপত্তা বিবেচনায় পারফিউমের বক্সের প্লাস্টিক সিল খোলা হলে বা একবারও স্প্রে করা হলে তা আর রিটার্ন বা এক্সচেঞ্জ করা সম্ভব নয়।</li>
                        <li style="margin-bottom: 8px;"><strong>সুগন্ধি ব্যক্তিগত পছন্দের বিষয়:</strong> "ঘ্রাণ আমার পছন্দ হয়নি" বা "অন্য ফ্লেভার নিতে চাই"—এমন কারণে সিল খোলা পারফিউম ফেরত গ্রহণযোগ্য হবে না। শুধুমাত্র বক্স ও সিল সম্পূর্ণ অক্ষত থাকা সাপেক্ষে এক্সচেঞ্জ বিবেচনা করা যাবে।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৪. রিটার্ন ও এক্সচেঞ্জ ডেলিভারি চার্জ</h3>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;"><strong>আমাদের ভুলের কারণে:</strong> ভুল পণ্য বা ক্ষতিগ্রস্ত পার্সেলের ক্ষেত্রে রিটার্ন ও পুনরায় ডেলিভারির সম্পূর্ণ খরচ নাবারিকা স্টোর বহন করবে।</li>
                        <li style="margin-bottom: 8px;"><strong>গ্রাহকের নিজস্ব পছন্দের কারণে:</strong> সিল অক্ষত থাকা অবস্থায় কোনো গ্রাহক অন্য প্রোডাক্ট নিতে চাইলে রিটার্ন কুরিয়ার চার্জ (ঢাকার মধ্যে ৮০ টাকা, ঢাকার বাইরে ১৫০ টাকা) গ্রাহককে বহন করতে হবে।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৫. রিফান্ড (Refund) ও টাকা ফেরতের পদ্ধতি</h3>
                    <p>রিটার্ন নিশ্চিত হওয়ার পর রিফান্ড প্রক্রিয়া নিম্নরূপ:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;">রিটার্নকৃত পণ্য আমাদের অফিসে পৌঁছানোর পর কোয়ালিটি চেকিং সম্পন্ন করা হবে।</li>
                        <li style="margin-bottom: 8px;">পণ্যটি নিয়ম অনুযায়ী অক্ষত থাকলে পরবর্তী <strong>৩ থেকে ৫ কার্যদিবসের মধ্যে</strong> আপনার বিকাশ (bKash), নগদ (Nagad) বা ব্যাংক অ্যাকাউন্টে রিফান্ডের টাকা পাঠিয়ে দেওয়া হবে।</li>
                        <li style="margin-bottom: 8px;">রিফান্ড সফল হওয়ার পর এসএমএস বা কলের মাধ্যমে গ্রাহককে অবহিত করা হবে।</li>
                    </ul>

                    <div class="policy-contact-box" style="margin-top: 32px; padding: 22px; background: var(--bg-tertiary); border: 1px solid var(--border-color); border-radius: 14px;">
                        <h4 style="margin-top: 0; margin-bottom: 10px; color: var(--text-primary); font-size: 1.1rem;">📞 যেকোনো প্রয়োজনে আমরা আছি আপনার পাশে</h4>
                        <p style="margin-bottom: 6px;">হটলাইন ও হোয়াটসঅ্যাপ: <strong>{{ $settings['contact_phone'] ?? '01884031111' }}</strong> (সকাল ১০:০০ টা - রাত ১০:০০ টা)</p>
                        <p style="margin-bottom: 6px;">ইমেইল: <strong>{{ $settings['contact_email'] ?? 'rodalsoft@gmail.com' }}</strong></p>
                        <p style="margin: 0;">অফিসিয়াল ওয়েবসাইট: <a href="https://nawarika.store" style="color: var(--accent-primary, #13b461); font-weight: 600; text-decoration: underline;">nawarika.store</a></p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Bottom CTA --}}
        <div style="text-align: center; margin-top: 36px;">
            <a href="{{ route('shop') }}" class="btn btn-primary" style="padding: 12px 32px; font-size: 1rem; border-radius: 30px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                🛍️ শপিং চালিয়ে যান
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
