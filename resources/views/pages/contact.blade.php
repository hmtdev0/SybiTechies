@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi bi-envelope-paper"></i> Contact Us';
    $pageHeaderTitle = 'Let\'s Start a Conversation';
    $pageHeaderSubtitle = 'Whether you have a project in mind or just a question — our team usually replies within one business day.';
    $pageHeaderCrumbs = ['Contact'];
    $pageHeaderOrbs = true;
    $pageHeaderChips = [
        ['icon' => 'bi-lightning-charge-fill', 'text' => 'Replies within 1 business day'],
        ['icon' => 'bi-chat-dots-fill', 'text' => 'Free consultation'],
        ['icon' => 'bi-shield-check', 'text' => 'NDA on request'],
    ];
@endphp
@include('partials.page-header')

@include('partials.contact-section', [
    'contactAnchorId' => 'contact-form',
    'contactEyebrow' => 'Get In Touch',
    'contactTitle' => 'Send Us a <span class="text-gradient">Message</span>',
    'contactLead' => 'Fill in the form below or reach out directly — every message reaches a real person on our team.',
])

{{-- ======================================================= --}}
{{-- MAP                                                      --}}
{{-- ======================================================= --}}
<section class="contact-map section-pad">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-geo-alt"></i> Find Us</span>
            <h2 class="section-title">Visit Our <span class="text-gradient">Office</span></h2>
            <p class="section-lead mx-auto">Drop by, or get directions straight from the map below.</p>
        </div>

        <div class="contact-map__card" data-aos="zoom-in">
            @if($siteSettings->google_maps_embed)
                {!! $siteSettings->google_maps_embed !!}
            @else
                <iframe
                    src="https://www.google.com/maps?q={{ urlencode($siteSettings->address ?: $siteSettings->company_name) }}&output=embed"
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="{{ $siteSettings->company_name }} office location"></iframe>
            @endif
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- FAQ                                                      --}}
{{-- ======================================================= --}}
@php
    $contactFaqs = [
        ['q' => 'How fast do you reply?', 'a' => "We aim to respond to every enquiry within one business day — most of the time it's a lot sooner. If your project is urgent, mention it in your message and we'll prioritize it."],
        ['q' => 'Do you sign NDAs?', 'a' => "Absolutely. We're happy to sign a mutual NDA before discussing any sensitive details of your project — just let us know when you reach out."],
        ['q' => "What's your typical project process?", 'a' => 'We start with a discovery call, follow up with a scoped proposal and timeline, then move through design, development, testing and launch in clear, milestone-based sprints.'],
        ['q' => 'Do you work with startups or only enterprises?', 'a' => "Both. Our team is built to flex — from lean MVPs for early-stage startups to complex, multi-department systems for established enterprises."],
        ['q' => 'What information should I include in my message?', 'a' => "A short description of what you're building, your rough timeline, and any budget range you have in mind. The more context you give us, the faster we can respond with something useful."],
    ];
@endphp
<section class="contact-faq section-pad">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-question-circle"></i> FAQs</span>
            <h2 class="section-title">Questions Before You <span class="text-gradient">Reach Out?</span></h2>
            <p class="section-lead mx-auto">A few things people usually ask before getting in touch.</p>
        </div>

        <div class="contact-faq__wrap" data-aos="fade-up">
            <div class="accordion contact-accordion" id="contactFaqAccordion">
                @foreach($contactFaqs as $i => $faq)
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="faqHeading{{ $i }}">
                            <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $i }}"
                                aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="faqCollapse{{ $i }}">
                                {{ $faq['q'] }}
                            </button>
                        </h3>
                        <div id="faqCollapse{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                            aria-labelledby="faqHeading{{ $i }}" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body">{{ $faq['a'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection
