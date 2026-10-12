<?php

namespace App\Mail;

use App\Services\MailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestSmtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $brand;

    public function __construct()
    {
        $this->brand = MailConfigService::getSiteBrandData();
    }

    public function envelope(): Envelope
    {
        $siteName = $this->brand['siteName'] ?? config('app.name', 'Nawarika');

        return new Envelope(
            subject: "🧪 SMTP টেস্ট সফল হয়েছে - {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test_smtp',
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
