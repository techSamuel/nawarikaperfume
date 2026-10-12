<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\MailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerOrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $brand;

    public function __construct(public Order $order)
    {
        $this->brand = MailConfigService::getSiteBrandData();
    }

    public function envelope(): Envelope
    {
        $siteName = $this->brand['siteName'] ?? config('app.name', 'Nawarika');

        return new Envelope(
            subject: "✅ অর্ডার গ্রহণ করা হয়েছে: #{$this->order->order_number} - {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer_order_placed',
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
