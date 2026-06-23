@extends('layouts.app')

@push('styles')
<style>
    .pw-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        max-width: 520px;
    }
    .pw-card-header {
        padding: 20px 28px;
        border-bottom: 1px solid #f1f3f7;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pw-card-header h5 { margin: 0; font-size: 17px; font-weight: 700; color: #111827; }
    .pw-card-header .icon-wrap {
        width: 38px; height: 38px;
        background: rgba(255,107,43,0.12);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FF6B2B;
        font-size: 18px;
        flex-shrink: 0;
    }
    .pw-card-body { padding: 28px; }

    .form-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .form-control {
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        color: #111827;
        transition: border-color 0.18s, box-shadow 0.18s;
    }
    .form-control:focus {
        border-color: #FF6B2B;
        box-shadow: 0 0 0 3px rgba(255,107,43,0.15);
        outline: none;
    }
    .form-control.is-invalid { border-color: #dc2626; }
    .invalid-feedback { font-size: 12px; }

    .pw-strength { margin-top: 6px; display: flex; gap: 4px; }
    .pw-strength .bar {
        flex: 1; height: 3px; border-radius: 2px;
        background: #e5e7eb; transition: background 0.3s;
    }
    .pw-strength.weak .bar:first-child { background: #dc2626; }
    .pw-strength.fair .bar:nth-child(-n+2) { background: #f97316; }
    .pw-strength.good .bar:nth-child(-n+3) { background: #eab308; }
    .pw-strength.strong .bar { background: #16a34a; }
    .pw-hint { font-size: 12px; color: #6b7280; margin-top: 4px; }

    .btn-save {
        background: #FF6B2B;
        color: #fff;
        border: none;
        padding: 11px 28px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.18s;
    }
    .btn-save:hover { background: #e5561e; }
    .btn-cancel {
        background: none;
        border: 1px solid #d1d5db;
        padding: 11px 24px;
        border-radius: 8px;
        font-size: 14px;
        color: #374151;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s;
    }
    .btn-cancel:hover { background: #f5f6fa; color: #111827; }
</style>
@endpush

@section('content')

<div class="page-title" style="font-size:22px; font-weight:700; color:#111827; margin-bottom:4px;">
    Account Security
</div>
<p style="font-size:13px; color:#6b7280; margin-bottom:24px;">
    Update your password to keep your account secure.
</p>

<div class="pw-card">
    <div class="pw-card-header">
        <div class="icon-wrap"><i class="bi bi-shield-lock-fill"></i></div>
        <h5>Change Password</h5>
    </div>
    <div class="pw-card-body">

        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('profile.password.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label" for="current_password">Current Password</label>
                <input
                    type="password"
                    name="current_password"
                    id="current_password"
                    class="form-control @error('current_password') is-invalid @enderror"
                    placeholder="Enter your current password"
                    autocomplete="current-password"
                >
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">New Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Minimum 8 characters"
                    autocomplete="new-password"
                    oninput="checkStrength(this.value)"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="pw-strength" id="strengthBars">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
                <div class="pw-hint" id="strengthHint">Enter a new password</div>
            </div>

            <div class="mb-4">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    placeholder="Re-enter new password"
                    autocomplete="new-password"
                >
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg me-1"></i> Update Password
                </button>
                <a href="{{ route('dashboard') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function checkStrength(val) {
    const bars = document.getElementById('strengthBars');
    const hint = document.getElementById('strengthHint');
    bars.className = 'pw-strength';
    if (!val) { hint.textContent = 'Enter a new password'; return; }
    let score = 0;
    if (val.length >= 8)  score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
    if (/\d/.test(val) && /[^A-Za-z0-9]/.test(val)) score++;
    const levels = ['', 'weak', 'fair', 'good', 'strong'];
    const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    bars.classList.add(levels[score] || 'weak');
    hint.textContent = 'Password strength: ' + (labels[score] || 'Weak');
}
</script>
@endpush
