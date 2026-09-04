@extends('layouts.admin')

@section('title', 'Add Job Opening')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add Job Opening</h2>
        <a href="{{ route('admin.job-openings.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Job Openings</a>
    </div>

    <form action="{{ route('admin.job-openings.store') }}" method="POST">
        @csrf
        @include('admin.job-openings._form', ['jobOpening' => null])
    </form>
@endsection
