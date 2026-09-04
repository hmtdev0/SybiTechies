@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add Category</h2>
        <a href="{{ route('admin.project-categories.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Categories</a>
    </div>

    <form action="{{ route('admin.project-categories.store') }}" method="POST">
        @csrf
        @include('admin.project-categories._form')
    </form>
@endsection
