@extends('layouts.admin')

@section('title', 'SEO Manager')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1">SEO Manager</h2>
        <p class="text-muted mb-0">Manage meta titles, descriptions and Open Graph data for every indexable page.</p>
    </div>

    <div class="row g-4">
        @foreach($pages as $page)
            <div class="col-md-6 col-xl-3">
                <div class="admin-card h-100">
                    <div class="admin-card__body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="admin-stat-card__icon admin-bg-2 mb-0" style="width:44px;height:44px;font-size:1.1rem;">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-capitalize">{{ $page->page_key }}</h5>
                                <small class="text-muted">/{{ $page->page_key === 'home' ? '' : $page->page_key }}</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-3">
                            {{ $page->title ?: 'No SEO title set yet.' }}
                        </p>
                        <a href="{{ route('admin.seo.edit', $page->page_key) }}" class="btn btn-admin-soft btn-sm w-100">
                            <i class="bi bi-pencil-square me-1"></i>Edit SEO
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
