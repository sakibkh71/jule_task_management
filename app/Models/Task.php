<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_number',
        'job_type',
        'title',
        'description',
        'status',
        'due_date',
        'start_time',
        'end_time',
        'user_id',
        'technician_id',
        'client_id',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        'due_date'   => 'date',
    ];

    protected static function booted()
    {
        static::creating(function (Task $task) {
            if (empty($task->job_number)) {
                $last = static::max('id') ?? 0;
                $task->job_number = '#' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'status', 'slug');
    }

    public function getJobTypeLabelAttribute(): string
    {
        return match ($this->job_type) {
            'project'      => 'PROJECT',
            'service_work' => 'SERVICE WORK',
            'on_call'      => 'ON CALL',
            'assignment'   => 'ASSIGNMENT',
            default        => strtoupper($this->job_type),
        };
    }

    public function getJobTypeBadgeClassAttribute(): string
    {
        return match ($this->job_type) {
            'project'      => 'badge-job-project',
            'service_work' => 'badge-job-service',
            'on_call'      => 'badge-job-oncall',
            'assignment'   => 'badge-job-assignment',
            default        => 'badge-job-project',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->taskStatus?->label
            ?? strtoupper(str_replace('_', ' ', $this->status ?? ''));
    }

    public function getStatusColorAttribute(): string
    {
        return $this->taskStatus?->color ?? '#6b7280';
    }

    /** @deprecated Use status_color inline style instead */
    public function getStatusBadgeClassAttribute(): string
    {
        return 'badge-status-dynamic';
    }
}
