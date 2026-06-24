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

    /* Stats Cards */
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 22px 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .stat-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }
    .stat-icon.orange { background: rgba(255,107,43,0.12); color: #FF6B2B; }
    .stat-icon.blue   { background: rgba(37,99,235,0.1);   color: #2563eb; }
    .stat-icon.green  { background: rgba(22,163,74,0.1);   color: #16a34a; }
    .stat-icon.purple { background: rgba(124,58,237,0.1);  color: #7c3aed; }

    .stat-label { font-size: 12px; color: #6b7280; font-weight: 500; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; line-height: 1; }

    /* Section card */
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
    .btn-view-all {
        font-size: 13px;
        color: #FF6B2B;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.18s;
    }
    .btn-view-all:hover { color: #e5561e; text-decoration: underline; }

    /* Recent Tasks Table */
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

    .job-num  { font-weight: 700; font-size: 13px; color: #111827; }
    .job-title { font-weight: 600; color: #111827; }
    .text-sub  { font-size: 12px; color: #6b7280; margin-top: 2px; }

    .status-select {
        display: inline-block;
        min-width: 128px;
        appearance: none;
        -webkit-appearance: none;
        border: none;
        padding: 4px 26px 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        white-space: nowrap;
        cursor: pointer;
        color: #fff;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 16 16' fill='white'%3E%3Cpath d='M4.5 6L8 9.5 11.5 6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        transition: opacity 0.15s, background-color 0.15s;
    }
    .status-select:hover:not(:disabled) { opacity: 0.9; }
    .status-select:disabled { opacity: 0.65; cursor: not-allowed; }
    .status-select option { color: #111827; background: #fff; font-weight: 600; }

    .technician-select {
        display: inline-block;
        min-width: 150px;
        max-width: 180px;
        appearance: none;
        -webkit-appearance: none;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 6px 28px 6px 10px;
        font-size: 13px;
        font-weight: 500;
        color: #111827;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 16 16' fill='%236b7280'%3E%3Cpath d='M4.5 6L8 9.5 11.5 6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        cursor: pointer;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .technician-select:hover:not(:disabled) { border-color: #d1d5db; }
    .technician-select:focus {
        outline: none;
        border-color: #FF6B2B;
        box-shadow: 0 0 0 3px rgba(255, 107, 43, 0.12);
    }
    .technician-select:disabled { opacity: 0.65; cursor: not-allowed; }
    .technician-select option { color: #111827; font-weight: 500; }

    .dashboard-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 2000;
        background: #111827;
        color: #fff;
        padding: 12px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.25s, transform 0.25s;
        pointer-events: none;
    }
    .dashboard-toast.show { opacity: 1; transform: translateY(0); }

    .action-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px; height: 30px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.15s;
        font-size: 14px;
    }
    .action-link:hover { background: #f3f4f6; color: #111827; border-color: #d1d5db; }

    .empty-cell { text-align: center; padding: 40px !important; color: #9ca3af; font-size: 14px; }
</style>
@endpush

@section('content')

<div class="page-title">Dashboard</div>
<p class="page-subtitle">Welcome back, <strong>{{ Auth::user()->name }}</strong> — here's what's happening.</p>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Tasks</div>
                <div class="stat-value">{{ $totalTasks }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-label">Completed</div>
                <div class="stat-value">{{ $completedTasks }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Tasks --}}
<div class="section-card">
    <div class="section-card-header">
        <h5><i class="bi bi-clock-history me-2" style="color:#FF6B2B;"></i>Recent Tasks</h5>
        <a href="{{ route('tasks.index') }}" class="btn-view-all">View All &rarr;</a>
    </div>

    <div class="table-responsive">
        <table class="rtable">
            <thead>
                <tr>
                    <th># JOB</th>
                    <th>TITLE</th>
                    <th>TECHNICIAN</th>
                    <th>CLIENT</th>
                    <th>DUE DATE</th>
                    <th>STATUS</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTasks as $task)
                <tr>
                    <td><span class="job-num">{{ $task->job_number }}</span></td>
                    <td>
                        <span class="job-title">{{ $task->title }}</span>
                        @if($task->creator)
                            <div class="text-sub">by {{ $task->creator->name }}</div>
                        @endif
                    </td>
                    <td>
                        <select class="technician-select"
                                data-task-id="{{ $task->id }}"
                                data-update-url="{{ route('tasks.technician', $task) }}"
                                data-prev-technician-id="{{ $task->technician_id ?? '' }}">
                            <option value="" {{ !$task->technician_id ? 'selected' : '' }}>Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $task->technician_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        @if($task->client)
                            {{ $task->client->name }}
                        @else
                            <span class="text-sub">—</span>
                        @endif
                    </td>
                    <td>
                        @if($task->due_date)
                            {{ $task->due_date->format('M d, Y') }}
                        @else
                            <span class="text-sub">—</span>
                        @endif
                    </td>
                    <td>
                        @include('tasks.partials.status-select', ['task' => $task, 'taskStatuses' => $taskStatuses])
                    </td>
                    <td>
                        <a href="{{ route('tasks.edit', ['task' => $task, 'back' => route('dashboard')]) }}" class="action-link" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-cell">
                        <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px; color:#d1d5db;"></i>
                        No tasks yet. <a href="{{ route('tasks.create') }}" style="color:#FF6B2B;">Create one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Status change confirmation --}}
<div class="modal fade" id="statusConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid #f1f3f7;padding:18px 22px;">
                <h5 class="modal-title" style="font-size:16px;font-weight:700;color:#111827;">
                    <i class="bi bi-arrow-repeat me-2" style="color:#FF6B2B;"></i>Change Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:22px;">
                <p id="statusConfirmMessage" style="margin:0;font-size:14px;color:#374151;line-height:1.6;"></p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f1f3f7;padding:14px 22px;gap:8px;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-size:13px;">Cancel</button>
                <button type="button" class="btn text-white" id="statusConfirmBtn"
                        style="background:#FF6B2B;font-size:13px;font-weight:600;border:none;">
                    Yes, Change Status
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Technician assignment confirmation --}}
<div class="modal fade" id="technicianConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid #f1f3f7;padding:18px 22px;">
                <h5 class="modal-title" style="font-size:16px;font-weight:700;color:#111827;">
                    <i class="bi bi-person-check me-2" style="color:#FF6B2B;"></i>Assign Technician
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:22px;">
                <p id="technicianConfirmMessage" style="margin:0;font-size:14px;color:#374151;line-height:1.6;"></p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f1f3f7;padding:14px 22px;gap:8px;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-size:13px;">Cancel</button>
                <button type="button" class="btn text-white" id="technicianConfirmBtn"
                        style="background:#FF6B2B;font-size:13px;font-weight:600;border:none;">
                    Yes, Assign Technician
                </button>
            </div>
        </div>
    </div>
</div>

<div id="dashboardToast" class="dashboard-toast" role="status" aria-live="polite"></div>

@endsection

@push('scripts')
<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const toastEl = document.getElementById('dashboardToast');
    if (!csrfToken || !toastEl) return;

    function showToast(message) {
        toastEl.textContent = message;
        toastEl.classList.add('show');
        clearTimeout(showToast._timer);
        showToast._timer = setTimeout(() => toastEl.classList.remove('show'), 2800);
    }

    window.dashboardShowToast = showToast;

    /* ── Status change ───────────────────────────── */
    const statusModalEl = document.getElementById('statusConfirmModal');
    const statusMessageEl = document.getElementById('statusConfirmMessage');
    const statusConfirmBtn = document.getElementById('statusConfirmBtn');
    if (!statusModalEl) return;

    const statusModal = new bootstrap.Modal(statusModalEl);

    let pendingStatusSelect = null;
    let previousStatus = null;
    let newStatus = null;
    let isSavingStatus = false;

    function statusLabel(select, slug) {
        const option = select.querySelector('option[value="' + slug + '"]');
        return option ? option.dataset.label : slug;
    }

    function applyStatusColor(select, slug, fallbackColor) {
        const option = select.querySelector('option[value="' + slug + '"]');
        select.style.backgroundColor = fallbackColor || option?.dataset.color || '#6b7280';
    }

    function revertStatusSelect() {
        if (!pendingStatusSelect || previousStatus === null) return;
        pendingStatusSelect.value = previousStatus;
        applyStatusColor(pendingStatusSelect, previousStatus);
    }

    document.querySelectorAll('.status-select').forEach(function (select) {
        applyStatusColor(select, select.dataset.prevStatus);
        select.addEventListener('change', function () {
            const chosen = select.value;
            const prev = select.dataset.prevStatus;

            if (chosen === prev) return;

            pendingStatusSelect = select;
            previousStatus = prev;
            newStatus = chosen;

            statusMessageEl.innerHTML = 'Change status from <strong>' + statusLabel(select, prev) + '</strong> to <strong>' + statusLabel(select, chosen) + '</strong>?';
            statusModal.show();
        });
    });

    statusModalEl.addEventListener('hidden.bs.modal', function () {
        if (!isSavingStatus) revertStatusSelect();
        pendingStatusSelect = null;
        previousStatus = null;
        newStatus = null;
        isSavingStatus = false;
    });

    statusConfirmBtn.addEventListener('click', function () {
        if (!pendingStatusSelect || !newStatus) return;

        isSavingStatus = true;
        statusConfirmBtn.disabled = true;
        pendingStatusSelect.disabled = true;

        fetch(pendingStatusSelect.dataset.updateUrl, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ status: newStatus }),
        })
        .then(function (response) {
            return response.json().then(function (data) {
                if (!response.ok) throw data;
                return data;
            });
        })
        .then(function (data) {
            pendingStatusSelect.dataset.prevStatus = data.status;
            applyStatusColor(pendingStatusSelect, data.status, data.status_color);
            showToast(data.message || 'Status updated.');
            statusModal.hide();
        })
        .catch(function (error) {
            isSavingStatus = false;
            revertStatusSelect();
            statusModal.hide();
            const msg = error?.message || error?.errors?.status?.[0] || 'Could not update status.';
            showToast(msg);
        })
        .finally(function () {
            statusConfirmBtn.disabled = false;
            if (pendingStatusSelect) pendingStatusSelect.disabled = false;
        });
    });

    /* ── Technician assignment ───────────────────── */
    const techModalEl = document.getElementById('technicianConfirmModal');
    const techMessageEl = document.getElementById('technicianConfirmMessage');
    const techConfirmBtn = document.getElementById('technicianConfirmBtn');
    if (!techModalEl) return;

    const techModal = new bootstrap.Modal(techModalEl);

    let pendingTechSelect = null;
    let previousTechnicianId = null;
    let newTechnicianId = null;
    let isSavingTechnician = false;

    function technicianLabel(select, id) {
        if (!id) return 'Unassigned';
        const option = select.querySelector('option[value="' + id + '"]');
        return option ? option.textContent.trim() : 'Unknown';
    }

    function revertTechnicianSelect() {
        if (!pendingTechSelect) return;
        pendingTechSelect.value = previousTechnicianId;
    }

    document.querySelectorAll('.technician-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const chosen = select.value;
            const prev = select.dataset.prevTechnicianId;

            if (chosen === prev) return;

            pendingTechSelect = select;
            previousTechnicianId = prev;
            newTechnicianId = chosen;

            techMessageEl.innerHTML = 'Assign technician from <strong>' + technicianLabel(select, prev) + '</strong> to <strong>' + technicianLabel(select, chosen) + '</strong>?';
            techModal.show();
        });
    });

    techModalEl.addEventListener('hidden.bs.modal', function () {
        if (!isSavingTechnician) revertTechnicianSelect();
        pendingTechSelect = null;
        previousTechnicianId = null;
        newTechnicianId = null;
        isSavingTechnician = false;
    });

    techConfirmBtn.addEventListener('click', function () {
        if (!pendingTechSelect || newTechnicianId === null) return;

        isSavingTechnician = true;
        techConfirmBtn.disabled = true;
        pendingTechSelect.disabled = true;

        fetch(pendingTechSelect.dataset.updateUrl, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ technician_id: newTechnicianId || null }),
        })
        .then(function (response) {
            return response.json().then(function (data) {
                if (!response.ok) throw data;
                return data;
            });
        })
        .then(function (data) {
            pendingTechSelect.dataset.prevTechnicianId = data.technician_id ?? '';
            showToast(data.message || 'Technician assigned.');
            techModal.hide();
        })
        .catch(function (error) {
            isSavingTechnician = false;
            revertTechnicianSelect();
            techModal.hide();
            const msg = error?.message || error?.errors?.technician_id?.[0] || 'Could not assign technician.';
            showToast(msg);
        })
        .finally(function () {
            techConfirmBtn.disabled = false;
            if (pendingTechSelect) pendingTechSelect.disabled = false;
        });
    });
})();
</script>
@endpush
