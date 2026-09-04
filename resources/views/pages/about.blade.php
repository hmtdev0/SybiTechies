@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi bi-people"></i> About Us';
    $pageHeaderTitle = 'The Team Behind '.$siteSettings->company_name;
    $pageHeaderSubtitle = 'Engineers, designers and product thinkers building software that businesses actually rely on.';
    $pageHeaderCrumbs = ['About'];

    $whyWorkWithUs = [
        ['icon' => 'bi-person-check-fill', 'title' => 'Senior-Only Team', 'description' => 'Every engagement is staffed with experienced engineers — no junior hand-offs, no learning curve on your budget.'],
        ['icon' => 'bi-eye-fill', 'title' => 'Transparent Process', 'description' => 'Regular updates and honest timelines mean you always know exactly where your project stands.'],
        ['icon' => 'bi-life-preserver', 'title' => 'Post-Launch Support', 'description' => 'We stay involved after launch with a dedicated support window and ongoing maintenance options.'],
        ['icon' => 'bi-clock-history', 'title' => 'On-Time Delivery', 'description' => 'Realistic milestones and dependable delivery — we plan around reality, not best-case guesses.'],
    ];
@endphp
@include('partials.page-header')

{{-- ======================================================= --}}
{{-- STORY / MISSION / VISION                                --}}
{{-- ======================================================= --}}
<section class="about section-pad" id="story">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="about__visual">
                    <div class="about__img-main">
                        <img src="{{ $about->image ? asset($about->image) : asset('assets/images/about/team-office.jpg') }}" alt="{{ $siteSettings->company_name }} team collaborating" loading="lazy">
                    </div>
                    @if($aboutStats->firstWhere('label', 'Years'))
                        @php $yearsStat = $aboutStats->firstWhere('label', 'Years'); @endphp
                        <div class="about__experience-badge" data-float>
                            <span class="about__experience-num">{{ $yearsStat->number }}{{ $yearsStat->suffix }}</span>
                            <span class="about__experience-text">Years of Engineering Excellence</span>
                        </div>
                    @endif
                    <span class="about__deco about__deco--1"></span>
                    <span class="about__deco about__deco--2"></span>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-eyebrow"><i class="bi bi-bookmark-star"></i> Our Story</span>
                <h2 class="section-title">{{ $about->story_title ?: 'How It All Started' }}</h2>
                <p class="section-lead">{{ $about->story_text }}</p>

                <div class="about__pillars">
                    @if($about->mission_title)
                        <div class="about__pillar">
                            <div class="about__pillar-icon"><i class="bi bi-bullseye"></i></div>
                            <div>
                                <h5>{{ $about->mission_title }}</h5>
                                <p>{{ $about->mission_text }}</p>
                            </div>
                        </div>
                    @endif
                    @if($about->vision_title)
                        <div class="about__pillar">
                            <div class="about__pillar-icon"><i class="bi bi-eye"></i></div>
                            <div>
                                <h5>{{ $about->vision_title }}</h5>
                                <p>{{ $about->vision_text }}</p>
                            </div>
                        </div>
                    @endif
                    @foreach($aboutFeatures as $feature)
                        <div class="about__pillar">
                            <div class="about__pillar-icon"><i class="bi {{ $feature->icon }}"></i></div>
                            <div>
                                <h5>{{ $feature->title }}</h5>
                                <p>{{ $feature->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="about-stats">
                    @foreach($aboutStats as $i => $stat)
                        <div class="about-stat-card" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                            <div class="about-stat-card__icon"><i class="bi {{ $stat->icon }}"></i></div>
                            <span class="about-stat-card__num counter" data-count="{{ $stat->number }}" data-suffix="{{ $stat->suffix }}">0</span>
                            <small class="about-stat-card__label">{{ $stat->label }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@if($timeline->isNotEmpty())
{{-- ======================================================= --}}
{{-- OUR JOURNEY / TIMELINE                                   --}}
{{-- ======================================================= --}}
<section class="journey section-pad" id="journey">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-signpost-split"></i> Our Journey</span>
            <h2 class="section-title">Milestones That <span class="text-gradient">Shaped Us</span></h2>
            <p class="section-lead mx-auto">A quick look at the path that brought us to where we are today.</p>
        </div>

        <div class="timeline">
            {{-- Reveal/progress animation here is scroll-driven (assets/js/timeline.js),
                 not AOS — it must reverse on scroll-up, which AOS's once:true can't do. --}}
            @foreach($timeline as $i => $item)
                <div class="timeline__item {{ $i % 2 === 0 ? 'timeline__item--left' : 'timeline__item--right' }}">
                    <div class="timeline__dot"></div>
                    <div class="timeline__card">
                        <span class="timeline__year">{{ $item->year }}</span>
                        <h5 class="timeline__title">{{ $item->title }}</h5>
                        <p class="timeline__desc">{{ $item->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($team->isNotEmpty())
{{-- ======================================================= --}}
{{-- TEAM                                                     --}}
{{-- ======================================================= --}}
<section class="team section-pad" id="team">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-person-badge"></i> Meet The Team</span>
            <h2 class="section-title">The People <span class="text-gradient">Behind The Work</span></h2>
            <p class="section-lead mx-auto">A small, senior team — no bloated bureaucracy, just people who ship.</p>
        </div>

        <div class="row g-4">
            @foreach($team as $i => $member)
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($i % 4) * 90 }}">
                <div class="team-card">
                    <div class="team-card__avatar-wrap">
                        <div class="team-card__photo">
                            @if($member->photo)
                                <img src="{{ asset($member->photo) }}" alt="{{ $member->name }}" loading="lazy">
                            @else
                                <div class="team-card__avatar" aria-hidden="true">{{ $member->initials() }}</div>
                            @endif
                            <div class="team-card__social">
                                @if($member->facebook_url)<a href="{{ $member->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>@endif
                                @if($member->twitter_url)<a href="{{ $member->twitter_url }}" target="_blank" rel="noopener" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>@endif
                                @if($member->linkedin_url)<a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>@endif
                                @if($member->github_url)<a href="{{ $member->github_url }}" target="_blank" rel="noopener" aria-label="GitHub"><i class="bi bi-github"></i></a>@endif
                            </div>
                        </div>
                    </div>
                    <h5 class="team-card__name">{{ $member->name }}</h5>
                    <span class="team-card__role">{{ $member->position }}</span>
                    @if($member->bio)
                        <p class="team-card__bio">{{ $member->bio }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ======================================================= --}}
{{-- WHY WORK WITH US                                         --}}
{{-- ======================================================= --}}
<section class="why-us section-pad" id="why-work-with-us">
    <div class="why-us__bg"></div>
    <div class="container position-relative">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-patch-check"></i> Why Work With Us</span>
            <h2 class="section-title text-white">Reasons Clients <span class="text-gradient-light">Stay With Us</span></h2>
            <p class="section-lead mx-auto text-white-70">A few things that consistently set our engagements apart.</p>
        </div>

        <div class="row g-4">
            @foreach($whyWorkWithUs as $i => $item)
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="{{ ($i % 4) * 80 }}">
                <div class="why-card">
                    <div class="why-card__icon"><i class="bi {{ $item['icon'] }}"></i></div>
                    <h5 class="why-card__title">{{ $item['title'] }}</h5>
                    <p class="why-card__desc">{{ $item['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
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
                <h2 class="cta__title">Like What You've <span class="text-gradient-light">Seen So Far?</span></h2>
                <p class="cta__desc">Let's talk about how we can bring the same level of care to your next project.</p>
                <div class="cta__actions">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg btn-ripple">Get In Touch <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/timeline.js') }}?v={{ filemtime(public_path('assets/js/timeline.js')) }}"></script>
@endpush
