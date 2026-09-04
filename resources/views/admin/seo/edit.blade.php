@extends('layouts.admin')

@section('title', 'Edit SEO — '.ucfirst($pageKey))

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1 text-capitalize">SEO — {{ $pageKey }} Page</h2>
            <p class="text-muted mb-0">Controls how this page appears in search results and social shares.</p>
        </div>
        <a href="{{ route('admin.seo.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to SEO Manager</a>
    </div>

    <form action="{{ route('admin.seo.update', $pageKey) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Search Engine Meta</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-12">
                            <label class="admin-form-label">SEO Title</label>
                            <input type="text" name="title" class="form-control" maxlength="255" data-char-counter="255" value="{{ old('title', $seo->title) }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="form-control" maxlength="500" data-char-counter="500">{{ old('meta_description', $seo->meta_description) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" placeholder="comma, separated, keywords" value="{{ old('meta_keywords', $seo->meta_keywords) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Canonical URL</label>
                            <input type="url" name="canonical_url" class="form-control" placeholder="https://" value="{{ old('canonical_url', $seo->canonical_url) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Robots</label>
                            <select name="robots" class="form-select">
                                @foreach(['index, follow', 'noindex, follow', 'index, nofollow', 'noindex, nofollow'] as $option)
                                    <option value="{{ $option }}" @selected(old('robots', $seo->robots) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Open Graph (Social Sharing)</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-12">
                            <label class="admin-form-label">OG Title</label>
                            <input type="text" name="og_title" class="form-control" value="{{ old('og_title', $seo->og_title) }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">OG Description</label>
                            <textarea name="og_description" rows="3" class="form-control" maxlength="500" data-char-counter="500">{{ old('og_description', $seo->og_description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">Schema (JSON-LD)</h3></div>
                    <div class="admin-card__body">
                        <textarea name="schema_json" rows="6" class="form-control font-monospace small" placeholder='{"@@context": "https://schema.org", "@@type": "Organization", ...}'>{{ old('schema_json', $seo->schema_json) }}</textarea>
                        <div class="form-text">Must be valid JSON. Leave blank to skip structured data for this page.</div>
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
                            <small class="text-muted">Recommended 1200×630px</small>
                            <input type="file" name="og_image" accept="image/*" data-preview-target="#ogImagePreview">
                        </label>
                        <img id="ogImagePreview" src="{{ $seo->og_image ? asset($seo->og_image) : '' }}"
                             class="admin-upload-preview w-100 {{ $seo->og_image ? '' : 'd-none' }}" style="max-width:100%;" alt="OG image preview">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-admin-gradient px-5">
                <i class="bi bi-check2-circle me-2"></i>Save SEO Settings
            </button>
        </div>
    </form>
@endsection
