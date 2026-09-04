@extends('layouts.admin')

@section('title', 'Custom Scripts')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Custom Scripts</h2>
            <p class="text-muted mb-0">Insert tracking pixels, analytics, chat widgets or other snippets into the public site's &lt;head&gt; or footer — no code changes needed.</p>
        </div>
        <a href="{{ route('admin.custom-scripts.create') }}" class="btn btn-admin-gradient">
            <i class="bi bi-plus-lg me-2"></i>Add Script
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card__header flex-wrap">
            <form action="{{ route('admin.custom-scripts.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input type="search" name="search" class="form-control" placeholder="Search scripts..." value="{{ request('search') }}" style="min-width:220px;">
                <select name="placement" class="form-select" style="min-width:140px;" onchange="this.form.submit()">
                    <option value="">All Placements</option>
                    <option value="head" @selected(request('placement') === 'head')>Head</option>
                    <option value="footer" @selected(request('placement') === 'footer')>Footer</option>
                </select>
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.custom-scripts.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table admin-table--stack mb-0">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>Name</th>
                            <th>Placement</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scripts as $script)
                            <tr>
                                <td data-label=""><input type="checkbox" name="ids[]" value="{{ $script->id }}" class="form-check-input row-check"></td>
                                <td class="fw-semibold" data-label="Name">{{ $script->name }}</td>
                                <td data-label="Placement">
                                    <span class="admin-badge admin-badge--info">
                                        <i class="bi {{ $script->placement === 'head' ? 'bi-code-square' : 'bi-layout-text-window-reverse' }}"></i>
                                        {{ ucfirst($script->placement) }}
                                    </span>
                                </td>
                                <td data-label="Status">
                                    {{-- Submits via the `form` attribute rather than wrapping this in its
                                         own <form> — nested forms are invalid HTML, and browsers silently
                                         drop them (this table already sits inside #bulkDeleteForm). --}}
                                    <button type="submit" form="toggle-script-{{ $script->id }}" class="btn border-0 p-0 admin-status-toggle">
                                        @include('admin.partials.status-badge', ['status' => $script->status])
                                    </button>
                                </td>
                                <td class="text-end" data-label="Actions">
                                    <a href="{{ route('admin.custom-scripts.edit', $script) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $script->name }}" data-action="{{ route('admin.custom-scripts.destroy', $script) }}"><i class="bi bi-trash3"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="admin-empty-state">
                                        <div class="admin-empty-state__icon"><i class="bi bi-code-slash"></i></div>
                                        <p class="admin-empty-state__title">No custom scripts yet</p>
                                        <p class="admin-empty-state__desc">Add one to inject tracking codes or widgets into the public site without touching any files.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        {{-- Out-of-band toggle-status forms — see comment above, kept outside
             #bulkDeleteForm so each one is valid, independently-submittable HTML. --}}
        @foreach($scripts as $script)
            <form id="toggle-script-{{ $script->id }}" action="{{ route('admin.custom-scripts.toggle-status', $script) }}" method="POST" class="d-none">
                @csrf @method('PATCH')
            </form>
        @endforeach

        @if($scripts->hasPages())
            <div class="p-3 border-top">{{ $scripts->links() }}</div>
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
