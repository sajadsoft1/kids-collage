<?php

declare(strict_types=1);

use App\Enums\CourseStatus;
use App\Enums\CourseType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->index()->constrained('courses')->cascadeOnDelete();
            $table->foreignId('course_session_template_id')->index()->constrained('course_session_templates')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->string('status')->default(CourseStatus::SCHEDULED->value);
            $table->text('meeting_link')->nullable();
            $table->text('recording_link')->nullable();
            $table->string('session_type')->default(CourseType::ONLINE->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
