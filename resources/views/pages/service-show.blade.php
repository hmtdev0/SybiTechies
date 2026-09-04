@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi '.$service->icon.'"></i> Our Services';
    $pageHeaderTitle = $service->title;
    $pageHeaderSubtitle = $service->short_description;
    $pageHeaderCrumbs = ['Services', $service->title];
@endphp
@include('partials.page-header')

<section class="service-detail section-pad">
    <div class="container">
        <div class="row g-5">
            {{-- Main content --}}
            <div class="col-lg-8" data-aos="fade-right">
                <div class="detail-icon"><i class="bi {{ $service->icon }}"></i></div>

                <h2 class="detail-heading">Overview</h2>
                <div class="detail-copy">
                    {!! nl2br(e($service->full_description ?: $service->short_description)) !!}
                </div>

                @if($service->features->isNotEmpty())
                    <h2 class="detail-heading mt-5">What's Included</h2>
                    <ul class="detail-features">
                        @foreach($service->features as $feature)
                            <li><i class="bi bi-check2-circle"></i> {{ $feature->feature_text }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-left">
                <div class="detail-sidebar-card detail-sidebar-card--cta">
                    <i class="bi bi-chat-dots-fill detail-sidebar-card__icon"></i>
                    <h5>Need This Service?</h5>
                    <p>Tell us about your project and get a free, no-obligation consultation.</p>
                    <a href="{{ route('contact') }}" class="btn btn-gradient w-100 btn-ripple">Get Free Quote <i class="bi bi-arrow-right ms-2"></i></a>
                    @if($siteSettings->phone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings->phone) }}" class="detail-sidebar-card__phone"><i class="bi bi-telephone-fill me-2"></i>{{ $siteSettings->phone }}</a>
                    @endif
                </div>

                @if($otherServices->isNotEmpty())
                    <div class="detail-sidebar-card">
                        <h5>Other Services</h5>
                        <ul class="detail-sidebar-list">
                            @foreach($otherServices as $other)
                                <li>
                                    <a href="{{ route('services.show', $other) }}">
                                        <i class="bi {{ $other->icon }}"></i> {{ $other->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
