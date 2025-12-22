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
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('enrollment_id')->index()->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('course_session_id')->index()->constrained('course_sessions')->cascadeOnDelete();
            $table->boolean('present')->index()->default(false);
            $table->timestamp('arrival_time')->nullable()->index();
            $table->timestamp('leave_time')->nullable()->index();
            $table->text('excuse_note')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate attendance records
            $table->index('branch_id');
            $table->unique(['enrollment_id', 'course_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
