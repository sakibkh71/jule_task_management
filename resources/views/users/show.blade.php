@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">User Details</h5>
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <img src="{{ $user->image_url }}" alt="{{ $user->name }}" class="img-thumbnail rounded" width="200">
                </div>
                <h3 class="mb-1">{{ $user->name }}</h3>
                <p class="text-muted">{{ $user->email }}</p>
                <hr>
                <div class="row text-start">
                    <div class="col-4 fw-bold">Role:</div>
                    <div class="col-8">{{ $user->role }}</div>
                </div>
                <div class="row text-start mt-2">
                    <div class="col-4 fw-bold">Status:</div>
                    <div class="col-8">
                        @if($user->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="row text-start mt-2">
                    <div class="col-4 fw-bold">Joined:</div>
                    <div class="col-8">{{ $user->created_at->format('M d, Y') }}</div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit User</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
