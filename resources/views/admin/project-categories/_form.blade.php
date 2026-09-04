<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Category Details</h3></div>
            <div class="admin-card__body row g-3">
                <div class="col-md-6">
                    <label class="admin-form-label">Name</label>
                    <input type="text" name="name" id="categoryName" class="form-control" value="{{ old('name', $category->name ?? '') }}" data-slug-source="#categorySlug" required>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Slug <small class="text-muted">(auto-generated if left blank)</small></label>
                    <input type="text" name="slug" id="categorySlug" class="form-control" value="{{ old('slug', $category->slug ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $category->display_order ?? 0) }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Status</h3></div>
            <div class="admin-card__body">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" @checked(old('status', $category->status ?? true))>
                    <label class="form-check-label" for="statusSwitch">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-admin-gradient px-5 mt-4">
    <i class="bi bi-check2-circle me-2"></i>{{ isset($category) ? 'Update' : 'Save' }} Category
</button>
