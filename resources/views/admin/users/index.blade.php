@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Users</h2>
            <p class="text-muted mb-0">Manage admin panel accounts — {{ $users->total() }} user{{ $users->total() === 1 ? '' : 's' }} total.</p>
        </div>
        @can('create users')
            <a href="{{ route('admin.users.create') }}" class="btn btn-admin-gradient">
                <i class="bi bi-plus-lg me-2"></i>Add User
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card__header flex-wrap">
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input type="search" name="search" class="form-control" placeholder="Search name or email..." value="{{ request('search') }}" style="min-width:240px;">
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.users.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            @can('delete users')
                                <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            @endcan
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Last Login</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                @can('delete users')
                                    <td>
                                        @if($user->id !== auth()->id() && ! ($user->hasRole('Super Admin') && ! auth()->user()->hasRole('Super Admin')))
                                            <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="form-check-input row-check">
                                        @endif
                                    </td>
                                @endcan
                                <td>
                                    @if($user->avatar)
                                        <img src="{{ asset($user->avatar) }}" class="admin-table-thumb rounded-circle" alt="{{ $user->name }}">
                                    @else
                                        <span class="admin-table-thumb rounded-circle d-flex align-items-center justify-content-center bg-light"><i class="bi bi-person-circle text-muted"></i></span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $user->name }} @if($user->id === auth()->id())<span class="admin-badge admin-badge--info ms-1">You</span>@endif</td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="admin-badge admin-badge--info">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted small">No role</span>
                                    @endforelse
                                </td>
                                <td class="text-muted">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                                <td>
                                    @can('edit users')
                                        @if($user->id === auth()->id() || ($user->hasRole('Super Admin') && ! auth()->user()->hasRole('Super Admin')))
                                            @include('admin.partials.status-badge', ['status' => $user->is_active])
                                        @else
                                            {{-- Submits via form="" — a <form> nested inside #bulkDeleteForm is
                                                 invalid HTML and gets silently dropped by the browser. --}}
                                            <button type="submit" form="toggle-user-{{ $user->id }}" class="btn border-0 p-0 admin-status-toggle">
                                                @include('admin.partials.status-badge', ['status' => $user->is_active])
                                            </button>
                                        @endif
                                    @else
                                        @include('admin.partials.status-badge', ['status' => $user->is_active])
                                    @endcan
                                </td>
                                <td class="text-end">
                                    @php $isUntouchableSuperAdmin = $user->hasRole('Super Admin') && ! auth()->user()->hasRole('Super Admin'); @endphp
                                    @can('edit users')
                                        @unless($isUntouchableSuperAdmin)
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                        @endunless
                                    @endcan
                                    @can('delete users')
                                        @if($user->id !== auth()->id() && ! $isUntouchableSuperAdmin)
                                            <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $user->name }}" data-action="{{ route('admin.users.destroy', $user) }}"><i class="bi bi-trash3"></i></button>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @foreach($users as $user)
            <form id="toggle-user-{{ $user->id }}" action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-none">
                @csrf @method('PATCH')
            </form>
        @endforeach

        @if($users->hasPages())
            <div class="p-3 border-top">{{ $users->links() }}</div>
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
