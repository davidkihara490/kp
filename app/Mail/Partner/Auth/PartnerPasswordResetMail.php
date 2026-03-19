<?php

namespace App\Mail\Partner\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;
    public $partner;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct(User $partner, $token)
    {
        $this->partner = $partner;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your ' . config('app.name') . ' Partner Password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.partners.password-reset',
            with: [
                'name' => $this->partner->getFullName()  ?? $this->partner->company_name,
                'resetUrl' => route('partners.reset-password', $this->token),
                'expiryHours' => 1,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
