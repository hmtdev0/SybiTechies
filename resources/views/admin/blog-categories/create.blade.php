@extends('layouts.admin')

@section('title', 'Add Blog Category')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add Blog Category</h2>
        <a href="{{ route('admin.blog-categories.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Categories</a>
    </div>

    <form action="{{ route('admin.blog-categories.store') }}" method="POST">
        @csrf
        @include('admin.blog-categories._form')
    </form>
@endsection
