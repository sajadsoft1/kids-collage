<?php

declare(strict_types=1);

use App\Enums\CourseStatusEnum;
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
            $table->foreignId('teacher_id')->index()->constrained('users')->cascadeOnDelete();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('capacity')->nullable();
            $table->string('status')->default(CourseStatusEnum::DRAFT->value);
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
