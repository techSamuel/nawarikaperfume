<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $newStatus,
        public array $changes = []
    ) {
    }

    public function envelope(): Envelope
    {
        $statusLabel = ucfirst($this->newStatus);
        $subject = !empty($this->changes) && count($this->changes) > 1
            ? "📦 Order Update: Details Changed for Order #{$this->order->order_number}"
            : "📦 Order Update: Your Order #{$this->order->order_number} is now {$statusLabel}";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_changed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
