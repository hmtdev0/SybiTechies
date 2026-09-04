@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi bi-shield-lock-fill"></i> Legal';
    $pageHeaderTitle = 'Privacy Policy';
    $pageHeaderSubtitle = 'How '.$siteSettings->company_name.' collects, uses and protects your information.';
    $pageHeaderCrumbs = ['Privacy Policy'];
    $pageHeaderPill = 'Last updated: '.now()->format('F j, Y');
@endphp
@include('partials.page-header')

@php
    $legalIntro = $siteSettings->company_name.' ("we", "us" or "our") respects your privacy and is committed '
        .'to protecting the personal information you share with us. This Privacy Policy explains what '
        .'information we collect, how we use it, and the choices you have — whether you\'re browsing our '
        .'website, submitting an enquiry, or working with us on a project.';

    $legalSections = [
        [
            'id' => 'information-we-collect',
            'icon' => 'bi-clipboard2-data-fill',
            'title' => 'Information We Collect',
            'blocks' => [
                ['type' => 'p', 'text' => 'We collect information you provide directly to us, such as when you fill out our contact form, subscribe to our newsletter, or communicate with us by email or phone. This may include:'],
                ['type' => 'ul', 'items' => [
                    'Your name, email address and phone number',
                    'Your company name and the services you\'re interested in',
                    'Any details you choose to share in your message',
                ]],
                ['type' => 'p', 'text' => 'We also automatically collect limited technical information — such as browser type, device type and pages visited — to help us understand how our website is used and to improve it.'],
            ],
        ],
        [
            'id' => 'how-we-use-your-information',
            'icon' => 'bi-gear-wide-connected',
            'title' => 'How We Use Your Information',
            'blocks' => [
                ['type' => 'ul', 'items' => [
                    'To respond to your enquiries and provide the services you request',
                    'To send project updates, quotes or proposals related to your request',
                    'To send newsletters or updates you\'ve opted in to receive',
                    'To improve our website, services and customer experience',
                    'To comply with legal obligations where applicable',
                ]],
                ['type' => 'p', 'text' => 'We do not sell, rent or trade your personal information to third parties for marketing purposes.'],
            ],
        ],
        [
            'id' => 'cookies-tracking',
            'icon' => 'bi-cookie',
            'title' => 'Cookies & Tracking',
            'blocks' => [
                ['type' => 'p', 'text' => 'Our website may use cookies and similar technologies to remember your preferences and understand how visitors interact with our site. You can control or disable cookies through your browser settings; doing so may affect some site functionality.'],
            ],
        ],
        [
            'id' => 'sharing-your-information',
            'icon' => 'bi-share-fill',
            'title' => 'Sharing Your Information',
            'blocks' => [
                ['type' => 'p', 'text' => 'We may share information with trusted third-party service providers who help us operate our website and deliver our services (for example, hosting or email delivery providers), and only to the extent necessary for them to perform those services. We may also disclose information if required to do so by law.'],
            ],
        ],
        [
            'id' => 'data-security',
            'icon' => 'bi-shield-lock-fill',
            'title' => 'Data Security',
            'blocks' => [
                ['type' => 'p', 'text' => 'We take reasonable technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure or destruction. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.'],
            ],
        ],
        [
            'id' => 'your-rights',
            'icon' => 'bi-person-check-fill',
            'title' => 'Your Rights',
            'blocks' => [
                ['type' => 'p', 'text' => 'Depending on your location, you may have the right to access, correct, update or request deletion of your personal information. To exercise any of these rights, please contact us using the details below.'],
            ],
        ],
        [
            'id' => 'childrens-privacy',
            'icon' => 'bi-emoji-smile-fill',
            'title' => 'Children\'s Privacy',
            'blocks' => [
                ['type' => 'p', 'text' => 'Our website and services are not directed at individuals under the age of 16, and we do not knowingly collect personal information from children.'],
            ],
        ],
        [
            'id' => 'changes-to-this-policy',
            'icon' => 'bi-arrow-repeat',
            'title' => 'Changes to This Policy',
            'blocks' => [
                ['type' => 'p', 'text' => 'We may update this Privacy Policy from time to time to reflect changes in our practices or for legal, operational or regulatory reasons. The "Last updated" date at the top of this page indicates when it was last revised.'],
            ],
        ],
        [
            'id' => 'contact-us',
            'icon' => 'bi-envelope-paper-fill',
            'title' => 'Contact Us',
            'cta' => true,
            'blocks' => [
                ['type' => 'p', 'text' => 'If you have any questions about this Privacy Policy or how we handle your information, please reach out to us:'],
            ],
        ],
    ];
@endphp
@include('partials.legal-page')

@endsection
