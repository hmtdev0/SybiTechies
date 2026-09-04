@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = $post->category ? '<i class="bi bi-tag-fill"></i> '.$post->category->name : '<i class="bi bi-journal-text"></i> Our Blog';
    $pageHeaderTitle = $post->title;
    $pageHeaderSubtitle = $post->excerpt;
    $pageHeaderCrumbs = ['Blog', $post->title];
@endphp
@include('partials.page-header')

<section class="blog-detail section-pad">
    <div class="container">

        @if($post->featured_image)
            <div class="detail-cover" data-aos="fade-up">
                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" loading="eager">
            </div>
        @endif

        <div class="row g-5">
            {{-- Main content --}}
            <div class="col-lg-8" data-aos="fade-right">
                <div class="detail-copy detail-copy--rich">{!! $post->content !!}</div>

                @if($post->images->isNotEmpty())
                    <h2 class="detail-heading mt-5">Gallery</h2>
                    <div class="detail-gallery">
                        @foreach($post->images as $image)
                            <a href="{{ asset($image->image_path) }}" target="_blank" rel="noopener" class="detail-gallery__item">
                                <img src="{{ asset($image->image_path) }}" alt="{{ $post->title }} screenshot" loading="lazy">
                            </a>
                        @endforeach
                    </div>
                @endif

                @if($post->tags->isNotEmpty())
                    <h6 class="detail-sidebar-card__subhead mt-5">Tags</h6>
                    <div class="detail-tech-tags">
                        @foreach($post->tags as $tag)
                            <span>{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-left">
                <div class="detail-sidebar-card">
                    <h5>Post Info</h5>
                    <ul class="detail-facts">
                        @if($post->published_at)
                            <li><span>Published</span><strong>{{ $post->published_at->format('M d, Y') }}</strong></li>
                        @endif
                        @if($post->category)
                            <li><span>Category</span><strong>{{ $post->category->name }}</strong></li>
                        @endif
                        <li><span>Views</span><strong>{{ number_format($post->views_count) }}</strong></li>
                    </ul>
                </div>

                <div class="detail-sidebar-card detail-sidebar-card--cta">
                    <i class="bi bi-rocket-takeoff-fill detail-sidebar-card__icon"></i>
                    <h5>Have a Project in Mind?</h5>
                    <p>Let's discuss how we can help bring your idea to life.</p>
                    <a href="{{ route('contact') }}" class="btn btn-gradient w-100 btn-ripple">Get in Touch <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>

        @if($relatedPosts->isNotEmpty())
            <div class="detail-related">
                <h2 class="detail-heading mb-4">Related Posts</h2>
                <div class="row g-4">
                    @foreach($relatedPosts as $other)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <a href="{{ route('blog.show', $other) }}" class="detail-related-card">
                                @if($other->featured_image)
                                    <img src="{{ asset($other->featured_image) }}" alt="{{ $other->title }}" loading="lazy">
                                @else
                                    <span class="detail-related-card__placeholder"><i class="bi bi-journal-richtext"></i></span>
                                @endif
                                <span class="detail-related-card__title">{{ $other->title }} <i class="bi bi-arrow-right"></i></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
