<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class PolicySettingsSeeder extends Seeder
{
    public function run(): void
    {
        $returnPolicyHtml = <<<'HTML'
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
    <li style="margin-bottom: 8px;">পণ্য পাওয়ার <strong>২৪ থেকে ৪৮ ঘণ্টার মধ্যে</strong> আমাদের অফিসিয়াল হটলাইন <strong>01884031111</strong> অথবা ফেসবুক পেজ/ইমেইলে অভিযোগ জানাতে হবে।</li>
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
    <p style="margin-bottom: 6px;">হটলাইন ও হোয়াটসঅ্যাপ: <strong>01884031111</strong> (সকাল ১০:০০ টা - রাত ১০:০০ টা)</p>
    <p style="margin-bottom: 6px;">ইমেইল: <strong>rodalsoft@gmail.com</strong></p>
    <p style="margin: 0;">অফিসিয়াল ওয়েবসাইট: <a href="https://nawarika.store" style="color: var(--accent-primary, #13b461); font-weight: 600; text-decoration: underline;">nawarika.store</a></p>
</div>
HTML;

        $shippingPolicyHtml = <<<'HTML'
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
    <li style="margin-bottom: 8px;">আমাদের ওয়েবসাইটের মেনু থেকে <a href="/track" style="color: var(--accent-primary, #13b461); font-weight: bold; text-decoration: underline;">অর্ডার ট্র্যাক করুন</a> পেজে প্রবেশ করুন।</li>
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
    <p style="margin-bottom: 6px;">হটলাইন ও হোয়াটসঅ্যাপ: <strong>01884031111</strong></p>
    <p style="margin-bottom: 6px;">ইমেইল: <strong>rodalsoft@gmail.com</strong></p>
    <p style="margin: 0;">অফিসিয়াল ওয়েবসাইট: <a href="https://nawarika.store" style="color: var(--accent-primary, #13b461); font-weight: 600; text-decoration: underline;">nawarika.store</a></p>
</div>
HTML;

        $privacyPolicyHtml = <<<'HTML'
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
    <p style="margin-bottom: 6px;">ইমেইল: <strong>rodalsoft@gmail.com</strong></p>
    <p style="margin-bottom: 6px;">হটলাইন ও হোয়াটসঅ্যাপ: <strong>01884031111</strong></p>
    <p style="margin: 0;">অফিসিয়াল ওয়েবসাইট: <a href="https://nawarika.store" style="color: var(--accent-primary, #13b461); font-weight: 600; text-decoration: underline;">nawarika.store</a></p>
</div>
HTML;

        Setting::updateOrCreate(
            ['key' => 'return_policy_content'],
            ['value' => $returnPolicyHtml]
        );

        Setting::updateOrCreate(
            ['key' => 'shipping_policy_content'],
            ['value' => $shippingPolicyHtml]
        );

        Setting::updateOrCreate(
            ['key' => 'privacy_policy_content'],
            ['value' => $privacyPolicyHtml]
        );

        Cache::forget('site_settings');
    }
}
