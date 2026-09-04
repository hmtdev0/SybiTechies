@extends('layouts.admin')

@section('title', 'Website Settings')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Website Settings</h2>
        <p class="text-muted mb-0">Manage your company info, contact details, footer content and social links.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- Company & Branding --}}
            <div class="col-lg-8">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Company Information</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-md-6">
                            <label class="admin-form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $settings->company_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $settings->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">WhatsApp Number</label>
                            <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $settings->whatsapp) }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $settings->address) }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Business Hours</label>
                            <input type="text" name="business_hours" class="form-control" placeholder="Mon – Fri: 9:00 AM – 6:00 PM" value="{{ old('business_hours', $settings->business_hours) }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Google Maps Embed Code</label>
                            <textarea name="google_maps_embed" rows="3" class="form-control" placeholder="Paste the <iframe> embed code from Google Maps">{{ old('google_maps_embed', $settings->google_maps_embed) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Footer &amp; Copyright</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-12">
                            <label class="admin-form-label">Footer Text</label>
                            <textarea name="footer_text" rows="3" class="form-control" maxlength="500" data-char-counter="500">{{ old('footer_text', $settings->footer_text) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Copyright Text</label>
                            <input type="text" name="copyright_text" class="form-control" placeholder="© {{ date('Y') }} SysbiTechies. All rights reserved." value="{{ old('copyright_text', $settings->copyright_text) }}">
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">Social Links</h3></div>
                    <div class="admin-card__body row g-3">
                        @foreach(['facebook_url' => 'bi-facebook', 'instagram_url' => 'bi-instagram', 'linkedin_url' => 'bi-linkedin', 'twitter_url' => 'bi-twitter-x', 'github_url' => 'bi-github', 'youtube_url' => 'bi-youtube'] as $field => $icon)
                            <div class="col-md-6">
                                <label class="admin-form-label text-capitalize"><i class="bi {{ $icon }} me-1"></i>{{ str_replace('_url', '', $field) }}</label>
                                <input type="url" name="{{ $field }}" class="form-control" placeholder="https://" value="{{ old($field, $settings->$field) }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Logo & Favicon --}}
            <div class="col-lg-4">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Logo</h3></div>
                    <div class="admin-card__body text-center">
                        <label class="admin-upload-box d-block mb-0">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="fw-semibold">Click or drag to upload</div>
                            <small class="text-muted">PNG, JPG, WEBP or SVG — max 2MB</small>
                            <input type="file" name="logo" accept="image/*" data-preview-target="#logoPreview">
                        </label>
                        <img id="logoPreview" src="{{ $settings->logo ? asset($settings->logo) : '' }}"
                             class="admin-upload-preview {{ $settings->logo ? '' : 'd-none' }}" alt="Logo preview">
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">Favicon</h3></div>
                    <div class="admin-card__body text-center">
                        <label class="admin-upload-box d-block mb-0">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="fw-semibold">Click or drag to upload</div>
                            <small class="text-muted">PNG, ICO or WEBP — max 512KB</small>
                            <input type="file" name="favicon" accept="image/*" data-preview-target="#faviconPreview">
                        </label>
                        <img id="faviconPreview" src="{{ $settings->favicon ? asset($settings->favicon) : '' }}"
                             class="admin-upload-preview {{ $settings->favicon ? '' : 'd-none' }}" style="max-width:64px; max-height:64px;" alt="Favicon preview">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-admin-gradient px-5">
                <i class="bi bi-check2-circle me-2"></i>Save Settings
            </button>
        </div>
    </form>
@endsection
