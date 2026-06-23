<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Emily Jones',
                'password' => Hash::make('password'),
                'role'     => 'Admin',
                'status'   => true,
            ]
        );

        $tech1 = User::firstOrCreate(
            ['email' => 'fedlogan@lasertech.com'],
            [
                'name'     => 'Fredric Logan',
                'password' => Hash::make('password'),
                'role'     => 'User',
                'status'   => true,
            ]
        );

        $tech2 = User::firstOrCreate(
            ['email' => 'sarah@techsolutions.com'],
            [
                'name'     => 'Sarah Mitchell',
                'password' => Hash::make('password'),
                'role'     => 'User',
                'status'   => true,
            ]
        );

        $client1 = Client::firstOrCreate(
            ['name' => 'Laser Tech'],
            ['email' => 'info@lasertech.com', 'address' => '743 St., Toronto, ON, Canada']
        );

        $client2 = Client::firstOrCreate(
            ['name' => 'TechSolutions Inc'],
            ['email' => 'info@techsolutions.com', 'address' => '210 King St., Vancouver, BC, Canada']
        );

        $client3 = Client::firstOrCreate(
            ['name' => 'BuildPro Corp'],
            ['email' => 'info@buildpro.com', 'address' => '55 Queen Ave., Calgary, AB, Canada']
        );

        $jobs = [
            ['job_number' => '#00225', 'job_type' => 'project',      'title' => 'PMA Fall Inspection',       'status' => 'completed',   'technician_id' => $tech1->id, 'client_id' => $client1->id],
            ['job_number' => '#00226', 'job_type' => 'service_work', 'title' => 'HVAC System Maintenance',   'status' => 'in_progress', 'technician_id' => $tech1->id, 'client_id' => $client1->id],
            ['job_number' => '#00227', 'job_type' => 'on_call',      'title' => 'Emergency Boiler Repair',   'status' => 'in_progress', 'technician_id' => $tech2->id, 'client_id' => $client2->id],
            ['job_number' => '#00228', 'job_type' => 'project',      'title' => 'Annual Fire Safety Check',  'status' => 'confirmed',   'technician_id' => $tech1->id, 'client_id' => $client3->id],
            ['job_number' => '#00229', 'job_type' => 'assignment',   'title' => 'Electrical Panel Upgrade',  'status' => 'confirmed',   'technician_id' => $tech2->id, 'client_id' => $client1->id],
            ['job_number' => '#00230', 'job_type' => 'service_work', 'title' => 'Plumbing Leak Fix',         'status' => 'assigned',    'technician_id' => $tech1->id, 'client_id' => $client2->id],
            ['job_number' => '#00231', 'job_type' => 'project',      'title' => 'Roof Inspection & Report',  'status' => 'in_progress', 'technician_id' => $tech2->id, 'client_id' => $client3->id],
            ['job_number' => '#00232', 'job_type' => 'project',      'title' => 'PMA Spring Inspection',     'status' => 'assigned',    'technician_id' => $tech1->id, 'client_id' => $client1->id],
        ];

        foreach ($jobs as $job) {
            Task::firstOrCreate(
                ['job_number' => $job['job_number']],
                array_merge($job, [
                    'user_id'     => $job['technician_id'],
                    'created_by'  => $admin->id,
                    'start_time'  => '2020-03-23 10:00:00',
                    'end_time'    => '2020-03-23 15:30:00',
                    'description' => null,
                    'due_date'    => null,
                ])
            );
        }
    }
}
