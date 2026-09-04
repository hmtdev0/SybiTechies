<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--a-radius-lg); border: none;">
            <form action="{{ $action }}" method="POST">
                @csrf
                @if($item)@method('PUT')@endif
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-6">
                        <label class="admin-form-label">Year</label>
                        <input type="text" name="year" class="form-control" value="{{ $item->year ?? '' }}" required>
                    </div>
                    <div class="col-6">
                        <label class="admin-form-label">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="{{ $item->display_order ?? 0 }}">
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $item->title ?? '' }}" required>
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control">{{ $item->description ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-admin-gradient px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
