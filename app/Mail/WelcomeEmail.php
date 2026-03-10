<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public ?string $verificationUrl;
    public bool $includeVerification;
    public ?string $password;

    /**
     * Create a new message instance.
     */

    public function __construct(User $user, bool $includeVerification = false, ?string $password = null)
    {
        $this->user = $user;
        $this->includeVerification = $includeVerification;
        $this->password = $password;
        if ($this->includeVerification) {
            $this->verificationUrl = $this->generateVerificationUrl();
        }
    }

    /**
     * Get the message envelope.
     */

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->includeVerification
                ? 'Welcome to Karibu Parcels – Verify Your Email'
                : 'Welcome to Karibu Parcels'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome.welcome-email',
            with: [
                'user' => $this->user,
                'includeVerification' => $this->includeVerification,
                'verificationUrl' => $this->verificationUrl,
                'password' => $this->password,
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

    /**
     * Generate the verification URL.
     */
    private function generateVerificationUrl(): string
    {
        $token = hash_hmac(
            'sha256',
            $this->user->id . $this->user->email,
            config('app.key')
        );

        return route('user.verify.email', [
            'id' => $this->user->id,
            'hash' => sha1($this->user->email),
            'token' => $token,
        ]);


        // $this->verificationUrl = URL::temporarySignedRoute(
        //     'verification.verify',
        //     now()->addMinutes(60),
        //     [
        //         'id' => $this->user->id,
        //         'hash' => sha1($this->user->email),
        //     ]
        // );
    }
}
