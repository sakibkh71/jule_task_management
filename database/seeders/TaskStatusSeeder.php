<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['slug' => 'assigned',    'label' => 'Assigned',    'color' => '#dc2626', 'sort_order' => 1],
            ['slug' => 'in_progress', 'label' => 'In Progress', 'color' => '#1e3a5f', 'sort_order' => 2],
            ['slug' => 'confirmed',   'label' => 'Confirmed',   'color' => '#16a34a', 'sort_order' => 3],
            ['slug' => 'completed',   'label' => 'Completed',   'color' => '#2563eb', 'sort_order' => 4],
            ['slug' => 'cancelled',   'label' => 'Cancelled',   'color' => '#6b7280', 'sort_order' => 5],
        ];

        foreach ($statuses as $status) {
            TaskStatus::updateOrCreate(
                ['slug' => $status['slug']],
                $status
            );
        }
    }
}
