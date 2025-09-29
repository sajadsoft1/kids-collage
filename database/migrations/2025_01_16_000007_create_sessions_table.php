<?php

declare(strict_types=1);

use App\Enums\SessionStatus;
use App\Enums\SessionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('course_session_template_id')->constrained('course_session_templates')->cascadeOnDelete();
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->string('meeting_link')->nullable();
            $table->string('recording_link')->nullable();
            $table->string('status')->default(SessionStatus::PLANNED->value);
            $table->string('session_type')->default(SessionType::IN_PERSON->value);
            $table->timestamps();
            $table->softDeletes();

            // Add indexes
            $table->index(['course_id', 'date']);
            $table->index(['course_session_template_id']);
            $table->index('status');
            $table->index('session_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_sessions');
    }
};
