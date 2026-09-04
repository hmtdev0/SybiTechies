<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailSettingRequest;
use App\Models\EmailSetting;
use App\Services\MailConfigurator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EmailSettingController extends Controller
{
    public function edit(): View
    {
        $this->authorize('manage settings');

        return view('admin.email-settings.edit', [
            'settings' => EmailSetting::current(),
            'breadcrumb' => 'Email Settings',
        ]);
    }

    public function update(EmailSettingRequest $request): RedirectResponse
    {
        $settings = EmailSetting::current();
        $data = $request->validated();

        $data['email_enabled'] = $request->boolean('email_enabled');
        $data['autoreply_enabled'] = $request->boolean('autoreply_enabled');

        // Leave the SMTP password field blank on the form to keep the
        // currently-saved password — only overwrite it when a new value
        // is actually typed in.
        if (! $request->filled('smtp_password')) {
            unset($data['smtp_password']);
        }

        $data = $this->sanitizeRichText($data);

        $settings->update($data);

        return back()->with('success', 'Email settings updated successfully.');
    }

    public function sendTest(Request $request): RedirectResponse
    {
        $request->validate(['test_email' => ['required', 'email']]);

        $settings = EmailSetting::current();

        if (blank($settings->smtp_host)) {
            return back()->with('error', 'Add and save your SMTP settings before sending a test email.');
        }

        try {
            MailConfigurator::apply($settings);
            Mail::raw(
                "This is a test email from your Email Settings page.\n\nIf you received this, your SMTP configuration is working correctly.",
                fn ($message) => $message->to($request->string('test_email'))->subject('Test Email — SMTP Configuration')
            );
        } catch (\Throwable $e) {
            return back()->with('error', 'Could not send test email: '.$e->getMessage());
        }

        return back()->with('success', 'Test email sent to '.$request->string('test_email').'.');
    }

    /**
     * autoreply_body is edited with a Quill rich-text editor and stored as
     * raw HTML that later gets emailed to visitors — strip anything outside
     * Quill's own output vocabulary (same allowlist used for Project/Service
     * rich-text fields).
     */
    protected function sanitizeRichText(array $data): array
    {
        if (empty($data['autoreply_body'])) {
            return $data;
        }

        $allowedTags = '<p><h2><h3><strong><em><u><s><ol><ul><li><blockquote><a><img><br>';

        $html = strip_tags($data['autoreply_body'], $allowedTags);
        $html = preg_replace('/\s(on\w+)\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1=$2#$2', $html);

        $data['autoreply_body'] = $html;

        return $data;
    }
}
