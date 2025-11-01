<?php

declare(strict_types=1);

use App\Enums\ExamStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Enums\ShowResultsEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->text('description')->nullable();

            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            // Store enum as string (ExamTypeEnum values)
            $table->string('type', 20)->default(ExamTypeEnum::SCORED->value);
            $table->decimal('total_score', 8, 2)->nullable();

            $table->integer('duration')->nullable()->comment('minutes');

            $table->decimal('pass_score', 8, 2)->nullable();
            $table->integer('max_attempts')->nullable();

            $table->boolean('shuffle_questions')->default(false);
            // Store enum as string (ShowResultsEnum values)
            $table->string('show_results', 20)->default(ShowResultsEnum::AFTER_SUBMIT->value);
            $table->boolean('allow_review')->default(true);

            $table->json('settings')->nullable();

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // Store enum as string (ExamStatusEnum values)
            $table->string('status', 20)->default(ExamStatusEnum::DRAFT->value);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('status');
            $table->index('type');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
