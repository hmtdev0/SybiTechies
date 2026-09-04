@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi bi-grid-1x2"></i> What We Do';
    $pageHeaderTitle = 'Our Services';
    $pageHeaderSubtitle = 'Services that cover your entire digital journey — from idea to launch and beyond.';
    $pageHeaderCrumbs = ['Services'];
@endphp
@include('partials.page-header')

<section class="services-index section-pad">
    <div class="container">
        @if($services->isEmpty())
            <div class="text-center py-5" data-aos="fade-up">
                <i class="bi bi-grid-1x2 display-3 text-muted"></i>
                <p class="text-muted mt-3 mb-0">No services found.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($services as $i => $service)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                    <div class="service-card">
                        <div class="service-card__icon"><i class="bi {{ $service->icon }}"></i></div>
                        <h4 class="service-card__title">{{ $service->title }}</h4>
                        <p class="service-card__desc">{{ $service->short_description }}</p>
                        <a href="{{ route('services.show', $service) }}" class="service-card__link">Learn More <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection
