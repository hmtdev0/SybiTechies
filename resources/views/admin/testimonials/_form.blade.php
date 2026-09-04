@php
    $testimonial ??= null;
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Testimonial Details</h3></div>
            <div class="admin-card__body row g-3">
                <div class="col-md-6">
                    <label class="admin-form-label">Client Name</label>
                    <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $testimonial->client_name ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Company</label>
                    <input type="text" name="company" class="form-control" value="{{ old('company', $testimonial->company ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Designation</label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation', $testimonial->designation ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Rating</label>
                    <select name="rating" class="form-select">
                        @for($r = 1; $r <= 5; $r++)
                            <option value="{{ $r }}" @selected(old('rating', $testimonial->rating ?? 5) == $r)>{{ $r }} Star{{ $r > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-12">
                    <label class="admin-form-label">Review</label>
                    <textarea name="review" rows="4" class="form-control" maxlength="1000" data-char-counter="1000" required>{{ old('review', $testimonial->review ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="admin-card__header"><h3 class="admin-card__title">Photo</h3></div>
            <div class="admin-card__body text-center">
                <label class="admin-upload-box d-block mb-0">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <div class="fw-semibold">Click or drag to upload</div>
                    <input type="file" name="photo" accept="image/*" data-preview-target="#photoPreview">
                </label>
                <img id="photoPreview" src="{{ $testimonial?->photo ? asset($testimonial->photo) : '' }}"
                     class="admin-upload-preview w-100 {{ $testimonial?->photo ? '' : 'd-none' }}" alt="Photo preview">
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Publishing</h3></div>
            <div class="admin-card__body d-flex flex-column gap-3">
                <div>
                    <label class="admin-form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $testimonial->display_order ?? 0) }}">
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" @checked(old('status', $testimonial->status ?? true))>
                    <label class="form-check-label" for="statusSwitch">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-admin-gradient px-5 mt-4">
    <i class="bi bi-check2-circle me-2"></i>{{ $testimonial ? 'Update' : 'Save' }} Testimonial
</button>
