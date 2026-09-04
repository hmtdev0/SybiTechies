@extends('layouts.admin')

@section('title', 'Add FAQ')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add FAQ</h2>
        <a href="{{ route('admin.faqs.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to FAQs</a>
    </div>

    <form action="{{ route('admin.faqs.store') }}" method="POST">
        @csrf
        @include('admin.faqs._form')
    </form>
@endsection
