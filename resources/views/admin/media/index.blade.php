@extends('layouts.admin')

@section('title', 'Media Library')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Media Library</h2>
            <p class="text-muted mb-0">Upload and manage files. Copy a file's URL to use it anywhere in the site.</p>
        </div>
    </div>

    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" id="mediaUploadForm">
        @csrf
        <label class="admin-upload-box mb-4" for="mediaUploadInput">
            <i class="bi bi-cloud-arrow-up-fill"></i>
            <div class="fw-semibold">Drag &amp; drop files here or click to browse</div>
            <div class="text-muted small">JPG, PNG, WEBP, GIF, SVG or PDF — up to 8 MB each. Multiple files allowed.</div>
            <input type="file" id="mediaUploadInput" name="files[]" multiple accept=".jpg,.jpeg,.png,.webp,.gif,.svg,.pdf">
        </label>
    </form>

    <div class="admin-card">
        <div class="admin-card__header">
            <form action="{{ route('admin.media.index') }}" method="GET" class="d-flex gap-2">
                <input type="search" name="search" class="form-control" placeholder="Search files by name..." value="{{ request('search') }}" style="min-width:240px;">
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <div class="d-flex align-items-center gap-3">
                <label class="d-flex align-items-center gap-2 mb-0 small text-muted">
                    <input type="checkbox" id="selectAll" class="form-check-input" form="bulkDeleteForm"> Select all
                </label>
                <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                    <i class="bi bi-trash3 me-1"></i>Delete Selected
                </button>
            </div>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.media.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="p-3">
                @if($mediaFiles->count())
                    <div class="admin-media-grid">
                        @foreach($mediaFiles as $file)
                            @php
                                $url = asset($file->file_path);
                                $isImage = str_starts_with($file->file_type ?? '', 'image/');
                                $icon = match (true) {
                                    $file->file_type === 'application/pdf' => 'bi-file-earmark-pdf',
                                    default => 'bi-file-earmark',
                                };
                                $bytes = (int) $file->file_size;
                                $sizeLabel = match (true) {
                                    $bytes >= 1048576 => number_format($bytes / 1048576, 1).' MB',
                                    $bytes >= 1024 => number_format($bytes / 1024, 0).' KB',
                                    default => $bytes.' B',
                                };
                            @endphp
                            <div class="admin-media-card">
                                <input type="checkbox" name="ids[]" value="{{ $file->id }}" class="form-check-input row-check admin-media-card__check">
                                <div class="admin-media-card__thumb">
                                    @if($isImage)
                                        <img src="{{ $url }}" alt="{{ $file->name }}" loading="lazy">
                                    @else
                                        <i class="bi {{ $icon }}"></i>
                                    @endif
                                </div>
                                <div class="admin-media-card__body">
                                    <div class="admin-media-card__name" title="{{ $file->name }}">{{ Str::limit($file->name, 22) }}</div>
                                    <div class="admin-media-card__size text-muted">{{ $sizeLabel }}</div>
                                </div>
                                <div class="admin-media-card__actions">
                                    <button type="button" class="btn btn-admin-soft btn-sm admin-media-copy" data-url="{{ $url }}">
                                        <i class="bi bi-link-45deg me-1"></i><span>Copy URL</span>
                                    </button>
                                    <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $file->name }}" data-action="{{ route('admin.media.destroy', $file) }}"><i class="bi bi-trash3"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">No media files found.</div>
                @endif
            </div>
        </form>

        @if($mediaFiles->hasPages())
            <div class="p-3 border-top">{{ $mediaFiles->links() }}</div>
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

    // Auto-submit the multi-file upload form as soon as files are chosen.
    (function () {
        var input = document.getElementById('mediaUploadInput');
        var form = document.getElementById('mediaUploadForm');
        if (input && form) {
            input.addEventListener('change', function () {
                if (input.files && input.files.length) {
                    form.submit();
                }
            });
        }
    })();

    // Copy-to-clipboard for each media card's URL.
    document.querySelectorAll('.admin-media-copy').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var url = btn.getAttribute('data-url');
            var label = btn.querySelector('span');
            navigator.clipboard.writeText(url).then(function () {
                var original = label.textContent;
                label.textContent = 'Copied!';
                setTimeout(function () { label.textContent = original; }, 1500);
            });
        });
    });
</script>
@endpush
