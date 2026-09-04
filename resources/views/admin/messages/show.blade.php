@extends('layouts.admin')

@section('title', 'View Message')

@php
    $emailSettings = \App\Models\EmailSetting::current();
    $replySubjectDefault = 'Re: Your enquiry to '.\App\Models\SiteSetting::current()->company_name;
@endphp

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Message from {{ $message->name }}</h2>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.messages.toggle-read', $message) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-admin-soft">
                    <i class="bi bi-envelope{{ $message->is_read ? '' : '-open' }} me-1"></i>
                    Mark as {{ $message->is_read ? 'Unread' : 'Read' }}
                </button>
            </form>
            <a href="{{ route('admin.messages.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Messages</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card__header"><h3 class="admin-card__title">Message</h3></div>
                <div class="admin-card__body">
                    <p class="mb-0" style="white-space: pre-line;">{{ $message->message }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card__header"><h3 class="admin-card__title">Contact Details</h3></div>
                <div class="admin-card__body d-flex flex-column gap-3">
                    <div>
                        <small class="text-muted d-block">Name</small>
                        <strong>{{ $message->name }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Email</small>
                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                    </div>
                    @if($message->phone)
                        <div>
                            <small class="text-muted d-block">Phone</small>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $message->phone) }}">{{ $message->phone }}</a>
                        </div>
                    @endif
                    @if($message->company)
                        <div>
                            <small class="text-muted d-block">Company</small>
                            <strong>{{ $message->company }}</strong>
                        </div>
                    @endif
                    @if($message->service_interested)
                        <div>
                            <small class="text-muted d-block">Service Interested In</small>
                            <span class="admin-badge admin-badge--info">{{ $message->service_interested }}</span>
                        </div>
                    @endif
                    <div>
                        <small class="text-muted d-block">Received</small>
                        <strong>{{ $message->created_at->format('M j, Y g:i A') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($message->admin_reply)
        <div class="admin-card mt-4">
            <div class="admin-card__header"><h3 class="admin-card__title">Last Reply</h3></div>
            <div class="admin-card__body">
                <div class="alert alert-success mb-0">
                    <div class="fw-semibold mb-2"><i class="bi bi-reply-fill me-1"></i>Sent {{ $message->replied_at?->diffForHumans() }}</div>
                    {!! $message->admin_reply !!}
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4 mt-1">
        <div class="col-lg-7">
            <div class="admin-card h-100">
                <div class="admin-card__header"><h3 class="admin-card__title">{{ $message->admin_reply ? 'Send Another Reply' : 'Send Reply' }}</h3></div>
                <div class="admin-card__body">
                    @if(! $emailSettings->canSendMail())
                        <div class="alert alert-warning small">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Email sending is off, so replies below will only be saved here, not emailed.
                            <a href="{{ route('admin.email-settings.edit') }}" class="fw-semibold">Turn it on in Email Settings</a>.
                        </div>
                    @endif

                    <form action="{{ route('admin.messages.reply', $message) }}" method="POST" enctype="multipart/form-data" id="replyForm">
                        @csrf
                        <label class="admin-form-label">Subject</label>
                        <input type="text" name="reply_subject" id="replySubject" class="form-control mb-3" maxlength="255" value="{{ old('reply_subject', $replySubjectDefault) }}">

                        <label class="admin-form-label">Message</label>
                        <textarea name="admin_reply" id="adminReplyBody" rows="8" class="form-control mb-3" data-quill-editor data-quill-upload-url="{{ route('admin.editor-uploads.store') }}" placeholder="Write your reply — it will be emailed directly to {{ $message->email }}...">{{ old('admin_reply') }}</textarea>

                        <label class="admin-form-label">Attachments <small class="text-muted">(optional — up to 5 files, 10MB each)</small></label>
                        <input type="file" name="attachments[]" id="replyAttachments" class="form-control mb-2" multiple accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.doc,.docx,.xls,.xlsx,.csv,.txt,.zip">
                        <div id="attachmentsList" class="d-flex flex-column gap-1 mb-3"></div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-admin-gradient"><i class="bi bi-send me-2"></i>Send Reply</button>
                            <a href="mailto:{{ $message->email }}?subject={{ urlencode($replySubjectDefault) }}" class="btn btn-admin-soft"><i class="bi bi-envelope me-2"></i>Open In Email Client</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="admin-card h-100">
                <div class="admin-card__header"><h3 class="admin-card__title">Preview — How It Looks In Gmail</h3></div>
                <div class="admin-card__body">
                    <div class="gmail-preview">
                        <div class="gmail-preview__toolbar">
                            <span class="gmail-preview__dot gmail-preview__dot--red"></span>
                            <span class="gmail-preview__dot gmail-preview__dot--yellow"></span>
                            <span class="gmail-preview__dot gmail-preview__dot--green"></span>
                            <span class="gmail-preview__tab">Primary</span>
                        </div>
                        <div class="gmail-preview__subject" id="replyPreviewSubject">{{ $replySubjectDefault }}</div>
                        <div class="gmail-preview__meta">
                            <div class="gmail-preview__avatar">{{ \Illuminate\Support\Str::substr(\App\Models\SiteSetting::current()->company_name, 0, 1) }}</div>
                            <div class="gmail-preview__meta-text">
                                <div class="gmail-preview__from"><strong>{{ \App\Models\SiteSetting::current()->company_name }}</strong></div>
                                <div class="gmail-preview__to">to {{ $message->name }}</div>
                            </div>
                        </div>
                        <div class="gmail-preview__body" id="replyPreviewBody"></div>
                        <div id="replyPreviewAttachments" class="gmail-preview__attachments"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var subjectInput = document.getElementById('replySubject');
        var bodyTextarea = document.getElementById('adminReplyBody');
        var fileInput = document.getElementById('replyAttachments');
        var attachmentsListEl = document.getElementById('attachmentsList');
        var previewSubject = document.getElementById('replyPreviewSubject');
        var previewBody = document.getElementById('replyPreviewBody');
        var previewAttachments = document.getElementById('replyPreviewAttachments');
        if (!subjectInput || !bodyTextarea) { return; }

        function formatSize(bytes) {
            if (bytes < 1024) { return bytes + ' B'; }
            if (bytes < 1024 * 1024) { return (bytes / 1024).toFixed(0) + ' KB'; }
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function renderAttachments() {
            var files = fileInput.files ? Array.from(fileInput.files) : [];
            attachmentsListEl.innerHTML = files.map(function (f) {
                return '<div class="small text-muted"><i class="bi bi-paperclip me-1"></i>' + f.name + ' (' + formatSize(f.size) + ')</div>';
            }).join('');

            if (previewAttachments) {
                previewAttachments.innerHTML = files.map(function (f) {
                    return '<span class="gmail-preview__attachment"><i class="bi bi-paperclip"></i>' + f.name + '</span>';
                }).join('');
            }
        }

        var lastBody = null;
        function render() {
            previewSubject.textContent = subjectInput.value || '(no subject)';
            if (bodyTextarea.value !== lastBody) {
                lastBody = bodyTextarea.value;
                previewBody.innerHTML = bodyTextarea.value || '<p style="color:#9AA0A6;">Nothing to preview yet.</p>';
            }
        }

        subjectInput.addEventListener('input', render);
        if (fileInput) { fileInput.addEventListener('change', renderAttachments); }
        // Quill syncs into the textarea without firing native input events,
        // so poll for changes rather than relying on an event that never fires.
        setInterval(render, 300);
        render();
    })();
</script>
@endpush
