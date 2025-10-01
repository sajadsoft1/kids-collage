<?php

declare(strict_types=1);

use App\Enums\CourseTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_session_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_template_id')->constrained('course_templates')->cascadeOnDelete();
            $table->unsignedInteger('order');
            $table->unsignedInteger('duration_minutes');
            $table->string('type')->default(CourseTypeEnum::IN_PERSON->value);
            $table->json('languages')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['course_template_id', 'order']);
            $table->index('duration_minutes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_session_templates');
    }
};
