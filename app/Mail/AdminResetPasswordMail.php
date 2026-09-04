<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;

    public function __construct(public User $user, string $token)
    {
        $this->resetUrl = route('admin.password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reset your SysbiTechies admin password');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-reset-password',
            with: [
                'resetUrl' => $this->resetUrl,
                'userName' => $this->user->name,
                'footerNote' => 'If you did not request a password reset, no further action is required — your password will not change.',
            ],
        );
    }
}
