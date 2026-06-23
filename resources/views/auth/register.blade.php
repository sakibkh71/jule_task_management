@extends('layouts.app')

@section('content')
<div class="text-center mb-4">
    <div style="width:52px;height:52px;background:rgba(255,107,43,0.12);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:24px;color:#FF6B2B;">
        <i class="bi bi-person-plus-fill"></i>
    </div>
    <h4 style="font-weight:700;color:#111827;margin-bottom:4px;">Create an account</h4>
    <p style="font-size:13px;color:#6b7280;margin:0;">Fill in your details to get started</p>
</div>

<form action="{{ route('register') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label" style="font-size:13px;font-weight:600;color:#374151;">Full Name</label>
        <input type="text" name="name" id="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name') }}" required autofocus
               style="padding:10px 14px;border-radius:8px;font-size:14px;">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label" style="font-size:13px;font-weight:600;color:#374151;">Email Address</label>
        <input type="email" name="email" id="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" required
               style="padding:10px 14px;border-radius:8px;font-size:14px;">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label" style="font-size:13px;font-weight:600;color:#374151;">Password</label>
        <input type="password" name="password" id="password"
               class="form-control @error('password') is-invalid @enderror"
               required
               style="padding:10px 14px;border-radius:8px;font-size:14px;">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label" style="font-size:13px;font-weight:600;color:#374151;">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation"
               class="form-control" required
               style="padding:10px 14px;border-radius:8px;font-size:14px;">
    </div>

    <div class="d-grid mb-3">
        <button type="submit"
                style="background:#FF6B2B;color:#fff;border:none;padding:11px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;transition:background 0.18s;"
                onmouseover="this.style.background='#e5561e'" onmouseout="this.style.background='#FF6B2B'">
            Create Account
        </button>
    </div>
</form>

<p style="text-align:center;font-size:13px;color:#6b7280;margin:0;">
    Already have an account?
    <a href="{{ route('login') }}" style="color:#FF6B2B;font-weight:600;text-decoration:none;">Sign in</a>
</p>
@endsection
