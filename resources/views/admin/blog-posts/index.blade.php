@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Blog Posts</h2>
            <p class="text-muted mb-0">Manage your blog — {{ $posts->total() }} post{{ $posts->total() === 1 ? '' : 's' }} total.</p>
        </div>
        @can('create blogs')
            <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-admin-gradient">
                <i class="bi bi-plus-lg me-2"></i>Add Post
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card__header flex-wrap">
            <form action="{{ route('admin.blog-posts.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input type="search" name="search" class="form-control" placeholder="Search posts..." value="{{ request('search') }}" style="min-width:220px;">
                <select name="category" class="form-select" style="min-width:160px;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select" style="min-width:140px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                </select>
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.blog-posts.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            @can('delete blogs')
                                <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            @endcan
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Views</th>
                            <th>Published At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                @can('delete blogs')
                                    <td><input type="checkbox" name="ids[]" value="{{ $post->id }}" class="form-check-input row-check"></td>
                                @endcan
                                <td>
                                    @if($post->featured_image)
                                        <img src="{{ asset($post->featured_image) }}" class="admin-table-thumb" alt="{{ $post->title }}">
                                    @else
                                        <span class="admin-table-thumb d-flex align-items-center justify-content-center bg-light"><i class="bi bi-image text-muted"></i></span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $post->title }}</td>
                                <td>{{ $post->category?->name ?: '—' }}</td>
                                <td>
                                    @if($post->status === 'published')
                                        <span class="admin-badge admin-badge--success"><i class="bi bi-check-circle"></i> Published</span>
                                    @else
                                        <span class="admin-badge admin-badge--warning"><i class="bi bi-pencil-square"></i> Draft</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->is_featured)
                                        <span class="admin-badge admin-badge--warning"><i class="bi bi-star-fill"></i> Featured</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ number_format($post->views_count) }}</td>
                                <td class="text-muted small">{{ $post->published_at?->format('M d, Y') ?: '—' }}</td>
                                <td class="text-end">
                                    @can('edit blogs')
                                        <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    @endcan
                                    @can('delete blogs')
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $post->title }}" data-action="{{ route('admin.blog-posts.destroy', $post) }}"><i class="bi bi-trash3"></i></button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">No blog posts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($posts->hasPages())
            <div class="p-3 border-top">{{ $posts->links() }}</div>
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
