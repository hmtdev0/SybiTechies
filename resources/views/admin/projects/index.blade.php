@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Projects</h2>
            <p class="text-muted mb-0">Manage your portfolio — {{ $projects->total() }} project{{ $projects->total() === 1 ? '' : 's' }} total.</p>
        </div>
        @can('create portfolio')
            <a href="{{ route('admin.projects.create') }}" class="btn btn-admin-gradient">
                <i class="bi bi-plus-lg me-2"></i>Add Project
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card__header flex-wrap">
            <form action="{{ route('admin.projects.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input type="search" name="search" class="form-control" placeholder="Search projects..." value="{{ request('search') }}" style="min-width:220px;">
                <select name="category" class="form-select" style="min-width:160px;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
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

        <form id="bulkDeleteForm" action="{{ route('admin.projects.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            @can('delete portfolio')
                                <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            @endcan
                            <th>Thumbnail</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Featured</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                @can('delete portfolio')
                                    <td><input type="checkbox" name="ids[]" value="{{ $project->id }}" class="form-check-input row-check"></td>
                                @endcan
                                <td>
                                    @if($project->thumbnail)
                                        <img src="{{ asset($project->thumbnail) }}" class="admin-table-thumb" alt="{{ $project->name }}">
                                    @else
                                        <span class="admin-table-thumb d-flex align-items-center justify-content-center bg-light"><i class="bi bi-image text-muted"></i></span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $project->name }}</td>
                                <td>{{ $project->category?->name ?: '—' }}</td>
                                <td>
                                    @if($project->is_featured)
                                        <span class="admin-badge admin-badge--warning"><i class="bi bi-star-fill"></i> Featured</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $project->display_order }}</td>
                                <td>
                                    @can('publish portfolio')
                                        {{-- Submits via form="" — a <form> nested inside #bulkDeleteForm is
                                             invalid HTML and gets silently dropped by the browser. --}}
                                        <button type="submit" form="toggle-project-{{ $project->id }}" class="btn border-0 p-0 admin-status-toggle">
                                            @include('admin.partials.status-badge', ['status' => $project->status])
                                        </button>
                                    @else
                                        @include('admin.partials.status-badge', ['status' => $project->status])
                                    @endcan
                                </td>
                                <td class="text-end">
                                    @can('edit portfolio')
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    @endcan
                                    @can('delete portfolio')
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $project->name }}" data-action="{{ route('admin.projects.destroy', $project) }}"><i class="bi bi-trash3"></i></button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No projects found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @foreach($projects as $project)
            <form id="toggle-project-{{ $project->id }}" action="{{ route('admin.projects.toggle-status', $project) }}" method="POST" class="d-none">
                @csrf @method('PATCH')
            </form>
        @endforeach

        @if($projects->hasPages())
            <div class="p-3 border-top">{{ $projects->links() }}</div>
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
