<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\MailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewOrderMail extends Mailable
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
            subject: "🚨 নতুন অর্ডার! #{$this->order->order_number} (৳" . number_format($this->order->total, 0) . ") - {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_new_order',
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
