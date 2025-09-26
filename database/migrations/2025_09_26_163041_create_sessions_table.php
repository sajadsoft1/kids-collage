<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->index()->constrained('courses')->cascadeOnDelete();
            $table->foreignId('teacher_id')->index()->constrained('users')->cascadeOnDelete();
            $table->dateTime('start_time')->index();
            $table->dateTime('end_time')->index();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->string('meeting_link')->nullable();
            $table->unsignedInteger('session_number')->index();
            $table->text('languages')->nullable();
            $table->timestamps();

            // Composite index for course session queries
            $table->index(['course_id', 'session_number']);

            // Data integrity constraints
            $table->check('start_time < end_time'); // Session start time must be before end time
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
