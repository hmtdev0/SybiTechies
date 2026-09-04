@extends('layouts.admin')

@section('title', 'Edit Custom Script')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Edit Custom Script</h2>
        <a href="{{ route('admin.custom-scripts.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Custom Scripts</a>
    </div>

    <form action="{{ route('admin.custom-scripts.update', $script) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.custom-scripts._form')
    </form>
@endsection
