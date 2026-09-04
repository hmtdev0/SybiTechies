@extends('emails.layout')

@section('content')
    {!! $replyBodyHtml !!}

    <div style="margin-top:28px; padding:16px 18px; background:#F8FAFC; border-left:3px solid #CBD5E1; border-radius:8px; color:#64748B; font-size:13.5px; line-height:1.7;">
        <p style="margin:0 0 6px; font-weight:600; color:#475569;">Your original message:</p>
        <p style="margin:0; white-space:pre-line;">{{ $originalMessage }}</p>
    </div>
@endsection
