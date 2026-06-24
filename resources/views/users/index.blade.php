@extends('layouts.app')

@push('styles')
<style>
    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }
    .page-subtitle {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 28px;
    }

    .section-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .section-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #f1f3f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .section-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }
    .btn-add-user {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: #FF6B2B;
        color: #fff;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.18s;
    }
    .btn-add-user:hover { background: #e5561e; color: #fff; }

    .rtable { width: 100%; border-collapse: collapse; }
    .rtable thead tr { background: #f8f9fc; }
    .rtable thead th {
        padding: 10px 16px;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        letter-spacing: .07em;
        text-transform: uppercase;
        white-space: nowrap;
        border-bottom: 1px solid #e8eaf0;
    }
    .rtable tbody tr { border-bottom: 1px solid #f1f3f7; transition: background 0.15s; }
    .rtable tbody tr:last-child { border-bottom: none; }
    .rtable tbody tr:hover { background: #f8f9fc; }
    .rtable td {
        padding: 13px 16px;
        font-size: 13px;
        color: #374151;
        vertical-align: middle;
    }

    .user-name { font-weight: 600; color: #111827; }
    .text-sub  { font-size: 12px; color: #6b7280; margin-top: 2px; }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f1f3f7;
    }

    .badge-user-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .badge-user-active   { background: #16a34a; color: #fff; }
    .badge-user-inactive { background: #6b7280; color: #fff; }

    .badge-role {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: #f3f4f6;
        color: #374151;
    }

    .action-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.15s;
        font-size: 14px;
        background: #fff;
        cursor: pointer;
        padding: 0;
    }
    .action-link:hover { background: #f3f4f6; color: #111827; border-color: #d1d5db; }
    .action-link.danger:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

    .empty-cell { text-align: center; padding: 40px !important; color: #9ca3af; font-size: 14px; }
</style>
@endpush

@section('content')

<div class="page-title">Users</div>
<p class="page-subtitle">Manage team members and their access roles.</p>

<div class="section-card">
    <div class="section-card-header">
        <h5><i class="bi bi-people-fill me-2" style="color:#FF6B2B;"></i>All Users</h5>
        <a href="{{ route('users.create') }}" class="btn-add-user">
            <i class="bi bi-plus-lg"></i> Add User
        </a>
    </div>

    <div class="table-responsive">
        <table class="rtable">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <img src="{{ $user->image_url }}" alt="{{ $user->name }}" class="user-avatar">
                    </td>
                    <td>
                        <span class="user-name">{{ $user->name }}</span>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge-role">{{ $user->role }}</span>
                    </td>
                    <td>
                        @if($user->status)
                            <span class="badge-user-status badge-user-active">Active</span>
                        @else
                            <span class="badge-user-status badge-user-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('users.show', $user) }}" class="action-link" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="action-link" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button"
                                    class="action-link danger btn-delete-user"
                                    title="Delete"
                                    data-user-name="{{ $user->name }}"
                                    data-delete-url="{{ route('users.destroy', $user) }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-cell">
                        <i class="bi bi-person-x" style="font-size:28px; display:block; margin-bottom:8px; color:#d1d5db;"></i>
                        No users found. <a href="{{ route('users.create') }}" style="color:#FF6B2B;">Add one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Delete confirmation --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid #f1f3f7;padding:18px 22px;">
                <h5 class="modal-title" style="font-size:16px;font-weight:700;color:#111827;">
                    <i class="bi bi-trash me-2" style="color:#FF6B2B;"></i>Delete User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:22px;">
                <p id="deleteUserMessage" style="margin:0;font-size:14px;color:#374151;line-height:1.6;"></p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f1f3f7;padding:14px 22px;gap:8px;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-size:13px;">Cancel</button>
                <form id="deleteUserForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn text-white"
                            style="background:#dc2626;font-size:13px;font-weight:600;border:none;">
                        Yes, Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const modalEl = document.getElementById('deleteUserModal');
    const messageEl = document.getElementById('deleteUserMessage');
    const formEl = document.getElementById('deleteUserForm');
    if (!modalEl || !messageEl || !formEl) return;

    const modal = new bootstrap.Modal(modalEl);

    document.querySelectorAll('.btn-delete-user').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = btn.dataset.userName || 'this user';
            messageEl.innerHTML = 'Are you sure you want to delete <strong>' + name + '</strong>? This action cannot be undone.';
            formEl.action = btn.dataset.deleteUrl;
            modal.show();
        });
    });
})();
</script>
@endpush
