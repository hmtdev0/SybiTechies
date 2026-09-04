@php
    $legalSections ??= [];
    $legalIntro ??= null;
@endphp

{{-- ===================== LEGAL PAGE (TOC + sections) ===================== --}}
<section class="legal section-pad">
    <div class="container">
        <div class="legal-layout">

            {{-- Table of contents --}}
            <aside class="legal-toc-wrap" data-aos="fade-right">
                <nav class="legal-toc" aria-label="Table of contents">
                    <h6 class="legal-toc__label"><i class="bi bi-list-ul me-2"></i>On This Page</h6>
                    <ul class="legal-toc__list">
                        @foreach($legalSections as $i => $section)
                            <li>
                                <a href="#{{ $section['id'] }}" class="legal-toc__link" data-legal-toc>
                                    <span class="legal-toc__num">{{ sprintf('%02d', $i + 1) }}</span>
                                    <span>{{ $section['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </aside>

            {{-- Main content --}}
            <div class="legal-content">
                @if($legalIntro)
                    <p class="legal-intro" data-aos="fade-up">{{ $legalIntro }}</p>
                @endif

                @foreach($legalSections as $i => $section)
                    <article id="{{ $section['id'] }}" class="legal-section @if(!empty($section['cta'])) legal-section--cta @endif" data-aos="fade-up">
                        <div class="legal-section__head">
                            <span class="legal-section__badge">{{ sprintf('%02d', $i + 1) }}</span>
                            <h2 class="legal-section__title"><i class="bi {{ $section['icon'] }}"></i> {{ $section['title'] }}</h2>
                        </div>

                        <div class="legal-section__body">
                            @foreach($section['blocks'] as $block)
                                @if($block['type'] === 'p')
                                    <p>{{ $block['text'] }}</p>
                                @elseif($block['type'] === 'ul')
                                    <ul class="legal-list">
                                        @foreach($block['items'] as $item)
                                            <li><i class="bi bi-check2-circle"></i><span>{{ $item }}</span></li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        </div>

                        @if(!empty($section['cta']))
                            <div class="legal-cta__rows">
                                @if($siteSettings->email)
                                    <a href="mailto:{{ $siteSettings->email }}" class="legal-cta__row">
                                        <span class="legal-cta__icon"><i class="bi bi-envelope-fill"></i></span>
                                        <div><small>Email</small><strong>{{ $siteSettings->email }}</strong></div>
                                    </a>
                                @endif
                                @if($siteSettings->phone)
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings->phone) }}" class="legal-cta__row">
                                        <span class="legal-cta__icon"><i class="bi bi-telephone-fill"></i></span>
                                        <div><small>Phone</small><strong>{{ $siteSettings->phone }}</strong></div>
                                    </a>
                                @endif
                                @if($siteSettings->address)
                                    <div class="legal-cta__row">
                                        <span class="legal-cta__icon"><i class="bi bi-geo-alt-fill"></i></span>
                                        <div><small>Address</small><strong>{{ $siteSettings->address }}</strong></div>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('contact') }}" class="btn btn-gradient btn-lg btn-ripple legal-cta__btn">
                                Get Free Quote <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
