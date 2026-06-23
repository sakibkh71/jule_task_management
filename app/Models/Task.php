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
        return match ($this->status) {
            'assigned'    => 'ASSIGNED',
            'in_progress' => 'IN PROGRESS',
            'confirmed'   => 'CONFIRMED',
            'completed'   => 'COMPLETED',
            default       => strtoupper($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'assigned'    => 'badge-status-assigned',
            'in_progress' => 'badge-status-inprogress',
            'confirmed'   => 'badge-status-confirmed',
            'completed'   => 'badge-status-completed',
            default       => 'badge-status-assigned',
        };
    }
}
