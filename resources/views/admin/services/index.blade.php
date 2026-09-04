@extends('layouts.admin')

@section('title', 'Services')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Services</h2>
            <p class="text-muted mb-0">Manage your service offerings — {{ $services->total() }} service{{ $services->total() === 1 ? '' : 's' }} total.</p>
        </div>
        @can('create services')
            <a href="{{ route('admin.services.create') }}" class="btn btn-admin-gradient">
                <i class="bi bi-plus-lg me-2"></i>Add Service
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card__header flex-wrap">
            <form action="{{ route('admin.services.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input type="search" name="search" class="form-control" placeholder="Search services..." value="{{ request('search') }}" style="min-width:220px;">
                <select name="status" class="form-select" style="min-width:140px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.services.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            @can('delete services')
                                <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            @endcan
                            <th>Icon</th>
                            <th>Title</th>
                            <th>Short Description</th>
                            <th>Featured</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                @can('delete services')
                                    <td><input type="checkbox" name="ids[]" value="{{ $service->id }}" class="form-check-input row-check"></td>
                                @endcan
                                <td>
                                    @if($service->image)
                                        <img src="{{ asset($service->image) }}" class="admin-table-thumb" alt="{{ $service->title }}">
                                    @else
                                        <span class="admin-table-thumb d-flex align-items-center justify-content-center bg-light"><i class="bi {{ $service->icon }} text-primary"></i></span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $service->title }}</td>
                                <td class="text-muted">{{ \Illuminate\Support\Str::limit($service->short_description, 60) }}</td>
                                <td>
                                    @if($service->is_featured)
                                        <span class="admin-badge admin-badge--warning"><i class="bi bi-star-fill"></i> Featured</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $service->display_order }}</td>
                                <td>
                                    @can('publish services')
                                        {{-- Submits via form="" — a <form> nested inside #bulkDeleteForm is
                                             invalid HTML and gets silently dropped by the browser. --}}
                                        <button type="submit" form="toggle-service-{{ $service->id }}" class="btn border-0 p-0 admin-status-toggle">
                                            @include('admin.partials.status-badge', ['status' => $service->status])
                                        </button>
                                    @else
                                        @include('admin.partials.status-badge', ['status' => $service->status])
                                    @endcan
                                </td>
                                <td class="text-end">
                                    @can('edit services')
                                        <a href="{{ route('admin.services.edit', $service) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    @endcan
                                    @can('delete services')
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $service->title }}" data-action="{{ route('admin.services.destroy', $service) }}"><i class="bi bi-trash3"></i></button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No services found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @foreach($services as $service)
            <form id="toggle-service-{{ $service->id }}" action="{{ route('admin.services.toggle-status', $service) }}" method="POST" class="d-none">
                @csrf @method('PATCH')
            </form>
        @endforeach

        @if($services->hasPages())
            <div class="p-3 border-top">{{ $services->links() }}</div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var selectAll = document.getElementById('selectAll');
        var bulkBtn = document.getElementById('bulkDeleteBtn');
        var checks = function () { return document.querySelectorAll('.row-check'); };
        var refresh = function () {
            var anyChecked = [...checks()].some(function (c) { return c.checked; });
            bulkBtn.classList.toggle('d-none', !anyChecked);
        };
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checks().forEach(function (c) { c.checked = selectAll.checked; });
                refresh();
            });
        }
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('row-check')) { refresh(); }
        });
    })();
</script>
@endpush
