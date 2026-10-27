<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $otp,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Verify your NR Fitness account');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.registration_otp');
    }

    public function attachments(): array
    {
        return [];
    }
}
