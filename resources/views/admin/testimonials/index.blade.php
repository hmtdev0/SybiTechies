@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Testimonials</h2>
            <p class="text-muted mb-0">Manage client testimonials shown on the homepage.</p>
        </div>
        @can('create testimonials')
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-admin-gradient">
                <i class="bi bi-plus-lg me-2"></i>Add Testimonial
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card__header">
            <form action="{{ route('admin.testimonials.index') }}" method="GET" class="d-flex gap-2">
                <input type="search" name="search" class="form-control" placeholder="Search testimonials..." value="{{ request('search') }}" style="min-width:240px;">
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.testimonials.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            @can('delete testimonials')
                                <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            @endcan
                            <th>Photo</th>
                            <th>Client Name</th>
                            <th>Company</th>
                            <th>Rating</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                            <tr>
                                @can('delete testimonials')
                                    <td><input type="checkbox" name="ids[]" value="{{ $testimonial->id }}" class="form-check-input row-check"></td>
                                @endcan
                                <td>
                                    @if($testimonial->photo)
                                        <img src="{{ asset($testimonial->photo) }}" class="admin-table-thumb" alt="{{ $testimonial->client_name }}">
                                    @else
                                        <span class="admin-table-thumb d-flex align-items-center justify-content-center bg-light"><i class="bi bi-person-circle text-muted"></i></span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $testimonial->client_name }}</td>
                                <td>{{ $testimonial->company ?: '—' }}</td>
                                <td>
                                    @for($s = 0; $s < 5; $s++)
                                        <i class="bi {{ $s < $testimonial->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </td>
                                <td>{{ $testimonial->display_order }}</td>
                                <td>
                                    @can('publish testimonials')
                                        {{-- Submits via form="" — a <form> nested inside #bulkDeleteForm is
                                             invalid HTML and gets silently dropped by the browser. --}}
                                        <button type="submit" form="toggle-testimonial-{{ $testimonial->id }}" class="btn border-0 p-0 admin-status-toggle">
                                            @include('admin.partials.status-badge', ['status' => $testimonial->status])
                                        </button>
                                    @else
                                        @include('admin.partials.status-badge', ['status' => $testimonial->status])
                                    @endcan
                                </td>
                                <td class="text-end">
                                    @can('edit testimonials')
                                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn-admin-icon btn-admin-icon--edit"><i class="bi bi-pencil"></i></a>
                                    @endcan
                                    @can('delete testimonials')
                                        <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $testimonial->client_name }}" data-action="{{ route('admin.testimonials.destroy', $testimonial) }}"><i class="bi bi-trash3"></i></button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No testimonials found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @foreach($testimonials as $testimonial)
            <form id="toggle-testimonial-{{ $testimonial->id }}" action="{{ route('admin.testimonials.toggle-status', $testimonial) }}" method="POST" class="d-none">
                @csrf @method('PATCH')
            </form>
        @endforeach

        @if($testimonials->hasPages())
            <div class="p-3 border-top">{{ $testimonials->links() }}</div>
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
