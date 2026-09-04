@extends('layouts.admin')

@section('title', 'Edit Industry')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Edit Industry</h2>
        <a href="{{ route('admin.industries.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Industries</a>
    </div>

    <form action="{{ route('admin.industries.update', $industry) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.industries._form')
    </form>
@endsection
