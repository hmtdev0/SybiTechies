<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Technology Details</h3></div>
            <div class="admin-card__body row g-3">
                <div class="col-md-6">
                    <label class="admin-form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $technology->name ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Icon <small class="text-muted">(Bootstrap Icons class)</small></label>
                    <input type="text" name="icon" class="form-control" placeholder="bi-server" value="{{ old('icon', $technology->icon ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Brand Color</label>
                    <input type="text" name="color" class="form-control" placeholder="#FF2D20" value="{{ old('color', $technology->color ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $technology->display_order ?? 0) }}">
                </div>
                <div class="col-12">
                    <label class="admin-form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control" maxlength="500" data-char-counter="500">{{ old('description', $technology->description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Status</h3></div>
            <div class="admin-card__body">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" @checked(old('status', $technology->status ?? true))>
                    <label class="form-check-label" for="statusSwitch">Active (visible on homepage)</label>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-admin-gradient px-5 mt-4">
    <i class="bi bi-check2-circle me-2"></i>{{ isset($technology) ? 'Update' : 'Save' }} Technology
</button>
