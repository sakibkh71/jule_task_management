@extends('layouts.app')

@push('styles')
<style>
.form-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
    overflow: hidden;
}
.form-card .card-head {
    padding: 16px 24px;
    border-bottom: 1px solid #e8eaf0;
    font-size: 16px;
    font-weight: 700;
    color: #111827;
}
.form-card .card-body-inner { padding: 24px; }
.form-card .form-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 5px; }
.form-card .form-control,
.form-card .form-select {
    font-size: 13px;
    border-color: #d1d5db;
    border-radius: 6px;
    padding: 8px 12px;
}
.form-card .form-control:focus,
.form-card .form-select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 640px) { .row-2 { grid-template-columns: 1fr; } }
.btn-save {
    padding: 9px 22px;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
}
.btn-save:hover { background: #1d4ed8; }
.btn-cancel {
    padding: 9px 22px;
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: background .2s;
}
.btn-cancel:hover { background: #e5e7eb; color: #374151; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="mb-3">
            <a href="{{ route('tasks.index') }}" style="font-size:13px;color:#6b7280;text-decoration:none;">
                &#8592; Back to Jobs
            </a>
        </div>

        <div class="form-card">
            <div class="card-head">New Job</div>
            <div class="card-body-inner">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Job Title <span style="color:#dc2626">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="e.g. PMA Fall Inspection" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row-2 mb-3">
                        <div>
                            <label class="form-label">Job Type <span style="color:#dc2626">*</span></label>
                            <select name="job_type" class="form-select @error('job_type') is-invalid @enderror" required>
                                <option value="project"      {{ old('job_type') == 'project'      ? 'selected' : '' }}>Project</option>
                                <option value="service_work" {{ old('job_type') == 'service_work' ? 'selected' : '' }}>Service Work</option>
                                <option value="on_call"      {{ old('job_type') == 'on_call'      ? 'selected' : '' }}>On Call</option>
                                <option value="assignment"   {{ old('job_type') == 'assignment'   ? 'selected' : '' }}>Assignment</option>
                            </select>
                            @error('job_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">Status <span style="color:#dc2626">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="assigned"    {{ old('status') == 'assigned'    ? 'selected' : '' }}>Assigned</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="confirmed"   {{ old('status') == 'confirmed'   ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed"   {{ old('status') == 'completed'   ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row-2 mb-3">
                        <div>
                            <label class="form-label">Technician</label>
                            <select name="technician_id" class="form-select @error('technician_id') is-invalid @enderror">
                                <option value="">— Select Technician —</option>
                                @foreach ($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ old('technician_id') == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('technician_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">Client</label>
                            <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                <option value="">— Select Client —</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row-2 mb-3">
                        <div>
                            <label class="form-label">Start Time</label>
                            <input type="datetime-local" name="start_time"
                                   class="form-control @error('start_time') is-invalid @enderror"
                                   value="{{ old('start_time') }}">
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">End Time</label>
                            <input type="datetime-local" name="end_time"
                                   class="form-control @error('end_time') is-invalid @enderror"
                                   value="{{ old('end_time') }}">
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date"
                               class="form-control @error('due_date') is-invalid @enderror"
                               value="{{ old('due_date') }}">
                        @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="3" placeholder="Optional notes...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('tasks.index') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-save">Create Job</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
