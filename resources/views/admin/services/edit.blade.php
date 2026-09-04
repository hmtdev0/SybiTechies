@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Edit Service</h2>
        <a href="{{ route('admin.services.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Services</a>
    </div>

    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.services._form')
    </form>
@endsection
