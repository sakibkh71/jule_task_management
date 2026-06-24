<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label');
            $table->string('color', 7);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE tasks MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'assigned'");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('task_statuses');

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('assigned','in_progress','confirmed','completed') NOT NULL DEFAULT 'assigned'");
        }
    }
};
