@extends('layouts.admin')

@section('title', 'Add Industry')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add Industry</h2>
        <a href="{{ route('admin.industries.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Industries</a>
    </div>

    <form action="{{ route('admin.industries.store') }}" method="POST">
        @csrf
        @include('admin.industries._form')
    </form>
@endsection
