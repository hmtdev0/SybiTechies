@extends('layouts.admin')

@section('title', 'About Page CMS')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1">About Page CMS</h2>
        <p class="text-muted mb-0">Manage the mission, vision, story, features and timeline shown in the About section.</p>
    </div>

    <form action="{{ route('admin.about-cms.update') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Mission &amp; Vision</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-md-6">
                            <label class="admin-form-label">Mission Title</label>
                            <input type="text" name="mission_title" class="form-control" value="{{ old('mission_title', $about->mission_title) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Vision Title</label>
                            <input type="text" name="vision_title" class="form-control" value="{{ old('vision_title', $about->vision_title) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Mission Text</label>
                            <textarea name="mission_text" rows="4" class="form-control">{{ old('mission_text', $about->mission_text) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Vision Text</label>
                            <textarea name="vision_text" rows="4" class="form-control">{{ old('vision_text', $about->vision_text) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">Our Story</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-12">
                            <label class="admin-form-label">Story Title</label>
                            <input type="text" name="story_title" class="form-control" value="{{ old('story_title', $about->story_title) }}">
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">Story Text</label>
                            <textarea name="story_text" rows="5" class="form-control" maxlength="2000" data-char-counter="2000">{{ old('story_text', $about->story_text) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">About Image</h3></div>
                    <div class="admin-card__body text-center">
                        <label class="admin-upload-box d-block mb-0">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="fw-semibold">Click or drag to upload</div>
                            <input type="file" name="image" accept="image/*" data-preview-target="#aboutImagePreview">
                        </label>
                        <img id="aboutImagePreview" src="{{ $about->image ? asset($about->image) : '' }}"
                             class="admin-upload-preview w-100 {{ $about->image ? '' : 'd-none' }}" alt="About image preview">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-admin-gradient px-5 mt-4">
            <i class="bi bi-check2-circle me-2"></i>Save About Page
        </button>
    </form>

    <div class="row g-4">
        {{-- Features --}}
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Features</h3>
                    <button class="btn btn-admin-soft btn-sm" data-bs-toggle="modal" data-bs-target="#addFeatureModal"><i class="bi bi-plus-lg me-1"></i>Add</button>
                </div>
                <div class="table-responsive">
                    <table class="admin-table mb-0">
                        <thead><tr><th>Icon</th><th>Title</th><th>Order</th><th></th></tr></thead>
                        <tbody>
                            @forelse($features as $feature)
                                <tr>
                                    <td><i class="bi {{ $feature->icon }}"></i></td>
                                    <td class="fw-semibold">{{ $feature->title }}</td>
                                    <td>{{ $feature->display_order }}</td>
                                    <td class="text-end">
                                        <button class="btn-admin-icon btn-admin-icon--edit" data-bs-toggle="modal" data-bs-target="#editFeatureModal{{ $feature->id }}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="this feature" data-action="{{ route('admin.about-cms.features.destroy', $feature) }}"><i class="bi bi-trash3"></i></button>
                                    </td>
                                </tr>
                                @include('admin.about-cms.partials.feature-modal', ['modalId' => 'editFeatureModal'.$feature->id, 'action' => route('admin.about-cms.features.update', $feature), 'item' => $feature, 'title' => 'Edit Feature'])
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No features yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @include('admin.about-cms.partials.feature-modal', ['modalId' => 'addFeatureModal', 'action' => route('admin.about-cms.features.store'), 'item' => null, 'title' => 'Add Feature'])
        </div>

        {{-- Timeline --}}
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Timeline</h3>
                    <button class="btn btn-admin-soft btn-sm" data-bs-toggle="modal" data-bs-target="#addTimelineModal"><i class="bi bi-plus-lg me-1"></i>Add</button>
                </div>
                <div class="table-responsive">
                    <table class="admin-table mb-0">
                        <thead><tr><th>Year</th><th>Title</th><th>Order</th><th></th></tr></thead>
                        <tbody>
                            @forelse($timeline as $entry)
                                <tr>
                                    <td class="fw-semibold">{{ $entry->year }}</td>
                                    <td>{{ $entry->title }}</td>
                                    <td>{{ $entry->display_order }}</td>
                                    <td class="text-end">
                                        <button class="btn-admin-icon btn-admin-icon--edit" data-bs-toggle="modal" data-bs-target="#editTimelineModal{{ $entry->id }}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="this timeline entry" data-action="{{ route('admin.about-cms.timeline.destroy', $entry) }}"><i class="bi bi-trash3"></i></button>
                                    </td>
                                </tr>
                                @include('admin.about-cms.partials.timeline-modal', ['modalId' => 'editTimelineModal'.$entry->id, 'action' => route('admin.about-cms.timeline.update', $entry), 'item' => $entry, 'title' => 'Edit Timeline Entry'])
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No timeline entries yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @include('admin.about-cms.partials.timeline-modal', ['modalId' => 'addTimelineModal', 'action' => route('admin.about-cms.timeline.store'), 'item' => null, 'title' => 'Add Timeline Entry'])
        </div>
    </div>
@endsection
