@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Dashboard</h4>
            </div>
            <div class="card-body">
                <h3>Welcome, {{ Auth::user()->name }}</h3>
                <p>You are logged in as <strong>{{ Auth::user()->role }}</strong>.</p>
                <hr>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
