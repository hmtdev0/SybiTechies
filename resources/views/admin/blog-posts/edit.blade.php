@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Edit Blog Post</h2>
        <a href="{{ route('admin.blog-posts.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Posts</a>
    </div>

    <form action="{{ route('admin.blog-posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.blog-posts._form')
    </form>
@endsection
