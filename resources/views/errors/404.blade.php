@extends('layouts.frontend')

@section('content')

<section class="error-404">
    <span class="page-header__orb page-header__orb--1"></span>
    <span class="page-header__orb page-header__orb--2"></span>
    <span class="page-header__orb page-header__orb--3"></span>

    <div class="container position-relative text-center">
        <span class="error-404__code text-gradient" data-aos="zoom-in">404</span>
        <h1 class="error-404__title" data-aos="fade-up">Page Not Found</h1>
        <p class="error-404__desc" data-aos="fade-up" data-aos-delay="80">
            The page you're looking for doesn't exist, may have moved, or the link might be broken.
        </p>

        <div class="error-404__actions" data-aos="fade-up" data-aos-delay="160">
            <a href="{{ route('home') }}" class="btn btn-gradient btn-lg btn-ripple">
                <i class="bi bi-house-door me-2"></i>Back to Home
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-dark-soft btn-lg">
                <i class="bi bi-envelope me-2"></i>Contact Us
            </a>
        </div>

        <div class="error-404__links" data-aos="fade-up" data-aos-delay="220">
            <a href="{{ route('services.index') }}"><i class="bi bi-grid-1x2"></i> Services</a>
            <a href="{{ route('projects.index') }}"><i class="bi bi-briefcase"></i> Projects</a>
            <a href="{{ route('about') }}"><i class="bi bi-people"></i> About</a>
            <a href="{{ route('faq') }}"><i class="bi bi-question-circle"></i> FAQ</a>
        </div>
    </div>
</section>

@endsection
