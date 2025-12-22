<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();

            // Type
            $table->string('type', 50);

            // Relations
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('question_subjects')->nullOnDelete();
            $table->foreignId('competency_id')->nullable()->constrained('question_competencies')->nullOnDelete();

            // Content
            $table->text('title');
            $table->longText('body')->nullable();
            $table->longText('explanation')->nullable();

            // Settings (store enum as string; enforce via PHP Enums)
            $table->string('difficulty', 20)->nullable();
            $table->decimal('default_score', 8, 2)->default(1.00);

            // JSON Data
            $table->json('config')->nullable();
            $table->json('correct_answer')->nullable();
            $table->json('metadata')->nullable();

            // Management
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false);
            $table->boolean('is_survey_question')->default(false);
            $table->unsignedInteger('usage_count')->default(0);

            $table->timestamps();

            // Indexes
            $table->index('branch_id');
            $table->index('type');
            $table->index('category_id');
            $table->index('subject_id');
            $table->index('competency_id');
            $table->index('difficulty');
            $table->index('is_active');
            $table->index('created_by');
            $table->fullText(['title', 'body']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
