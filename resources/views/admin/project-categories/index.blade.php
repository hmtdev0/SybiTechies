@extends('layouts.admin')

@section('title', 'Project Categories')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Project Categories</h2>
            <p class="text-muted mb-0">Manage the categories used to organize and filter projects.</p>
        </div>
        <a href="{{ route('admin.project-categories.create') }}" class="btn btn-admin-gradient">
            <i class="bi bi-plus-lg me-2"></i>Add Category
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card__header">
            <form action="{{ route('admin.project-categories.index') }}" method="GET" class="d-flex gap-2">
                <input type="search" name="search" class="form-control" placeholder="Search categories..." value="{{ request('search') }}" style="min-width:240px;">
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.project-categories.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Projects</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $category->id }}" class="form-check-input row-check"></td>
                                <td class="fw-semibold">{{ $category->name }}</td>
                                <td class="text-muted small">{{ $category->slug }}</td>
                                <td><span class="admin-badge admin-badge--info">{{ $category->projects_count }}</span></td>
                                <td>{{ $category->display_order }}</td>
                                <td>
                                    {{-- Submits via form="" — a <form> nested inside #bulkDeleteForm is
                                         invalid HTML and gets silently dropped by the browser. --}}
                                    <button type="submit" form="toggle-category-{{ $category->id }}" class="btn border-0 p-0 admin-status-toggle">
                                        @include('admin.partials.status-badge', ['status' => $category->status])
                                    </button>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.project-categories.edit', $category) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $category->name }}" data-action="{{ route('admin.project-categories.destroy', $category) }}"><i class="bi bi-trash3"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No categories found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @foreach($categories as $category)
            <form id="toggle-category-{{ $category->id }}" action="{{ route('admin.project-categories.toggle-status', $category) }}" method="POST" class="d-none">
                @csrf @method('PATCH')
            </form>
        @endforeach

        @if($categories->hasPages())
            <div class="p-3 border-top">{{ $categories->links() }}</div>
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
