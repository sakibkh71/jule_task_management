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

    .badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .badge-status-completed   { background: #2563eb; color: #fff; }
    .badge-status-inprogress  { background: #1e3a5f; color: #fff; }
    .badge-status-confirmed   { background: #16a34a; color: #fff; }
    .badge-status-assigned    { background: #dc2626; color: #fff; }

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
                        @if($task->technician)
                            <span style="font-weight:500;">{{ $task->technician->name }}</span>
                        @else
                            <span class="text-sub">Unassigned</span>
                        @endif
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
                        <span class="badge-status {{ $task->status_badge_class }}">{{ $task->status_label }}</span>
                    </td>
                    <td>
                        <a href="{{ route('tasks.edit', $task) }}" class="action-link" title="Edit">
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

@endsection
