@extends('layouts.admin')

@section('title', 'Edit Technology')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Edit Technology</h2>
        <a href="{{ route('admin.technologies.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Technologies</a>
    </div>

    <form action="{{ route('admin.technologies.update', $technology) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.technologies._form')
    </form>
@endsection
