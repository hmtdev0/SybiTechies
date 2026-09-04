@extends('layouts.admin')

@section('title', 'Add Service')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add Service</h2>
        <a href="{{ route('admin.services.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Services</a>
    </div>

    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.services._form', ['service' => null])
    </form>
@endsection
