<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobFieldsToTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'job_number')) {
                $table->string('job_number')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('tasks', 'job_type')) {
                $table->enum('job_type', ['project', 'service_work', 'on_call', 'assignment'])->default('project')->after('job_number');
            }
            if (!Schema::hasColumn('tasks', 'technician_id')) {
                $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null')->after('job_type');
            }
            if (!Schema::hasColumn('tasks', 'client_id')) {
                $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null')->after('technician_id');
            }
            if (!Schema::hasColumn('tasks', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('client_id');
            }
            if (!Schema::hasColumn('tasks', 'start_time')) {
                $table->dateTime('start_time')->nullable()->after('due_date');
            }
            if (!Schema::hasColumn('tasks', 'end_time')) {
                $table->dateTime('end_time')->nullable()->after('start_time');
            }
        });

        // Expand status enum — raw SQL avoids doctrine/dbal dependency
        \DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('assigned','in_progress','confirmed','completed') NOT NULL DEFAULT 'assigned'");
        \DB::statement("UPDATE tasks SET status = 'assigned' WHERE status = 'pending'");
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['technician_id']);
            $table->dropForeign(['client_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn(['job_number', 'job_type', 'technician_id', 'client_id', 'created_by', 'start_time', 'end_time']);
        });
        \DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('pending','in_progress','completed') NOT NULL DEFAULT 'pending'");
    }
}
