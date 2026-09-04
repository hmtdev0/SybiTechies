@php
    $jobOpening ??= null;
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <div class="admin-card__header"><h3 class="admin-card__title">Position Details</h3></div>
            <div class="admin-card__body row g-3">
                <div class="col-md-8">
                    <label class="admin-form-label">Job Title</label>
                    <input type="text" name="title" id="jobTitle" class="form-control" value="{{ old('title', $jobOpening->title ?? '') }}" data-slug-source="#jobSlug" required>
                </div>
                <div class="col-md-4">
                    <label class="admin-form-label">Slug <small class="text-muted">(auto)</small></label>
                    <input type="text" name="slug" id="jobSlug" class="form-control" value="{{ old('slug', $jobOpening->slug ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="admin-form-label">Department</label>
                    <input type="text" name="department" class="form-control" placeholder="e.g. Engineering" value="{{ old('department', $jobOpening->department ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="admin-form-label">Location</label>
                    <input type="text" name="location" class="form-control" placeholder="e.g. Remote / New York, NY" value="{{ old('location', $jobOpening->location ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="admin-form-label">Employment Type</label>
                    <select name="type" class="form-select">
                        @foreach(['Full-time', 'Part-time', 'Contract', 'Internship'] as $option)
                            <option value="{{ $option }}" @selected(old('type', $jobOpening->type ?? 'Full-time') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="admin-form-label">Role Description</label>
                    <textarea name="description" rows="6" class="form-control" data-quill-editor data-quill-upload-url="{{ route('admin.editor-uploads.store') }}" placeholder="What this role does day to day...">{{ old('description', $jobOpening->description ?? '') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="admin-form-label">Requirements</label>
                    <textarea name="requirements" rows="6" class="form-control" data-quill-editor data-quill-upload-url="{{ route('admin.editor-uploads.store') }}" placeholder="Skills, experience and qualifications you're looking for...">{{ old('requirements', $jobOpening->requirements ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Publishing</h3></div>
            <div class="admin-card__body d-flex flex-column gap-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" @checked(old('status', $jobOpening->status ?? true))>
                    <label class="form-check-label" for="statusSwitch">Open for applications</label>
                </div>
                <div>
                    <label class="admin-form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $jobOpening->display_order ?? 0) }}">
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-admin-gradient px-5 mt-4">
    <i class="bi bi-check2-circle me-2"></i>{{ $jobOpening ? 'Update' : 'Save' }} Job Opening
</button>
