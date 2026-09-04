@php $withStatus = $withStatus ?? false; @endphp
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
                    <div class="col-12">
                        <label class="admin-form-label">Icon <small class="text-muted">(Bootstrap Icons class, e.g. bi-rocket-takeoff)</small></label>
                        <input type="text" name="icon" class="form-control" value="{{ $item->icon ?? '' }}">
                    </div>
                    <div class="col-6">
                        <label class="admin-form-label">Number</label>
                        <input type="number" name="number" class="form-control" value="{{ $item->number ?? 0 }}" required>
                    </div>
                    <div class="col-6">
                        <label class="admin-form-label">Suffix</label>
                        <input type="text" name="suffix" class="form-control" value="{{ $item->suffix ?? '+' }}">
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Label</label>
                        <input type="text" name="label" class="form-control" value="{{ $item->label ?? '' }}" required>
                    </div>
                    <div class="col-6">
                        <label class="admin-form-label">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="{{ $item->display_order ?? 0 }}">
                    </div>
                    @if($withStatus)
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="{{ $modalId }}Status" @checked($item->status ?? true)>
                                <label class="form-check-label" for="{{ $modalId }}Status">Active</label>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-admin-gradient px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
