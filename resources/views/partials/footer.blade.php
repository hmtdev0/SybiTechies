@php
    $siteSettings ??= \App\Models\SiteSetting::current();
    $footerMenuItems ??= optional(\App\Models\Menu::forLocation('footer'))->activeItems ?? collect();
    $footerServices ??= \App\Models\Service::query()->active()->ordered()->limit(6)->get();
@endphp

{{-- ===================== FOOTER ===================== --}}
<footer class="site-footer" id="footer">
    {{-- Decorative blobs --}}
    <span class="footer-blob footer-blob--1"></span>
    <span class="footer-blob footer-blob--2"></span>

    <div class="container position-relative">
        <div class="row gy-5">

            {{-- About column --}}
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <a class="navbar-brand footer-brand" href="{{ route('home') }}">
                    <span class="brand-mark">
                        <i class="bi bi-hexagon-fill"></i>
                        <i class="bi bi-code-slash brand-mark__glyph"></i>
                    </span>
                    <span class="brand-text">{{ $siteSettings->brandFirstPart() }}<span class="brand-text__accent">{{ $siteSettings->brandAccentPart() }}</span></span>
                </a>
                <p class="footer-about">
                    {{ $siteSettings->footer_text }}
                </p>
                <div class="footer-social">
                    @if($siteSettings->facebook_url)<a href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>@endif
                    @if($siteSettings->twitter_url)<a href="{{ $siteSettings->twitter_url }}" target="_blank" rel="noopener" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>@endif
                    @if($siteSettings->linkedin_url)<a href="{{ $siteSettings->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>@endif
                    @if($siteSettings->instagram_url)<a href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>@endif
                    @if($siteSettings->github_url)<a href="{{ $siteSettings->github_url }}" target="_blank" rel="noopener" aria-label="GitHub"><i class="bi bi-github"></i></a>@endif
                </div>
            </div>

            {{-- Quick links --}}
            <div class="col-lg-2 col-6" data-aos="fade-up" data-aos-delay="100">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="footer-links">
                    @foreach($footerMenuItems as $item)
                        <li><a href="{{ $item->url }}">{{ $item->label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Services --}}
            <div class="col-lg-3 col-6" data-aos="fade-up" data-aos-delay="200">
                <h5 class="footer-title">Services</h5>
                <ul class="footer-links">
                    @forelse($footerServices as $service)
                        <li><a href="{{ route('services.show', $service) }}">{{ $service->title }}</a></li>
                    @empty
                        <li><a href="{{ route('home') }}#services">Our Services</a></li>
                    @endforelse
                </ul>
            </div>

            {{-- Newsletter --}}
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <h5 class="footer-title">Newsletter</h5>
                <p class="footer-about mb-3">Subscribe to get the latest updates, insights and offers from our team.</p>
                <form class="footer-newsletter" action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <div class="footer-newsletter__field">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" placeholder="Your email address" aria-label="Email" required>
                        <button type="submit" aria-label="Subscribe"><i class="bi bi-send-fill"></i></button>
                    </div>
                    <small class="footer-newsletter__note">We respect your privacy. Unsubscribe anytime.</small>
                </form>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="footer-bottom">
            <p class="mb-0">{{ $siteSettings->copyright_text ?: '© '.date('Y').' '.$siteSettings->company_name.'. All rights reserved.' }}</p>
            <ul class="footer-bottom__links">
                <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                <li><a href="{{ route('home') }}">Sitemap</a></li>
            </ul>
        </div>
    </div>
</footer>
<script src="https://helpchatly.com/js/chat-widget.js" data-key="2K9EkHi4AKHPIRMiSYltyqw0LUdxq3RV"></script>
