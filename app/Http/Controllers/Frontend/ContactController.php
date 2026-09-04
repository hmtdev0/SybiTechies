<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactAutoReplyMail;
use App\Models\ContactMessage;
use App\Models\EmailSetting;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Services\MailConfigurator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('pages.contact', [
            'seo' => SeoMeta::forPageKey('contact'),
            'siteSettings' => SiteSetting::current(),
            'services' => Service::query()->active()->ordered()->get(),
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $contactMessage = ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'service_interested' => $data['service'] ?? null,
            'message' => $data['message'],
        ]);

        $this->sendAutoReplyIfEnabled($contactMessage);

        return back()
            ->with('success', "Message sent — we'll reply within 1 business day.")
            ->withFragment('contact-form');
    }

    /**
     * The message is always stored above regardless of email settings.
     * Sending is best-effort: a broken SMTP config must never prevent the
     * visitor's message from being saved or show them an error.
     */
    protected function sendAutoReplyIfEnabled(ContactMessage $contactMessage): void
    {
        $emailSettings = EmailSetting::current();

        if (! $emailSettings->canSendAutoReply()) {
            return;
        }

        try {
            MailConfigurator::apply($emailSettings);
            Mail::to($contactMessage->email)->send(new ContactAutoReplyMail($contactMessage, $emailSettings));
        } catch (\Throwable $e) {
            Log::error('Contact auto-reply email failed to send.', [
                'contact_message_id' => $contactMessage->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
