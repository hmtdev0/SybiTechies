<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Process Step Details</h3></div>
            <div class="admin-card__body row g-3">
                <div class="col-md-6">
                    <label class="admin-form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $processStep->title ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Icon <small class="text-muted">(Bootstrap Icons class)</small></label>
                    <input type="text" name="icon" class="form-control" placeholder="e.g. bi-search" value="{{ old('icon', $processStep->icon ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Step Number</label>
                    <input type="number" name="step_number" class="form-control" min="1" value="{{ old('step_number', $processStep->step_number ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" min="0" value="{{ old('display_order', $processStep->display_order ?? 0) }}">
                </div>
                <div class="col-12">
                    <label class="admin-form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control" maxlength="500" data-char-counter="500" required>{{ old('description', $processStep->description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-admin-gradient px-5 mt-4">
    <i class="bi bi-check2-circle me-2"></i>{{ isset($processStep) ? 'Update' : 'Save' }} Process Step
</button>
