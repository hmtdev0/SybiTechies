@extends('layouts.admin')

@section('title', 'Menu Manager')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Menu Manager</h2>
        <p class="text-muted mb-0">Manage the links shown in the site header and footer navigation.</p>
    </div>

    @php
        $locations = [
            'header' => ['label' => 'Header Navigation', 'icon' => 'bi-menu-button-wide'],
            'footer' => ['label' => 'Footer Navigation', 'icon' => 'bi-layout-text-window-reverse'],
        ];
    @endphp

    @foreach ($locations as $locationKey => $meta)
        @php $menu = $menus[$locationKey] ?? null; @endphp
        <div class="admin-card mb-4">
            <div class="admin-card__header">
                <h3 class="admin-card__title"><i class="bi {{ $meta['icon'] }} me-2"></i>{{ $meta['label'] }}</h3>
                @if($menu)
                    <button type="button" class="btn btn-admin-soft btn-sm" data-bs-toggle="modal" data-bs-target="#menuItemModal"
                        data-menu-item-trigger data-mode="add"
                        data-action="{{ route('admin.menus.items.store') }}"
                        data-menu-id="{{ $menu->id }}">
                        <i class="bi bi-plus-lg me-1"></i>Add Item
                    </button>
                @endif
            </div>
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            <th>Label</th>
                            <th>URL</th>
                            <th>Order</th>
                            <th>New Tab?</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($menu->items ?? collect()) as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->label }}</td>
                                <td class="text-muted small">{{ $item->url }}</td>
                                <td>{{ $item->display_order }}</td>
                                <td>{{ $item->is_new_tab ? 'Yes' : 'No' }}</td>
                                <td>
                                    <form action="{{ route('admin.menus.items.toggle-status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn border-0 p-0 admin-status-toggle">
                                            @include('admin.partials.status-badge', ['status' => $item->status])
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn-admin-icon btn-admin-icon--edit"
                                        data-bs-toggle="modal" data-bs-target="#menuItemModal"
                                        data-menu-item-trigger data-mode="edit"
                                        data-action="{{ route('admin.menus.items.update', $item) }}"
                                        data-menu-id="{{ $item->menu_id }}"
                                        data-id="{{ $item->id }}"
                                        data-label="{{ $item->label }}"
                                        data-url="{{ $item->url }}"
                                        data-order="{{ $item->display_order }}"
                                        data-new-tab="{{ $item->is_new_tab ? 1 : 0 }}"
                                        data-status="{{ $item->status ? 1 : 0 }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn-admin-icon btn-admin-icon--delete"
                                        data-confirm-delete="{{ $item->label }}"
                                        data-action="{{ route('admin.menus.items.destroy', $item) }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No menu items yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    {{-- Shared Add/Edit modal --}}
    <div class="modal fade" id="menuItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: var(--a-radius-lg); border: none;">
                <form id="menuItemForm" method="POST" action="#">
                    @csrf
                    <input type="hidden" name="_method" id="menuItemMethod" value="">
                    <input type="hidden" name="menu_id" value="">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="menuItemModalLabel">Add Menu Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label class="admin-form-label">Label</label>
                            <input type="text" name="label" class="form-control" maxlength="100" required>
                        </div>
                        <div class="col-12">
                            <label class="admin-form-label">URL <small class="text-muted">(e.g. /about or /#services)</small></label>
                            <input type="text" name="url" class="form-control" maxlength="255" required>
                        </div>
                        <div class="col-6">
                            <label class="admin-form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_new_tab" value="1" id="menuItemNewTab">
                                <label class="form-check-label" for="menuItemNewTab">Open in New Tab</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="menuItemStatus" checked>
                                <label class="form-check-label" for="menuItemStatus">Active</label>
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('menuItemForm');
        if (!form) { return; }

        var titleEl = document.getElementById('menuItemModalLabel');
        var methodField = document.getElementById('menuItemMethod');
        var menuIdField = form.querySelector('[name="menu_id"]');
        var labelField = form.querySelector('[name="label"]');
        var urlField = form.querySelector('[name="url"]');
        var orderField = form.querySelector('[name="display_order"]');
        var newTabField = form.querySelector('[name="is_new_tab"]');
        var statusField = form.querySelector('[name="status"]');

        document.querySelectorAll('[data-menu-item-trigger]').forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                var mode = trigger.getAttribute('data-mode');
                form.setAttribute('action', trigger.getAttribute('data-action'));

                if (mode === 'edit') {
                    titleEl.textContent = 'Edit Menu Item';
                    methodField.value = 'PUT';
                    menuIdField.value = trigger.getAttribute('data-menu-id') || '';
                    labelField.value = trigger.getAttribute('data-label') || '';
                    urlField.value = trigger.getAttribute('data-url') || '';
                    orderField.value = trigger.getAttribute('data-order') || 0;
                    newTabField.checked = trigger.getAttribute('data-new-tab') === '1';
                    statusField.checked = trigger.getAttribute('data-status') === '1';
                } else {
                    form.reset();
                    titleEl.textContent = 'Add Menu Item';
                    methodField.value = '';
                    menuIdField.value = trigger.getAttribute('data-menu-id') || '';
                    statusField.checked = true;
                }
            });
        });
    });
</script>
@endpush
