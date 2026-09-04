@php
    $user ??= null;
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <div class="admin-card__header"><h3 class="admin-card__title">Account Details</h3></div>
            <div class="admin-card__body row g-3">
                <div class="col-md-6">
                    <label class="admin-form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                </div>
            </div>
        </div>

        <div class="admin-card mb-4">
            <div class="admin-card__header"><h3 class="admin-card__title">Roles</h3></div>
            <div class="admin-card__body">
                @error('roles')
                    <div class="admin-alert admin-alert--danger mb-3"><i class="bi bi-exclamation-triangle-fill"></i><span>{{ $message }}</span></div>
                @enderror
                <div class="row g-2">
                    @php $assignedRoles = old('roles', $assignedRoles ?? []); @endphp
                    @foreach($roles as $role)
                        @php $isSuperAdmin = $role->name === 'Super Admin'; @endphp
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" id="role-{{ $role->id }}"
                                       value="{{ $role->name }}"
                                       @checked(in_array($role->name, $assignedRoles))
                                       @disabled($isSuperAdmin && ! auth()->user()->hasRole('Super Admin'))>
                                <label class="form-check-label" for="role-{{ $role->id }}">
                                    {{ $role->name }}
                                    @if($isSuperAdmin)
                                        <span class="admin-badge admin-badge--info ms-1" style="font-size:.65rem;">Full access</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if(! auth()->user()->hasRole('Super Admin'))
                    <p class="text-muted small mt-2 mb-0">Only a Super Admin can assign the Super Admin role.</p>
                @endif
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">{{ $user ? 'Change Password' : 'Set Password' }}</h3></div>
            <div class="admin-card__body row g-3">
                @if($user)
                    <div class="col-12"><p class="text-muted small mb-0">Leave blank to keep the current password.</p></div>
                @endif
                <div class="col-md-6">
                    <label class="admin-form-label">Password</label>
                    <input type="password" name="password" class="form-control" {{ $user ? '' : 'required' }}>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" {{ $user ? '' : 'required' }}>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="admin-card__header"><h3 class="admin-card__title">Avatar</h3></div>
            <div class="admin-card__body text-center">
                <label class="admin-upload-box d-block mb-0">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <div class="fw-semibold">Click or drag to upload</div>
                    <input type="file" name="avatar" accept="image/*" data-preview-target="#avatarPreview">
                </label>
                <img id="avatarPreview" src="{{ $user?->avatar ? asset($user->avatar) : '' }}"
                     class="admin-upload-preview w-100 rounded-circle {{ $user?->avatar ? '' : 'd-none' }}" alt="Avatar preview">
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Status</h3></div>
            <div class="admin-card__body">
                @if($user && $user->id === auth()->id())
                    <p class="text-muted small mb-0">You can't deactivate your own account.</p>
                @else
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeSwitch" @checked(old('is_active', $user->is_active ?? true))>
                        <label class="form-check-label" for="activeSwitch">Active</label>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-admin-gradient px-5 mt-4">
    <i class="bi bi-check2-circle me-2"></i>{{ $user ? 'Update' : 'Save' }} User
</button>
