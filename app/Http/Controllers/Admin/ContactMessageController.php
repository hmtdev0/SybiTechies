<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactReplyRequest;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use App\Models\EmailSetting;
use App\Services\MailConfigurator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('view inquiries');

        $messages = ContactMessage::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->string('search').'%';
                $q->where(fn ($q2) => $q2->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('is_read', $request->string('status') === 'read'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'unreadCount' => ContactMessage::query()->unread()->count(),
            'breadcrumb' => 'Contact Messages',
        ]);
    }

    public function show(ContactMessage $message): View
    {
        $this->authorize('view inquiries');

        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', [
            'message' => $message,
            'breadcrumb' => 'Contact Messages — View',
        ]);
    }

    public function reply(ContactReplyRequest $request, ContactMessage $message): RedirectResponse
    {
        $this->authorize('reply inquiries');

        $replySubject = $request->validated('reply_subject');
        $replyBody = $this->sanitizeRichText($request->validated('admin_reply'));
        $attachments = $request->file('attachments') ?? [];

        $message->update([
            'admin_reply' => $replyBody,
            'replied_at' => now(),
            'is_read' => true,
        ]);

        $emailSettings = EmailSetting::current();

        if (! $emailSettings->canSendMail()) {
            return back()->with('error', 'Reply saved, but not emailed — email sending is disabled. Turn it on in Email Settings to actually send replies.');
        }

        try {
            MailConfigurator::apply($emailSettings);
            Mail::to($message->email)->send(new ContactReplyMail($message, $replySubject, $replyBody, $attachments));
        } catch (\Throwable $e) {
            Log::error('Contact reply email failed to send.', [
                'contact_message_id' => $message->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Reply saved, but the email failed to send. Check your SMTP settings and try again.');
        }

        return back()->with('success', 'Reply sent to '.$message->email.'.');
    }

    /**
     * admin_reply is edited with a Quill rich-text editor and stored as raw
     * HTML that later gets emailed to the sender — strip anything outside
     * Quill's own output vocabulary (same allowlist used elsewhere for
     * admin-authored rich-text fields).
     */
    protected function sanitizeRichText(string $html): string
    {
        $allowedTags = '<p><h2><h3><strong><em><u><s><ol><ul><li><blockquote><a><img><br>';

        $html = strip_tags($html, $allowedTags);
        $html = preg_replace('/\s(on\w+)\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1=$2#$2', $html);

        return $html;
    }

    public function toggleRead(ContactMessage $message): RedirectResponse
    {
        $this->authorize('view inquiries');

        $message->update(['is_read' => ! $message->is_read]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $this->authorize('delete inquiries');

        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('delete inquiries');

        $ids = (array) $request->input('ids', []);

        ContactMessage::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' messages deleted.');
    }
}
