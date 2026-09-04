@extends('layouts.admin')

@section('title', 'Job Openings')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Job Openings</h2>
            <p class="text-muted mb-0">Manage open positions — {{ $jobOpenings->total() }} opening{{ $jobOpenings->total() === 1 ? '' : 's' }} total.</p>
        </div>
        <a href="{{ route('admin.job-openings.create') }}" class="btn btn-admin-gradient">
            <i class="bi bi-plus-lg me-2"></i>Add Job Opening
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card__header flex-wrap">
            <form action="{{ route('admin.job-openings.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input type="search" name="search" class="form-control" placeholder="Search job openings..." value="{{ request('search') }}" style="min-width:220px;">
                <select name="status" class="form-select" style="min-width:140px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status') === 'active')>Open</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Closed</option>
                </select>
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.job-openings.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobOpenings as $jobOpening)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $jobOpening->id }}" class="form-check-input row-check"></td>
                                <td class="fw-semibold">{{ $jobOpening->title }}</td>
                                <td class="text-muted">{{ $jobOpening->department ?: '—' }}</td>
                                <td class="text-muted">{{ $jobOpening->location ?: '—' }}</td>
                                <td><span class="admin-badge admin-badge--info">{{ $jobOpening->type }}</span></td>
                                <td>{{ $jobOpening->display_order }}</td>
                                <td>
                                    {{-- Submits via form="" — a <form> nested inside #bulkDeleteForm is
                                         invalid HTML and gets silently dropped by the browser. --}}
                                    <button type="submit" form="toggle-opening-{{ $jobOpening->id }}" class="btn border-0 p-0 admin-status-toggle">
                                        @if($jobOpening->status)
                                            <span class="admin-badge admin-badge--success"><i class="bi bi-check-circle"></i> Open</span>
                                        @else
                                            <span class="admin-badge admin-badge--danger"><i class="bi bi-x-circle"></i> Closed</span>
                                        @endif
                                    </button>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.job-openings.edit', $jobOpening) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $jobOpening->title }}" data-action="{{ route('admin.job-openings.destroy', $jobOpening) }}"><i class="bi bi-trash3"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No job openings found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @foreach($jobOpenings as $jobOpening)
            <form id="toggle-opening-{{ $jobOpening->id }}" action="{{ route('admin.job-openings.toggle-status', $jobOpening) }}" method="POST" class="d-none">
                @csrf @method('PATCH')
            </form>
        @endforeach

        @if($jobOpenings->hasPages())
            <div class="p-3 border-top">{{ $jobOpenings->links() }}</div>
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
