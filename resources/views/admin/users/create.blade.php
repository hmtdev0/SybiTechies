@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add User</h2>
        <a href="{{ route('admin.users.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Users</a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.users._form', ['user' => null])
    </form>
@endsection
