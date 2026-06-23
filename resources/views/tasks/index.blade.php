@extends('layouts.app')

@push('styles')
<style>
    .jobs-wrapper {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
        overflow: hidden;
    }

    /* Filter bar */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 20px;
        border-bottom: 1px solid #e8eaf0;
        flex-wrap: wrap;
    }
    .filter-bar .search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
    }
    .filter-bar .search-wrap input {
        width: 100%;
        padding: 8px 14px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        color: #374151;
        outline: none;
        transition: border-color .2s;
    }
    .filter-bar .search-wrap input:focus { border-color: #2563eb; }
    .filter-bar .search-wrap input::placeholder { color: #9ca3af; }

    .filter-bar select {
        padding: 8px 32px 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        color: #374151;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;
        -webkit-appearance: none;
        appearance: none;
        min-width: 160px;
        outline: none;
        cursor: pointer;
        transition: border-color .2s;
    }
    .filter-bar select:focus { border-color: #2563eb; }

    .btn-search {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        white-space: nowrap;
        transition: background .2s;
    }
    .btn-search:hover { background: #1d4ed8; }

    /* Table */
    .jobs-table { width: 100%; border-collapse: collapse; }
    .jobs-table thead tr {
        background: #f8f9fc;
        border-bottom: 1px solid #e8eaf0;
    }
    .jobs-table thead th {
        padding: 11px 16px;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        letter-spacing: .06em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .jobs-table tbody tr {
        border-bottom: 1px solid #f1f3f7;
        transition: background .15s;
    }
    .jobs-table tbody tr:last-child { border-bottom: none; }
    .jobs-table tbody tr:hover { background: #f8f9fc; }
    .jobs-table td {
        padding: 14px 16px;
        font-size: 13px;
        color: #374151;
        vertical-align: middle;
    }

    /* Job number cell */
    .job-num { font-weight: 700; font-size: 14px; color: #111827; display: block; }

    /* Job type badges */
    .badge-job {
        display: inline-block;
        margin-top: 5px;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .badge-job-project    { background: #dbeafe; color: #1d4ed8; }
    .badge-job-service    { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
    .badge-job-oncall     { background: #fef3c7; color: #b45309; }
    .badge-job-assignment { background: #e0f2fe; color: #0369a1; }

    /* Table text helpers */
    .text-main  { font-weight: 600; color: #111827; }
    .text-sub   { font-size: 12px; color: #6b7280; margin-top: 2px; }

    /* Status badges */
    .badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .badge-status-completed   { background: #2563eb; color: #fff; }
    .badge-status-inprogress  { background: #1e3a5f; color: #fff; }
    .badge-status-confirmed   { background: #16a34a; color: #fff; }
    .badge-status-assigned    { background: #dc2626; color: #fff; }

    /* Actions */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 5px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #6b7280;
        font-size: 13px;
        text-decoration: none;
        transition: all .15s;
        cursor: pointer;
    }
    .action-btn:hover { background: #f3f4f6; color: #111827; border-color: #d1d5db; }
    .action-btn.danger:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

    /* Pagination */
    .pag-bar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        padding: 14px 20px;
        border-top: 1px solid #e8eaf0;
        font-size: 13px;
        color: #374151;
    }
    .pag-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid #d1d5db;
        border-radius: 5px;
        background: #fff;
        color: #374151;
        font-size: 13px;
        text-decoration: none;
        transition: all .15s;
    }
    .pag-btn:hover:not(.disabled) { background: #f3f4f6; }
    .pag-btn.disabled { color: #d1d5db; pointer-events: none; }
    .pag-current {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border: 1px solid #2563eb;
        border-radius: 5px;
        background: #fff;
        color: #2563eb;
        font-weight: 600;
        font-size: 13px;
    }

    .empty-row td { text-align: center; padding: 48px; color: #9ca3af; font-size: 14px; }

    /* Top action row */
    .top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .top-row h1 { font-size: 20px; font-weight: 700; color: #111827; margin: 0; }
    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: #2563eb;
        color: #fff;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: background .2s;
    }
    .btn-create:hover { background: #1d4ed8; color: #fff; }
</style>
@endpush

@section('content')

<div class="top-row">
    <h1>Jobs</h1>
    <a href="{{ route('tasks.create') }}" class="btn-create">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Job
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<div class="jobs-wrapper">

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('tasks.index') }}">
        <div class="filter-bar">
            <div class="search-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search tech, client, job etc..">
            </div>

            <select name="technician_id">
                <option value="">Tech Status (All)</option>
                @foreach ($technicians as $tech)
                    <option value="{{ $tech->id }}" {{ request('technician_id') == $tech->id ? 'selected' : '' }}>
                        {{ $tech->name }}
                    </option>
                @endforeach
            </select>

            <select name="job_type">
                <option value="">Job Type (All)</option>
                <option value="project"      {{ request('job_type') == 'project'      ? 'selected' : '' }}>Project</option>
                <option value="service_work" {{ request('job_type') == 'service_work' ? 'selected' : '' }}>Service Work</option>
                <option value="on_call"      {{ request('job_type') == 'on_call'      ? 'selected' : '' }}>On Call</option>
                <option value="assignment"   {{ request('job_type') == 'assignment'   ? 'selected' : '' }}>Assignment</option>
            </select>

            <select name="job_status">
                <option value="">Job Status (All)</option>
                <option value="assigned"    {{ request('job_status') == 'assigned'    ? 'selected' : '' }}>Assigned</option>
                <option value="in_progress" {{ request('job_status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="confirmed"   {{ request('job_status') == 'confirmed'   ? 'selected' : '' }}>Confirmed</option>
                <option value="completed"   {{ request('job_status') == 'completed'   ? 'selected' : '' }}>Completed</option>
            </select>

            <button type="submit" class="btn-search">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Search
            </button>
        </div>
    </form>

    {{-- Table --}}
    <table class="jobs-table">
        <thead>
            <tr>
                <th># JOB</th>
                <th>JOB TITLE</th>
                <th>JOB CREATED</th>
                <th>START-END TIME</th>
                <th>TECHNICIAN</th>
                <th>CLIENT</th>
                <th>STATUS</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    {{-- # JOB --}}
                    <td>
                        <span class="job-num">{{ $task->job_number }}</span>
                        <span class="badge-job {{ $task->job_type_badge_class }}">{{ $task->job_type_label }}</span>
                    </td>

                    {{-- JOB TITLE --}}
                    <td>
                        <span class="text-main">{{ $task->title }}</span>
                    </td>

                    {{-- JOB CREATED --}}
                    <td>
                        <span class="text-main">{{ $task->created_at->format('M d, Y') }}</span>
                        @if ($task->creator)
                            <div class="text-sub">{{ $task->creator->name }}</div>
                        @endif
                    </td>

                    {{-- START-END TIME --}}
                    <td>
                        @if ($task->start_time)
                            <span class="text-main">{{ $task->start_time->format('M d, Y') }}</span>
                            <div class="text-sub">
                                {{ $task->start_time->format('g:i A') }}
                                @if ($task->end_time) – {{ $task->end_time->format('g:i A') }} @endif
                            </div>
                        @else
                            <span class="text-sub">—</span>
                        @endif
                    </td>

                    {{-- TECHNICIAN --}}
                    <td>
                        @if ($task->technician)
                            <span class="text-main">{{ $task->technician->name }}</span>
                            <div class="text-sub">{{ $task->technician->email }}</div>
                        @else
                            <span class="text-sub">Unassigned</span>
                        @endif
                    </td>

                    {{-- CLIENT --}}
                    <td>
                        @if ($task->client)
                            <span class="text-main">{{ $task->client->name }}</span>
                            <div class="text-sub">{{ $task->client->address }}</div>
                        @else
                            <span class="text-sub">—</span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        <span class="badge-status {{ $task->status_badge_class }}">{{ $task->status_label }}</span>
                    </td>

                    {{-- ACTIONS --}}
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('tasks.edit', $task) }}" class="action-btn" title="Edit">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <button type="button" class="action-btn danger" title="Delete"
                                data-bs-toggle="modal" data-bs-target="#del{{ $task->id }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </div>

                        {{-- Delete modal --}}
                        <div class="modal fade" id="del{{ $task->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title">Delete Job</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body" style="font-size:13px;">
                                        Delete <strong>{{ $task->job_number }}</strong> — {{ $task->title }}?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="8">No jobs found. <a href="{{ route('tasks.create') }}">Create one</a>.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if ($tasks->hasPages())
        <div class="pag-bar">
            @if ($tasks->onFirstPage())
                <span class="pag-btn disabled">&#8249;</span>
            @else
                <a class="pag-btn" href="{{ $tasks->previousPageUrl() }}">&#8249;</a>
            @endif

            <span class="pag-current">{{ $tasks->currentPage() }}</span>
            <span style="color:#9ca3af">of {{ $tasks->lastPage() }}</span>

            @if ($tasks->hasMorePages())
                <a class="pag-btn" href="{{ $tasks->nextPageUrl() }}">&#8250;</a>
            @else
                <span class="pag-btn disabled">&#8250;</span>
            @endif
        </div>
    @endif

</div>
@endsection
