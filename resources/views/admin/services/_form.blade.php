@php
    $service ??= null;
    $featureTexts = old('features', $service?->features->pluck('feature_text')->all() ?? ['']);
    $seo = $service?->seo;
@endphp

<ul class="nav nav-pills gap-2 mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-details" type="button">Details</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-features" type="button">Features</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-seo" type="button">SEO</button>
    </li>
</ul>

<div class="tab-content">

    {{-- =============== DETAILS =============== --}}
    <div class="tab-pane fade show active" id="tab-details">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Service Details</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-md-8">
                            <label class="admin-form-label">Service Title</label>
                            <input type="text" name="title" id="serviceTitle" class="form-control" value="{{ old('title', $service->title ?? '') }}" data-slug-source="#serviceSlug" required>
                        </div>
                        <div class="col-md-4">
                            <label class="admin-form-label">Slug <small class="text-muted">(auto)</small></label>
                            <input type="text" name="slug" id="serviceSlug" class="form-control" value="{{ old('slug', $service->slug ?? '') }}">
                        </div>
                        <div class="col-md-12">
                            <label class="admin-form-label">Icon <small class="text-muted">(Bootstrap Icon class)</small></label>
                            <input type="text" name="icon" class="form-control" placeholder="e.g. bi-code-slash" value="{{ old('icon', $service->icon ?? '') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Short Description <small class="text-muted">(shown on service cards)</small></label>
                            <textarea name="short_description" rows="2" class="form-control" maxlength="255" data-char-counter="255" required>{{ old('short_description', $service->short_description ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Full Description <small class="text-muted">(shown on the service detail page)</small></label>
                            <textarea name="full_description" rows="6" class="form-control" data-quill-editor data-quill-upload-url="{{ route('admin.editor-uploads.store') }}" placeholder="Describe this service in depth — you can format text and drop in images.">{{ old('full_description', $service->full_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Card Image</h3></div>
                    <div class="admin-card__body text-center">
                        <label class="admin-upload-box d-block mb-0">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="fw-semibold">Click or drag to upload</div>
                            <small class="text-muted d-block mt-1">Optional — falls back to the icon if left empty</small>
                            <input type="file" name="image" accept="image/*" data-preview-target="#imagePreview">
                        </label>
                        <img id="imagePreview" src="{{ $service?->image ? asset($service->image) : '' }}"
                             class="admin-upload-preview w-100 {{ $service?->image ? '' : 'd-none' }}" alt="Service image preview">
                    </div>
                </div>
                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">Publishing</h3></div>
                    <div class="admin-card__body d-flex flex-column gap-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" @checked(old('status', $service->status ?? true))>
                            <label class="form-check-label" for="statusSwitch">Active</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredSwitch" @checked(old('is_featured', $service->is_featured ?? false))>
                            <label class="form-check-label" for="featuredSwitch">Featured</label>
                        </div>
                        <div>
                            <label class="admin-form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $service->display_order ?? 0) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =============== FEATURES =============== --}}
    <div class="tab-pane fade" id="tab-features">
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">Key Features</h3>
                <button type="button" class="btn btn-admin-soft btn-sm" id="addFeatureBtn"><i class="bi bi-plus-lg me-1"></i>Add Feature</button>
            </div>
            <div class="admin-card__body">
                <div id="featuresRepeater" class="d-flex flex-column gap-2">
                    @foreach($featureTexts as $text)
                        <div class="input-group feature-row">
                            <input type="text" name="features[]" class="form-control" value="{{ $text }}" placeholder="e.g. Dedicated project manager">
                            <button type="button" class="btn btn-outline-danger remove-feature-row"><i class="bi bi-trash3"></i></button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- =============== SEO =============== --}}
    <div class="tab-pane fade" id="tab-seo">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Search Engine Meta</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-12">
                            <label class="admin-form-label">SEO Title</label>
                            <input type="text" name="seo_title" class="form-control" maxlength="255" data-char-counter="255" value="{{ old('seo_title', $seo->title ?? '') }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Meta Description</label>
                            <textarea name="seo_meta_description" rows="3" class="form-control" maxlength="500" data-char-counter="500">{{ old('seo_meta_description', $seo->meta_description ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Meta Keywords</label>
                            <input type="text" name="seo_meta_keywords" class="form-control" value="{{ old('seo_meta_keywords', $seo->meta_keywords ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Canonical URL</label>
                            <input type="url" name="seo_canonical_url" class="form-control" value="{{ old('seo_canonical_url', $seo->canonical_url ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Robots</label>
                            <select name="seo_robots" class="form-select">
                                @foreach(['index, follow', 'noindex, follow', 'index, nofollow', 'noindex, nofollow'] as $option)
                                    <option value="{{ $option }}" @selected(old('seo_robots', $seo->robots ?? 'index, follow') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">OG Title</label>
                            <input type="text" name="seo_og_title" class="form-control" value="{{ old('seo_og_title', $seo->og_title ?? '') }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">OG Description</label>
                            <textarea name="seo_og_description" rows="3" class="form-control" maxlength="500" data-char-counter="500">{{ old('seo_og_description', $seo->og_description ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Schema (JSON-LD)</label>
                            <textarea name="seo_schema_json" rows="5" class="form-control font-monospace small">{{ old('seo_schema_json', $seo->schema_json ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">OG Image</h3></div>
                    <div class="admin-card__body text-center">
                        <label class="admin-upload-box d-block mb-0">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="fw-semibold">Click or drag to upload</div>
                            <input type="file" name="seo_og_image" accept="image/*" data-preview-target="#ogImagePreview">
                        </label>
                        <img id="ogImagePreview" src="{{ $seo?->og_image ? asset($seo->og_image) : '' }}"
                             class="admin-upload-preview w-100 {{ $seo?->og_image ? '' : 'd-none' }}" alt="OG image preview">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<button type="submit" class="btn btn-admin-gradient px-5 mt-4">
    <i class="bi bi-check2-circle me-2"></i>{{ $service ? 'Update' : 'Save' }} Service
</button>

@push('scripts')
<script>
    (function () {
        var repeater = document.getElementById('featuresRepeater');
        var addBtn = document.getElementById('addFeatureBtn');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                var row = document.createElement('div');
                row.className = 'input-group feature-row';
                row.innerHTML = '<input type="text" name="features[]" class="form-control" placeholder="e.g. Dedicated project manager">'
                    + '<button type="button" class="btn btn-outline-danger remove-feature-row"><i class="bi bi-trash3"></i></button>';
                repeater.appendChild(row);
            });
        }
        if (repeater) {
            repeater.addEventListener('click', function (e) {
                var btn = e.target.closest('.remove-feature-row');
                if (btn) { btn.closest('.feature-row').remove(); }
            });
        }
    })();
</script>
@endpush
