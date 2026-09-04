@extends('layouts.frontend')

@section('content')

@php
    // Highlight the configured sub-string within the hero title with the
    // brand gradient — mirrors the original hardcoded <span class="text-gradient">.
    $heroTitleHtml = e($hero->title);
    if ($hero->highlight_text) {
        $heroTitleHtml = str_replace(
            e($hero->highlight_text),
            '<span class="text-gradient">'.e($hero->highlight_text).'</span>',
            $heroTitleHtml
        );
    }
    $heroFloatingClasses = ['floating-card--projects', 'floating-card--clients', 'floating-card--exp'];
@endphp

{{-- ======================================================= --}}
{{-- HERO SECTION                                            --}}
{{-- ======================================================= --}}
<section class="hero" id="home">
    {{-- Animated gradient background + floating shapes --}}
    <div class="hero__bg">
        <span class="hero-blob hero-blob--1"></span>
        <span class="hero-blob hero-blob--2"></span>
        <span class="hero-blob hero-blob--3"></span>
        <span class="hero-grid"></span>
    </div>

    <div class="container">
        <div class="row align-items-center g-5">

            {{-- LEFT (text) --}}
            <div class="col-lg-6 hero__col-text" data-aos="fade-right">
                @if($hero->badge_text)
                    <span class="hero__badge">
                        <span class="hero__badge-dot"></span>
                        {{ $hero->badge_text }}
                    </span>
                @endif

                <h1 class="hero__title">{!! $heroTitleHtml !!}</h1>

                @if(!empty($hero->typed_words))
                    <p class="hero__typed-line">
                        We build
                        <span class="text-gradient typed-strong" id="typed-words"></span>
                    </p>
                @endif

                @if($hero->description)
                    <p class="hero__desc">{{ $hero->description }}</p>
                @endif

                <div class="hero__actions">
                    @if($hero->btn1_text)
                        <a href="{{ $hero->btn1_link ?: route('contact') }}" class="btn btn-gradient btn-lg btn-ripple">
                            {{ $hero->btn1_text }} <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    @endif
                    @if($hero->btn2_text)
                        <a href="{{ $hero->btn2_link ?: '#projects' }}" class="btn btn-outline-dark-soft btn-lg">
                            <i class="bi bi-play-circle me-2"></i>{{ $hero->btn2_text }}
                        </a>
                    @endif
                </div>

                <div class="hero__trust">
                    <div class="hero__avatars">
                        <img src="{{ asset('assets/images/testimonials/client-1.jpg') }}" alt="Client" loading="lazy">
                        <img src="{{ asset('assets/images/testimonials/client-2.jpg') }}" alt="Client" loading="lazy">
                        <img src="{{ asset('assets/images/testimonials/client-3.jpg') }}" alt="Client" loading="lazy">
                        <img src="{{ asset('assets/images/testimonials/client-4.jpg') }}" alt="Client" loading="lazy">
                    </div>
                    <div class="hero__trust-text">
                        <div class="hero__stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <span>Loved by <strong>100+</strong> happy clients worldwide</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT (visual) --}}
            <div class="col-lg-6 hero__col-visual" data-aos="fade-left" data-aos-delay="150">
                <div class="hero__visual" data-parallax>
                    <div class="hero__visual-glow"></div>

                    @if($hero->image)
                        <div class="hero__image-card">
                            <img src="{{ asset($hero->image) }}" alt="{{ $siteSettings->company_name }}" loading="eager">
                        </div>
                    @else
                        @include('partials.hero-illustration')
                    @endif

                    {{-- Floating info cards (driven by Home Page CMS > Hero > Floating Cards) --}}
                    @foreach($heroStats as $stat)
                        <div class="floating-card {{ $heroFloatingClasses[$loop->index] ?? '' }}" data-float>
                            <div class="floating-card__icon {{ ['bg-primary-soft', 'bg-accent-soft', 'bg-secondary-soft'][$loop->index % 3] }}">
                                <i class="bi {{ $stat->icon }}"></i>
                            </div>
                            <div>
                                <span class="floating-card__num" data-count="{{ $stat->number }}" data-suffix="{{ $stat->suffix }}">0</span>
                                <span class="floating-card__label">{{ $stat->label }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="floating-card floating-card--code" data-float>
                        <div class="code-dots"><span></span><span></span><span></span></div>
                        <pre class="code-snippet"><code>&lt;<span class="tk-tag">Sysbi</span> <span class="tk-attr">grow</span>=<span class="tk-str">"true"</span> /&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <a href="#clients" class="hero__scroll" aria-label="Scroll down">
        <span class="hero__scroll-mouse"><span></span></span>
    </a>
</section>


{{-- ======================================================= --}}
{{-- CLIENT LOGOS (Trusted Companies)                        --}}
{{-- ======================================================= --}}
<section class="clients" id="clients">
    <div class="container">
        <p class="clients__label" data-aos="fade-up">Trusted by forward-thinking businesses worldwide</p>
    </div>
    <div class="clients__marquee" data-aos="fade-up">
        <div class="clients__track">
            @foreach($trustedCompanies->concat($trustedCompanies) as $company)
                <div class="client-logo">
                    @if($company->logo)
                        <img src="{{ asset($company->logo) }}" alt="{{ $company->name }}" style="height:28px;width:auto;" loading="lazy">
                    @else
                        <i class="bi bi-hexagon"></i>
                    @endif
                    <span>{{ $company->name }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- ABOUT SECTION                                           --}}
{{-- ======================================================= --}}
<section class="about section-pad" id="about">
    <div class="container">
        <div class="row align-items-center g-5">

            {{-- Left: image / illustration --}}
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

            {{-- Right: content --}}
            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-eyebrow"><i class="bi bi-people"></i> About {{ $siteSettings->company_name }}</span>
                <h2 class="section-title">A Software House Engineered Around <span class="text-gradient">Your Business Goals</span></h2>
                <p class="section-lead">
                    We are a team of engineers, designers and product thinkers who turn complex business
                    challenges into elegant, scalable software. From startups to enterprises, we deliver
                    solutions that are secure, maintainable and built to last.
                </p>

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

                {{-- Counters --}}
                <div class="about__counters">
                    @foreach($aboutStats as $stat)
                        <div class="about__counter">
                            <span class="counter" data-count="{{ $stat->number }}" data-suffix="{{ $stat->suffix }}">0</span>
                            <small>{{ $stat->label }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- SERVICES SECTION                                        --}}
{{-- ======================================================= --}}
<section class="services section-pad" id="services">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-grid-1x2"></i> What We Do</span>
            <h2 class="section-title">Services That Cover Your <span class="text-gradient">Entire Digital Journey</span></h2>
            <p class="section-lead mx-auto">From idea to launch and beyond — one team for every layer of your software.</p>
        </div>

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

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('services.index') }}" class="btn btn-outline-dark-soft btn-lg">
                View All Services <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- WHY CHOOSE US                                           --}}
{{-- ======================================================= --}}
<section class="why-us section-pad" id="why-us">
    <div class="why-us__bg"></div>
    <div class="container position-relative">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-patch-check"></i> Why Choose Us</span>
            <h2 class="section-title text-white">The Partner That Delivers <span class="text-gradient-light">Beyond Expectations</span></h2>
            <p class="section-lead mx-auto text-white-70">Engineering discipline, business thinking and a genuine commitment to your success.</p>
        </div>

        <div class="row g-4">
            @foreach($whyUsItems as $i => $item)
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="{{ ($i % 4) * 80 }}">
                <div class="why-card">
                    <div class="why-card__icon"><i class="bi {{ $item->icon }}"></i></div>
                    <h5 class="why-card__title">{{ $item->title }}</h5>
                    <p class="why-card__desc">{{ $item->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- DEVELOPMENT PROCESS                                     --}}
{{-- ======================================================= --}}
<section class="process section-pad" id="process">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-diagram-3"></i> How We Work</span>
            <h2 class="section-title">A Proven <span class="text-gradient">Development Process</span></h2>
            <p class="section-lead mx-auto">Seven clear steps that take your idea from concept to a thriving product.</p>
        </div>

        <div class="process__timeline">
            @foreach($processSteps as $i => $step)
            <div class="process-step" data-aos="fade-up" data-aos-delay="{{ ($i % 4) * 100 }}">
                <div class="process-step__connector"></div>
                <div class="process-step__num">{{ sprintf('%02d', $step->step_number ?: $i + 1) }}</div>
                <div class="process-step__icon"><i class="bi {{ $step->icon }}"></i></div>
                <h5 class="process-step__title">{{ $step->title }}</h5>
                <p class="process-step__desc">{{ $step->description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- TECHNOLOGIES WE USE                                     --}}
{{-- ======================================================= --}}
<section class="tech section-pad" id="tech">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-cpu"></i> Our Stack</span>
            <h2 class="section-title">Technologies We <span class="text-gradient">Build With</span></h2>
            <p class="section-lead mx-auto">A modern, battle-tested toolkit chosen for performance and longevity.</p>
        </div>

        <div class="tech__grid">
            @foreach($technologies as $i => $tech)
            <div class="tech-card" data-aos="zoom-in" data-aos-delay="{{ ($i % 6) * 60 }}" style="--tech-color: {{ $tech->color }}">
                <div class="tech-card__icon"><i class="bi {{ $tech->icon }}"></i></div>
                <span class="tech-card__name">{{ $tech->name }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- FEATURED PROJECTS                                       --}}
{{-- ======================================================= --}}
<section class="projects section-pad" id="projects">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-briefcase"></i> Our Portfolio</span>
            <h2 class="section-title">Featured <span class="text-gradient">Projects</span></h2>
            <p class="section-lead mx-auto">A selection of the software products we have designed and shipped for our clients.</p>
        </div>

        <div class="row g-4 projects__grid">
            @foreach($projects as $i => $project)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                <div class="project-card">
                    <div class="project-card__media">
                        <img src="{{ $project->thumbnail ? asset($project->thumbnail) : asset('assets/images/projects/clinic-management.jpg') }}" alt="{{ $project->name }}" loading="lazy">
                        <span class="project-card__cat">{{ $project->category?->name }}</span>
                        <div class="project-card__overlay">
                            <div class="project-card__actions">
                                <a href="{{ route('projects.show', $project) }}" class="project-card__btn" aria-label="View details"><i class="bi bi-eye"></i></a>
                                @if($project->live_url)
                                    <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="project-card__btn" aria-label="Live preview"><i class="bi bi-box-arrow-up-right"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="project-card__body">
                        <h4 class="project-card__title">{{ $project->name }}</h4>
                        <p class="project-card__desc">{{ \Illuminate\Support\Str::limit(strip_tags($project->description), 110) }}</p>
                        <div class="project-card__tech">
                            @foreach($project->technologies as $tech)
                                <span>{{ $tech->name }}</span>
                            @endforeach
                        </div>
                        <div class="project-card__links">
                            <a href="{{ route('projects.show', $project) }}">View Details <i class="bi bi-arrow-right"></i></a>
                            @if($project->live_url)
                                <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="project-card__preview"><i class="bi bi-box-arrow-up-right"></i> Live Preview</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('projects.index') }}" class="btn btn-outline-dark-soft btn-lg">
                View All Projects <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- COMPANY STATISTICS                                      --}}
{{-- ======================================================= --}}
<section class="stats" id="stats">
    <div class="stats__bg"></div>
    <div class="container position-relative">
        <div class="row g-4 justify-content-center">
            @foreach($homeStats as $i => $stat)
            <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                <div class="stat-box">
                    <i class="bi {{ $stat->icon }} stat-box__icon"></i>
                    <span class="stat-box__num counter" data-count="{{ $stat->number }}" data-suffix="{{ $stat->suffix }}">0</span>
                    <span class="stat-box__label">{{ $stat->label }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- INDUSTRIES WE SERVE                                     --}}
{{-- ======================================================= --}}
<section class="industries section-pad" id="industries">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-globe"></i> Industries</span>
            <h2 class="section-title">Industries We <span class="text-gradient">Serve</span></h2>
            <p class="section-lead mx-auto">Domain expertise across the sectors that power the modern economy.</p>
        </div>

        <div class="industries__grid">
            @foreach($industries as $i => $industry)
            <div class="industry-card" data-aos="zoom-in" data-aos-delay="{{ ($i % 5) * 70 }}">
                <div class="industry-card__icon"><i class="bi {{ $industry->icon }}"></i></div>
                <span class="industry-card__name">{{ $industry->name }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- TESTIMONIALS                                            --}}
{{-- ======================================================= --}}
<section class="testimonials section-pad" id="testimonials">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-chat-quote"></i> Testimonials</span>
            <h2 class="section-title">What Our <span class="text-gradient">Clients Say</span></h2>
            <p class="section-lead mx-auto">Real feedback from businesses we have proudly partnered with.</p>
        </div>

        <div class="swiper testimonials__swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                @foreach($testimonials as $review)
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <i class="bi bi-quote testimonial-card__quote"></i>
                        <div class="testimonial-card__stars">
                            @for($s = 0; $s < 5; $s++)
                                <i class="bi {{ $s < $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <p class="testimonial-card__text">{{ $review->review }}</p>
                        <div class="testimonial-card__author">
                            <img src="{{ $review->photo ? asset($review->photo) : asset('assets/images/testimonials/client-1.jpg') }}" alt="{{ $review->client_name }}" loading="lazy">
                            <div>
                                <h6>{{ $review->client_name }}</h6>
                                <span>{{ $review->designation }}{{ $review->designation && $review->company ? ', ' : '' }}{{ $review->company }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination testimonials__pagination"></div>
        </div>
    </div>
</section>


{{-- ======================================================= --}}
{{-- CALL TO ACTION                                          --}}
{{-- ======================================================= --}}
<section class="cta">
    <div class="container">
        <div class="cta__inner" data-aos="zoom-in">
            <span class="cta__blob cta__blob--1"></span>
            <span class="cta__blob cta__blob--2"></span>
            <div class="cta__content">
                <h2 class="cta__title">Let's Build Something <span class="text-gradient-light">Amazing Together</span></h2>
                <p class="cta__desc">Have a project in mind? Let's turn your idea into a powerful digital product your customers will love.</p>
                <div class="cta__actions">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg btn-ripple">Start Your Project <i class="bi bi-arrow-right ms-2"></i></a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

@push('scripts')
<script>
    // Home Page CMS > Hero > Typed Rotating Words — read by main.js's Typed.js init.
    window.SYSBI_TYPED_WORDS = @json($hero->typed_words ?? []);
</script>
@endpush
