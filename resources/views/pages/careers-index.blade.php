@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi bi-person-workspace"></i> Careers';
    $pageHeaderTitle = 'Build The Future With Us';
    $pageHeaderSubtitle = 'We\'re a small, senior team that cares about craft. If that sounds like your kind of place, take a look at what\'s open.';
    $pageHeaderCrumbs = ['Careers'];
    $pageHeaderOrbs = true;
    $pageHeaderChips = [
        ['icon' => 'bi-laptop', 'text' => 'Remote-friendly'],
        ['icon' => 'bi-graph-up-arrow', 'text' => 'Real growth, real ownership'],
        ['icon' => 'bi-people', 'text' => 'Small, senior team'],
    ];
@endphp
@include('partials.page-header')

{{-- ======================================================= --}}
{{-- WHY WORK HERE                                            --}}
{{-- ======================================================= --}}
<section class="why-us section-pad" id="why-work-here">
    <div class="why-us__bg"></div>
    <div class="container position-relative">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-stars"></i> Life Here</span>
            <h2 class="section-title text-white">Why People <span class="text-gradient-light">Stay</span></h2>
            <p class="section-lead mx-auto text-white-70">A few things that make working here different.</p>
        </div>

        <div class="row g-4">
            @php
                $perks = [
                    ['icon' => 'bi-laptop', 'title' => 'Remote-Friendly', 'description' => 'Work from wherever you do your best work — we care about output, not office hours.'],
                    ['icon' => 'bi-graph-up-arrow', 'title' => 'Real Growth', 'description' => 'Small team, real ownership — your decisions ship and your impact is visible.'],
                    ['icon' => 'bi-mortarboard', 'title' => 'Learning Culture', 'description' => 'Time and support to pick up new tools, frameworks and skills on the job.'],
                    ['icon' => 'bi-emoji-smile', 'title' => 'Sane Pace', 'description' => 'Realistic timelines and a team that respects your time outside of work.'],
                ];
            @endphp
            @foreach($perks as $i => $perk)
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="{{ ($i % 4) * 80 }}">
                <div class="why-card">
                    <div class="why-card__icon"><i class="bi {{ $perk['icon'] }}"></i></div>
                    <h5 class="why-card__title">{{ $perk['title'] }}</h5>
                    <p class="why-card__desc">{{ $perk['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- OPEN POSITIONS                                           --}}
{{-- ======================================================= --}}
<section class="jobs-list section-pad">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-briefcase"></i> Open Positions</span>
            <h2 class="section-title">Current <span class="text-gradient">Openings</span></h2>
            <p class="section-lead mx-auto">No match today? Send your resume anyway — we're always happy to hear from good people.</p>
        </div>

        @if($jobOpenings->isEmpty())
            <div class="text-center py-5" data-aos="fade-up">
                <i class="bi bi-briefcase display-3 text-muted"></i>
                <p class="text-muted mt-3 mb-1">No open positions right now — check back soon.</p>
                @if($siteSettings->email)
                    <p class="text-muted mb-0">Or reach out directly at <a href="mailto:{{ $siteSettings->email }}">{{ $siteSettings->email }}</a>.</p>
                @endif
            </div>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach($jobOpenings as $i => $job)
                <a href="{{ route('careers.show', $job) }}" class="job-card" data-aos="fade-up" data-aos-delay="{{ ($i % 4) * 70 }}">
                    <div>
                        <h4 class="job-card__title">{{ $job->title }}</h4>
                        <div class="job-card__meta">
                            @if($job->department)<span><i class="bi bi-diagram-3"></i>{{ $job->department }}</span>@endif
                            @if($job->location)<span><i class="bi bi-geo-alt"></i>{{ $job->location }}</span>@endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="job-card__type">{{ $job->type }}</span>
                        <i class="bi bi-arrow-right job-card__arrow"></i>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection
