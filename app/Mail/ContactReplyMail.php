<?php

namespace App\Mail;

use App\Mail\Concerns\EmbedsLocalImages;
use App\Models\ContactMessage;
use App\Services\EmailTemplateRenderer;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use EmbedsLocalImages, Queueable, SerializesModels;

    /**
     * @param  UploadedFile[]  $attachedFiles  Attached straight from the
     *      request's temp upload path — never persisted to disk, since
     *      they only need to exist for this one synchronous send.
     */
    public function __construct(
        public ContactMessage $contactMessage,
        public string $replySubject,
        public string $replyBody,
        protected array $attachedFiles = [],
    ) {
        $this->replyBody = EmailTemplateRenderer::styleContent($replyBody);
        $this->embedLocalImages();
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->replySubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply',
            with: [
                'replyBodyHtml' => $this->replyBody,
                'originalMessage' => $this->contactMessage->message,
                'footerNote' => 'Replying to this email will reach us directly.',
            ],
        );
    }

    public function attachments(): array
    {
        return collect($this->attachedFiles)
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->map(fn (UploadedFile $file) => Attachment::fromPath($file->getRealPath())
                ->as($file->getClientOriginalName())
                ->withMime($file->getMimeType() ?: 'application/octet-stream'))
            ->all();
    }
}
