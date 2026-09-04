@php
    $contactAnchorId ??= 'contact';
    $contactEyebrow ??= 'Get In Touch';
    $contactTitle ??= 'Let\'s Talk About Your <span class="text-gradient">Project</span>';
    $contactLead ??= "Tell us what you're building — we'll get back to you within one business day.";
@endphp

{{-- ===================== CONTACT SECTION ===================== --}}
<section class="contact section-pad" id="{{ $contactAnchorId }}">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-envelope-paper"></i> {{ $contactEyebrow }}</span>
            <h2 class="section-title">{!! $contactTitle !!}</h2>
            <p class="section-lead mx-auto">{{ $contactLead }}</p>
        </div>

        <div class="row g-4 g-lg-5 align-items-stretch">
            {{-- Left: details --}}
            <div class="col-lg-5" data-aos="fade-right">
                <div class="contact__info">
                    <h3>Contact Information</h3>
                    <p>Reach out through any channel below or use the form — we're happy to help.</p>

                    <ul class="contact__list">
                        @if($siteSettings->phone)
                            <li>
                                <span class="contact__icon"><i class="bi bi-telephone-fill"></i></span>
                                <div><small>Phone</small><a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings->phone) }}">{{ $siteSettings->phone }}</a></div>
                            </li>
                        @endif
                        @if($siteSettings->email)
                            <li>
                                <span class="contact__icon"><i class="bi bi-envelope-fill"></i></span>
                                <div><small>Email</small><a href="mailto:{{ $siteSettings->email }}">{{ $siteSettings->email }}</a></div>
                            </li>
                        @endif
                        @if($siteSettings->address)
                            <li>
                                <span class="contact__icon"><i class="bi bi-geo-alt-fill"></i></span>
                                <div><small>Address</small><a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($siteSettings->address) }}" target="_blank" rel="noopener">{{ $siteSettings->address }}</a></div>
                            </li>
                        @endif
                        @if($siteSettings->business_hours)
                            <li>
                                <span class="contact__icon"><i class="bi bi-clock-fill"></i></span>
                                <div><small>Business Hours</small><span>{{ $siteSettings->business_hours }}</span></div>
                            </li>
                        @endif
                    </ul>

                    @if($siteSettings->phone)
                        <div class="contact__divider"></div>

                        <div class="contact__quickcall">
                            <div>
                                <span class="contact__quickcall-label">Prefer a quick call?</span>
                                <span class="contact__quickcall-sub">We're happy to talk it through.</span>
                            </div>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings->phone) }}" class="contact__quickcall-btn">
                                <i class="bi bi-telephone-fill"></i>Call Now
                            </a>
                        </div>
                    @endif

                    <div class="contact__social">
                        @if($siteSettings->facebook_url)<a href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook" class="contact__social-link--facebook"><i class="bi bi-facebook"></i></a>@endif
                        @if($siteSettings->twitter_url)<a href="{{ $siteSettings->twitter_url }}" target="_blank" rel="noopener" aria-label="Twitter" class="contact__social-link--twitter"><i class="bi bi-twitter-x"></i></a>@endif
                        @if($siteSettings->linkedin_url)<a href="{{ $siteSettings->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn" class="contact__social-link--linkedin"><i class="bi bi-linkedin"></i></a>@endif
                        @if($siteSettings->instagram_url)<a href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram" class="contact__social-link--instagram"><i class="bi bi-instagram"></i></a>@endif
                    </div>
                </div>
            </div>

            {{-- Right: form --}}
            <div class="col-lg-7" data-aos="fade-left">
                <div class="contact__form-wrap">
                    @if(session('success'))
                        <div class="contact__success" data-aos="zoom-in">
                            <span class="contact__success-icon"><i class="bi bi-check-lg"></i></span>
                            <div>
                                <h4>Message Sent!</h4>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="contact__form" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-float form-float--icon">
                                    <i class="bi bi-person form-float__icon"></i>
                                    <input type="text" name="name" id="name" class="form-control" placeholder=" " value="{{ old('name') }}" required>
                                    <label for="name">Full Name</label>
                                    <div class="invalid-feedback">Please tell us your name.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-float form-float--icon">
                                    <i class="bi bi-envelope form-float__icon"></i>
                                    <input type="email" name="email" id="email" class="form-control" placeholder=" " value="{{ old('email') }}" required>
                                    <label for="email">Email Address</label>
                                    <div class="invalid-feedback">Enter a valid email address.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-float form-float--icon">
                                    <i class="bi bi-telephone form-float__icon"></i>
                                    <input type="tel" name="phone" id="phone" class="form-control" placeholder=" " value="{{ old('phone') }}">
                                    <label for="phone">Phone Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-float form-float--icon">
                                    <i class="bi bi-building form-float__icon"></i>
                                    <input type="text" name="company" id="company" class="form-control" placeholder=" " value="{{ old('company') }}">
                                    <label for="company">Company</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-float form-float--icon form-float--select">
                                    <i class="bi bi-briefcase form-float__icon"></i>
                                    <select name="service" id="service" class="form-select" required>
                                        <option value="" selected disabled hidden></option>
                                        @foreach($services as $service)
                                            <option @selected(old('service') === $service->title)>{{ $service->title }}</option>
                                        @endforeach
                                        <option @selected(old('service') === 'Other')>Other</option>
                                    </select>
                                    <label for="service">Service Interested In</label>
                                    <div class="invalid-feedback">Please choose an option.</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-float form-float--icon form-float--textarea">
                                    <i class="bi bi-chat-left-text form-float__icon"></i>
                                    <textarea name="message" id="message" class="form-control" rows="4" placeholder=" " required>{{ old('message') }}</textarea>
                                    <label for="message">Tell us about your project</label>
                                    <div class="invalid-feedback">Let us know a little about your project.</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gradient btn-lg w-100 btn-ripple contact__submit-btn">
                                    <span class="contact__submit-label">Send Message <i class="bi bi-send ms-2"></i></span>
                                    <span class="contact__submit-spinner spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </button>
                                <p class="contact__privacy-note"><i class="bi bi-shield-lock me-1"></i>We'll never share your details.</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    (function () {
        var form = document.querySelector('.contact__form');
        if (!form) { return; }

        // Live per-field validity feedback: as soon as a field is touched,
        // scope Bootstrap's native :valid/:invalid styling to just that field.
        form.querySelectorAll('.form-control, .form-select').forEach(function (field) {
            field.addEventListener('blur', function () {
                field.closest('.form-float').classList.add('was-validated');
            });
        });

        form.addEventListener('submit', function (e) {
            form.classList.add('was-validated');
            form.querySelectorAll('.form-float').forEach(function (wrap) { wrap.classList.add('was-validated'); });

            if (!form.checkValidity()) {
                e.preventDefault();
                var firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) { firstInvalid.focus(); }
                return;
            }

            var btn = form.querySelector('.contact__submit-btn');
            if (btn && !btn.classList.contains('is-loading')) {
                btn.classList.add('is-loading');
                btn.disabled = true;
            }
        });
    })();
</script>
@endpush
