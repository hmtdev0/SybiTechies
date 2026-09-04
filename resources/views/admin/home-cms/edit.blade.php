@extends('layouts.admin')

@section('title', 'Home Page CMS')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Home Page CMS</h2>
        <p class="text-muted mb-0">Everything shown on the homepage — hero, statistics, trusted companies and why-choose-us — is editable here.</p>
    </div>

    <ul class="nav nav-pills gap-2 mb-4" id="homeCmsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-hero" type="button">Hero</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-stats" type="button">Statistics</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-companies" type="button">Trusted Companies</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-whyus" type="button">Why Choose Us</button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- =============== HERO =============== --}}
        <div class="tab-pane fade show active" id="tab-hero">
            <form action="{{ route('admin.home-cms.hero.update') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="admin-card">
                            <div class="admin-card__header"><h3 class="admin-card__title">Hero Content</h3></div>
                            <div class="admin-card__body row g-3">
                                <div class="col-12">
                                    <label class="admin-form-label">Badge Text</label>
                                    <input type="text" name="badge_text" class="form-control" value="{{ old('badge_text', $hero->badge_text) }}">
                                </div>
                                <div class="col-12">
                                    <label class="admin-form-label">Title</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $hero->title) }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="admin-form-label">Highlighted Text <small class="text-muted">(gradient sub-string within the title)</small></label>
                                    <input type="text" name="highlight_text" class="form-control" value="{{ old('highlight_text', $hero->highlight_text) }}">
                                </div>
                                <div class="col-12">
                                    <label class="admin-form-label">Typed Rotating Words <small class="text-muted">(one per line)</small></label>
                                    <textarea name="typed_words" rows="5" class="form-control">{{ old('typed_words', implode("\n", $hero->typed_words ?? [])) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="admin-form-label">Description</label>
                                    <textarea name="description" rows="3" class="form-control" maxlength="1000" data-char-counter="1000">{{ old('description', $hero->description) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-form-label">Button 1 Text</label>
                                    <input type="text" name="btn1_text" class="form-control" value="{{ old('btn1_text', $hero->btn1_text) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-form-label">Button 1 Link</label>
                                    <input type="text" name="btn1_link" class="form-control" value="{{ old('btn1_link', $hero->btn1_link) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-form-label">Button 2 Text</label>
                                    <input type="text" name="btn2_text" class="form-control" value="{{ old('btn2_text', $hero->btn2_text) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-form-label">Button 2 Link</label>
                                    <input type="text" name="btn2_link" class="form-control" value="{{ old('btn2_link', $hero->btn2_link) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="admin-card">
                            <div class="admin-card__header"><h3 class="admin-card__title">Hero Image</h3></div>
                            <div class="admin-card__body text-center">
                                <label class="admin-upload-box d-block mb-0">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <div class="fw-semibold">Click or drag to upload</div>
                                    <small class="text-muted">Leave empty to keep the default illustration</small>
                                    <input type="file" name="image" accept="image/*" data-preview-target="#heroImagePreview">
                                </label>
                                <img id="heroImagePreview" src="{{ $hero->image ? asset($hero->image) : '' }}"
                                     class="admin-upload-preview w-100 {{ $hero->image ? '' : 'd-none' }}" alt="Hero image preview">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-admin-gradient px-5 mt-4">
                    <i class="bi bi-check2-circle me-2"></i>Save Hero
                </button>
            </form>

            {{-- Floating stat cards --}}
            <div class="admin-card">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Floating Stat Cards</h3>
                    <button class="btn btn-admin-soft btn-sm" data-bs-toggle="modal" data-bs-target="#addHeroStatModal"><i class="bi bi-plus-lg me-1"></i>Add</button>
                </div>
                <div class="table-responsive">
                    <table class="admin-table mb-0">
                        <thead><tr><th>Icon</th><th>Number</th><th>Suffix</th><th>Label</th><th>Order</th><th></th></tr></thead>
                        <tbody>
                            @forelse($heroStats as $stat)
                                <tr>
                                    <td><i class="bi {{ $stat->icon }}"></i></td>
                                    <td class="fw-semibold">{{ $stat->number }}</td>
                                    <td>{{ $stat->suffix }}</td>
                                    <td>{{ $stat->label }}</td>
                                    <td>{{ $stat->display_order }}</td>
                                    <td class="text-end">
                                        <button class="btn-admin-icon btn-admin-icon--edit" data-bs-toggle="modal" data-bs-target="#editHeroStatModal{{ $stat->id }}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="this stat" data-action="{{ route('admin.home-cms.hero-stats.destroy', $stat) }}"><i class="bi bi-trash3"></i></button>
                                    </td>
                                </tr>
                                @include('admin.home-cms.partials.stat-modal', ['modalId' => 'editHeroStatModal'.$stat->id, 'action' => route('admin.home-cms.hero-stats.update', $stat), 'item' => $stat, 'title' => 'Edit Floating Stat'])
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No floating stats yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @include('admin.home-cms.partials.stat-modal', ['modalId' => 'addHeroStatModal', 'action' => route('admin.home-cms.hero-stats.store'), 'item' => null, 'title' => 'Add Floating Stat'])
        </div>

        {{-- =============== MAIN STATISTICS =============== --}}
        <div class="tab-pane fade" id="tab-stats">
            <div class="admin-card">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Statistics Band</h3>
                    <button class="btn btn-admin-soft btn-sm" data-bs-toggle="modal" data-bs-target="#addStatModal"><i class="bi bi-plus-lg me-1"></i>Add</button>
                </div>
                <div class="table-responsive">
                    <table class="admin-table mb-0">
                        <thead><tr><th>Icon</th><th>Number</th><th>Suffix</th><th>Label</th><th>Order</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @forelse($mainStats as $stat)
                                <tr>
                                    <td><i class="bi {{ $stat->icon }}"></i></td>
                                    <td class="fw-semibold">{{ $stat->number }}</td>
                                    <td>{{ $stat->suffix }}</td>
                                    <td>{{ $stat->label }}</td>
                                    <td>{{ $stat->display_order }}</td>
                                    <td>@include('admin.partials.status-badge', ['status' => $stat->status])</td>
                                    <td class="text-end">
                                        <button class="btn-admin-icon btn-admin-icon--edit" data-bs-toggle="modal" data-bs-target="#editStatModal{{ $stat->id }}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="this statistic" data-action="{{ route('admin.home-cms.statistics.destroy', $stat) }}"><i class="bi bi-trash3"></i></button>
                                    </td>
                                </tr>
                                @include('admin.home-cms.partials.stat-modal', ['modalId' => 'editStatModal'.$stat->id, 'action' => route('admin.home-cms.statistics.update', $stat), 'item' => $stat, 'title' => 'Edit Statistic', 'withStatus' => true])
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No statistics yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @include('admin.home-cms.partials.stat-modal', ['modalId' => 'addStatModal', 'action' => route('admin.home-cms.statistics.store'), 'item' => null, 'title' => 'Add Statistic', 'withStatus' => true])
        </div>

        {{-- =============== TRUSTED COMPANIES =============== --}}
        <div class="tab-pane fade" id="tab-companies">
            <div class="admin-card">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Trusted Companies</h3>
                    <button class="btn btn-admin-soft btn-sm" data-bs-toggle="modal" data-bs-target="#addCompanyModal"><i class="bi bi-plus-lg me-1"></i>Add</button>
                </div>
                <div class="table-responsive">
                    <table class="admin-table mb-0">
                        <thead><tr><th>Logo</th><th>Name</th><th>Order</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @forelse($trustedCompanies as $company)
                                <tr>
                                    <td>@if($company->logo)<img src="{{ asset($company->logo) }}" class="admin-table-thumb" alt="">@else <i class="bi bi-hexagon text-muted"></i> @endif</td>
                                    <td class="fw-semibold">{{ $company->name }}</td>
                                    <td>{{ $company->display_order }}</td>
                                    <td>@include('admin.partials.status-badge', ['status' => $company->status])</td>
                                    <td class="text-end">
                                        <button class="btn-admin-icon btn-admin-icon--edit" data-bs-toggle="modal" data-bs-target="#editCompanyModal{{ $company->id }}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="this company" data-action="{{ route('admin.home-cms.trusted-companies.destroy', $company) }}"><i class="bi bi-trash3"></i></button>
                                    </td>
                                </tr>
                                @include('admin.home-cms.partials.company-modal', ['modalId' => 'editCompanyModal'.$company->id, 'action' => route('admin.home-cms.trusted-companies.update', $company), 'item' => $company, 'title' => 'Edit Company'])
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No trusted companies yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @include('admin.home-cms.partials.company-modal', ['modalId' => 'addCompanyModal', 'action' => route('admin.home-cms.trusted-companies.store'), 'item' => null, 'title' => 'Add Company'])
        </div>

        {{-- =============== WHY CHOOSE US =============== --}}
        <div class="tab-pane fade" id="tab-whyus">
            <div class="admin-card">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Why Choose Us Items</h3>
                    <button class="btn btn-admin-soft btn-sm" data-bs-toggle="modal" data-bs-target="#addWhyUsModal"><i class="bi bi-plus-lg me-1"></i>Add</button>
                </div>
                <div class="table-responsive">
                    <table class="admin-table mb-0">
                        <thead><tr><th>Icon</th><th>Title</th><th>Order</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @forelse($whyUsItems as $item)
                                <tr>
                                    <td><i class="bi {{ $item->icon }}"></i></td>
                                    <td class="fw-semibold">{{ $item->title }}</td>
                                    <td>{{ $item->display_order }}</td>
                                    <td>@include('admin.partials.status-badge', ['status' => $item->status])</td>
                                    <td class="text-end">
                                        <button class="btn-admin-icon btn-admin-icon--edit" data-bs-toggle="modal" data-bs-target="#editWhyUsModal{{ $item->id }}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="this item" data-action="{{ route('admin.home-cms.why-us.destroy', $item) }}"><i class="bi bi-trash3"></i></button>
                                    </td>
                                </tr>
                                @include('admin.home-cms.partials.icon-text-modal', ['modalId' => 'editWhyUsModal'.$item->id, 'action' => route('admin.home-cms.why-us.update', $item), 'item' => $item, 'title' => 'Edit Item'])
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No items yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @include('admin.home-cms.partials.icon-text-modal', ['modalId' => 'addWhyUsModal', 'action' => route('admin.home-cms.why-us.store'), 'item' => null, 'title' => 'Add Item'])
        </div>

    </div>
@endsection
