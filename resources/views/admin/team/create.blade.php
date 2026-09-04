@extends('layouts.admin')

@section('title', 'Add Team Member')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Add Team Member</h2>
        <a href="{{ route('admin.team.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Team Members</a>
    </div>

    <form action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.team._form', ['member' => null])
    </form>
@endsection
