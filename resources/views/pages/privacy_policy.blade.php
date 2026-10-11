@extends('layouts.app')

@section('title', 'প্রাইভেসি ও গোপনীয়তা নীতিমালা')

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
                🔒 তথ্যের শতভাগ নিরাপত্তা
            </div>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--text-primary); margin: 0 0 12px 0; letter-spacing: -0.5px;">
                প্রাইভেসি ও গোপনীয়তা নীতিমালা
            </h1>
            <p style="color: var(--text-muted); font-size: 1rem; max-width: 600px; margin: 0 auto;">
                নাবারিকা স্টোরে আপনার ব্যক্তিগত তথ্যের নিরাপত্তা ও গোপনীয়তা বজায় রাখা আমাদের সর্বোচ্চ দায়িত্ব।
            </p>

            {{-- Trust Badges --}}
            <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 16px; margin-top: 24px;">
                <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-secondary);">
                    <span>🛡️</span> এনক্রিপ্টেড ডাটা সুরক্ষা
                </div>
                <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-secondary);">
                    <span>🚫</span> তথ্য বিক্রয় সম্পূর্ণ নিষিদ্ধ
                </div>
                <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-secondary);">
                    <span>🤝</span> সুরক্ষিত গ্রাহক সেবা
                </div>
            </div>
        </div>

        {{-- Content Box --}}
        <div class="policy-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); color: var(--text-secondary); line-height: 1.85; font-size: 1.02rem;">
            @if(isset($settings['privacy_policy_content']) && trim($settings['privacy_policy_content']) !== '')
                <div class="policy-rendered-content">
                    {!! $settings['privacy_policy_content'] !!}
                </div>
            @else
                <div class="policy-rendered-content">
                    <div class="policy-highlight-box" style="background: rgba(19, 180, 97, 0.08); border-left: 4px solid var(--accent-primary, #13b461); padding: 18px 20px; border-radius: 8px; margin-bottom: 24px;">
                        <h4 style="color: var(--accent-primary, #13b461); margin-top: 0; margin-bottom: 8px;">🔒 গ্রাহকের তথ্যের সর্বোচ্চ গোপনীয়তা আমাদের অঙ্গীকার</h4>
                        <p style="margin: 0; font-size: 0.95rem; line-height: 1.6;">নাবারিকা স্টোর (Nawarika Store / nawarika.store) গ্রাহকদের ব্যক্তিগত তথ্যের গোপনীয়তাকে সর্বোচ্চ গুরুত্ব প্রদান করে। আমাদের সাথে শপিং করার সময় আপনার প্রদত্ত তথ্য কীভাবে সংগৃহীত, সুরক্ষিত এবং ব্যবহৃত হয় তা নিচে পরিষ্কারভাবে তুলে ধরা হলো।</p>
                    </div>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">১. আমরা কী কী তথ্য সংগ্রহ করি</h3>
                    <p>অর্ডার প্রসেসিং এবং নিরবচ্ছিন্ন সেবা প্রদানের জন্য আমরা শুধুমাত্র প্রয়োজনীয় তথ্য সংগ্রহ করি:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;"><strong>ব্যক্তিগত যোগাযোগের তথ্য:</strong> আপনার পূর্ণ নাম, সক্রিয় মোবাইল নম্বর, এবং ইমেইল ঠিকানা।</li>
                        <li style="margin-bottom: 8px;"><strong>ডেলিভারি ঠিকানা:</strong> পার্সেল পৌঁছানোর জন্য আপনার পূর্ণাঙ্গ ঠিকানা (বাসা/ফ্ল্যাট নং, রোড, এলাকা, থানা এবং জেলা)।</li>
                        <li style="margin-bottom: 8px;"><strong>অর্ডারের তথ্য:</strong> ক্রয়কৃত পণ্যের বিবরণ, পরিমাণ, মূল্য এবং অর্ডারের জন্য বিশেষ কোনো নির্দেশনা।</li>
                        <li style="margin-bottom: 8px;"><strong>ব্রাউজিং তথ্য (কুকিজ):</strong> সাইটের পারফরম্যান্স ও ইউজার অভিজ্ঞতা বৃদ্ধির জন্য আইপি অ্যাড্রেস ও ব্রাউজার সম্পর্কিত প্রাথমিক প্রযুক্তিগত তথ্য।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">২. সংগৃহীত তথ্য আমরা কীভাবে ব্যবহার করি</h3>
                    <p>আপনার তথ্য শুধুমাত্র বৈধ বাণিজ্যিক ও সেবা প্রদানের উদ্দেশ্যে ব্যবহৃত হয়:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;">অর্ডার নিশ্চিতকরণ, পার্সেল প্যাকিং এবং সঠিক ঠিকানায় ডেলিভারি পৌঁছানো।</li>
                        <li style="margin-bottom: 8px;">অর্ডারের বর্তমান অবস্থা বা ট্র্যাকিং তথ্য এসএমএস অথবা কল করে জানানো।</li>
                        <li style="margin-bottom: 8px;">গ্রাহক সেবা ও বিক্রয়োত্তর যেকোনো সহায়তা প্রদান করা।</li>
                        <li style="margin-bottom: 8px;">ভুয়া বা প্রতারণামূলক অর্ডার যাচাই ও সাইটের নিরাপত্তা বজায় রাখা।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৩. তথ্যের সুরক্ষা ও তৃতীয় পক্ষের সাথে নীতি</h3>
                    <p>আমাদের কঠোর গোপনীয়তা নীতিমালার অধীনে:</p>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;"><strong>তথ্য বিক্রয় বা ভাড়া দেওয়া সম্পূর্ণ নিষিদ্ধ:</strong> আমরা কখনোই কোনো গ্রাহকের ব্যক্তিগত তথ্য বিজ্ঞাপনদাতা বা কোনো তৃতীয় পক্ষের কাছে বিক্রয়, লিজ বা হস্তান্তর করি না।</li>
                        <li style="margin-bottom: 8px;"><strong>শুধুমাত্র কুরিয়ার পার্টনারের সাথে সীমিত শেয়ার:</strong> আপনার পার্সেলটি আপনার ঠিকানায় পৌঁছে দেওয়ার স্বার্থে শুধুমাত্র আপনার নাম, ঠিকানা ও ফোন নম্বর অনুমোদিত কুরিয়ার সার্ভিসের সাথে শেয়ার করা হয়।</li>
                        <li style="margin-bottom: 8px;">আমাদের সিস্টেমে সংরক্ষিত সকল তথ্য আধুনিক এনক্রিপশন ও সিকিউর সার্ভার কাঠামোর মাধ্যমে সুরক্ষিত থাকে।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৪. পেমেন্ট ও আর্থিক তথ্যের নিরাপত্তা</h3>
                    <ul style="padding-left: 20px; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;">নাবারিকা স্টোর মূলত ক্যাশ অন ডেলিভারি (Cash on Delivery) ভিত্তিক পরিচালিত হয়, তাই আমাদের সাইটে কোনো ব্যাংক কার্ড নম্বর বা নেট ব্যাংকিং পাসওয়ার্ড সংরক্ষণ করা হয় না।</li>
                        <li style="margin-bottom: 8px;">বিকাশ/নগদ পেমেন্টের ক্ষেত্রে আমাদের কোনো প্রতিনিধি কখনোই আপনার ব্যক্তিগত পিন (PIN) বা ভেরিফিকেশন কোড (OTP) জানতে চাইবে না।</li>
                    </ul>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৫. কুকিজ (Cookies) নীতিমালা</h3>
                    <p>আমাদের ওয়েবসাইটে আপনার শপিং অভিজ্ঞতা আরও দ্রুত ও সহজ করতে কুকিজ প্রযুক্তি ব্যবহৃত হয়। কুকিজের মাধ্যমে আপনার কার্টের পণ্য মনে রাখা সহজ হয়। আপনি চাইলে আপনার ব্রাউজার সেটিংস থেকে যেকোনো সময় কুকিজ নিয়ন্ত্রণ বা বন্ধ রাখতে পারেন।</p>

                    <h3 style="color: var(--text-primary); margin-top: 28px; margin-bottom: 12px; font-size: 1.3rem;">৬. তথ্যের পরিবর্তন ও আপনার অধিকার</h3>
                    <p>আপনার সংরক্ষিত তথ্যের কোনো ভুল থাকলে তা সংশোধন করতে বা যেকোনো সময় তথ্য মুছে ফেলার অনুরোধ জানাতে আমাদের সাথে নির্দ্বিধায় যোগাযোগ করতে পারেন।</p>

                    <div class="policy-contact-box" style="margin-top: 32px; padding: 22px; background: var(--bg-tertiary); border: 1px solid var(--border-color); border-radius: 14px;">
                        <h4 style="margin-top: 0; margin-bottom: 10px; color: var(--text-primary); font-size: 1.1rem;">🛡️ প্রাইভেসি বিষয়ক যেকোনো তথ্যের জন্য যোগাযোগ করুন</h4>
                        <p style="margin-bottom: 6px;">ইমেইল: <strong>{{ $settings['contact_email'] ?? 'rodalsoft@gmail.com' }}</strong></p>
                        <p style="margin-bottom: 6px;">হটলাইন ও হোয়াটসঅ্যাপ: <strong>{{ $settings['contact_phone'] ?? '01884031111' }}</strong></p>
                        <p style="margin: 0;">অফিসিয়াল ওয়েবসাইট: <a href="https://nawarika.store" style="color: var(--accent-primary, #13b461); font-weight: 600; text-decoration: underline;">nawarika.store</a></p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Bottom CTA --}}
        <div style="text-align: center; margin-top: 36px;">
            <a href="{{ route('shop') }}" class="btn btn-primary" style="padding: 12px 32px; font-size: 1rem; border-radius: 30px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
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
