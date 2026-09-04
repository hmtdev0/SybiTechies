@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1">My Profile</h2>
        <p class="text-muted mb-0">Manage your account details and password.</p>
    </div>

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card mb-4">
                    <div class="admin-card__header"><h3 class="admin-card__title">Account Details</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-md-6">
                            <label class="admin-form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Role</label>
                            <input type="text" class="form-control" value="{{ $user->role }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="admin-form-label">Last Login</label>
                            <input type="text" class="form-control" value="{{ $user->last_login_at?->format('M j, Y g:i A') ?? 'Never' }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">Change Password</h3></div>
                    <div class="admin-card__body row g-3">
                        <div class="col-12"><p class="text-muted small mb-0">Leave the new password fields blank to keep your current password.</p></div>
                        <div class="col-md-4">
                            <label class="admin-form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                        </div>
                        <div class="col-md-4">
                            <label class="admin-form-label">New Password</label>
                            <input type="password" name="password" class="form-control" autocomplete="new-password">
                        </div>
                        <div class="col-md-4">
                            <label class="admin-form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card__header"><h3 class="admin-card__title">Avatar</h3></div>
                    <div class="admin-card__body text-center">
                        <label class="admin-upload-box d-block mb-0">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="fw-semibold">Click or drag to upload</div>
                            <input type="file" name="avatar" accept="image/*" data-preview-target="#avatarPreview">
                        </label>
                        <img id="avatarPreview" src="{{ $user->avatar ? asset($user->avatar) : '' }}"
                             class="admin-upload-preview w-100 rounded-circle {{ $user->avatar ? '' : 'd-none' }}" alt="Avatar preview">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-admin-gradient px-5 mt-4">
            <i class="bi bi-check2-circle me-2"></i>Save Changes
        </button>
    </form>
@endsection
