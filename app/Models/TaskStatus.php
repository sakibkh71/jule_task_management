<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TaskStatus extends Model
{
    protected $fillable = [
        'slug',
        'label',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'status', 'slug');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public static function activeList(): Collection
    {
        return static::active()->get();
    }

    public static function activeSlugs(): array
    {
        return static::active()->pluck('slug')->all();
    }
}
