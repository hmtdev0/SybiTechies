@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = '<i class="bi bi-journal-text"></i> Our Blog';
    $pageHeaderTitle = 'Our Blog';
    $pageHeaderSubtitle = 'Insights, tutorials and updates from our team';
    $pageHeaderCrumbs = ['Blog'];
@endphp
@include('partials.page-header')

<section class="blog-index section-pad">
    <div class="container">

        {{-- Category filter pills --}}
        <div class="projects__filters" data-aos="fade-up">
            <a href="{{ route('blog.index') }}" class="filter-btn {{ request('category') ? '' : 'active' }}">All</a>
            @foreach($categories as $category)
                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="filter-btn {{ request('category') === $category->slug ? 'active' : '' }}">{{ $category->name }}</a>
            @endforeach
        </div>

        @if($posts->isEmpty())
            <div class="text-center py-5" data-aos="fade-up">
                <i class="bi bi-journal-x display-3 text-muted"></i>
                <p class="text-muted mt-3 mb-0">No blog posts found{{ request('category') ? ' in this category' : '' }}.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($posts as $i => $post)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                        <a href="{{ route('blog.show', $post) }}" class="blog-card">
                            <div class="blog-card__media">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy">
                                @else
                                    <span class="blog-card__placeholder"><i class="bi bi-journal-richtext"></i></span>
                                @endif
                                @if($post->category)
                                    <span class="blog-card__cat">{{ $post->category->name }}</span>
                                @endif
                            </div>
                            <div class="blog-card__body">
                                <span class="blog-card__date"><i class="bi bi-calendar3"></i> {{ $post->published_at?->format('M d, Y') }}</span>
                                <h4 class="blog-card__title">{{ $post->title }}</h4>
                                <p class="blog-card__desc">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 110) }}</p>
                                <span class="blog-card__link">Read More <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="mt-5 d-flex justify-content-center">{{ $posts->links() }}</div>
            @endif
        @endif
    </div>
</section>

@endsection
