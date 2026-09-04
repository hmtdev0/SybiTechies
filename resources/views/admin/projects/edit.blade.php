@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Edit Project</h2>
        <a href="{{ route('admin.projects.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Projects</a>
    </div>

    <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.projects._form')
    </form>
@endsection
