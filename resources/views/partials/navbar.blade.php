@php
    $siteSettings ??= \App\Models\SiteSetting::current();
    $headerMenuItems ??= optional(\App\Models\Menu::forLocation('header'))->activeItems ?? collect();
@endphp

{{-- ===================== NAVBAR ===================== --}}
<nav class="navbar navbar-expand-lg site-navbar" id="siteNavbar">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand" href="{{ route('home') }}#home">
            <span class="brand-mark">
                <i class="bi bi-hexagon-fill"></i>
                <i class="bi bi-code-slash brand-mark__glyph"></i>
            </span>
            <span
                class="brand-text">{{ $siteSettings->brandFirstPart() }}<span
                    class="brand-text__accent">{{ $siteSettings->brandAccentPart() }}</span></span>
        </a>

        {{-- Mobile toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu"
            aria-controls="mobileMenu" aria-label="Toggle navigation">
            <span class="toggler-bar"></span>
            <span class="toggler-bar"></span>
            <span class="toggler-bar"></span>
        </button>

        {{-- Desktop menu --}}
        <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex">
            <ul class="navbar-nav nav-links">
                @foreach ($headerMenuItems as $item)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" href="{{ $item->url }}"
                            @if ($item->is_new_tab) target="_blank" rel="noopener" @endif>{{ $item->label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Desktop CTA --}}
        <div class="d-none d-lg-block">
            <a href="{{ route('contact') }}" class="btn btn-gradient btn-quote">
                <i class="bi bi-chat-dots me-2"></i>Get Free Quote
            </a>
        </div>
    </div>
</nav>

{{-- Mobile offcanvas menu --}}
<div class="offcanvas offcanvas-end mobile-menu" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <a class="navbar-brand" href="{{ route('home') }}#home" data-bs-dismiss="offcanvas">
            <span class="brand-mark">
                <i class="bi bi-hexagon-fill"></i>
                <i class="bi bi-code-slash brand-mark__glyph"></i>
            </span>
            <span
                class="brand-text">{{ $siteSettings->brandFirstPart() }}<span
                    class="brand-text__accent">{{ $siteSettings->brandAccentPart() }}</span></span>
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav mobile-nav">
            @foreach ($headerMenuItems as $item)
                <li class="nav-item"><a class="nav-link" href="{{ $item->url }}" data-bs-dismiss="offcanvas"><i
                            class="bi bi-chevron-right me-2"></i>{{ $item->label }}</a></li>
            @endforeach
        </ul>
        <a href="{{ route('contact') }}" class="btn btn-gradient w-100 mt-4" data-bs-dismiss="offcanvas">
            <i class="bi bi-chat-dots me-2"></i>Get Free Quote
        </a>
        <div class="mobile-menu__contact mt-4">
            @if ($siteSettings->phone)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings->phone) }}"><i
                        class="bi bi-telephone-fill me-2"></i>{{ $siteSettings->phone }}</a>
            @endif
            @if ($siteSettings->email)
                <a href="mailto:{{ $siteSettings->email }}"><i
                        class="bi bi-envelope-fill me-2"></i>{{ $siteSettings->email }}</a>
            @endif
        </div>
        <div class="mobile-menu__social mt-4">
            @if ($siteSettings->facebook_url)
                <a href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook"><i
                        class="bi bi-facebook"></i></a>
            @endif
            @if ($siteSettings->twitter_url)
                <a href="{{ $siteSettings->twitter_url }}" target="_blank" rel="noopener" aria-label="Twitter"><i
                        class="bi bi-twitter-x"></i></a>
            @endif
            @if ($siteSettings->linkedin_url)
                <a href="{{ $siteSettings->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i
                        class="bi bi-linkedin"></i></a>
            @endif
            @if ($siteSettings->instagram_url)
                <a href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram"><i
                        class="bi bi-instagram"></i></a>
            @endif
        </div>
    </div>
</div>
