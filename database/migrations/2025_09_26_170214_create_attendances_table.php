<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->index()->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('session_id')->index()->constrained('sessions')->cascadeOnDelete();
            $table->boolean('present')->index()->default(false);
            $table->timestamp('arrival_time')->nullable()->index();
            $table->timestamp('leave_time')->nullable()->index();
            $table->timestamps();

            // Unique constraint to prevent duplicate attendance records
            $table->unique(['enrollment_id', 'session_id']);

            // Data integrity constraints
            $table->check('leave_time IS NULL OR arrival_time IS NULL OR arrival_time <= leave_time'); // Leave time must be after or equal to arrival time
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
