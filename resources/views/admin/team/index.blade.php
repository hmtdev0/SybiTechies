@extends('layouts.admin')

@section('title', 'Team Members')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Team Members</h2>
            <p class="text-muted mb-0">Manage the team profiles shown on the About page.</p>
        </div>
        @can('create team')
            <a href="{{ route('admin.team.create') }}" class="btn btn-admin-gradient">
                <i class="bi bi-plus-lg me-2"></i>Add Team Member
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card__header">
            <form action="{{ route('admin.team.index') }}" method="GET" class="d-flex gap-2">
                <input type="search" name="search" class="form-control" placeholder="Search team members..." value="{{ request('search') }}" style="min-width:240px;">
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.team.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            @can('delete team')
                                <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            @endcan
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                @can('delete team')
                                    <td><input type="checkbox" name="ids[]" value="{{ $member->id }}" class="form-check-input row-check"></td>
                                @endcan
                                <td>
                                    @if($member->photo)
                                        <img src="{{ asset($member->photo) }}" class="admin-table-thumb" alt="{{ $member->name }}">
                                    @else
                                        <span class="admin-table-thumb d-flex align-items-center justify-content-center bg-light"><i class="bi bi-person-circle text-muted"></i></span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $member->name }}</td>
                                <td class="text-muted small">{{ $member->position }}</td>
                                <td>{{ $member->display_order }}</td>
                                <td>
                                    @can('edit team')
                                        {{-- Submits via form="" — a <form> nested inside #bulkDeleteForm is
                                             invalid HTML and gets silently dropped by the browser. --}}
                                        <button type="submit" form="toggle-member-{{ $member->id }}" class="btn border-0 p-0 admin-status-toggle">
                                            @include('admin.partials.status-badge', ['status' => $member->status])
                                        </button>
                                    @else
                                        @include('admin.partials.status-badge', ['status' => $member->status])
                                    @endcan
                                </td>
                                <td class="text-end">
                                    @can('edit team')
                                        <a href="{{ route('admin.team.edit', $member) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    @endcan
                                    @can('delete team')
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $member->name }}" data-action="{{ route('admin.team.destroy', $member) }}"><i class="bi bi-trash3"></i></button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No team members found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @foreach($members as $member)
            <form id="toggle-member-{{ $member->id }}" action="{{ route('admin.team.toggle-status', $member) }}" method="POST" class="d-none">
                @csrf @method('PATCH')
            </form>
        @endforeach

        @if($members->hasPages())
            <div class="p-3 border-top">{{ $members->links() }}</div>
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
