@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi bi-question-circle"></i> FAQs';
    $pageHeaderTitle = 'Frequently Asked Questions';
    $pageHeaderSubtitle = 'Answers to the things people usually ask before working with us.';
    $pageHeaderCrumbs = ['FAQ'];
@endphp
@include('partials.page-header')

<section class="faq-page section-pad">
    <div class="container">
        @if($faqs->isEmpty())
            <div class="text-center py-5" data-aos="fade-up">
                <i class="bi bi-question-circle display-3 text-muted"></i>
                <p class="text-muted mt-3 mb-0">No FAQs published yet.</p>
            </div>
        @else
            <div class="contact-faq__wrap" data-aos="fade-up">
                <div class="accordion contact-accordion" id="pageFaqAccordion">
                    @foreach($faqs as $i => $faq)
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="pageFaqHeading{{ $i }}">
                                <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#pageFaqCollapse{{ $i }}"
                                    aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="pageFaqCollapse{{ $i }}">
                                    {{ $faq->question }}
                                </button>
                            </h3>
                            <div id="pageFaqCollapse{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                aria-labelledby="pageFaqHeading{{ $i }}" data-bs-parent="#pageFaqAccordion">
                                <div class="accordion-body">{{ $faq->answer }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

{{-- ======================================================= --}}
{{-- CTA                                                      --}}
{{-- ======================================================= --}}
<section class="cta">
    <div class="container">
        <div class="cta__inner" data-aos="zoom-in">
            <span class="cta__blob cta__blob--1"></span>
            <span class="cta__blob cta__blob--2"></span>
            <div class="cta__content">
                <h2 class="cta__title">Still Have <span class="text-gradient-light">Questions?</span></h2>
                <p class="cta__desc">Can't find what you're looking for? Our team is happy to help.</p>
                <div class="cta__actions">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg btn-ripple">Get In Touch <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
