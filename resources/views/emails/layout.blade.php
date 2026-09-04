@php
    $siteSettings = \App\Models\SiteSetting::current();
    $footerNote ??= "This is an automated message — please don't reply directly to this email.";
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteSettings->company_name }}</title>
</head>
<body style="margin:0; padding:0; background-color:#F1F5F9; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F1F5F9; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 6px 24px rgba(15,23,42,.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#2563EB 0%,#7C3AED 100%); padding:28px 32px;">
                            <span style="font-family:Georgia, 'Times New Roman', serif; font-size:20px; font-weight:700; color:#ffffff; letter-spacing:.02em;">
                                {{ $siteSettings->company_name }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px; color:#334155; font-size:15px; line-height:1.7;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px 28px; border-top:1px solid #E2E8F0; color:#94A3B8; font-size:12.5px; line-height:1.6;">
                            <p style="margin:0 0 6px;">{{ $siteSettings->company_name }}@if($siteSettings->address) &middot; {{ $siteSettings->address }}@endif</p>
                            <p style="margin:0;">{{ $footerNote }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
