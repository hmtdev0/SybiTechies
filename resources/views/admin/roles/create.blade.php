@extends('layouts.admin')

@section('title', 'Add Role')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add Role</h2>
        <a href="{{ route('admin.roles.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Roles</a>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @include('admin.roles._form')
    </form>
@endsection
