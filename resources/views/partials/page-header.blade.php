@php
    $pageHeaderCrumbs ??= [];
    $pageHeaderPill ??= null;
    $pageHeaderOrbs ??= false;
    $pageHeaderChips ??= [];
@endphp

{{-- ===================== INNER PAGE HEADER ===================== --}}
<section class="page-header @if($pageHeaderOrbs) page-header--orbs @endif">
    <div class="page-header__bg">
        <span class="page-header__glow"></span>
        <span class="hero-blob hero-blob--1"></span>
        <span class="hero-blob hero-blob--2"></span>
        <span class="hero-grid"></span>
        @if($pageHeaderOrbs)
            <span class="page-header__orb page-header__orb--1"></span>
            <span class="page-header__orb page-header__orb--2"></span>
            <span class="page-header__orb page-header__orb--3"></span>
        @endif
    </div>

    <div class="container position-relative text-center" data-aos="fade-up">
        @if(!empty($pageHeaderEyebrow))
            <span class="section-eyebrow justify-content-center">{!! $pageHeaderEyebrow !!}</span>
        @endif

        <h1 class="page-header__title">{{ $pageHeaderTitle }}</h1>

        @if(!empty($pageHeaderSubtitle))
            <p class="page-header__subtitle">{{ $pageHeaderSubtitle }}</p>
        @endif

        @if(!empty($pageHeaderChips))
            <div class="page-header__chips" data-aos="fade-up" data-aos-delay="100">
                @foreach($pageHeaderChips as $chip)
                    <span class="page-header__chip"><i class="bi {{ $chip['icon'] }}"></i>{{ $chip['text'] }}</span>
                @endforeach
            </div>
        @endif

        @if($pageHeaderPill)
            <div class="page-header__pill"><i class="bi bi-clock-history"></i>{{ $pageHeaderPill }}</div>
        @endif

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb page-header__breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                @foreach($pageHeaderCrumbs as $crumb)
                    <li class="breadcrumb-item active" aria-current="page">{{ $crumb }}</li>
                @endforeach
            </ol>
        </nav>
    </div>
</section>
