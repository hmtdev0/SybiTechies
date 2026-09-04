@extends('layouts.admin')

@section('title', 'View Application')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Application from {{ $application->name }}</h2>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.job-applications.toggle-read', $application) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-admin-soft">
                    <i class="bi bi-envelope{{ $application->is_read ? '' : '-open' }} me-1"></i>
                    Mark as {{ $application->is_read ? 'Unread' : 'Read' }}
                </button>
            </form>
            <a href="{{ route('admin.job-applications.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Applications</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="admin-card__header"><h3 class="admin-card__title">Cover Message</h3></div>
                <div class="admin-card__body">
                    @if($application->message)
                        <p class="mb-0" style="white-space: pre-line;">{{ $application->message }}</p>
                    @else
                        <p class="text-muted mb-0">No message was included with this application.</p>
                    @endif
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card__header"><h3 class="admin-card__title">Resume</h3></div>
                <div class="admin-card__body">
                    @if($application->resume_path)
                        <a href="{{ asset($application->resume_path) }}" target="_blank" rel="noopener" class="btn btn-admin-gradient">
                            <i class="bi bi-file-earmark-arrow-down me-2"></i>Download Resume
                        </a>
                    @else
                        <p class="text-muted mb-0">No resume was attached.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card__header"><h3 class="admin-card__title">Applicant Details</h3></div>
                <div class="admin-card__body d-flex flex-column gap-3">
                    <div>
                        <small class="text-muted d-block">Name</small>
                        <strong>{{ $application->name }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Email</small>
                        <a href="mailto:{{ $application->email }}">{{ $application->email }}</a>
                    </div>
                    @if($application->phone)
                        <div>
                            <small class="text-muted d-block">Phone</small>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $application->phone) }}">{{ $application->phone }}</a>
                        </div>
                    @endif
                    <div>
                        <small class="text-muted d-block">Applied</small>
                        <strong>{{ $application->created_at->format('M j, Y g:i A') }}</strong>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card__header"><h3 class="admin-card__title">Position</h3></div>
                <div class="admin-card__body">
                    @if($application->jobOpening)
                        <strong class="d-block mb-1">{{ $application->jobOpening->title }}</strong>
                        <span class="text-muted small">{{ $application->jobOpening->department }}{{ $application->jobOpening->department && $application->jobOpening->location ? ' · ' : '' }}{{ $application->jobOpening->location }}</span>
                        <a href="{{ route('admin.job-openings.edit', $application->jobOpening) }}" class="d-block mt-2 small">View job opening <i class="bi bi-arrow-right"></i></a>
                    @else
                        <span class="text-muted">General application</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
