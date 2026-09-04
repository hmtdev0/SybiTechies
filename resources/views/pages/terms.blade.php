@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi bi-file-earmark-text-fill"></i> Legal';
    $pageHeaderTitle = 'Terms & Conditions';
    $pageHeaderSubtitle = 'The terms that govern your use of our website and services.';
    $pageHeaderCrumbs = ['Terms & Conditions'];
    $pageHeaderPill = 'Last updated: '.now()->format('F j, Y');
@endphp
@include('partials.page-header')

@php
    $legalIntro = 'These Terms & Conditions ("Terms") govern your access to and use of the '.$siteSettings->company_name
        .' website and services. By using our website or engaging us for a project, you agree to be bound '
        .'by these Terms. If you do not agree, please do not use our website or services.';

    $legalSections = [
        [
            'id' => 'services-provided',
            'icon' => 'bi-stack',
            'title' => 'Services Provided',
            'blocks' => [
                ['type' => 'p', 'text' => $siteSettings->company_name.' provides software development and related digital services, including but not limited to web applications, mobile apps, desktop software, and consulting. The exact scope, deliverables, timeline and cost of any engagement are defined in a separate proposal or agreement between us and the client.'],
            ],
        ],
        [
            'id' => 'use-of-our-website',
            'icon' => 'bi-window',
            'title' => 'Use of Our Website',
            'blocks' => [
                ['type' => 'ul', 'items' => [
                    'You agree to use our website only for lawful purposes and in a way that does not infringe the rights of others',
                    'You will not attempt to gain unauthorized access to our systems or interfere with the website\'s operation',
                    'Content submitted through our contact or newsletter forms must be accurate and not misleading',
                ]],
            ],
        ],
        [
            'id' => 'intellectual-property',
            'icon' => 'bi-c-circle-fill',
            'title' => 'Intellectual Property',
            'blocks' => [
                ['type' => 'p', 'text' => 'Unless otherwise agreed in writing, all content on this website — including text, graphics, logos and code — is the property of '.$siteSettings->company_name.' and protected by applicable intellectual property laws. Deliverables created for a specific client engagement are governed by the terms of that project\'s agreement, which will specify ownership and licensing.'],
            ],
        ],
        [
            'id' => 'client-responsibilities',
            'icon' => 'bi-clipboard2-check-fill',
            'title' => 'Client Responsibilities',
            'blocks' => [
                ['type' => 'p', 'text' => 'Clients engaging our services agree to provide timely feedback, accurate project requirements, and any necessary access or materials needed for us to deliver the agreed work. Delays caused by missing information or approvals may affect project timelines.'],
            ],
        ],
        [
            'id' => 'payment-terms',
            'icon' => 'bi-credit-card-fill',
            'title' => 'Payment Terms',
            'blocks' => [
                ['type' => 'p', 'text' => 'Payment schedules, milestones and methods are agreed upon per project and outlined in the relevant proposal or contract. Late payments may result in a pause of ongoing work until outstanding amounts are settled.'],
            ],
        ],
        [
            'id' => 'limitation-of-liability',
            'icon' => 'bi-exclamation-octagon-fill',
            'title' => 'Limitation of Liability',
            'blocks' => [
                ['type' => 'p', 'text' => 'To the fullest extent permitted by law, '.$siteSettings->company_name.' shall not be liable for any indirect, incidental or consequential damages arising from the use of our website or services. Our total liability for any claim related to a project shall not exceed the amount paid by the client for that specific engagement.'],
            ],
        ],
        [
            'id' => 'third-party-links',
            'icon' => 'bi-link-45deg',
            'title' => 'Third-Party Links',
            'blocks' => [
                ['type' => 'p', 'text' => 'Our website may contain links to third-party websites. We are not responsible for the content, accuracy or practices of any linked external sites.'],
            ],
        ],
        [
            'id' => 'termination',
            'icon' => 'bi-x-octagon-fill',
            'title' => 'Termination',
            'blocks' => [
                ['type' => 'p', 'text' => 'We reserve the right to suspend or terminate access to our website for any user who violates these Terms. Termination of a service engagement is governed by the terms outlined in the applicable project agreement.'],
            ],
        ],
        [
            'id' => 'governing-law',
            'icon' => 'bi-bank2',
            'title' => 'Governing Law',
            'blocks' => [
                ['type' => 'p', 'text' => 'These Terms are governed by and construed in accordance with applicable local laws, without regard to conflict-of-law principles. Any disputes arising from these Terms will be resolved through good-faith negotiation in the first instance.'],
            ],
        ],
        [
            'id' => 'changes-to-these-terms',
            'icon' => 'bi-arrow-repeat',
            'title' => 'Changes to These Terms',
            'blocks' => [
                ['type' => 'p', 'text' => 'We may revise these Terms from time to time. Continued use of our website after changes are posted constitutes acceptance of the updated Terms. The "Last updated" date above reflects the most recent revision.'],
            ],
        ],
        [
            'id' => 'contact-us',
            'icon' => 'bi-envelope-paper-fill',
            'title' => 'Contact Us',
            'cta' => true,
            'blocks' => [
                ['type' => 'p', 'text' => 'If you have any questions about these Terms & Conditions, please contact us:'],
            ],
        ],
    ];
@endphp
@include('partials.legal-page')

@endsection
