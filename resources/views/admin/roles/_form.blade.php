@csrf
@isset($role)
    @method('PUT')
@endisset

<div class="admin-card mb-4">
    <div class="admin-card__header">
        <h3 class="admin-card__title">Role Details</h3>
    </div>
    <div class="admin-card__body">
        <label for="name" class="admin-form-label">Role Name</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $role->name ?? '') }}" placeholder="e.g. Content Manager" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="admin-card mb-4">
    <div class="admin-card__header">
        <h3 class="admin-card__title">Permissions</h3>
    </div>
    <div class="admin-card__body">
        <div class="row g-4">
            @foreach($permissionGroups as $group => $permissions)
                <div class="col-md-6 col-lg-4">
                    <div class="fw-semibold mb-2">{{ $group }}</div>
                    @foreach($permissions as $permission)
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                   id="perm-{{ Str::slug($permission) }}" value="{{ $permission }}"
                                   @checked(in_array($permission, old('permissions', $assignedPermissions)))>
                            <label class="form-check-label" for="perm-{{ Str::slug($permission) }}">{{ ucfirst($permission) }}</label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-admin-gradient">
        <i class="bi bi-check-lg me-2"></i>{{ isset($role) ? 'Update Role' : 'Create Role' }}
    </button>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-admin-soft">Cancel</a>
</div>
