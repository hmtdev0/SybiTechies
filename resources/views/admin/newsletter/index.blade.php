@extends('layouts.admin')

@section('title', 'Newsletter')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Newsletter</h2>
            <p class="text-muted mb-0">View and manage newsletter subscribers collected from the public site.</p>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-sm-6">
            <div class="admin-stat-card">
                <div class="admin-stat-card__icon admin-bg-1"><i class="bi bi-people-fill"></i></div>
                <div class="admin-stat-card__num">{{ $totalSubscribers }}</div>
                <div class="admin-stat-card__label">Total Subscribers</div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="admin-stat-card">
                <div class="admin-stat-card__icon admin-bg-4"><i class="bi bi-check-circle-fill"></i></div>
                <div class="admin-stat-card__num">{{ $totalSubscribed }}</div>
                <div class="admin-stat-card__label">Subscribed</div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="admin-stat-card">
                <div class="admin-stat-card__icon admin-bg-3"><i class="bi bi-x-circle-fill"></i></div>
                <div class="admin-stat-card__num">{{ $totalUnsubscribed }}</div>
                <div class="admin-stat-card__label">Unsubscribed</div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card__header flex-wrap">
            <form action="{{ route('admin.newsletter.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input type="search" name="search" class="form-control" placeholder="Search by email..." value="{{ request('search') }}" style="min-width:220px;">
                <select name="status" class="form-select" style="min-width:160px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="subscribed" @selected(request('status') === 'subscribed')>Subscribed</option>
                    <option value="unsubscribed" @selected(request('status') === 'unsubscribed')>Unsubscribed</option>
                </select>
                <button type="submit" class="btn btn-admin-soft"><i class="bi bi-search"></i></button>
            </form>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.newsletter.export', request()->query()) }}" class="btn btn-admin-soft">
                    <i class="bi bi-download me-1"></i>Export CSV
                </a>
                <button type="submit" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="bulkDeleteBtn">
                    <i class="bi bi-trash3 me-1"></i>Delete Selected
                </button>
            </div>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.newsletter.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="admin-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Subscribed At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscribers as $subscriber)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $subscriber->id }}" class="form-check-input row-check"></td>
                                <td class="fw-semibold">{{ $subscriber->email }}</td>
                                <td>
                                    @if($subscriber->status === 'subscribed')
                                        <span class="admin-badge admin-badge--success"><i class="bi bi-check-circle"></i> Subscribed</span>
                                    @else
                                        <span class="admin-badge admin-badge--danger"><i class="bi bi-x-circle"></i> Unsubscribed</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ optional($subscriber->subscribed_at)->format('M d, Y') ?: '—' }}</td>
                                <td class="text-end">
                                    <button class="btn-admin-icon btn-admin-icon--delete" type="button" data-confirm-delete="{{ $subscriber->email }}" data-action="{{ route('admin.newsletter.destroy', $subscriber) }}"><i class="bi bi-trash3"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No subscribers found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($subscribers->hasPages())
            <div class="p-3 border-top">{{ $subscribers->links() }}</div>
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
