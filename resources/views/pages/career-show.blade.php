@extends('layouts.frontend')

@section('content')

@php
    $pageHeaderEyebrow = ($jobOpening->department ? '<i class="bi bi-diagram-3"></i> '.$jobOpening->department : '<i class="bi bi-person-workspace"></i> Careers');
    $pageHeaderTitle = $jobOpening->title;
    $pageHeaderSubtitle = trim(($jobOpening->location ?: '').(($jobOpening->location && $jobOpening->type) ? ' — ' : '').$jobOpening->type);
    $pageHeaderCrumbs = ['Careers', $jobOpening->title];
@endphp
@include('partials.page-header')

<section class="service-detail section-pad">
    <div class="container">
        <div class="row g-5">
            {{-- Main content --}}
            <div class="col-lg-8" data-aos="fade-right">
                <h2 class="detail-heading">About The Role</h2>
                <div class="detail-copy detail-copy--rich">
                    {!! $jobOpening->description ?: '<p>No description provided yet — check back soon or reach out with questions.</p>' !!}
                </div>

                @if($jobOpening->requirements)
                    <h2 class="detail-heading mt-5">What We're Looking For</h2>
                    <div class="detail-copy detail-copy--rich">
                        {!! $jobOpening->requirements !!}
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-left">
                <div class="detail-sidebar-card">
                    <h5>Job Info</h5>
                    <ul class="detail-facts">
                        @if($jobOpening->department)
                            <li><span>Department</span><strong>{{ $jobOpening->department }}</strong></li>
                        @endif
                        @if($jobOpening->location)
                            <li><span>Location</span><strong>{{ $jobOpening->location }}</strong></li>
                        @endif
                        <li><span>Type</span><strong>{{ $jobOpening->type }}</strong></li>
                    </ul>
                    <div class="detail-sidebar-card__actions">
                        <a href="#apply" class="btn btn-gradient w-100 btn-ripple">Apply Now <i class="bi bi-arrow-down ms-2"></i></a>
                    </div>
                </div>

                <div class="detail-sidebar-card">
                    <h5>Other Openings</h5>
                    @php $otherJobs = \App\Models\JobOpening::query()->active()->ordered()->where('id', '!=', $jobOpening->id)->limit(5)->get(); @endphp
                    @if($otherJobs->isNotEmpty())
                        <ul class="detail-sidebar-list">
                            @foreach($otherJobs as $other)
                                <li>
                                    <a href="{{ route('careers.show', $other) }}">
                                        <i class="bi bi-briefcase"></i> {{ $other->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small mb-0">No other openings right now.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- APPLY                                                    --}}
{{-- ======================================================= --}}
<section class="career-apply section-pad" id="apply">
    <div class="container">
        <div class="section-head text-center" data-aos="fade-up">
            <span class="section-eyebrow justify-content-center"><i class="bi bi-send"></i> Apply</span>
            <h2 class="section-title">Apply For <span class="text-gradient">{{ $jobOpening->title }}</span></h2>
            <p class="section-lead mx-auto">Tell us a bit about yourself and attach your resume — we read every application.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact__form-wrap" data-aos="zoom-in">
                    @if(session('success'))
                        <div class="contact__success">
                            <span class="contact__success-icon"><i class="bi bi-check-lg"></i></span>
                            <div>
                                <h4>Application Sent!</h4>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('careers.apply', $jobOpening) }}" method="POST" enctype="multipart/form-data" class="career-apply__form" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-float form-float--icon">
                                    <i class="bi bi-person form-float__icon"></i>
                                    <input type="text" name="name" id="applicantName" class="form-control" placeholder=" " value="{{ old('name') }}" required>
                                    <label for="applicantName">Full Name</label>
                                    <div class="invalid-feedback">Please tell us your name.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-float form-float--icon">
                                    <i class="bi bi-envelope form-float__icon"></i>
                                    <input type="email" name="email" id="applicantEmail" class="form-control" placeholder=" " value="{{ old('email') }}" required>
                                    <label for="applicantEmail">Email Address</label>
                                    <div class="invalid-feedback">Enter a valid email address.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-float form-float--icon">
                                    <i class="bi bi-telephone form-float__icon"></i>
                                    <input type="tel" name="phone" id="applicantPhone" class="form-control" placeholder=" " value="{{ old('phone') }}">
                                    <label for="applicantPhone">Phone Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-file-label">Resume / CV <small>(PDF, DOC or DOCX, max 5MB)</small></label>
                                <label class="form-file">
                                    <input type="file" name="resume" accept=".pdf,.doc,.docx" required>
                                    <span class="form-file__box">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <span class="form-file__text">Click to upload or drag a file here</span>
                                    </span>
                                </label>
                                <div class="invalid-feedback">Please attach your resume.</div>
                            </div>
                            <div class="col-12">
                                <div class="form-float form-float--icon form-float--textarea">
                                    <i class="bi bi-chat-left-text form-float__icon"></i>
                                    <textarea name="message" id="applicantMessage" class="form-control" rows="4" placeholder=" ">{{ old('message') }}</textarea>
                                    <label for="applicantMessage">Anything else you'd like us to know? (optional)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gradient btn-lg w-100 btn-ripple contact__submit-btn">
                                    <span class="contact__submit-label">Submit Application <i class="bi bi-send ms-2"></i></span>
                                    <span class="contact__submit-spinner spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </button>
                                <p class="contact__privacy-note"><i class="bi bi-shield-lock me-1"></i>Your resume is only used to evaluate this application.</p>
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
        var form = document.querySelector('.career-apply__form');
        if (!form) { return; }

        var fileInput = form.querySelector('.form-file input[type="file"]');
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                var label = fileInput.closest('.form-file');
                var text = label.querySelector('.form-file__text');
                if (fileInput.files.length) {
                    label.classList.add('has-file');
                    text.textContent = fileInput.files[0].name;
                } else {
                    label.classList.remove('has-file');
                    text.textContent = 'Click to upload or drag a file here';
                }
            });
        }

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

@endsection
