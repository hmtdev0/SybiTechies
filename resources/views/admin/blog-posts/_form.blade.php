@php
    $post ??= null;
    $selectedTagIds = old('tags', $post?->tags->pluck('id')->all() ?? []);
    $seo = $post?->seo;
@endphp

<ul class="nav nav-pills gap-2 mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-details" type="button">Details</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-content" type="button">Content</button>
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
                    <div class="admin-card__header"><h3 class="admin-card__title">Post Details</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-md-8">
                            <label class="admin-form-label">Title</label>
                            <input type="text" name="title" id="postTitle" class="form-control" value="{{ old('title', $post->title ?? '') }}" data-slug-source="#postSlug" required>
                        </div>
                        <div class="col-md-4">
                            <label class="admin-form-label">Slug <small class="text-muted">(auto)</small></label>
                            <input type="text" name="slug" id="postSlug" class="form-control" value="{{ old('slug', $post->slug ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Category</label>
                            <select name="blog_category_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('blog_category_id', $post->blog_category_id ?? null) == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Published At</label>
                            <input type="datetime-local" name="published_at" class="form-control"
                                   value="{{ old('published_at', $post?->published_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Excerpt <small class="text-muted">(short summary shown on listing cards)</small></label>
                            <textarea name="excerpt" rows="3" class="form-control" maxlength="300" data-char-counter="300">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Tags</label>
                            <select name="tags[]" class="form-select" multiple size="5">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTagIds))>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl (or Cmd) to select multiple existing tags.</small>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Add New Tags <small class="text-muted">(comma-separated)</small></label>
                            <input type="text" name="new_tags" class="form-control" placeholder="e.g. Laravel, DevOps, Cloud">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Featured Image</h3></div>
                    <div class="admin-card__body text-center">
                        <label class="admin-upload-box d-block mb-0">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="fw-semibold">Click or drag to upload</div>
                            <input type="file" name="featured_image" accept="image/*" data-preview-target="#featuredImagePreview">
                        </label>
                        <img id="featuredImagePreview" src="{{ $post?->featured_image ? asset($post->featured_image) : '' }}"
                             class="admin-upload-preview w-100 {{ $post?->featured_image ? '' : 'd-none' }}" alt="Featured image preview">
                    </div>
                </div>
                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">Publishing</h3></div>
                    <div class="admin-card__body d-flex flex-column gap-3">
                        <div>
                            <label class="admin-form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" @selected(old('status', $post->status ?? 'draft') === 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $post->status ?? 'draft') === 'published')>Published</option>
                            </select>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredSwitch" @checked(old('is_featured', $post->is_featured ?? false))>
                            <label class="form-check-label" for="featuredSwitch">Featured</label>
                        </div>
                        @if($post)
                            <div class="text-muted small"><i class="bi bi-eye me-1"></i>{{ number_format($post->views_count) }} views</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =============== CONTENT =============== --}}
    <div class="tab-pane fade" id="tab-content">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Post Content</h3></div>
            <div class="admin-card__body">
                <textarea name="content" rows="16" class="form-control" data-quill-editor data-quill-upload-url="{{ route('admin.editor-uploads.store') }}" placeholder="Write the full article — you can format text and drop in images.">{{ old('content', $post->content ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- =============== GALLERY =============== --}}
    <div class="tab-pane fade" id="tab-gallery">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Gallery Images</h3></div>
            <div class="admin-card__body">
                @if($post && $post->images->isNotEmpty())
                    <div class="row g-3 mb-4">
                        @foreach($post->images as $image)
                            <div class="col-md-3 col-6">
                                <div class="position-relative">
                                    <img src="{{ asset($image->image_path) }}" class="w-100 rounded-3 border" style="aspect-ratio:4/3; object-fit:cover;" alt="Gallery image">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle" style="width:28px;height:28px;padding:0;"
                                            data-confirm-delete="this gallery image" data-action="{{ route('admin.blog-posts.images.destroy', [$post, $image]) }}">
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
    <i class="bi bi-check2-circle me-2"></i>{{ $post ? 'Update' : 'Save' }} Post
</button>
