@php
    $member ??= null;
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <div class="admin-card__header"><h3 class="admin-card__title">Member Details</h3></div>
            <div class="admin-card__body row g-3">
                <div class="col-md-6">
                    <label class="admin-form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $member->name ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Position</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position', $member->position ?? '') }}" required>
                </div>
                <div class="col-12">
                    <label class="admin-form-label">Bio</label>
                    <textarea name="bio" rows="4" class="form-control" maxlength="1000" data-char-counter="1000">{{ old('bio', $member->bio ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Social Links</h3></div>
            <div class="admin-card__body row g-3">
                <div class="col-md-6">
                    <label class="admin-form-label"><i class="bi bi-facebook me-1"></i>Facebook URL</label>
                    <input type="url" name="facebook_url" class="form-control" placeholder="https://" value="{{ old('facebook_url', $member->facebook_url ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label"><i class="bi bi-twitter-x me-1"></i>Twitter / X URL</label>
                    <input type="url" name="twitter_url" class="form-control" placeholder="https://" value="{{ old('twitter_url', $member->twitter_url ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label"><i class="bi bi-linkedin me-1"></i>LinkedIn URL</label>
                    <input type="url" name="linkedin_url" class="form-control" placeholder="https://" value="{{ old('linkedin_url', $member->linkedin_url ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label"><i class="bi bi-instagram me-1"></i>Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-control" placeholder="https://" value="{{ old('instagram_url', $member->instagram_url ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label"><i class="bi bi-github me-1"></i>GitHub URL</label>
                    <input type="url" name="github_url" class="form-control" placeholder="https://" value="{{ old('github_url', $member->github_url ?? '') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="admin-card__header"><h3 class="admin-card__title">Photo</h3></div>
            <div class="admin-card__body text-center">
                <label class="admin-upload-box d-block mb-0">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <div class="fw-semibold">Click or drag to upload</div>
                    <input type="file" name="photo" accept="image/*" data-preview-target="#photoPreview">
                </label>
                <img id="photoPreview" src="{{ $member?->photo ? asset($member->photo) : '' }}"
                     class="admin-upload-preview w-100 {{ $member?->photo ? '' : 'd-none' }}" alt="Photo preview">
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card__header"><h3 class="admin-card__title">Publishing</h3></div>
            <div class="admin-card__body d-flex flex-column gap-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" @checked(old('status', $member->status ?? true))>
                    <label class="form-check-label" for="statusSwitch">Active</label>
                </div>
                <div>
                    <label class="admin-form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $member->display_order ?? 0) }}">
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-admin-gradient px-5 mt-4">
    <i class="bi bi-check2-circle me-2"></i>{{ $member ? 'Update' : 'Save' }} Team Member
</button>
