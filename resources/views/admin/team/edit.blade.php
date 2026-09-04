@extends('layouts.admin')

@section('title', 'Edit Team Member')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">Edit Team Member</h2>
        <a href="{{ route('admin.team.index') }}" class="btn-admin-soft btn"><i class="bi bi-arrow-left me-1"></i>Back to Team Members</a>
    </div>

    <form action="{{ route('admin.team.update', $member) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.team._form')
    </form>
@endsection
