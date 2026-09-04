@extends('layouts.admin')

@section('title', 'Add Testimonial')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add Testimonial</h2>
        <a href="{{ route('admin.testimonials.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Testimonials</a>
    </div>

    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.testimonials._form', ['testimonial' => null])
    </form>
@endsection
