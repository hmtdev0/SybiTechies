@php
    $project ??= null;
    $selectedTechIds = old('technologies', $project?->technologies->pluck('id')->all() ?? []);
    $featureTexts = old('features', $project?->features->pluck('feature_text')->all() ?? ['']);
    $seo = $project?->seo;
@endphp

<ul class="nav nav-pills gap-2 mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-details" type="button">Details</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-tech" type="button">Technologies</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-features" type="button">Features</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-gallery" type="button">Gallery</button>
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
                    <div class="admin-card__header"><h3 class="admin-card__title">Project Details</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-md-8">
                            <label class="admin-form-label">Project Name</label>
                            <input type="text" name="name" id="projectName" class="form-control" value="{{ old('name', $project->name ?? '') }}" data-slug-source="#projectSlug" required>
                        </div>
                        <div class="col-md-4">
                            <label class="admin-form-label">Slug <small class="text-muted">(auto)</small></label>
                            <input type="text" name="slug" id="projectSlug" class="form-control" value="{{ old('slug', $project->slug ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Category</label>
                            <select name="project_category_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('project_category_id', $project->project_category_id ?? null) == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="admin-form-label">Client</label>
                            <input type="text" name="client" class="form-control" value="{{ old('client', $project->client ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="admin-form-label">Duration</label>
                            <input type="text" name="duration" class="form-control" placeholder="e.g. 8 weeks" value="{{ old('duration', $project->duration ?? '') }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control" data-quill-editor data-quill-upload-url="{{ route('admin.editor-uploads.store') }}" placeholder="Describe the project — you can format text and drop in images.">{{ old('description', $project->description ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Challenges</label>
                            <textarea name="challenges" rows="4" class="form-control" data-quill-editor data-quill-upload-url="{{ route('admin.editor-uploads.store') }}" placeholder="What made this project difficult?">{{ old('challenges', $project->challenges ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Solutions</label>
                            <textarea name="solutions" rows="4" class="form-control" data-quill-editor data-quill-upload-url="{{ route('admin.editor-uploads.store') }}" placeholder="How did you solve it?">{{ old('solutions', $project->solutions ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Live URL</label>
                            <input type="url" name="live_url" class="form-control" placeholder="https://" value="{{ old('live_url', $project->live_url ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">GitHub URL</label>
                            <input type="url" name="github_url" class="form-control" placeholder="https://" value="{{ old('github_url', $project->github_url ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Thumbnail</h3></div>
                    <div class="admin-card__body text-center">
                        <label class="admin-upload-box d-block mb-0">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="fw-semibold">Click or drag to upload</div>
                            <input type="file" name="thumbnail" accept="image/*" data-preview-target="#thumbnailPreview">
                        </label>
                        <img id="thumbnailPreview" src="{{ $project?->thumbnail ? asset($project->thumbnail) : '' }}"
                             class="admin-upload-preview w-100 {{ $project?->thumbnail ? '' : 'd-none' }}" alt="Thumbnail preview">
                    </div>
                </div>
                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">Publishing</h3></div>
                    <div class="admin-card__body d-flex flex-column gap-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" @checked(old('status', $project->status ?? true))>
                            <label class="form-check-label" for="statusSwitch">Active</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredSwitch" @checked(old('is_featured', $project->is_featured ?? false))>
                            <label class="form-check-label" for="featuredSwitch">Featured</label>
                        </div>
                        <div>
                            <label class="admin-form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $project->display_order ?? 0) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =============== TECHNOLOGIES =============== --}}
    <div class="tab-pane fade" id="tab-tech">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Technologies Used</h3></div>
            <div class="admin-card__body">
                <div class="row g-2">
                    @foreach($technologies as $tech)
                        <div class="col-md-3 col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="technologies[]" value="{{ $tech->id }}" id="tech{{ $tech->id }}" @checked(in_array($tech->id, $selectedTechIds))>
                                <label class="form-check-label" for="tech{{ $tech->id }}">
                                    <i class="bi {{ $tech->icon }} me-1" style="color:{{ $tech->color }}"></i>{{ $tech->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
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
                            <input type="text" name="features[]" class="form-control" value="{{ $text }}" placeholder="e.g. Real-time inventory sync">
                            <button type="button" class="btn btn-outline-danger remove-feature-row"><i class="bi bi-trash3"></i></button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- =============== GALLERY =============== --}}
    <div class="tab-pane fade" id="tab-gallery">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Gallery Images</h3></div>
            <div class="admin-card__body">
                @if($project && $project->images->isNotEmpty())
                    <div class="row g-3 mb-4">
                        @foreach($project->images as $image)
                            <div class="col-md-3 col-6">
                                <div class="position-relative">
                                    <img src="{{ asset($image->image_path) }}" class="w-100 rounded-3 border" style="aspect-ratio:4/3; object-fit:cover;" alt="Gallery image">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle" style="width:28px;height:28px;padding:0;"
                                            data-confirm-delete="this gallery image" data-action="{{ route('admin.projects.images.destroy', [$project, $image]) }}">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <label class="admin-upload-box d-block mb-0">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <div class="fw-semibold">Click or drag to upload gallery images</div>
                    <small class="text-muted">You can select multiple images — they'll be added to the gallery above</small>
                    <input type="file" name="images[]" accept="image/*" multiple>
                </label>
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
    <i class="bi bi-check2-circle me-2"></i>{{ $project ? 'Update' : 'Save' }} Project
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
                row.innerHTML = '<input type="text" name="features[]" class="form-control" placeholder="e.g. Real-time inventory sync">'
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
