<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563EB">

    @php
        // Every frontend controller passes a $seo (SeoMeta) instance; fall back
        // gracefully if a view ever forgets to (never render a blank <title>).
        $seo ??= new \App\Models\SeoMeta();
        $siteSettings ??= \App\Models\SiteSetting::current();

        // Admin-managed snippets (Admin > Custom Scripts) — injected on every
        // public page so tracking pixels / chat widgets / etc. never require
        // touching a Blade file. Deliberately unescaped: this field's whole
        // purpose is to hold raw <script>/<meta> markup.
        $customHeadScripts = \App\Models\CustomScript::query()->active()->forPlacement('head')->ordered()->pluck('code');
        $customFooterScripts = \App\Models\CustomScript::query()->active()->forPlacement('footer')->ordered()->pluck('code');
    @endphp

    {{-- SEO --}}
    <title>{{ $seo->title ?: $siteSettings->company_name.' — '.$siteSettings->footer_text }}</title>
    <meta name="description" content="{{ $seo->meta_description }}">
    <meta name="keywords" content="{{ $seo->meta_keywords }}">
    <meta name="author" content="{{ $siteSettings->company_name }}">
    <meta name="robots" content="{{ $seo->robots ?: 'index, follow' }}">
    @if($seo->canonical_url)
        <link rel="canonical" href="{{ $seo->canonical_url }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seo->og_title ?: $seo->title }}">
    <meta property="og:description" content="{{ $seo->og_description ?: $seo->meta_description }}">
    <meta property="og:image" content="{{ $seo->og_image ? asset($seo->og_image) : asset('assets/images/hero/dashboard.jpg') }}">

    @if($seo->schema_json)
        <script type="application/ld+json">{!! $seo->schema_json !!}</script>
    @endif

    {{-- Favicon --}}
    <link rel="icon" href="{{ $siteSettings->favicon ? asset($siteSettings->favicon) : asset('favicon.ico') }}" sizes="any">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 (local) --}}
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    {{-- Bootstrap Icons (local) --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">

    {{-- AOS (local) --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/aos/aos.css') }}">

    {{-- SwiperJS (local) --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}">

    {{-- Site styles --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ filemtime(public_path('assets/css/style.css')) }}">
    {{-- Premium polish layer (overrides base, sits before responsive so breakpoints still win) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/premium.css') }}?v={{ filemtime(public_path('assets/css/premium.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}?v={{ filemtime(public_path('assets/css/responsive.css')) }}">

    @stack('styles')

    {{-- Admin-managed custom scripts — Admin > Custom Scripts --}}
    @foreach($customHeadScripts as $code)
        {!! $code !!}
    @endforeach
</head>
<body>

    {{-- Scroll progress bar --}}
    <div class="scroll-progress" id="scrollProgress"></div>

    {{-- Preloader --}}
    <div class="preloader" id="preloader">
        <div class="preloader__spinner"></div>
    </div>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Page content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Back to top --}}
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="bi bi-arrow-up"></i>
    </button>

    {{-- Bootstrap bundle (local) --}}
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Libraries (local) --}}
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/typed/typed.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/countup/countUp.umd.js') }}"></script>

    {{-- Site scripts --}}
    <script src="{{ asset('assets/js/main.js') }}?v={{ filemtime(public_path('assets/js/main.js')) }}"></script>

    @stack('scripts')

    {{-- Admin-managed custom scripts — Admin > Custom Scripts --}}
    @foreach($customFooterScripts as $code)
        {!! $code !!}
    @endforeach
</body>
</html>
