@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Roles &amp; Permissions</h2>
            <p class="text-muted mb-0">Manage who can do what inside the admin panel.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-admin-gradient">
            <i class="bi bi-plus-lg me-2"></i>Add Role
        </a>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table admin-table--stack mb-0">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th>Users</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td data-label="Role">
                                <span class="fw-semibold">{{ $role->name }}</span>
                                @if($role->name === $protectedRole)
                                    <span class="admin-badge admin-badge--info ms-2"><i class="bi bi-shield-lock-fill"></i> Protected</span>
                                @endif
                            </td>
                            <td data-label="Permissions">
                                @if($role->name === $protectedRole)
                                    <span class="admin-badge admin-badge--success">All permissions</span>
                                @else
                                    <span class="admin-badge admin-badge--info">{{ $role->permissions_count }} permission{{ $role->permissions_count === 1 ? '' : 's' }}</span>
                                @endif
                            </td>
                            <td data-label="Users">{{ $role->users_count }}</td>
                            <td class="text-end" data-label="Actions">
                                @if($role->name === $protectedRole)
                                    <span class="text-muted small">Not editable</span>
                                @else
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $role->name }}" data-action="{{ route('admin.roles.destroy', $role) }}"><i class="bi bi-trash3"></i></button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
