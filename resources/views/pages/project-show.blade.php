@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = $project->category ? '<i class="bi bi-tag-fill"></i> '.$project->category->name : '<i class="bi bi-briefcase-fill"></i> Our Work';
    $pageHeaderTitle = $project->name;
    $pageHeaderSubtitle = $project->client ? 'Built for '.$project->client : null;
    $pageHeaderCrumbs = ['Projects', $project->name];
@endphp
@include('partials.page-header')

<section class="project-detail section-pad">
    <div class="container">

        @if($project->thumbnail)
            <div class="detail-cover" data-aos="fade-up">
                <img src="{{ asset($project->thumbnail) }}" alt="{{ $project->name }}" loading="eager">
            </div>
        @endif

        <div class="row g-5">
            {{-- Main content --}}
            <div class="col-lg-8" data-aos="fade-right">
                <h2 class="detail-heading">Project Overview</h2>
                <div class="detail-copy detail-copy--rich">{!! $project->description !!}</div>

                @if($project->challenges)
                    <h2 class="detail-heading mt-5">The Challenge</h2>
                    <div class="detail-copy detail-copy--rich">{!! $project->challenges !!}</div>
                @endif

                @if($project->solutions)
                    <h2 class="detail-heading mt-5">Our Solution</h2>
                    <div class="detail-copy detail-copy--rich">{!! $project->solutions !!}</div>
                @endif

                @if($project->features->isNotEmpty())
                    <h2 class="detail-heading mt-5">Key Features</h2>
                    <ul class="detail-features">
                        @foreach($project->features as $feature)
                            <li><i class="bi bi-check2-circle"></i> {{ $feature->feature_text }}</li>
                        @endforeach
                    </ul>
                @endif

                @if($project->images->isNotEmpty())
                    <h2 class="detail-heading mt-5">Gallery</h2>
                    <div class="detail-gallery">
                        @foreach($project->images as $image)
                            <a href="{{ asset($image->image_path) }}" target="_blank" rel="noopener" class="detail-gallery__item">
                                <img src="{{ asset($image->image_path) }}" alt="{{ $project->name }} screenshot" loading="lazy">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-left">
                <div class="detail-sidebar-card">
                    <h5>Project Info</h5>
                    <ul class="detail-facts">
                        @if($project->client)
                            <li><span>Client</span><strong>{{ $project->client }}</strong></li>
                        @endif
                        @if($project->category)
                            <li><span>Category</span><strong>{{ $project->category->name }}</strong></li>
                        @endif
                        @if($project->duration)
                            <li><span>Duration</span><strong>{{ $project->duration }}</strong></li>
                        @endif
                    </ul>

                    @if($project->technologies->isNotEmpty())
                        <h6 class="detail-sidebar-card__subhead">Technologies</h6>
                        <div class="detail-tech-tags">
                            @foreach($project->technologies as $tech)
                                <span>{{ $tech->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="detail-sidebar-card__actions">
                        @if($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn btn-gradient w-100 btn-ripple">
                                <i class="bi bi-box-arrow-up-right me-2"></i>Live Preview
                            </a>
                        @endif
                        @if($project->github_url)
                            <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-outline-dark-soft w-100">
                                <i class="bi bi-github me-2"></i>View Code
                            </a>
                        @endif
                    </div>
                </div>

                <div class="detail-sidebar-card detail-sidebar-card--cta">
                    <i class="bi bi-rocket-takeoff-fill detail-sidebar-card__icon"></i>
                    <h5>Have a Similar Project?</h5>
                    <p>Let's discuss how we can build something just as great for you.</p>
                    <a href="{{ route('contact') }}" class="btn btn-gradient w-100 btn-ripple">Start a Project <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>

        @if($otherProjects->isNotEmpty())
            <div class="detail-related">
                <h2 class="detail-heading mb-4">More Projects</h2>
                <div class="row g-4">
                    @foreach($otherProjects as $other)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <a href="{{ route('projects.show', $other) }}" class="detail-related-card">
                                @if($other->thumbnail)
                                    <img src="{{ asset($other->thumbnail) }}" alt="{{ $other->name }}" loading="lazy">
                                @else
                                    <span class="detail-related-card__placeholder"><i class="bi bi-image"></i></span>
                                @endif
                                <span class="detail-related-card__title">{{ $other->name }} <i class="bi bi-arrow-right"></i></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
