@extends('layouts.admin')

@section('title', 'Email Settings')

@php
    $siteSettings = \App\Models\SiteSetting::current();
    $exampleDomain = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::slug($siteSettings->company_name));
    $exampleFromAddress = 'hello@'.$exampleDomain.'.com';
@endphp

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Email Settings</h2>
            <p class="text-muted mb-0">Configure outgoing email and the contact-form auto-reply.</p>
        </div>
    </div>

    <ul class="nav nav-pills gap-2 mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-smtp" type="button">SMTP Settings</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-autoreply" type="button">Auto-Reply Email</button>
        </li>
    </ul>

    <form action="{{ route('admin.email-settings.update') }}" method="POST" id="emailSettingsForm">
        @csrf
        @method('PUT')

        <div class="tab-content">

            {{-- =============== SMTP =============== --}}
            <div class="tab-pane fade show active" id="tab-smtp">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="admin-card mb-4">
                            <div class="admin-card__header"><h3 class="admin-card__title">SMTP Server</h3></div>
                            <div class="admin-card__body row g-3">
                                <div class="col-md-8">
                                    <label class="admin-form-label">SMTP Host</label>
                                    <input type="text" name="smtp_host" class="form-control" placeholder="e.g. smtp.gmail.com" value="{{ old('smtp_host', $settings->smtp_host) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="admin-form-label">Port</label>
                                    <input type="number" name="smtp_port" class="form-control" placeholder="587" value="{{ old('smtp_port', $settings->smtp_port) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-form-label">Username</label>
                                    <input type="text" name="smtp_username" class="form-control" value="{{ old('smtp_username', $settings->smtp_username) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                                    <input type="password" name="smtp_password" class="form-control" autocomplete="new-password" placeholder="{{ $settings->smtp_password ? '••••••••' : '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-form-label">Encryption</label>
                                    <select name="smtp_encryption" class="form-select">
                                        <option value="tls" @selected(old('smtp_encryption', $settings->smtp_encryption) === 'tls')>TLS</option>
                                        <option value="ssl" @selected(old('smtp_encryption', $settings->smtp_encryption) === 'ssl')>SSL</option>
                                        <option value="none" @selected(old('smtp_encryption', $settings->smtp_encryption) === 'none')>None</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-form-label">From Address</label>
                                    <input type="email" name="from_address" class="form-control" placeholder="{{ $exampleFromAddress }}" value="{{ old('from_address', $settings->from_address) }}">
                                </div>
                                <div class="col-12">
                                    <label class="admin-form-label">From Name</label>
                                    <input type="text" name="from_name" class="form-control" placeholder="{{ $siteSettings->company_name }}" value="{{ old('from_name', $settings->from_name) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="admin-card mb-4">
                            <div class="admin-card__header"><h3 class="admin-card__title">Email Sending</h3></div>
                            <div class="admin-card__body">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="email_enabled" value="1" id="emailEnabledSwitch" @checked(old('email_enabled', $settings->email_enabled))>
                                    <label class="form-check-label" for="emailEnabledSwitch">Enable email sending</label>
                                </div>
                                <p class="text-muted small mt-2 mb-0">
                                    When off, the contact form only saves messages to Contact Messages — no emails are sent at all.
                                </p>
                            </div>
                        </div>

                        <div class="admin-card">
                            <div class="admin-card__header"><h3 class="admin-card__title">Send Test Email</h3></div>
                            <div class="admin-card__body">
                                <p class="text-muted small">Save your SMTP settings first, then send yourself a test to confirm they work.</p>
                                <div class="input-group">
                                    <input type="email" form="sendTestForm" name="test_email" class="form-control" placeholder="you@example.com" value="{{ $siteSettings->email }}" required>
                                    <button type="submit" form="sendTestForm" class="btn btn-admin-soft"><i class="bi bi-send"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =============== AUTO-REPLY =============== --}}
            <div class="tab-pane fade" id="tab-autoreply">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="admin-card mb-4">
                            <div class="admin-card__header"><h3 class="admin-card__title">"Thank You" Auto-Reply</h3></div>
                            <div class="admin-card__body row g-3">
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="autoreply_enabled" value="1" id="autoreplyEnabledSwitch" @checked(old('autoreply_enabled', $settings->autoreply_enabled))>
                                        <label class="form-check-label" for="autoreplyEnabledSwitch">Send this email to visitors after they submit the contact form</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-info small mb-0">
                                        Available placeholders: <code>@{{name}}</code>, <code>@{{email}}</code>, <code>@{{company_name}}</code>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="admin-form-label">Subject</label>
                                    <input type="text" name="autoreply_subject" id="autoreplySubject" class="form-control" value="{{ old('autoreply_subject', $settings->autoreply_subject) }}">
                                </div>
                                <div class="col-12">
                                    <label class="admin-form-label">Body</label>
                                    <textarea name="autoreply_body" id="autoreplyBody" rows="10" class="form-control" data-quill-editor data-quill-upload-url="{{ route('admin.editor-uploads.store') }}">{{ old('autoreply_body', $settings->autoreply_body) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="admin-card">
                            <div class="admin-card__header"><h3 class="admin-card__title">Preview — How It Looks In Gmail</h3></div>
                            <div class="admin-card__body">
                                <div class="gmail-preview">
                                    <div class="gmail-preview__toolbar">
                                        <span class="gmail-preview__dot gmail-preview__dot--red"></span>
                                        <span class="gmail-preview__dot gmail-preview__dot--yellow"></span>
                                        <span class="gmail-preview__dot gmail-preview__dot--green"></span>
                                        <span class="gmail-preview__tab">Primary</span>
                                    </div>
                                    <div class="gmail-preview__subject" id="gmailPreviewSubject">Thank you for contacting us!</div>
                                    <div class="gmail-preview__meta">
                                        <div class="gmail-preview__avatar">{{ \Illuminate\Support\Str::substr($siteSettings->company_name, 0, 1) }}</div>
                                        <div class="gmail-preview__meta-text">
                                            <div class="gmail-preview__from"><strong>{{ $settings->from_name ?: $siteSettings->company_name }}</strong> <span>&lt;{{ $settings->from_address ?: $exampleFromAddress }}&gt;</span></div>
                                            <div class="gmail-preview__to">to John Doe</div>
                                        </div>
                                    </div>
                                    <div class="gmail-preview__body" id="gmailPreviewBody"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <button type="submit" class="btn btn-admin-gradient px-5 mt-4">
            <i class="bi bi-check2-circle me-2"></i>Save Email Settings
        </button>
    </form>

    <form action="{{ route('admin.email-settings.send-test') }}" method="POST" id="sendTestForm">
        @csrf
    </form>
@endsection

@push('scripts')
<script>
    (function () {
        var subjectInput = document.getElementById('autoreplySubject');
        var bodyTextarea = document.getElementById('autoreplyBody');
        var previewSubject = document.getElementById('gmailPreviewSubject');
        var previewBody = document.getElementById('gmailPreviewBody');
        if (!subjectInput || !bodyTextarea || !previewBody) { return; }

        var sample = { name: 'John Doe', email: 'john@example.com', company_name: '{{ addslashes($siteSettings->company_name) }}' };

        function fill(template) {
            return (template || '').replace(/\{\{(\w+)\}\}/g, function (match, key) {
                return Object.prototype.hasOwnProperty.call(sample, key) ? sample[key] : match;
            });
        }

        var lastBody = null;
        function render() {
            previewSubject.textContent = fill(subjectInput.value) || '(no subject)';
            if (bodyTextarea.value !== lastBody) {
                lastBody = bodyTextarea.value;
                previewBody.innerHTML = fill(bodyTextarea.value) || '<p style="color:#9AA0A6;">Nothing to preview yet.</p>';
            }
        }

        subjectInput.addEventListener('input', render);
        // Quill syncs into the textarea without firing native input events,
        // so poll for changes rather than relying on an event that never fires.
        setInterval(render, 300);
        render();
    })();
</script>
@endpush
