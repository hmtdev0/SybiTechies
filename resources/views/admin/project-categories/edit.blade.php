@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Edit Category</h2>
        <a href="{{ route('admin.project-categories.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Categories</a>
    </div>

    <form action="{{ route('admin.project-categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.project-categories._form')
    </form>
@endsection
