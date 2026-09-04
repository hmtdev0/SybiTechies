@extends('layouts.admin')

@section('title', 'Development Process')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Development Process</h2>
            <p class="text-muted mb-0">Manage the process steps shown on the homepage.</p>
        </div>
        <a href="{{ route('admin.process-steps.create') }}" class="btn btn-admin-gradient">
            <i class="bi bi-plus-lg me-2"></i>Add Process Step
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card__header">
            <form action="{{ route('admin.process-steps.index') }}" method="GET" class="d-flex gap-2">
                <input type="search" name="search" class="form-control" placeholder="Search process steps..." value="{{ request('search') }}" style="min-width:240px;">
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.process-steps.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>Step #</th>
                            <th>Icon</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Order</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($processSteps as $step)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $step->id }}" class="form-check-input row-check"></td>
                                <td>{{ $step->step_number }}</td>
                                <td><i class="bi {{ $step->icon }}" style="font-size:1.4rem;"></i></td>
                                <td class="fw-semibold">{{ $step->title }}</td>
                                <td class="text-muted small">{{ Str::limit($step->description, 50) ?: '—' }}</td>
                                <td>{{ $step->display_order }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.process-steps.edit', $step) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $step->title }}" data-action="{{ route('admin.process-steps.destroy', $step) }}"><i class="bi bi-trash3"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No process steps found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($processSteps->hasPages())
            <div class="p-3 border-top">{{ $processSteps->links() }}</div>
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
