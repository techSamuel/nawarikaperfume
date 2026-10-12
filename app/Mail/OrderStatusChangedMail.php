<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\MailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $brand;

    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $newStatus,
        public array $changes = []
    ) {
        $this->brand = MailConfigService::getSiteBrandData();
    }

    public function envelope(): Envelope
    {
        $siteName = $this->brand['siteName'] ?? config('app.name', 'Nawarika');

        $statusText = match($this->newStatus) {
            'confirm' => 'অর্ডার নিশ্চিত হয়েছে',
            'processing' => 'প্রসেসিং ও প্যাকিং চলছে',
            'shipped' => 'কুরিয়ারে হস্তান্তর করা হয়েছে',
            'delivered' => 'ডেলিভারি সম্পন্ন হয়েছে',
            'cancelled' => 'অর্ডার বাতিল করা হয়েছে',
            default => 'স্ট্যাটাস আপডেট',
        };

        $subject = !empty($this->changes) && count($this->changes) > 1
            ? "📝 অর্ডারের তথ্য আপডেট: #{$this->order->order_number} - {$siteName}"
            : "📦 {$statusText}: #{$this->order->order_number} - {$siteName}";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_changed',
            with: [
                'brand' => $this->brand,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
