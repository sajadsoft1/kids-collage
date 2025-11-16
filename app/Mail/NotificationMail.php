<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @param array<string, mixed> $payload */
    public function __construct(
        private readonly array $payload,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->payload['subject'] ?? $this->payload['title'] ?? 'Notification';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $locale = $this->payload['meta']['locale'] ?? App::getLocale();

        return new Content(
            markdown: 'mail.notification',
            with: [
                'title' => $this->payload['title'] ?? null,
                'subtitle' => $this->payload['subtitle'] ?? null,
                'body' => $this->payload['body'] ?? null,
                'cta' => $this->payload['cta'] ?? null,
                'rtl' => $this->payload['rtl'] ?? $locale === 'fa',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
