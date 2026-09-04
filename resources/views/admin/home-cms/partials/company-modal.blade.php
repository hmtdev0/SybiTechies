<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--a-radius-lg); border: none;">
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($item)@method('PUT')@endif
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="admin-form-label">Company Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $item->name ?? '' }}" required>
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Logo <small class="text-muted">(optional)</small></label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if($item?->logo)
                            <img src="{{ asset($item->logo) }}" class="admin-upload-preview mt-2" alt="">
                        @endif
                    </div>
                    <div class="col-6">
                        <label class="admin-form-label">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="{{ $item->display_order ?? 0 }}">
                    </div>
                    <div class="col-6 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="{{ $modalId }}Status" @checked($item->status ?? true)>
                            <label class="form-check-label" for="{{ $modalId }}Status">Active</label>
                        </div>
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
