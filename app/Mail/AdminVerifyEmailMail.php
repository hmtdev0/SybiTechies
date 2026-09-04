<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AdminVerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $verifyUrl;

    public function __construct(public User $user)
    {
        $this->verifyUrl = URL::temporarySignedRoute(
            'admin.verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ],
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Verify your SysbiTechies admin email address');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-verify-email',
            with: [
                'verifyUrl' => $this->verifyUrl,
                'userName' => $this->user->name,
                'footerNote' => 'If you did not create this account, you can safely ignore this email.',
            ],
        );
    }
}
