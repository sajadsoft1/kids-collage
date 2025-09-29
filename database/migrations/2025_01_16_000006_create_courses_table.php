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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_template_id')->constrained('course_templates')->cascadeOnDelete();
            $table->foreignId('term_id')->constrained('terms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->index()->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('capacity')->nullable();
            $table->string('status')->default(CourseStatus::DRAFT->value);
            $table->json('days_of_week')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->string('meeting_link')->nullable();
            $table->string('type')->default(CourseType::IN_PERSON->value);
            $table->timestamps();
            $table->softDeletes();

            // Add indexes
            $table->index(['course_template_id', 'term_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
