@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi bi-briefcase-fill"></i> Our Portfolio';
    $pageHeaderTitle = 'Our Projects';
    $pageHeaderSubtitle = 'A selection of the software products we have designed and shipped for our clients.';
    $pageHeaderCrumbs = ['Projects'];
@endphp
@include('partials.page-header')

<section class="projects-index section-pad">
    <div class="container">

        {{-- Category filter pills --}}
        <div class="projects__filters" data-aos="fade-up">
            <a href="{{ route('projects.index') }}" class="filter-btn {{ request('category') ? '' : 'active' }}">All</a>
            @foreach($categories as $category)
                <a href="{{ route('projects.index', ['category' => $category->slug]) }}" class="filter-btn {{ request('category') === $category->slug ? 'active' : '' }}">{{ $category->name }}</a>
            @endforeach
        </div>

        @if($projects->isEmpty())
            <div class="text-center py-5" data-aos="fade-up">
                <i class="bi bi-briefcase display-3 text-muted"></i>
                <p class="text-muted mt-3 mb-0">No projects found{{ request('category') ? ' in this category' : '' }}.</p>
            </div>
        @else
            <div class="row g-4">
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

            @if($projects->hasPages())
                <div class="mt-5 d-flex justify-content-center">{{ $projects->links() }}</div>
            @endif
        @endif
    </div>
</section>

@endsection
