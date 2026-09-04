@extends('layouts.admin')

@section('title', 'Edit Process Step')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Edit Process Step</h2>
        <a href="{{ route('admin.process-steps.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Development Process</a>
    </div>

    <form action="{{ route('admin.process-steps.update', $processStep) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.process-steps._form')
    </form>
@endsection
