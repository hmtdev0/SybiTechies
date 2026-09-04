@extends('emails.layout')

@section('content')
    <p style="margin:0 0 16px;">Hi {{ $userName }},</p>
    <p style="margin:0 0 16px;">We received a request to reset the password for your SysbiTechies admin account. Click the button below to choose a new password. This link expires in 60 minutes.</p>
    <p style="text-align:center;margin:28px 0;">
        <a href="{{ $resetUrl }}" style="display:inline-block;background:linear-gradient(135deg,#2563EB 0%,#7C3AED 100%);color:#ffffff;text-decoration:none;padding:13px 32px;border-radius:8px;font-weight:600;font-size:15px;">Reset Password</a>
    </p>
    <p style="margin:0 0 8px;">If the button doesn't work, copy and paste this link into your browser:</p>
    <p style="margin:0 0 16px;word-break:break-all;"><a href="{{ $resetUrl }}" style="color:#2563EB;">{{ $resetUrl }}</a></p>
    <p style="margin:0;">If you didn't request this, you can safely ignore this email — your password won't change.</p>
@endsection
