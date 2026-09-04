@extends('layouts.admin')

@section('title', 'Job Applications')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Job Applications</h2>
            <p class="text-muted mb-0">{{ $applications->total() }} application{{ $applications->total() === 1 ? '' : 's' }} total — {{ $unreadCount }} unread.</p>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card__header flex-wrap">
            <form action="{{ route('admin.job-applications.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input type="search" name="search" class="form-control" placeholder="Search name or email..." value="{{ request('search') }}" style="min-width:220px;">
                <select name="status" class="form-select" style="min-width:140px;" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="unread" @selected(request('status') === 'unread')>Unread</option>
                    <option value="read" @selected(request('status') === 'read')>Read</option>
                </select>
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.job-applications.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>Applicant</th>
                            <th>Applied For</th>
                            <th>Phone</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr class="{{ $application->is_read ? '' : 'fw-semibold' }}">
                                <td><input type="checkbox" name="ids[]" value="{{ $application->id }}" class="form-check-input row-check"></td>
                                <td>
                                    <a href="{{ route('admin.job-applications.show', $application) }}" class="text-decoration-none text-reset">
                                        {{ $application->name }}
                                        <div class="text-muted small fw-normal">{{ $application->email }}</div>
                                    </a>
                                </td>
                                <td class="text-muted">{{ $application->jobOpening->title ?? 'General Application' }}</td>
                                <td class="text-muted fw-normal">{{ $application->phone ?: '—' }}</td>
                                <td class="text-muted fw-normal">{{ $application->created_at->diffForHumans() }}</td>
                                <td>
                                    @if($application->is_read)
                                        <span class="admin-badge admin-badge--info">Read</span>
                                    @else
                                        <span class="admin-badge admin-badge--warning">Unread</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.job-applications.show', $application) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-eye"></i></a>
                                    <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="the application from {{ $application->name }}" data-action="{{ route('admin.job-applications.destroy', $application) }}"><i class="bi bi-trash3"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No applications found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($applications->hasPages())
            <div class="p-3 border-top">{{ $applications->links() }}</div>
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
