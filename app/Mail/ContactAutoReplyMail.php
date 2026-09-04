<?php

namespace App\Mail;

use App\Mail\Concerns\EmbedsLocalImages;
use App\Models\ContactMessage;
use App\Models\EmailSetting;
use App\Models\SiteSetting;
use App\Services\EmailTemplateRenderer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAutoReplyMail extends Mailable
{
    use EmbedsLocalImages, Queueable, SerializesModels;

    public string $renderedSubject;

    public string $renderedBody;

    public function __construct(ContactMessage $contactMessage, EmailSetting $emailSetting)
    {
        $placeholders = [
            'name' => $contactMessage->name,
            'email' => $contactMessage->email,
            'company_name' => SiteSetting::current()->company_name,
        ];

        $this->renderedSubject = EmailTemplateRenderer::render($emailSetting->autoreply_subject, $placeholders, escape: false);
        $this->renderedBody = EmailTemplateRenderer::styleContent(
            EmailTemplateRenderer::render($emailSetting->autoreply_body, $placeholders)
        );

        $this->embedLocalImages();
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->renderedSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-autoreply',
            with: ['body' => $this->renderedBody],
        );
    }
}
